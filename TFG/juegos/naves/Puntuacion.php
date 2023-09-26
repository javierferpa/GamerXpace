<?php
// incluye las conexiones a la BBDD
include '../../conexion.php';
// decodifica los datos pasados del js
$datos = json_decode(stripslashes($_POST['datos']));
// recoge de la BBDD la puntuacion total y maxima del usuario
$MyBBDD->consulta("SELECT * FROM usuarios where id_usu = '" . $datos->user . "'");
    while ($fila = $MyBBDD->extraer_registro()) {

      $punt_actu = $fila['totalPuntnav'];
      $puntosMax = $fila['maxPuntnav'];
      
    }

// suma la puntuacion que tenia con la nueva
    $punt_actu = $punt_actu + $datos->punt;
// actualiza la tabla de la BBDD con la nueva puntuacion total
$MyBBDD->consulta("UPDATE usuarios SET totalPuntnav= '$punt_actu'  WHERE id_usu = '" . $datos->user . "'");
// si la nueva puntuacion maxima es supoerior a la anterior la actualiza en la BBDD
if( $puntosMax < $datos->punt){
  $MyBBDD->consulta("UPDATE usuarios SET maxPuntnav= '" . $datos->punt  . "'  WHERE id_usu = '" . $datos->user . "'");

}

echo $datos->punt;

?>