<?php
session_start();
include 'conexion.php';





?>

<!DOCTYPE html>
<html>

<head>
  <title>Crear Hilo</title>
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


  
  
  
  <!-- input que crea el hilo con el js -->
      <p class="title">Escriba un titulo</p>
      <input type="text" id="tema" maxlength="44">
        <button class="nes-btn is-primary" onclick="enviarHilo(this)">Crear hilo</button>


  
    
    
  

</div>
  
  

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