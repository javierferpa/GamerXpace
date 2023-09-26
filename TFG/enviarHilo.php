<?php

include 'conexion.php';

$datos = json_decode(stripslashes($_POST['datos']));
// comprueba si ya existe el hilo
$MyBBDD->consulta("SELECT * FROM `hilos` WHERE `tema` = '" . $datos->tema . "'");
// si no existe lo crea y si no devuelve el error
$fila = $MyBBDD->extraer_registro();
  if($fila == ""){
    $MyBBDD->consulta("INSERT INTO `hilos`(`tema`, `autor`) VALUES ('" . $datos->tema . "', '" . $datos->usu . "')");
  } else {
    echo "El tema ya existe";
  }






?>