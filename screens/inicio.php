<?php
//Se importa la barra de navegacion y la imagen del tribunal supremo de elecciones
include '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'barra_navegacion_opciones.php';
include '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'imagen_tse_opciones.php';

//Se verifica si no existe la variable '$_SESSION' con el identificador 'NombreCompleto'
if (!isset($_SESSION['NombreCompleto'])) {
    //Si es asi entonces se redirecciona al usuario al formulario de inicio de sesion
    header('location: ../index.php');
}

//Se hace un require_once de la conexion a la base de datos
require('../includes/conexion.php');

//Se genera una consulta la cual devuelve la cantidad de votaciones activas, las cuales se sacan por medio de la fecha y hora actual del sistema
$sql = "SELECT COUNT(PK_IdVotacion) as votacionesActivas " .
       "FROM Votaciones " .
       "WHERE CONCAT(CURDATE(), ' ' ,CURTIME()) BETWEEN FechaInicio AND FechaFin";
$mod = mysql_query($sql, ConectarMySQL::conexion());
while ($result = mysql_fetch_assoc($mod)) {
    //Se asigna a la variable '$numeroVotacionesActivas' la cantidad de votaciones activas obtenidas de la consulta
    $numeroVotacionesActivas = $result["votacionesActivas"];
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sistema de Votaciones</title>
        <link rel="stylesheet" href="../css/styles_screens.css">
        <script type="text/javascript" src="../js/scripts.js"></script>
        <link rel="shortcut icon" href="../images/favicon.ico" />
    </head>
    <!--Cuando carga el formulario de llama a la funcion para que muestre el reloj en la pantalla-->
    <body onload="muestraReloj()">
        <div class="wrap">
        <div id="fechaHoraActual">
            <span id="fechaHoraJavaScript"></span>
            <?php
            //Se generan variables para obtener la fecha y hora actual del sistema
            date_default_timezone_set("America/Costa_Rica");
            'Fecha y hora actual: ' . $fechaHoraActual = date("d-m-Y h:i A", strtotime(date("d-m-Y H:i:s")));
            ?>
        </div>
            <form id="votaciones" name="votaciones" action="votacion.php" method="post">
                <?php 
                /* Se genera una condicion para validar si hay solamente una votacion activa, para asi validar que el titulo sea correcto, ya que si hay solamente
                 * votacion deberá de colocar 'Actualmente hay 1 votación activa', de lo contrario si hay mas de una deberá de colocar 'Actualmente hay <N> votaciones activas'
                 */
                $stringVotacion = ($numeroVotacionesActivas == 1) ? "votaci&oacute;n activa" : "votaciones activas"; ?>
                <center><h1 id="tituloVotaciones">Actualmente hay <span id="tituloVotacionesActivas"><?php echo $numeroVotacionesActivas; ?></span> <?php echo $stringVotacion ?></h1></center>
                <h1>Votaciones</h1>
                <center><span id="nombreOpcion">Ingrese a la votaci&oacute;n</span></center>
                <ul class="votacion index">
                    <br />
                    <?php
                    //Se genera un consulta consulta la cual obtiene las votaciones activas, las cuales se sacan por medio de la fecha y hora actual del sistema
                    $sql = "SELECT PK_IdVotacion, CASE Tipo WHEN 'P' THEN 'Presidencial' WHEN 'M' THEN 'Municipal' WHEN 'R' THEN 'Referendum' END as votacionesActivas " .
                           "FROM Votaciones " .
                           "WHERE CONCAT(CURDATE(), ' ' ,CURTIME()) BETWEEN FechaInicio AND FechaFin " . 
                           "ORDER BY PK_IdVotacion DESC";
                    $req = mysql_query($sql, ConectarMySQL::conexion());
                    while ($result = mysql_fetch_assoc($req)) {
                        //Se genera una lista de las votaciones activas, las cuales cada una tiene un enlace que lleva al usuario a la pantalla de votacion
                        echo '<li><a href="votacion.php?PK_IdVotacion=' . $result["PK_IdVotacion"] . '">' . $result["votacionesActivas"] . '</a></li>';
                    }
                    ?>
                </ul>
            </form>
        </div>
    </body>
</html>