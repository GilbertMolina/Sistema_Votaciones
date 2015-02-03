<?php
//Se hace un require_once de la conexion a la base de datos
require_once ("conexion.php");

//Se crea la clase 'registrarPersonas'
class registrarPersonas {

    //Se crea una variable '$consultas' privada para almacenar las consultas
    private $consultas;

    //Funcion para el constructor
    public function __construct() {
        //La variable 'consultas' Se le asigna un arreglo vacio
        $this->consultas = array();
    }

    /* Se crea la funcion 'registrarPersona()' la cual se encarga de agregar una nueva persona, esta funcion recibe como parametro
     * los datos del usuario como lo son el numero de cedula, el nombre, primer apellido, segundo apellido y la contraseña del mismo
     */
    public function registrarPersona($cedula, $nombre, $apellido1, $apellido2, $provincia, $contraseña) {
        //El parametro '$contraseña' se encripta por medio de la funcion 'md5()'
        $contraseña = md5($contraseña);
        
        //Se declara la variable '$sql' y se le asigna la el insert a la base de datos con los datos proveeidos por el usuario
        $sql = "INSERT INTO Personas (PK_Cedula, Nombre, Apellido1, Apellido2, Provincia, Contrasena) 
                VALUES ('$cedula','$nombre','$apellido1','$apellido2','$provincia','$contraseña') ";
        
        /* Se declara una variable de tipo objeto '$registroPersona' y se le asigna lo que devuelve la consulta por medio de la funcion 'mysql_query()', 
         * pasandole como para parametros el insert y la variable 'conexion()' de la clase 'ConectarMySQL'
         */
        $registroPersona = mysql_query($sql, ConectarMySQL::conexion());
        
        //Se redirecciona al usuario a la pagina de inicio de sesion
        header("location: ../index.php");
    }

}

?>