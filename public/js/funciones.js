// FUNCIONES DE CAJA DE TEXTO.
/** Esta funcion me permite controlar los caracteres que se van a diguitar en el campo numero de documento **/
function NumDoc(n){

    key = n.keyCode || n.which;
    tecla = String.fromCharCode(key).toLowerCase();
    numeros = "";
    especiales = [11,32,35,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,
    84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,
    110,111,112,113,114,115,116,117,118,119,120,121,122];
    tecla_especial = false
        for(var i in especiales){
            if(key == especiales[i]){
              tecla_especial = true;
            break;
              } 
          }
                         
        if(numeros.indexOf(tecla)==-1 && !tecla_especial)
          return false;
}


/** Esta funcion me permite controlar los caracteres que se van a diguitar en el campo Nombres y Apellidos **/
function Letras(n){

  key = n.keyCode || n.which;
  tecla = String.fromCharCode(key).toLowerCase();
  numeros = "ñÑ ";
  especiales = [14,15,32,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,
  84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,
  110,111,112,113,114,115,116,117,118,119,120,121,122,130,160,161,162,163,164,165,239];
  tecla_especial = false
      for(var i in especiales){
          if(key == especiales[i]){
            tecla_especial = true;
          break;
            } 
        }
                       
      if(numeros.indexOf(tecla)==-1 && !tecla_especial)
        return false;
}


/**Esta funcion me permite convertir los textos digitados a mayusculas **/
function aMayusculas(obj,id){

    obj = obj.toUpperCase();
    document.getElementById(id).value = obj;
}

/** Esta funcion me devuelve solo los numeros se usa para las cajas varchar con opcion numerica o campos time y date**/
function Numeros(e){

    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = "0123456789";
    especiales = [8,37];
    tecla_especial = false
        for(var i in especiales){
            if(key == especiales[i]){
              tecla_especial = true;
            break;
              } 
          }
                         
        if(letras.indexOf(tecla)==-1 && !tecla_especial)
          return false;
}

function IP(e){

    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = "0123456789.";
    especiales = [8,37];
    tecla_especial = false
        for(var i in especiales){
            if(key == especiales[i]){
              tecla_especial = true;
            break;
              } 
          }
                         
        if(letras.indexOf(tecla)==-1 && !tecla_especial)
          return false;
}


/** Funcion email la cual me permite la digitacion de los carateres necesarios para el registro de un correo electronico **/
function Email(em){

    key = em.keyCode || em.which;
    tecla = String.fromCharCode(key).toLowerCase();
    numeros = "!@-_&.0123456789";
    especiales = [9,32,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,
    84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,
    110,111,112,113,114,115,116,117,118,119,120,121,122];
    tecla_especial = false
        for(var i in especiales){
            if(key == especiales[i]){
              tecla_especial = true;
            break;
              } 
          }
                         
        if(numeros.indexOf(tecla)==-1 && !tecla_especial)
          return false;
}

// funcion para limitar campos de direcciones
function Direccion(n){

  key = n.keyCode || n.which;
  tecla = String.fromCharCode(key).toLowerCase();
  numeros = "0123456789-#ñÑ.: ";
  especiales = [14,15,32,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,
  84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,
  110,111,112,113,114,115,116,117,118,119,120,121,122,130,160,161,162,163,164,165,239];
  tecla_especial = false
      for(var i in especiales){
          if(key == especiales[i]){
            tecla_especial = true;
          break;
            } 
        }
                       
      if(numeros.indexOf(tecla)==-1 && !tecla_especial)
        return false;
}

// funcion campo date: maximo

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //
    var yyyy = today.getFullYear();
     if(dd<10){
            dd='0'+dd
        } 
        if(mm<10){
            mm='0'+mm
        } 

    // today = yyyy+'-'+mm+'-'+dd;
    // document.getElementById("fecha_matricula").setAttribute("max", today);

