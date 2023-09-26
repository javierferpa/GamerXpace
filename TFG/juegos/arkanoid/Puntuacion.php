<?php
// archivo que incluye las conexiones
include '../../conexion.php';
// decodifica la puntuacion y el usuario que estaban en json
$datos = json_decode(stripslashes($_POST['datos']));
// recoge los puntos del usuario que esta en el objeto $datos
$MyBBDD->consulta("SELECT * FROM usuarios where id_usu = '" . $datos->user . "'");
    while ($fila = $MyBBDD->extraer_registro()) {

      $punt_actu = $fila['TotalPunt'];
      $puntosMax = $fila['puntosMax'];
      
    }

// suma los puntos que tenia el usuario con los nuevos
    $punt_actu = $punt_actu + $datos->punt;
// actualiza la puntuacion con la nueva
$MyBBDD->consulta("UPDATE usuarios SET TotalPunt= '$punt_actu'  WHERE id_usu = '" . $datos->user . "'");
// comprueba si la puntuacion maxima actual es mayor que la nueva no la actualiza
if( $puntosMax < $datos->punt){
  $MyBBDD->consulta("UPDATE usuarios SET puntosMax= '" . $datos->punt  . "'  WHERE id_usu = '" . $datos->user . "'");

}
// devuelve la puntuacion para comprabarla por consola
echo $datos->punt;

?>