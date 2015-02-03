<?php
//Se importa la barra de navegacion y la imagen del tribunal supremo de elecciones
include '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'barra_navegacion_opciones.php';
include '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'imagen_tse_opciones.php';
//Se hace un require_once de la conexion a la base de datos
require('../includes/conexion.php');

//Se verifica si existe la variable '$_GET' con el identificador 'PK_IdVotacion' y la variable '$_GET' con el identificador 'PK_IdOpcion'
if (isset($_GET['PK_IdVotacion']) && isset($_GET['PK_IdOpcion'])) {
//Se obtiene por medio de '$_GET' el id de la votacion 'PK_IdVotacion'
$PK_IdVotacion = $_GET['PK_IdVotacion'];
//Se obtiene por medio de '$_GET' el id de la opcion 'PK_IdOpcion'
$PK_IdOpcion = $_GET['PK_IdOpcion'];
} else {
    //Si es asi entonces se redirecciona al usuario al formulario de inicio de sesion
    header('location: ../index.php');
}

//Se declaran las variables que almacenaran el nombre del Partido y el nombre del Candidato
$nombrePartido = '';
$nombreCandidado = '';

//Se declaran las variables que almacenaran la cantidad de votos por cada provincia
$cantidadVotosGuanacaste = '';
$cantidadVotosAlajuela = '';
$cantidadVotosHeredia = '';
$cantidadVotosSanJose = '';
$cantidadVotosCartago = '';
$cantidadVotosLimon = '';
$cantidadVotosPuntarenas = '';

//Se genera la consulta para obtener el nombre del Partido y el nombre del Candidato
$sql = "SELECT Partido, Candidato FROM Votaciones_Opciones WHERE FK_IdVotacion = " . $PK_IdVotacion . " AND PK_IdOpcion = " . $PK_IdOpcion;
$mod = mysql_query($sql, ConectarMySQL::conexion());
while ($result = mysql_fetch_assoc($mod)) {
    $nombrePartido = $result["Partido"];
    $nombreCandidado = $result["Candidato"];
}

//Se generan la consulta que traen la cantidad de votos para la provincia de Guanacaste
$sql = "SELECT COUNT(vt.PK_Cedula) as cantidadVotantesProvincia FROM Votos vt INNER JOIN Personas p ON p.PK_Cedula = vt.PK_Cedula WHERE vt.PK_IdVotacion = " . $PK_IdVotacion . " AND vt.FK_IdOpcion = " . $PK_IdOpcion . " AND p.Provincia = 'Guanacaste'";
$mod = mysql_query($sql, ConectarMySQL::conexion());
while ($result = mysql_fetch_assoc($mod)) {
    $cantidadVotosGuanacaste = $result["cantidadVotantesProvincia"];
}

//Se generan la consulta que traen la cantidad de votos para la provincia de Alajuela
$sql = "SELECT COUNT(vt.PK_Cedula) as cantidadVotantesProvincia FROM Votos vt INNER JOIN Personas p ON p.PK_Cedula = vt.PK_Cedula WHERE vt.PK_IdVotacion = " . $PK_IdVotacion . " AND vt.FK_IdOpcion = " . $PK_IdOpcion . " AND p.Provincia = 'Alajuela'";
$mod = mysql_query($sql, ConectarMySQL::conexion());
while ($result = mysql_fetch_assoc($mod)) {
    $cantidadVotosAlajuela = $result["cantidadVotantesProvincia"];
}

//Se generan la consulta que traen la cantidad de votos para la provincia de Heredia
$sql = "SELECT COUNT(vt.PK_Cedula) as cantidadVotantesProvincia FROM Votos vt INNER JOIN Personas p ON p.PK_Cedula = vt.PK_Cedula WHERE vt.PK_IdVotacion = " . $PK_IdVotacion . " AND vt.FK_IdOpcion = " . $PK_IdOpcion . " AND p.Provincia = 'Heredia'";
$mod = mysql_query($sql, ConectarMySQL::conexion());
while ($result = mysql_fetch_assoc($mod)) {
    $cantidadVotosHeredia = $result["cantidadVotantesProvincia"];
}

//Se generan la consulta que traen la cantidad de votos para la provincia de SanJose
$sql = "SELECT COUNT(vt.PK_Cedula) as cantidadVotantesProvincia FROM Votos vt INNER JOIN Personas p ON p.PK_Cedula = vt.PK_Cedula WHERE vt.PK_IdVotacion = " . $PK_IdVotacion . " AND vt.FK_IdOpcion = " . $PK_IdOpcion . " AND p.Provincia = 'San José'";
$mod = mysql_query($sql, ConectarMySQL::conexion());
while ($result = mysql_fetch_assoc($mod)) {
    $cantidadVotosSanJose = $result["cantidadVotantesProvincia"];
}

//Se generan la consulta que traen la cantidad de votos para la provincia de Cartago
$sql = "SELECT COUNT(vt.PK_Cedula) as cantidadVotantesProvincia FROM Votos vt INNER JOIN Personas p ON p.PK_Cedula = vt.PK_Cedula WHERE vt.PK_IdVotacion = " . $PK_IdVotacion . " AND vt.FK_IdOpcion = " . $PK_IdOpcion . " AND p.Provincia = 'Cartago'";
$mod = mysql_query($sql, ConectarMySQL::conexion());
while ($result = mysql_fetch_assoc($mod)) {
    $cantidadVotosCartago = $result["cantidadVotantesProvincia"];
}

//Se generan la consulta que traen la cantidad de votos para la provincia de Limon
$sql = "SELECT COUNT(vt.PK_Cedula) as cantidadVotantesProvincia FROM Votos vt INNER JOIN Personas p ON p.PK_Cedula = vt.PK_Cedula WHERE vt.PK_IdVotacion = " . $PK_IdVotacion . " AND vt.FK_IdOpcion = " . $PK_IdOpcion . " AND p.Provincia = 'Limón'";
$mod = mysql_query($sql, ConectarMySQL::conexion());
while ($result = mysql_fetch_assoc($mod)) {
    $cantidadVotosLimon = $result["cantidadVotantesProvincia"];
}

//Se generan la consulta que traen la cantidad de votos para la provincia de Puntarenas
$sql = "SELECT COUNT(vt.PK_Cedula) as cantidadVotantesProvincia FROM Votos vt INNER JOIN Personas p ON p.PK_Cedula = vt.PK_Cedula WHERE vt.PK_IdVotacion = " . $PK_IdVotacion . " AND vt.FK_IdOpcion = " . $PK_IdOpcion . " AND p.Provincia = 'Puntarenas'";
$mod = mysql_query($sql, ConectarMySQL::conexion());
while ($result = mysql_fetch_assoc($mod)) {
    $cantidadVotosPuntarenas = $result["cantidadVotantesProvincia"];
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
        <div class="wrapMapa">
            <div id="fechaHoraActualMapa">
                <span id="fechaHoraJavaScript"></span>
            </div>
            <form id="votaciones" name="votaciones" action="votacion.php" method="post">
                <!--Se le muestra el titulo al usuario-->
                <h1>Votaciones por provincia para <?php echo ($PK_IdVotacion != 3)? 'la votaci&oacute;n: ': 'la pregunta: ';?> <?php echo $nombrePartido; ?></h1>
                <center><h2 style="font-size: 16px;">Para ver la cantidad de votos por provincia posicione el ratón sobre cada uno de los nombres de las provincias</h2></center>
                <img id="mapaVotaciones" src="../images/mapaCostaRica.png" width="145" height="126" alt="Mapa de Costa Rica" usemap="#mapaCostaRica">
                <br />
                <a href="resultadoFinal.php?PK_IdVotacion=<?php echo $PK_IdVotacion; ?>"><button id="volverResultados" name="volverResultados" type="button"><img src="../images/atras.png" style="vertical-align: text-bottom; width: 22px; height: 22px;" alt="Volver"><span style="vertical-align: baseline"> &nbsp;Volver</span></button></a>
                <br />
                <br />
                <!--Se generan las coordenadas del mapa para los tooltips-->
                <map name="mapaCostaRica">
                    <area circle="Guanacaste" coords="95,120,40" alt="Guanacaste" title="Total de votos para '<?php echo $nombreCandidado; ?>' en Guanacaste: <?php echo $cantidadVotosGuanacaste; ?> " href="#">
                    <area circle="Alajuela" coords="218,115,40" alt="Alajuela" title="Total de votos para '<?php echo $nombreCandidado; ?>' en Alajuela: <?php echo $cantidadVotosAlajuela; ?>"  href="#">
                    <area circle="Heredia" coords="290,135,35" alt="Heredia" title="Total de votos para '<?php echo $nombreCandidado; ?>' en Heredia: <?php echo $cantidadVotosHeredia; ?>"  href="#">
                    <area circle="SanJose" coords="247,235,40" alt="SanJos&eacute;" title="Total de votos para '<?php echo $nombreCandidado; ?>' en San Jos&eacute;: <?php echo $cantidadVotosSanJose; ?>"  href="#">
                    <area circle="Cartago" coords="335,220,35" alt="Cartago" title="Total de votos para '<?php echo $nombreCandidado; ?>' en Cartago: <?php echo $cantidadVotosCartago; ?>"  href="#">
                    <area circle="Limon" coords="415,220,40" alt="Lim&oacute;n" title="Total de votos para '<?php echo $nombreCandidado; ?>' en Lim&oacute;n: <?php echo $cantidadVotosLimon; ?> " href="#">
                    <area circle="Puntarenas" coords="400,355,45" alt="Puntarenas" title="Total de votos para '<?php echo $nombreCandidado; ?>' en Puntarenas: <?php echo $cantidadVotosPuntarenas; ?>"  href="#">
                </map>
            </form>
        </div>
        <br />
        <br />
        <br />
    </body>
</html>