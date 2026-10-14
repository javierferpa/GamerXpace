import { createHash } from 'node:crypto';
import { readFile, mkdir, mkdtemp, copyFile, writeFile } from 'node:fs/promises';
import { dirname, resolve, join, extname } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const args = process.argv.slice(2);
const option = name => {
  const at = args.indexOf(name);
  return at < 0 ? undefined : args[at + 1];
};
const sourceManifest = JSON.parse(await readFile(join(root, 'hosting/source-manifest.json'), 'utf8'));
const hash = content => createHash('sha256').update(content).digest('hex');
for (const [path, expected] of Object.entries(sourceManifest.files)) {
  if (hash(await readFile(join(root, path))) !== expected) throw new Error(`Original source changed: ${path}`);
}
if (args.includes('--verify-only')) {
  console.log(`${Object.keys(sourceManifest.files).length} original TFG files verified unchanged.`);
  process.exit(0);
}

const origin = new URL(option('--origin'));
if (origin.protocol !== 'https:' || origin.username || origin.password || origin.search || origin.hash || origin.pathname !== '/') {
  throw new Error('Supply a plain HTTPS origin without credentials or a path.');
}
if (!/^[a-z0-9-]+\.alwaysdata\.net$/.test(origin.hostname) && origin.hostname !== 'migration-test.invalid') {
  throw new Error('Only the selected alwaysdata host or the local check placeholder is supported.');
}
const dataMode = option('--data');
if (!['full', 'empty'].includes(dataMode)) throw new Error('Explicit --data full or --data empty is required.');
const workspace = join(root, '.hosting');
await mkdir(workspace, { recursive: true });
const release = await mkdtemp(join(workspace, 'release-'));
await mkdir(join(release, 'public/tfg'), { recursive: true });
await mkdir(join(release, 'private'), { mode: 0o700 });

const excluded = new Set(['.sql', '.docx', '.pptx', '.xcf']);
const receipts = [];
for (const [path, expected] of Object.entries(sourceManifest.files)) {
  if (excluded.has(extname(path).toLowerCase()) || path === 'TFG/Readme.txt') continue;
  const relative = path.slice(4);
  const destination = join(release, 'public/tfg', relative);
  await mkdir(dirname(destination), { recursive: true });
  if (relative !== 'conexion.php') {
    await copyFile(join(root, path), destination);
    if (hash(await readFile(destination)) !== expected) throw new Error(`Copy changed: ${path}`);
    receipts.push({ path: `tfg/${relative}`, unchanged: true });
    continue;
  }
  const original = await readFile(join(root, path), 'utf8');
  let configured = original;
  const settings = { host: "['host']", port: "['port']", user: "['user']", password: "['password']", dbname: "['database']" };
  for (const [variable, key] of Object.entries(settings)) {
    const pattern = new RegExp(`^\\$${variable}[ \\t]*=[^\\r\\n]*;[ \\t]*$`, 'm');
    if (!pattern.test(configured)) throw new Error(`Connection setting not found: ${variable}`);
    configured = configured.replace(pattern, () => `$${variable} = $gamerHostConfig${key};`);
  }
  configured = configured.replace(/^\$host[ \t]*=/m, matched => "$gamerHostConfig = require dirname(__DIR__, 2) . '/private/database.php';\n" + matched);
  if (configured.slice(0, configured.indexOf('$gamerHostConfig')) !== original.slice(0, original.indexOf('$host'))) {
    throw new Error('The database class must remain unchanged.');
  }
  await writeFile(destination, configured);
  receipts.push({ path: 'tfg/conexion.php', unchanged: false, reason: 'Deployment connection settings only; original source preserved.' });
}

const configFile = option('--database-config');
let databasePhp = "<?php\nthrow new RuntimeException('Deployment database configuration missing');\n";
if (configFile) {
  const config = JSON.parse(await readFile(resolve(configFile), 'utf8'));
  if (!['host', 'user', 'password', 'database'].every(key => typeof config[key] === 'string' && config[key].length)) {
    throw new Error('Database configuration needs host, user, password and database.');
  }
  if (!Number.isSafeInteger(config.port) || config.port < 1 || config.port > 65535) throw new Error('Invalid database port.');
  // Values stay in a private server file, outside both the web root and Git.
  const encoded = Buffer.from(JSON.stringify(config)).toString('base64');
  databasePhp = `<?php\nreturn json_decode(base64_decode('${encoded}'), true, 512, JSON_THROW_ON_ERROR);\n`;
}
await writeFile(join(release, 'private/database.php'), databasePhp, { mode: 0o600 });
let sql = await readFile(join(root, 'TFG/id16510789_gamewxpace.sql'), 'utf8');
if (dataMode === 'empty') sql = sql.replace(/INSERT INTO `[\s\S]*?;\r?\n/g, '');
await writeFile(join(release, 'private/database.sql'), sql, { mode: 0o600 });

const apache = `# Hosting configuration; the TFG application source is preserved.\nOptions -Indexes\nDirectoryIndex index.php\nRewriteEngine On\nRewriteRule ^$ /tfg/index.php [R=302,L]\n<FilesMatch "(?i)(^\\.|\\.(sql|ini|log|env|bak|docx|pptx|xcf)$)">\n  Require all denied\n</FilesMatch>\n<Files "conexion.php">\n  Require all denied\n</Files>\nAddOutputFilterByType SUBSTITUTE text/html\nSubstitute "s|https://gamerspace69.000webhostapp.com|${origin.origin}|n"\nSubstitute "s|https://gamerxpace.000webhostapp.com|${origin.origin}|n"\n<IfModule headers_module>\n  Header always set X-Content-Type-Options "nosniff"\n  Header always set Referrer-Policy "strict-origin-when-cross-origin"\n</IfModule>\n`;
await writeFile(join(release, 'public/.htaccess'), apache);
await copyFile(join(root, 'hosting/php.ini'), join(release, 'public/.user.ini'));
await writeFile(join(release, 'manifest.json'), JSON.stringify({
  sourceCommit: sourceManifest.sourceCommit,
  publicOrigin: origin.origin,
  dataMode,
  originalSourceUnchanged: true,
  files: receipts,
}, null, 2) + '\n');
console.log(JSON.stringify({ release, originalFilesUnchanged: Object.keys(sourceManifest.files).length,
  publishedFiles: receipts.length, privateDatabaseConfigured: Boolean(configFile), dataMode }, null, 2));

