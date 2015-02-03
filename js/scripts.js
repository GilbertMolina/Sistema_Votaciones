//Se crea la funcion que se encarga de validar los datos que digita el usuario en el formulario de registro de personas
function validarRegistroPersonas() {
    var a = document.forms["registro_de_personas"]["cedula"].value;
    var b = document.forms["registro_de_personas"]["nombre"].value;
    var c = document.forms["registro_de_personas"]["apellido1"].value;
    var d = document.forms["registro_de_personas"]["apellido2"].value;
    var e = document.getElementById("provincia");
    var f = document.forms["registro_de_personas"]["contrasena1"].value;
    var g = document.forms["registro_de_personas"]["contrasena2"].value;
    var camposCorrectos = true;

    //Se valida la cedula, si la cedula no cumple se le avisa al usuario y retorna false
    if (a == 0 || a == "") {
        alert("Debe ingresar su c\u00e9dula.");
        document.registro_de_personas.cedula.select();
        camposCorrectos = false;
        return false;
    }

    if (!/^([0-9.])*$/.test(a)) {
        alert("La c\u00e9dula no es v\xe1lida, debe ser un valor n\xfamerico");
        document.registro_de_personas.cedula.select();
        camposCorrectos = false;
        return false;
    }

    //Se valida el nombre, si el nombre no cumple se le avisa al usuario y retorna false
    if (b == 0 || b == "") {
        alert("Debe ingresar su nombre.");
        document.registro_de_personas.nombre.select();
        camposCorrectos = false;
        return false;
    }
    if (/^([0-9])*$/.test(b)) {
        alert("El nombre no es v\xe1lido, debe ser texto.");
        document.registro_de_personas.nombre.select();
        camposCorrectos = false;
        return false;
    }

    //Se valida el primer apellido, si el primer apellido no cumple se le avisa al usuario y retorna false
    if (c == 0 || c == "") {
        alert("Debe ingresar su primer apellido.");
        document.registro_de_personas.apellido1.select();
        camposCorrectos = false;
        return false;
    }
    if (/^([0-9])*$/.test(c)) {
        alert("El apellido no es v\xe1lido, debe ser texto.");
        document.registro_de_personas.apellido1.select();
        camposCorrectos = false;
        return false;
    }

    //Se valida el segundo apellido, si el segundo apellido no cumple se le avisa al usuario y retorna false
    if (d == 0 || d == "") {
        alert("Debe ingresar su segundo apellido.");
        document.registro_de_personas.apellido2.select();
        camposCorrectos = false;
        return false;
    }
    if (/^([0-9])*$/.test(d)) {
        alert("El apellido no es v\xe1lido, debe ser texto.");
        document.registro_de_personas.apellido2.select();
        camposCorrectos = false;
        return false;
    }

    //Se valida se ha seleccionado una provincia de la lista, si no es así se le avisa al usuario y retorna false
    if (e.selectedIndex == 0){
        alert("Debe seleccionar una provincia de la lista.");
        camposCorrectos = false;
        return false;
    }
    //else{
        //alert('La opción seleccionada es: ' + e.options[e.selectedIndex].value);
        //registrarPersona = true;
    //}

    //Se valida la contraseña, si la contraseña no cumple se le avisa al usuario y retorna false
    if (f == 0 || f == "") {
        alert("Debe ingresar una contrase\xf1a.");
        document.registro_de_personas.contrasena1.select();
        camposCorrectos = false;
        return false;
    }

    //Se valida la confirmacion de la contraseña, si la confirmacion de la contraseña no cumple se le avisa al usuario y retorna false
    if (g == 0 || g == "") {
        alert("Debe confirmar la contrase\xf1a.");
        document.registro_de_personas.contrasena2.select();
        camposCorrectos = false;
        return false;
    }

    //Se valida que la contraseña ingresada y la contraseña confirmada sean iguales, si esto no cumple se le avisa al usuario y retorna false
    if (f != g) {
        alert("Las contrase\xf1as no coinciden.");
        document.registro_de_personas.contrasena2.select();
        camposCorrectos = false;
        return false;
    }
    
    //Se le informa al usuario de que la persona ha sido registrada
    if(camposCorrectos) {
        alert("El usuario ha sido registrado, a continuaci\u00f3n ser\xe1 redireccionado a la pantalla inicio de sesi\u00f3n.\n\nMuchas gracias.");
    }

    //Si los datos anteriores son correctos se retorna true
    return true;
}

function validarIngresoFechas() {
    var fechaInicio = document.forms["nuevaVotacion"]["fechaInicio"].value;
    var fechaFin = document.forms["nuevaVotacion"]["fechaFin"].value;

    if (fechaInicio == 0 || fechaInicio == "") {
        alert("Debe ingresar la fecha de inicio de la votaci\xf3n.");
        document.nuevaVotacion.fechaInicio.select();
        return false;
    }

    if (fechaFin == 0 || fechaFin == "") {
        alert("Debe ingresar la fecha de fin de la votaci\xf3n.");
        document.nuevaVotacion.fechaFin.select();
        return false;
    }

    return true;
}

//Se crea la funcion que se encarga de mostrar la fecha actualizado segundo a segundo al usuario
function muestraReloj() {
    //Se declaran las variables necesarias
    var puntos = ":";
    var fecha = new Date();
    var dia = fecha.getDate();
    var mes = fecha.getMonth() + 1;
    var anno = fecha.getFullYear();
    var hora = fecha.getHours();
    var horaAux = fecha.getHours();
    var minuto = fecha.getMinutes();
    var segundos = fecha.getSeconds();
    var meridiano = " a.m.";

    //Se pone un 0 a la izquierda en caso de que el mes sea menor a 10
    if (mes < 10) {
        mes = '0' + mes;
    }
    //Para cambiar el reloj de hora militar a normal entonces se valida si la hora es mayor a 12, entonces se le resta 12 a la hora y se cambia el merdiano a 'p.m.'
    if (hora > 12) {
        hora -= 12;
        meridiano = " p.m.";
    }
    //Se pone un 0 a la izquierda en caso de que la hora sea menor a 10
    if (hora < 10) {
        hora = "0" + hora;
    }
    /* Cuando son las 12 media noche, la hora por venir en militar se cambia a '00', entonces se arregla verificando si la hora es igual a '00', si es asi
     * entonces se asigna '12' a la hora
     */
    if (hora == 00) {
        hora = '12';
    }
    /* Si son las 12 media noche entonces el meridiano se asigna a 'a.m.', esto se arregla haciendo uso de un variable auxiliar para la hora, entonces se verifica
     * si la hora auxiliar actual es '00' entonces el meridiano se asigna a 'a.m.', de lo contrario si la hora auxiliar actual es '12' entonces el meridiano se asigna a 'p.m.'
     */
    if (hora == 12) {
        if (horaAux == 00) {
            meridiano = " a.m.";
        } else {
            meridiano = " p.m.";
        }
    }
    //Se pone un 0 a la izquierda en caso de que el minuto sea menor a 10
    if (minuto < 10) {
        minuto = "0" + minuto;
    }
    //Se pone un 0 a la izquierda en caso de que los segundos sean menores a 10
    if (segundos < 10) {
        segundos = '0' + segundos;
    }
    //Se declara una variable que da formato a la fecha actual
    var fechaConformada = dia + '-' + mes + '-' + anno;
    //Se declara una variable que da formato a la hora actual
    var horaConformada = hora + puntos + minuto + puntos + segundos + meridiano;
    //Se declara una variable que se conforma de la fecha y hora actual
    var horaFechaActualFinal = 'Fecha y hora actual: ' + fechaConformada + ' ' + horaConformada;
    //Se le asigna al objeto html obtenido por javascript la variable 'horaFechaActualFinal'
    document.getElementById("fechaHoraJavaScript").innerHTML = horaFechaActualFinal;

}

//Se ejecuta la funcion 'muestraReloj()' cada segundo
window.onload = function() {
    setInterval(muestraReloj, 1000);
}
