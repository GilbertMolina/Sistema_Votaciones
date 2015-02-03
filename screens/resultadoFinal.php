<?php
//Se importa la barra de navegacion y la imagen del tribunal supremo de elecciones
include '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'barra_navegacion_opciones.php';
include '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'imagen_tse_opciones.php';

//Se hace un require_once de la conexion a la base de datos
require('../includes/conexion.php');

//Se genera una consulta que otiene el id votacion de la ultima votacion activa
$sql = "SELECT PK_IdVotacion FROM Votaciones WHERE FechaFin = (SELECT MAX(FechaFin) FROM Votaciones)";
$mod = mysql_query($sql, ConectarMySQL::conexion());
while ($result = mysql_fetch_assoc($mod)) {
    //Se crea una variable '$PK_IdVotacion' y se le asigna el id votacion 'PK_IdVotacion' obtenido de la consulta
    $PK_IdVotacion = $result["PK_IdVotacion"];
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
        <div class="wrapResultadoFinal">
        <div id="fechaHoraActualResultadoFinal">
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
                                WHERE PK_IdVotacion = " . $PK_IdVotacion;
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
                        echo "<h1>La &uacute;ltima votaci&oacute;n estuvo activa hasta el " . $fechaFinVotacion . " " . $articuloCorrecto . " " . $horaFinVotacion . " y fue la siguiente</h1>";
                        if ($votacionActiva != 'Referendum') {
                            //Se muestra el titulo de la votacion
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
                        echo '<a title="' . $partido . ' - ' . $nombreCandidado . '" href="mapaVotacionesFinal.php?PK_IdVotacion=' . $PK_IdVotacion . '&PK_IdOpcion=' . $PK_IdOpcion . '"><div class="barra" style="width:' . ($cantidadVotosOpcion * 100 / $sumaTotalVotos) . '%;">' . round($cantidadVotosOpcion * 100 / $sumaTotalVotos) . '%</div></a></li>';
                    }
                }
                echo '</ul>';
                //Se verifica si la varaible '$aux' existe
                if (isset($aux)) {
                    //Si es asi entonces se muestra la final al usuario la suma de votos de la votacion
                    echo '<span id="frSuma"><b>Total Votos:</b> ' . $sumaTotalVotos . '</span>';
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