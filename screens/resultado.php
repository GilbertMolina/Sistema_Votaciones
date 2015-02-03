<?php
//Se importa la barra de navegacion y la imagen del tribunal supremo de elecciones
include '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'barra_navegacion_opciones.php';
include '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'imagen_tse_opciones.php';

//Se hace un require_once de la conexion a la base de datos
require('../includes/conexion.php');

//Se verifica si no existe la variable '$_SESSION' con el identificador 'NombreCompleto'
if (!isset($_SESSION['NombreCompleto'])) {
    //Si es asi entonces se redirecciona al usuario al formulario de inicio de sesion
    header('location: ../index.php');
}

//Se recibe por '$_GET' el id de la votacion 'PK_IdVotacion'
$PK_IdVotacion = $_GET['PK_IdVotacion'];

//Se verifica si no existe el id de la votacion 'PK_IdVotacion' pasado por '$_GET'
if (!isset($_GET['PK_IdVotacion'])) {
    //Si es asie entonces se redirecciona al usuario a la pantalla de inicio
    header('location: inicio.php');
}

//Se crea una variable que almacenara la cantidad total de votos de una votacion
$sumaTotalVotos = 0;

//Se genera la consulta que obtiene el total de votos para la votacion actual
$sql = "SELECT COUNT(PK_Cedula) as cantidadVotosOpcion FROM Votos WHERE PK_IdVotacion = " . $PK_IdVotacion;
$mod = mysql_query($sql, ConectarMySQL::conexion());
while ($result = mysql_fetch_assoc($mod)) {
    //Se almacenara en la variable '$sumaTotalVotos' el total de votos obtenidos de la consulta
    $sumaTotalVotos = $result["cantidadVotosOpcion"];
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
        <div class="wrapResultado">
        <div id="fechaHoraActualResultado">
            <span id="fechaHoraJavaScript"></span>
            <?php
            //Se generan variables para obtener la fecha y hora actual del sistema
            date_default_timezone_set("America/Costa_Rica");
            'Fecha y hora actual: ' . $fechaHoraActual = date("d-m-Y h:i A", strtotime(date("d-m-Y H:i:s")));
            ?>
        </div>
            <form action="" method="post">
                <?php
                //Se declara una variable auxiliar que será utilizada mas adelante
                $aux = 0;
                //Se genere un consulta la cual mostrara al usuario la votacion y las opciones de la votacion con su totalidad de votos por cada opcion
                $sqlVotacion = "SELECT DISTINCT v.PK_IdVotacion, v.Tipo, CASE Tipo WHEN 'P' THEN 'Presidencial' WHEN 'M' THEN 'Municipal' WHEN 'R' THEN 'Referendum' END as 'votacionActiva', 
                                       DATE_FORMAT(v.FechaInicio, '%d-%m-%Y') as fechaInicio, DATE_FORMAT(v.FechaInicio, '%h:%i %p') as horaInicio, 
                                       DATE_FORMAT(v.FechaFin, '%d-%m-%Y') as fechaFin, DATE_FORMAT(v.FechaFin, '%h:%i %p') as horaFin, vo.PK_IdOpcion, vo.Bandera, vo.Partido, vo.Candidato 
                                FROM Votaciones v 
                                INNER JOIN Votaciones_Opciones vo 
                                ON v.PK_IdVotacion = vo.FK_IdVotacion 
                                WHERE CONCAT(CURDATE(),' ',CURTIME()) BETWEEN v.FechaInicio AND v.FechaFin 
                                AND PK_IdVotacion = " . $PK_IdVotacion;
                $reqVotacion = mysql_query($sqlVotacion, ConectarMySQL::conexion());
                while ($votosVotacion = mysql_fetch_assoc($reqVotacion)) {
                    /* Se obtienen algunos datos de la consulta, como el id de la opcion 'PK_IdOpcion', el nombre de la votacion 'votacionActiva', el nombre del candidato 'Candidato',
                     * la fecha fin de la votacion 'fechaFin' y la hora fin de la votacion 'horaFin'
                     */
                    $PK_IdOpcion = $votosVotacion["PK_IdOpcion"];
                    $partido = $votosVotacion["Partido"];
                    $votacionActiva = $votosVotacion["votacionActiva"];
                    $nombreCandidado = $votosVotacion["Candidato"];
                    $fechaFinVotacion = $votosVotacion["fechaFin"];
                    $horaFinVotacion = $votosVotacion["horaFin"];

                    //Por cada opcion se consulta la totalidad de votos para esa opcion
                    $sqlVotacionOpcion = "SELECT COUNT(PK_Cedula) as cantidadVotosOpcion FROM Votos WHERE PK_IdVotacion = " . $PK_IdVotacion . " AND FK_IdOpcion = " . $PK_IdOpcion;
                    $reqVotacionOpcion = mysql_query($sqlVotacionOpcion, ConectarMySQL::conexion());
                    while ($votosOpcion = mysql_fetch_assoc($reqVotacionOpcion)) {
                        //Se declara una variable '$cantidadVotosOpcion' y se le asigna la totalidad de votos para la opcion actual 'cantidadVotosOpcion'
                        $cantidadVotosOpcion = $votosOpcion["cantidadVotosOpcion"];
                    }
                    //Se verifica si es la primer iteracion para colocar la fecha limite de la votacion y el titulo del nombre de la votacion
                    if ($aux == 0) {
                        //Se declara una variable '$hora' la cual almacenara el numero de la hora fin de la votacion, si son las '02:00 p.m.' almanacera '2'
                        $hora = substr($horaFinVotacion, 1, 1);
                        //Se declara una variable que almanacera el articulo correcto dependiendo de la hora, si es la '01:00 p.m.' deberá de almanacer 'la', de lo contrario deberá almanacer 'las'
                        $articuloCorrecto = ($hora == 1) ? "a la" : "a las";
                        //Se muestra la fecha fin de la votacion
                        echo "<h1>Esta votaci&oacute;n estar&aacute; activa hasta el " . $fechaFinVotacion . " " . $articuloCorrecto . " " . $horaFinVotacion . "</h1>";
                        //Se muestra el titulo de la votacion
                        if ($votacionActiva != 'Referendum') {
                            echo '<h1>Votaci&oacute;n ' . $votacionActiva . '</h1>';
                            echo '<center><h2 style="font-size: 15px;">Para ver los resultados por provincia haga clic sobre la barra de de la votaci&oacute;n deseada</h2></center>';
                        } else {
                            echo '<h1>' . $votacionActiva . '</h1>';
                            echo '<center><h2 style="font-size: 15px;">Para ver los resultados por provincia haga clic sobre la barra de la pregunta deseada</h2></center>';
                        }
                        echo '<br/>';
                        echo "<ul class='votacion'>";
                        $aux = 1;
                    }
                    //Se muestra el nombre de la opcion con la cantidad de votos de la opcion
                    echo '<li><div class="fl"><b>' . $nombreCandidado . '</b></div><div class="fr"><b>Votos:</b> ' . $cantidadVotosOpcion . '</div>';
                    //Se verifica si la suma de votos de la opcion es igual 0
                    if ($sumaTotalVotos == 0) {
                        //Se muestra la barra de porcentaje en 0%
                        echo '<div class="barra cero" style="width:0%;"></div></li>';
                    } else {
                        /* De lo contrario de muestra la barra haciendo uso de algunos calculo como para sacar el porcentaje de la opcion, este se calcula tomando la totalidad de votos de la opcion, 
                         * multiplicandolo por 100 y diviendolo entre la suma de votos de la votacion, ademas se muestra el porcentaje pero redondeado a la decenima mas proxima para que sea mas agradable
                         * de ver para el usuario
                         */
                        echo '<a title="' . $partido . ' - ' . $nombreCandidado . '" href="mapaVotaciones.php?PK_IdVotacion=' . $PK_IdVotacion . '&PK_IdOpcion=' . $PK_IdOpcion . '"><div class="barra" style="width:' . ($cantidadVotosOpcion * 100 / $sumaTotalVotos) . '%;">' . round($cantidadVotosOpcion * 100 / $sumaTotalVotos) . '%</div></a></li>';
                    }
                }
                echo '</ul>';
                //Se verifica si la variable '$aux' existe
                if (isset($aux)) {
                    //Si es asi entonces se muestra la final al usuario la suma de votos de la votacion
                    echo '<span id="frSuma"><b>Total Votos:</b> ' . $sumaTotalVotos . '</span>';
                    echo '<br />';
                    echo '<br />';
                    //Se muestra un boton al usuario para que pueda volver a la votacion
                    echo '<a href="votacion.php?PK_IdVotacion=' . $PK_IdVotacion . '"><button id="volverVotacion" name="volverVotacion" type="button"><img style="vertical-align: text-bottom" src="../images/atras.png" alt="Volver" width="22" height="22"/><span style="vertical-align: baseline"> &nbsp;Volver</span></button></a>';
                    echo '<br />';
                    echo '<br />';
                }
                ?>
                </ul>
            </form>
        </div>
        <br />
        <br />
    </body>
</html>