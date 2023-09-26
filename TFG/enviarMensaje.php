<?php
include 'conexion.php';

$datos = json_decode(stripslashes($_POST['datos']));
// inserta los datos en la BBDD
$MyBBDD->consulta("INSERT INTO `Mensajes`(`autor`, `mensaje`, `fk_id_hilo`) VALUES ('" . $datos->usu . "', '" . $datos->mens . "','" . $datos->idHilo . "')");


echo $datos->idHilo . " " . $datos->mens  . " " . $datos->usu;

?>