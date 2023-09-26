<?php
session_start();

if(!isset($_SESSION[ "loggedin"])){
  $_SESSION[ "loggedin"] = false;
  $_SESSION["user"] = "";   
}

include 'conexion.php';
?>

<!DOCTYPE html>
<html>

<head>
  <title>Inicio</title>
  <meta charset="UTF-8" />
  <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
  <link href="/tfg/ness.css" rel="stylesheet" />

  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- less -->
  <link rel="stylesheet/less" type="text/css" href="style2.less" />
  <script src="less.min.js" type="text/javascript"></script>


</head>

<body>
  <!-- div que contiene el header con el titulo -->
  <div class="header nes-container is-centered">
    <div id="tit">
      <h2>GAMER</h2>
      <H1>X</H1>
      <h4>PACE</h4>
    </div>
  </div>
<!-- div que contiene la barra de navegacion -->
  <div class="navbar nes-container">
    <a href="/tfg/index.php" class="nes-btn is-primary">INICIO</a>
    <?php
      if($_SESSION["loggedin"]){
        echo "<a href='/tfg/perfil.php?id=" . $_SESSION["user"] . "' class='nes-btn is-primary'>PERFIL:" . $_SESSION["user"] . "</a>";
      } else {
        echo "<a href='/tfg/login.php' class='nes-btn is-primary'>LOGARSE</a>";
      }
    ?>
    
    <a href="/tfg/foro.php" class="nes-btn is-primary">FORO</a>
  </div>
<!-- div principal -->
  <div class="row">
    <div class="main wrap nes-container">
<!-- muestra las caratulas de los juegos como enlaces -->
      <div class="caratula">
        <a href="/tfg/juegos/arkanoid/arkanoid.php">
          <img src="img/arkanoid.jpg">
        </a>
      </div>
      <div class="caratula">
        <a href="/tfg/juegos/naves/naves.php">
          <img src="img/naves.jpg">
        </a>
      </div>

    </div>
    <!-- side con los rankings  -->
    <div class="side nes-container is-centered">
      <h2>Ranking mayores puntuaciones</h2>
      <?php 
      // select que comprueba los ranking del juego arkanoid
      $MyBBDD->consulta("SELECT `id_usu`, `puntosMax` FROM `usuarios` ORDER BY puntosMax DESC LIMIT 3");
      $linea = 1;
      while ($fila = $MyBBDD->extraer_registro()) {
        echo "<p>" . $linea . ".- <a href='https://gamerspace69.000webhostapp.com/tfg/perfil.php?id=" . $fila["id_usu"] . "'>" . $fila["id_usu"] . "</a><br> Puntuacion: " . $fila["puntosMax"] . ".</p>";
        $linea += 1;
      }
    ?>

      <h2>Hilos mas comentados</h2>
      <?php 
      // select que comprueba los hilos con mas mensajes
      $MyBBDD->consulta("SELECT fk_id_hilo as tema, count(id_mensaje) as total FROM Mensajes GROUP BY fk_id_hilo ORDER BY total DESC LIMIT 3");
      $linea = 1;
      $MyBBDD2 = clone $MyBBDD;
      while ($fila = $MyBBDD->extraer_registro()) {
        $MyBBDD2->consulta("SELECT tema, id_tema FROM hilos where id_tema =". $fila['tema']);
        $fila2 = $MyBBDD2->extraer_registro();
        echo "<p>" . $linea . ".- <a href='https://gamerspace69.000webhostapp.com/tfg/mensajes.php?hilo=" . $fila2["id_tema"] . "'>" . $fila2["tema"] . "</a><br>Mensajes: " . $fila["total"] . ".</p>";
        
        $linea += 1;
      }
    ?>
    </div>

  </div>
  <div class="footer nes-container is-centered">
    
    <p>Creado por Javier Fernandez y Miguel Hernandez.Contactanos al: 9123123123
    </p><br>
    <a href="https://www.youtube.com/"><i class="nes-icon youtube is-medium"></i></a>
  <a href="https://www.instagram.com/"><i class="nes-icon instagram is-medium"></i></a>
  <a href="https://www.twitch.tv/"><i class="nes-icon twitch is-medium"></i></a>
  <a href="https://twitter.com/"><i class="nes-icon twitter is-medium"></i></a>

  </div>
</body>

</html>