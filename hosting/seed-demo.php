<?php
// CLI-only hosting utility. The original TFG application is unchanged.
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$options = getopt('', ['database-config:', 'data:', 'receipt:']);
$db = null;
try {
    foreach (['database-config', 'data', 'receipt'] as $key) {
        if (empty($options[$key])) throw new RuntimeException('Required option missing: ' . $key);
    }
    $config = require $options['database-config'];
    if ($config['database'] !== 'gamerxpace_tfg') throw new RuntimeException('Unexpected database target');
    $raw = file_get_contents($options['data']);
    $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    $dataHash = hash('sha256', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
    $users = [];
    $avatars = ['nes-mario', 'nes-ash', 'nes-pokeball', 'nes-bulbasaur', 'nes-charmander', 'nes-squirtle', 'nes-kirby'];
    foreach ($data['users'] as $user) {
        if (!preg_match('/^[a-z0-9_]{3,29}$/', $user['name']) || isset($users[$user['name']])) throw new RuntimeException('Invalid or duplicate example user');
        if (!in_array($user['avatar'], $avatars, true)) throw new RuntimeException('Unknown avatar');
        foreach (['arkanoid', 'naves'] as $game) {
            if (!is_int($user[$game]['best']) || !is_int($user[$game]['total']) || $user[$game]['best'] < 0 || $user[$game]['total'] < $user[$game]['best']) throw new RuntimeException('Inconsistent scores');
        }
        $users[$user['name']] = $user;
    }
    foreach ($data['topics'] as $topic) {
        if (mb_strlen($topic['title']) > 44 || !isset($users[$topic['author']]) || $topic['created'] < $users[$topic['author']]['created']) throw new RuntimeException('Invalid example topic');
        foreach ($topic['messages'] as $message) {
            if (!isset($users[$message['author']]) || $message['at'] < $topic['created'] || $message['at'] < $users[$message['author']]['created'] || $message['at'] > date('Y-m-d H:i:s')) throw new RuntimeException('Inconsistent example message chronology');
            if (count(array_unique($message['likedBy'])) !== count($message['likedBy'])) throw new RuntimeException('Duplicate example like');
            foreach ($message['likedBy'] as $name) if (!isset($users[$name]) || $name === $message['author']) throw new RuntimeException('Invalid example like');
        }
    }
    $db = new mysqli($config['host'], $config['user'], $config['password'], $config['database'], $config['port']);
    $db->set_charset('utf8mb4');
    $lock = $db->query("SELECT GET_LOCK(CONCAT(DATABASE(), ':gamerxpace-examples'), 0)")->fetch_row()[0];
    if ((int)$lock !== 1) throw new RuntimeException('Another example import is running');
    $select = function (string $table, string $key, array $ids) use ($db): array {
        if (!$ids) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $db->prepare("SELECT * FROM `$table` WHERE `$key` IN ($placeholders) ORDER BY `$key`");
        $stmt->bind_param(str_repeat('s', count($ids)), ...$ids);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    };
    $digest = function (array $ids) use ($select): array {
        $keys = ['usuarios' => 'id_usu', 'hilos' => 'id_tema', 'Mensajes' => 'id_mensaje', 'likes' => 'id_like'];
        $out = [];
        foreach ($keys as $table => $key) $out[$table] = hash('sha256', json_encode($select($table, $key, $ids[$table]), JSON_THROW_ON_ERROR));
        return $out;
    };
    if (file_exists($options['receipt'])) {
        $receipt = json_decode(file_get_contents($options['receipt']), true, 512, JSON_THROW_ON_ERROR);
        if ($receipt['sourceSha256'] !== $dataHash || $digest($receipt['ids']) !== $receipt['rowDigests']) throw new RuntimeException('Existing import differs or has an uncertain outcome; inspect it before any retry');
        if ($receipt['stage'] === 'prepared') {
            $receipt['stage'] = 'verified';
            file_put_contents($options['receipt'], json_encode($receipt, JSON_PRETTY_PRINT), LOCK_EX);
        }
        echo json_encode(['examples' => 'already_present', 'counts' => $receipt['counts']]), "\n";
        exit(0);
    }
    $originalIds = [];
    foreach (['usuarios' => 'id_usu', 'hilos' => 'id_tema', 'Mensajes' => 'id_mensaje', 'likes' => 'id_like'] as $table => $key) {
        $originalIds[$table] = array_column($db->query("SELECT `$key` FROM `$table`")->fetch_all(MYSQLI_ASSOC), $key);
    }
    $originalDigest = $digest($originalIds);
    if ($select('usuarios', 'id_usu', array_keys($users))) throw new RuntimeException('An example username is already in use; refusing to overwrite it');
    $db->begin_transaction();
    $ids = ['usuarios' => [], 'hilos' => [], 'Mensajes' => [], 'likes' => []];
    $insertUser = $db->prepare('INSERT INTO usuarios (id_usu,pass,naci,`create`,puntosMax,TotalPunt,img_perf,totalPuntnav,maxPuntnav) VALUES (?,?,?,?,?,?,?,?,?)');
    foreach ($users as $user) {
        // No shared demo password exists. The random input is never retained.
        $passwordHash = md5(random_bytes(32));
        $insertUser->bind_param('ssssiisii', $user['name'], $passwordHash, $user['birth'], $user['created'], $user['arkanoid']['best'], $user['arkanoid']['total'], $user['avatar'], $user['naves']['total'], $user['naves']['best']);
        $insertUser->execute();
        $ids['usuarios'][] = $user['name'];
    }
    $insertTopic = $db->prepare('INSERT INTO hilos (tema,autor,`create`) VALUES (?,?,?)');
    $insertMessage = $db->prepare('INSERT INTO Mensajes (autor,mensaje,creacion,fk_id_hilo) VALUES (?,?,?,?)');
    $insertLike = $db->prepare("INSERT INTO likes (fk_id_mensaje,`like`,fk_id_usu) VALUES (?,b'1',?)");
    foreach ($data['topics'] as $topic) {
        $insertTopic->bind_param('sss', $topic['title'], $topic['author'], $topic['created']);
        $insertTopic->execute(); $topicId = $db->insert_id; $ids['hilos'][] = $topicId;
        foreach ($topic['messages'] as $message) {
            $insertMessage->bind_param('sssi', $message['author'], $message['text'], $message['at'], $topicId);
            $insertMessage->execute(); $messageId = $db->insert_id; $ids['Mensajes'][] = $messageId;
            foreach ($message['likedBy'] as $name) {
                $insertLike->bind_param('is', $messageId, $name); $insertLike->execute(); $ids['likes'][] = $db->insert_id;
            }
        }
    }
    if ($digest($originalIds) !== $originalDigest) throw new RuntimeException('An original record changed; rolling back the examples');
    $counts = array_map('count', $ids);
    $receipt = ['batch' => $data['batch'], 'sourceSha256' => $dataHash, 'stage' => 'prepared', 'ids' => $ids, 'rowDigests' => $digest($ids), 'counts' => $counts, 'originalRecordsUnchanged' => true];
    if (file_put_contents($options['receipt'], json_encode($receipt, JSON_PRETTY_PRINT), LOCK_EX) === false) throw new RuntimeException('Cannot save import receipt');
    chmod($options['receipt'], 0600);
    $db->commit();
    if ($digest($ids) !== $receipt['rowDigests'] || $digest($originalIds) !== $originalDigest) throw new RuntimeException('Committed data differs; inspect the receipt before any retry');
    $receipt['stage'] = 'verified'; $receipt['verifiedAt'] = gmdate('c');
    if (file_put_contents($options['receipt'], json_encode($receipt, JSON_PRETTY_PRINT), LOCK_EX) === false) throw new RuntimeException('Commit succeeded but final receipt update failed; inspect before retrying');
    echo json_encode(['examples' => 'verified', 'counts' => $counts, 'originalRecordsUnchanged' => true]), "\n";
    $db->close();
} catch (Throwable $error) {
    if ($db) $db->rollback();
    fwrite(STDERR, json_encode(['examples' => 'failed', 'code' => $error->getCode(), 'message' => $error instanceof mysqli_sql_exception ? 'Database operation failed; inspect before retrying' : $error->getMessage()]), "\n");
    exit(1);
}
