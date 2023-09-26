<?php
session_start();
include 'conexion.php';


//comprueba si existe una id pasada por parametro en la url
if(isset($_GET["id"])){
  // recoge los campos de la BBDD del usuario
  $MyBBDD->consulta("SELECT * FROM usuarios where id_usu = '" . $_GET["id"] ."'");
  // los guarda en variables
    while ($fila = $MyBBDD->extraer_registro()) {

      $user = $fila['id_usu'];
      $naci = $fila['naci'];
      $crea = $fila['create'];
      $puntosMax = $fila['puntosMax'];
      $totalPunt = $fila['TotalPunt'];
      $perf = $fila['img_perf'];
      $fech_nac = $fila['naci'];
      $puntosMaxNav = $fila['maxPuntnav'];
      $puntostotalNav = $fila['totalPuntnav'];

    }
    // coge la posicion del rankin de cada juego tanto de puntuacion total como de la puntuacion mas alta
    $MyBBDD->consulta("SELECT pos FROM (SELECT id_usu, ROW_NUMBER() OVER(ORDER BY `puntosMax` DESC) AS 'pos' FROM usuarios) as rank WHERE id_usu = '$user'");
    $fila = $MyBBDD->extraer_registro();
    
    $rank =  $fila['pos'];

    $MyBBDD->consulta("SELECT pos FROM (SELECT id_usu, ROW_NUMBER() OVER(ORDER BY `TotalPunt` DESC) AS 'pos' FROM usuarios) as rank WHERE id_usu = '$user'");
    $fila = $MyBBDD->extraer_registro();
    
    $rankT = $fila['pos'];

    $MyBBDD->consulta("SELECT pos FROM (SELECT id_usu, ROW_NUMBER() OVER(ORDER BY `maxPuntnav` DESC) AS 'pos' FROM usuarios) as rank WHERE id_usu = '$user'");
    $fila = $MyBBDD->extraer_registro();
    
    $rankNav = $fila['pos'];

    $MyBBDD->consulta("SELECT pos FROM (SELECT id_usu, ROW_NUMBER() OVER(ORDER BY `totalPuntnav` DESC) AS 'pos' FROM usuarios) as rank WHERE id_usu = '$user'");
    $fila = $MyBBDD->extraer_registro();
    
    $rankNavT = $fila['pos'];
    // si no hay id vuelve al inicio
} else {
  header("location: login.php");
  echo "<script type='text/javascript'>window.location.replace('login.php')</script>;";
}
// si se pulsa el boton de cerrar sesion las variables de sesion loggedin y user se resetean y vuelve al index
if(isset($_POST["Cerrar_Sesion"])){
  $_SESSION["loggedin"] = false;
  $_SESSION["user"] = "";   
  header("location: index.php");
  echo "<script type='text/javascript'>window.location.replace('index.php')</script>;";
}  






?>

<!DOCTYPE html>
<html>

<head>
  <title>Perfil</title>
  <meta charset="UTF-8" />
  <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
  <link href="/tfg/ness.css" rel="stylesheet" />

  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- less -->
  <link rel="stylesheet/less" type="text/css" href="style2.less" />
  <script src="less.min.js" type="text/javascript"></script>


</head>

<body>
  <!-- <img src="img/titulo.gif" alt="Funny image" width="200px" height="200px" style="position: absolute; z-index: 100; animation-name: example; animation-duration: 4s" >  -->

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
<!-- muestra las variables recogidas de la BBDD -->
  <div class="register nes-container ">
    <?php echo "<p>Imagen de perfil: </p><i class='$perf'></i>" ?>
    <p>
       usuario: <?php echo $user?>
    </p>
    
    <p>
       arkanoid:<br><br>

       Puntuacion total:  <?php echo $totalPunt?><br>
       Puntuacion maxima: <?php echo $puntosMax?><br>
       Ranking:<br>
       -Puntuacion maxima: <?php echo $rank?><br>
       -Puntuacion total:  <?php echo $rankT?><br><br>

       SpaceInvader:<br><br>

       Puntuacion total:  <?php echo$puntosMaxNav?><br>
       Puntuacion maxima: <?php echo$puntostotalNav?><br>
       Ranking:<br>
       -Puntuacion maxima: <?php echo $rankNav?><br>
       -Puntuacion total:  <?php echo $rankNavT?><br>

       
    </p>
    <p>
       Fecha de nacimiento: <?php echo $fech_nac?><br>
       Fecha de creacion de cuenta: <?php echo $crea?>
    </p>
  <?php 
  // si no estas viendo tu usuario no aparece el boton de cerrar sesion
    if($_SESSION['user'] == $_GET["id"]){
      echo "<form method='POST' action=''>";
      echo    "<button name='Cerrar_Sesion' class='nes-btn'>cerrar sesión</button>";
      echo "</form>";
    }
    ?>
  </div>
  <div class="footer nes-container is-centered">
    
  <p>Creado por Javier Fernandez y Miguel Hernandez.Contactanos al: 9123123123    </p><br>
    <a href="https://www.youtube.com/"><i class="nes-icon youtube is-medium"></i></a>
  <a href="https://www.instagram.com/"><i class="nes-icon instagram is-medium"></i></a>
  <a href="https://www.twitch.tv/"><i class="nes-icon twitch is-medium"></i></a>
  <a href="https://twitter.com/"><i class="nes-icon twitter is-medium"></i></a>

  </div>
</body>
</html>