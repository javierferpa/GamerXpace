<?php
// inicia la sesion de php
session_start();

// si ya esta iniciada la sesion devuelve al index
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: index.php");
  echo "<script type='text/javascript'>window.location.replace('index.php')</script>;";
}

// añadimos el fichero de conexion a la bd
include 'conexion.php';

// Creacion de variables
$user = "";
$pass = "";
$pass2 = "";
$naci = "";
$perf = "";
$user_err;
$pass_err;
$pass2_err;
$naci_err = "";
$perf_err = "";

$user_bd = "";
$pass_bd = "";


if ($_POST) {

  // Comprobamos que no hay ningun campo vacio
  if(empty(trim($_POST["usuario"]))){
    $user_err = "Por favor ingrese su usuario.";
  } else{
    $user = trim($_POST["usuario"]);
  }

  if(empty(trim($_POST["pass"]))){
    $pass_err = "Por favor ingrese su contraseña.";
  } else{
    $pass = trim($_POST["pass"]);
  }
// comprobamos que las contraseñas coinciden
  if(empty(trim($_POST["pass2"])) || $_POST["pass2"] != $pass){
    $pass2_err = "Por favor comfirme su contraseña.";
  } else{
    $pass2 = trim($_POST["pass2"]);
  }

  if(empty(trim($_POST["nacimiento"]))){
    $naci_err = "Por favor comfirme su contraseña.";
  } else{
    $naci = trim($_POST["nacimiento"]);
  }

  if(empty(trim($_POST["perf"]))){
    $user_err = "Por favor elige una foto de perfil.";
  } else{
    $user = trim($_POST["perf"]);
  }

  // Si no hay errores añadimos el usuario a la bbdd
  if(empty($username_err) && empty($password_err) && empty($pass2_err) && empty($naci_err) && empty($user_err)){
    // Comprobamos que el usuario no existe ya en la bbdd

    $MyBBDD->consulta("SELECT id_usu FROM usuarios where id_usu = '" . $user ."'");
    while ($fila = $MyBBDD->extraer_registro()) {
      $user_bd = $fila['id_usu'];
    }
// si no existe lo agregamos a la bbdd
    if($user_bd != $user){    
      
      $user = $_POST["usuario"];
      $pass = md5($_POST["pass"]);
      $nac = $_POST["nacimiento"];
      $perf = $_POST["perf"];

      $MyBBDD->consulta("INSERT INTO usuarios (id_usu, pass, naci,img_perf) VALUES ('$user' , '$pass', '$nac','$perf')");

                  // Iniciamos la sesion
                  $_SESSION["loggedin"] = true;
                  $_SESSION["user"] = $user;          
                  // Redirigimos a la pagina de inicio
                  echo "<script type='text/javascript'>window.location.replace('index.php')</script>;";
                  header("location: index.php");

      } else {
        // Mensaje de error si el usuario ya existe
        $user_err = "Usuario en uso";
      }
  } 
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Registro</title>
  <meta charset="UTF-8" />
  <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
  <link href="/tfg/ness.css" rel="stylesheet" />

  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <script>
// cambia la imagen de perfil segun se seleccione una u otra
    window.addEventListener("load", function () {
            var perfil = document.getElementById("perf")
            var select = document.getElementById("default_select")

            select.addEventListener("change", mostrarImg);

            function mostrarImg(){
             perfil.setAttribute("class" , select.value)
            }
            
    })

  </script>

  <!-- less -->
  <link rel="stylesheet/less" type="text/css" href="style2.less" />
  <script src="less.min.js" type="text/javascript"></script>


</head>

<body>
  
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

  <div class="register nes-container is-centered">
<!-- formulario de registro -->
    <form method='POST' action="">

      
      <div class="input">
        <label for="nombre">Nombre de Usuario</label>
        <input type="text" id="nombre" name="usuario" class="nes-input" required maxlength="29">
<!-- php que escribe el error en caso de que haya -->
        <?php echo $user_err ?>

      </div>
      <div class="input">
        <label for="pass">Contraseña</label>
        <input type="password" id="pass" name="pass" class="nes-input" required>

        <?php echo $pass_err ?>

      </div>
      <div class="input">
        <label for="pass">Confirma Contraseña</label>
        <input type="password" id="pass2" name="pass2" class="nes-input" required>

        <?php echo $pass2_err ?>

      </div>
      <div class="input">
        <label for="name_field">Fecha de nacimiento</label>
        <input type="date" id="name_field" name="nacimiento" class="nes-input" required>

        <?php echo $naci_err ?>

      </div>
      <!-- selec de la imagen de perfil -->
      <div class="input">
        <label for="default_select">Imagen de perfil</label>
        <div class="nes-select">
          <select required id="default_select" name="perf" required>
            <option value="" disabled selected hidden>Elige...</option>
            <option value="nes-mario">Mario</option>
            <option value="nes-ash">ash</option>
            <option value="nes-pokeball">pokeball</option>
            <option value="nes-bulbasaur">bulbasaur</option>
            <option value="nes-charmander">charmander</option>
            <option value="nes-squirtle">squirtle</option>
            <option value="nes-kirby">kirby</option>
          </select>
        </div>
        <br>
        <!-- area donde muestra la imagen -->
        <i class="" id="perf"></i>

        <?php echo $perf_err ?>

      </div>

      
<!-- boton de enviar registro -->
      <div class="input">
        <button type='submit' name='registrar' class='nes-btn'>Registrar</button>
      </div>
    </form>

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