<?php
session_start();


?>
<!DOCTYPE html>
<html>

<head>
  <title>Arkanoid</title>
  <meta charset="UTF-8" />
  <!-- Links al framework de nes.css -->
  <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
  <link href="/tfg/ness.css" rel="stylesheet" />
  
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- less -->
  <link rel="stylesheet/less" type="text/css" href="/tfg/style2.less" />
  <script src="/tfg/less.min.js" type="text/javascript"></script>


</head>

<body>
<!-- Primer div que contiene el titulo -->
  <div class="header nes-container is-centered">
    <div id="tit">
      <h2 >GAMER</h2><H1>X</H1><h4>PACE</h4>
    </div>
    </div>
 <!-- Segundo div que contiene la barra de navegación -->
    <div class="navbar nes-container">
    <a href="/tfg/index.php" class="nes-btn is-primary">INICIO</a>
    <!-- Si no esta logado aparece boton logarse y si lo esta aparece el boton de perfil -->
    <?php
      if($_SESSION["loggedin"]){
        echo "<a href='/tfg/perfil.php?id=" . $_SESSION["user"] . "' class='nes-btn is-primary'>PERFIL:" . $_SESSION["user"] . "</a>";
      } else {
        echo "<a href='/tfg/login.php' class='nes-btn is-primary'>LOGARSE</a>";
      }
    ?>
    
    <a href="/tfg/foro.php" class="nes-btn is-primary">FORO</a>
  </div>
<!-- div que contiene los dos divs principales uno seria el central y otro el side(ranking) -->
  <div class="row">
<!-- div principal -->
    <div class="main nes-container" id="main">
      <div id="instrucciones">
      <h4>ARKANOID</h4>
      <p id="text">
      ¡¡DESTRUYE LOS BLOQUES DE ANTIMATERIA!!
      El reactor está obstruido por bloques de antimateria como residuo de su combustión y debes eliminarlos del compartimento o nos harán volar en mil pedazos.
      Para ello cuentas con una esfera de energía y una pala cubierta de un armazón resistente, recuerda que no debes dejar que la esfera cruce el 
      umbral del compartimento pue podría entrar en contacto con el combustible y destruir la nave por completo.<br>
        Instrucciones:<br>
        Para controlar el movimiento de la pala se pueden usar las teclas A y D o las flechas de izquierda y derecha.<br>
        El objetivo es destruir todos los ladrillos sin que la pelota llegue a caer.
      </p>
      <p id="punt"></p>
      <button id="play" class="nes-btn">play</button>
    </div>
      <div id="juego">
        <canvas id="myCanvas"></canvas>
      </div>
    
    
      <br>
      <!-- Botones para la version movil, ocultos en pantallas mas grandes -->
      <div>
        <button id="iz" class="nes-btn">◀</button>
        <button id="der" class="nes-btn">▶</button>
      </div>
    <!-- Codigo embebido para poder crear la variable de usuario en js desde php -->
      <script type="text/javascript">
        var usuario = "<?php if(isset($_SESSION["loggedin"])){echo $_SESSION["user"];}; ?>"
      </script>
    <!-- script principal del juego -->
      <script src="juego3.js" type="text/javascript"></script>

    </div>
    <!-- div del side -->
    <div class="side nes-container is-centered">
      <h2>Mayores puntuaciones</h2>
      
      <?php 
      // archivo php con los parametros de conexion
      include '../../conexion.php';
      // mediante esta consulta se obtienen las 3 puntuaciones mas alta del arkanoid
      $MyBBDD->consulta("SELECT `id_usu`, `puntosMax` FROM `usuarios` ORDER BY puntosMax DESC LIMIT 3");
      // contador para saber el numero de fila que esta escribiendo
      $linea = 1;
      while ($fila = $MyBBDD->extraer_registro()) {
        // redireccionamos a la pagina de perfil escribiendo el id de usuario como parametro en la url
        echo "<p>" . $linea . ".- <a href='https://gamerspace69.000webhostapp.com/tfg/perfil.php?id=" . $fila["id_usu"] . "'>" . $fila["id_usu"] . "</a><br> Puntuacion: " . $fila["puntosMax"] . "</p>";
        $linea += 1;
      }
    ?>
      <hr>
      <h2>Mayores puntuaciones totales</h2>
      <?php 
      // mediante esta consulta se obtienen las 3 mayores puntuaciones totales del arkanoid
        $MyBBDD->consulta("SELECT `id_usu`, `TotalPunt` FROM `usuarios` ORDER BY TotalPunt DESC LIMIT 3");
        $linea = 1;
        while ($fila = $MyBBDD->extraer_registro()) {
          // redireccionamos a la pagina de perfil escribiendo el id de usuario como parametro en la url
          echo "<p>" . $linea . ".- <a href='https://gamerspace69.000webhostapp.com/tfg/perfil.php?id=" . $fila["id_usu"] . "'>" . $fila["id_usu"] . "</a><br> Puntuacion: " . $fila["TotalPunt"] . "</p>";
          $linea += 1;
        }
      ?>
    </div>
    
  </div>
  <!-- div con el footer -->
  <div class="footer nes-container is-centered">
    
  <p>Creado por Javier Fernandez y Miguel Hernandez.Contactanos al: 9123123123    </p><br>
  <!-- iconos con enlaces a redes sociales -->
    <a href="https://www.youtube.com/"><i class="nes-icon youtube is-medium"></i></a>
  <a href="https://www.instagram.com/"><i class="nes-icon instagram is-medium"></i></a>
  <a href="https://www.twitch.tv/"><i class="nes-icon twitch is-medium"></i></a>
  <a href="https://twitter.com/"><i class="nes-icon twitter is-medium"></i></a>

  </div>
</body>
</body>

</html>





