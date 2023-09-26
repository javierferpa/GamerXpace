<?php
session_start();
include 'conexion.php';





?>

<!DOCTYPE html>
<html>

<head>
  <title>Foro</title>
  <meta charset="UTF-8" />
  <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
  <link href="/tfg/ness.css" rel="stylesheet" />

  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- less -->
  <link rel="stylesheet/less" type="text/css" href="style2.less" />
  <script src="less.min.js" type="text/javascript"></script>
  <script src="mensajes.js" type="text/javascript"></script>

</head>

<body>
  <script type="text/javascript">
        var usuario = "<?php if(isset($_SESSION["loggedin"])){echo $_SESSION["user"];}; ?>"
  </script>

  <div class="header nes-container is-centered">
    <div id="tit">
      <h2>GAMER</h2>
      <H1>X</H1>
      <h4>PACE</h4>
    </div>
  </div>

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

  <div class="register nes-container ">


  <div class="nes-table-responsive">
  <table class="nes-table is-bordered is-centered foro">
    <thead>
      <tr>
        <th>Tema</th>
        <th>Autor</th>
        <th>Ultimo mensaje</th>
        <th>Resp</th>
        <th>Creacion</th>


      </tr>
    </thead>
    <tbody>

    <?php 
    // selecciona todos los hilos
    $MyBBDD->consulta("SELECT * FROM hilos");

    while ($fila = $MyBBDD->extraer_registro()) {
// Muestra todos los hilos con su informacion
      echo "<tr>";
      echo "<td> <a href='mensajes.php?hilo=". $fila['id_tema'] . "'>" . $fila['tema'] . "</a></td>";
      echo "<td>" . $fila['autor'] . "</td>";
// comprueba cual es el ultimo mensaje del hilo y lo muestra
      $MyBBDD2 = clone $MyBBDD;
      $MyBBDD2->consulta("SELECT `autor`, `creacion` FROM `Mensajes` WHERE `fk_id_hilo` =". $fila['id_tema'] . " ORDER BY creacion DESC ");
      $fila2 = $MyBBDD2->extraer_registro();

      if($fila2['autor'] != ""){
        echo "<td>" . $fila2['autor'] . "<br>" . $fila2['creacion'] . "</td>";
                } else{
                  // en caso de que no tenga muestra esto
        echo "<td> No hay mensajes </td>";
                }

     
      // select que cuenta el numero de mensajes de cada hilo
      $MyBBDD2 = clone $MyBBDD;
      $MyBBDD2->consulta("SELECT count(id_mensaje) as total FROM Mensajes where fk_id_hilo = ". $fila['id_tema']);
      $fila2 = $MyBBDD2->extraer_registro();

      echo "<td>" . $fila2['total'] . "</td>";
      echo "<td>" . $fila['create'] . "</td>";

      echo "</tr>";

    };

    ?>

    </tbody>
  </table>
  <br>
  <p id="error"></p>

  
  <a class="nes-btn is-primary" href="/tfg/crearHilo.php" <?php if($_SESSION["loggedin"] == false){ echo "style='display: none'";};?> >CREAR HILO
  </a>

  
    
    
  

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