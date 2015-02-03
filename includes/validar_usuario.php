<?php
//Se invoca a la funcion 'session_start()' la cual inicia una nueva sesion
session_start();

//Se crea la clase 'ValidarIngreso'
class ValidarIngreso {

    //Funcion que recibe una consulta y retorna los datos devueltos o nulo
    public function query($pQuery) {
        /* Se declara una variable de tipo objeto '$oResult' y se le asigna lo que devuelve la consulta por medio de la funcion 'mysql_query()', 
         * pasandole como para parametros la consulta y la variable 'conexion()' de la clase 'ConectarMySQL'
         */
        $oResult = mysql_query($pQuery, ConectarMySQL::conexion());
        //Se verifica si el objeto '$oResult' seteado en la linea anterior es diferente a false
        if ($oResult != false) {
            //Se crea una variable de tipo arreglo
            $aData = array();
            //Se crea una variable que por medio de la funcion 'mysql_num_rows()' contendrá el numero de filas del objeto '$oResult'
            $iFilas = mysql_num_rows($oResult);

            //Se verifica si '$iFilas' es mayor a 0
            if ($iFilas > 0) {
                /* Se genera un ciclo que recorrera el objeto '$oResult' por medio de la funcion 'mysql_fetch_array()'
                 * y se lo asignara a la variable '$row'
                 */
                while ($row = mysql_fetch_array($oResult, MYSQL_ASSOC)) {
                    //Por cada fila recorrida lo que contenta el objeto '$row' se va almacenando en el arreglo '$aData[]'
                    $aData[] = $row;
                }
                //Si '$iFilas' es mayor a 0 se retorna el arreglo '$aData'
                return $aData;
            } else {
                //Si '$iFilas' es igual a 0 se retorna null
                return null;
            }
        } else {
            //Se el objeto '$oResult' es false se retorna null
            return null;
        }
    }

    //Funcion que valida el ingreso del usuario a la aplicacion, si el ingreso es erroneo va a retornar el error
    public function validar() {
        //Se verifica si el formulario fue enviado
        if ($_POST) {
            //Se crea un arreglo de errores, el cual será para almacenar si los campos estaban en blanco o si las credenciales son incorrectas
            $aErrores = array('campos_en_blanco' => "", 'credenciales_incorrectas' => "");
            //Se almacena en la variable '$cedula' lo que venga del formulario en el input 'cedula'
            $cedula = $_POST["cedula"];
            //Se almacena en la variable '$contrasena' lo que venga del formulario en el input 'contrasena' y se encripta con la funcion 'md5()'
            $contrasena = md5($_POST["contrasena"]);

            //Se verifica si la cedula ingresada por el usuario y la contraseña estan vacias
            if (strlen($_POST["cedula"]) == 0 || strlen($_POST["contrasena"]) == 0) {
                /* En caso de que esten vacias se almacena en arreglo errores, en la indice 'campos_en_blanco', un mensaje avisandole al usuario que
                 * se deben de ingresar ambos cammpos
                 */
                $aErrores['campos_en_blanco'] = "Debe de proveer tanto el n&uacute;mero de c&eacute;dula como su contrase&ntilde;a para continuar.";

                //Se retorna el mensaje de error para mostrarselo al usuario
                echo "<br /><center><span style='color: red; text-align: center; width: 150px;'><b>" . $aErrores['campos_en_blanco'] . "</b></span></center><br/>";
                
                //La variable '$exito' se setea a '0'
                $exito = 0;
            } else {
                //Se declara la variable '$sqlDatos' y se le asigna la consulta que verificará si la cedula y contraseña digitadas por el usuario existen
                $sqlDatos = "SELECT PK_Cedula, Nombre, Apellido1, Apellido2, Contrasena
                             FROM Personas
                             WHERE PK_Cedula = '$cedula'
                             AND Contrasena = '$contrasena'";
                /* Se crea una variable '$resultado' que almacenara los datos proveeidos por la consulta, esto es por medio de la funcion 'query()', esta funcion
                 * recibe como parametro la consulta que almacena la variable $sqlDatos'
                 */
                $resultado = $this->query($sqlDatos);

                //Se verifica si la variable '$resultado' es igual a NULL
                if ($resultado == NULL) {
                    //Si es asi la variable '$exito' se setea a '0'
                    $existe = 0;
                } else {
                    //Si no lo es, entonces la variable '$exito' se setea a '1'
                    $existe = 1;
                }

                //Se verifica si '$existe' es diferente a '0'
                if ($existe != 0) {
                    /* Se recorre la variable '$resultado' para obtener los datos que obtuvo la consulta, como lo son el numero de cedula, el nombre de la persona,
                     * el primer apellido, el segundo apellido y la contraseña
                     */
                    foreach ($resultado as $resultadoP) {
                        $cedulaResultado = $resultadoP['PK_Cedula'];
                        $nombreResultado = $resultadoP['Nombre'];
                        $apellido1Resultado = $resultadoP['Apellido1'];
                        $apellido2Resultado = $resultadoP['Apellido2'];
                        $contrasenaResultado = $resultadoP['Contrasena'];
                    }
                    //Se asigna a la sesion activa los resultados obtenidos en el foreach anterior, para hacer uso de la variable '$_SESSION' mas adelante en la aplicacion
                    $_SESSION["PK_Cedula"] = $cedulaResultado;
                    $_SESSION["NombreCompleto"] = $nombreResultado . ' ' . $apellido1Resultado . ' ' . $apellido2Resultado;
                    $_SESSION["Nombre"] = $nombreResultado;
                    $_SESSION["Apellido1"] = $apellido1Resultado;
                    $_SESSION["Apellido2"] = $apellido2Resultado;
                    $_SESSION["Contrasena"] = $contrasenaResultado;
                    
                    //La variable '$exito' se setea a '1'
                    $exito = 1;
                    //Se redirecciona al usuario a la pagina de inicio para que pueda ver las votaciones activas
                    header("Location: screens/inicio.php");
                    
                } else {
                    //Si no lo es, y '$existe' es igual a 0, entonces se muestra al usuario el mensaje de error de que no existe un usuario con la cedula y contraseña ingresadas
                    $aErrores['credenciales_incorrectas'] = "No existe un usuario con las credenciales ingresadas.";
                    
                    //Se retorna el mensaje de error para mostrarselo al usuario
                    echo "<br /><center><span style='color: red; text-align: center; width: 150px;'><b>" . $aErrores['credenciales_incorrectas'] . "</b></span></center><br/>";
                    
                    //La variable '$exito' se setea a '0'
                    $exito = 0;
                }
            }
        }
    }

    /* Funcion que valida si al cargar la aplicacion existen votaciones activas, de lo contrario se redirecciona al usuario inmediatamente a la pantalla de resultados finales
     * mostrandole la ultima votacion activa
     */
    public function votacionesActivas() {
        //Se declara una variable la cual será para contar el numero de votaciones activas
        $contadorRegistros = '';
        //Se declara la variable '$sql' y se le asigna la consulta que verificará si existen votaciones activas
        $sql = "SELECT PK_IdVotacion, CASE Tipo WHEN 'P' THEN 'Presidencial' WHEN 'M' THEN 'Municipal' WHEN 'R' THEN 'Referendum' END as votacionesActivas " .
               "FROM Votaciones " .
               "WHERE concat(curdate(), ' ' ,curtime()) BETWEEN FechaInicio AND FechaFin " . 
               "ORDER BY PK_IdVotacion DESC";
        /* Se declara una variable de tipo objeto '$req' y se le asigna lo que devuelve la consulta por medio de la funcion 'mysql_query()', 
         * pasandole como para parametros la consulta y la variable 'conexion()' de la clase 'ConectarMySQL'
         */
        $req = mysql_query($sql, ConectarMySQL::conexion());
        /* Se genera un ciclo que recorrera el objeto '$req' por medio de la funcion 'mysql_fetch_assoc()' 
         * y se lo asignara a la variable '$result'
         */
        while ($result = mysql_fetch_assoc($req)) {
            //Si el objeto '$req' retorna datos, entonces se va aumentando la variable '$contadorRegistros'
            $contadorRegistros++;
        }
        //Se verifica si luego de todo el ciclo, la variable '$contadorRegistros' es igual a 0
        if ($contadorRegistros == 0) {
            //Si es asi entonces se redireciona al usuario a la pantalla de resultados finales, mostrandole la ultima votacion activa
            header('location: screens/resultadoFinal.php');
        }
    }

}
?>