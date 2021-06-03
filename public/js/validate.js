$(document).ready(function () {
    /*============================================================================
    =            input de usuarios
                id = "user_validate"        maxlength =60
    ============================================================================*/

    $("#user_validate").change(function () {
        var inputdd09 = document.getElementById("user_validate").value;
        var ValInputdd09 = inputdd09.match(/^[a-zA-Z0-9\.@\s]{4,60}$/);
        if (ValInputdd09 == null) {
            alert(
                "Solo se permiten los caracteres especiales (.), ni menos de 4 caracteres"
            );
            $("#user_validate").focus();
            $("#user_validate").val("");
        }
    });

    /*============================================================================
    =            input de  nombres
                id = "name_validate"        maxlength =20
    ============================================================================*/

    $("#name_validate").change(function () {
        var input1 = document.getElementById("name_validate").value;
        var ValInput1 = input1.match(
            /^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ ]{3,20}$/
        );
        if (ValInput1 == null) {
            alert(
                "No se permiten números, caracteres especiales, espacios o menos de tres(3) letras ni más de quince(20) letras"
            );
            $("#name_validate").focus();
            $("#name_validate").val("");
        }
    });

    /*============================================================================
    =            input de  apellidos
                id = "name_validate"        maxlength =20
    ============================================================================*/

    $("#lastname_validate").change(function () {
        var input1 = document.getElementById("lastname_validate").value;
        var ValInput1 = input1.match(
            /^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ ]{3,20}$/
        );
        if (ValInput1 == null) {
            alert(
                "No se permiten números, caracteres especiales, espacios o menos de tres(3) letras ni más de quince(20) letras"
            );
            $("#lastname_validate").focus();
            $("#lastname_validate").val("");
        }
    });

    /*============================================================================
    =            input de  numero de documento
                id = "document_validate"        maxlength =15
    ============================================================================*/

    $("#document_validate").change(function () {
        var input1 = document.getElementById("document_validate").value;
        var ValInput1 = input1.match(/^[a-zA-Z0-9\-]{5,15}$/);
        if (ValInput1 == null) {
            alert(
                "No se permiten espacios, solo se permite el carácter especial (-)"
            );
            $("#document_validate").focus();
            $("#document_validate").val("");
        }
    });

    /*============================================================================
    =            input de  direccion
                id = "address_validate"        maxlength =100
    ============================================================================*/

    $("#address_validate").change(function () {
        var inputdd09 = document.getElementById("address_validate").value;
        var ValInputdd09 = inputdd09.match(/^[a-zA-Z0-9\-#\s]{5,100}$/);
        if (ValInputdd09 == null) {
            alert(
                "Solo se permiten los caracteres especiales (#) (-) o menos de 5 digitos"
            );
            $("#address_validate").focus();
            $("#address_validate").val("");
        }
    });

    /*============================================================================
    =            input de  correo electronico
                id = "email_validate"        maxlength =50
    ============================================================================*/

    $("#email_validate").change(function () {
        var input10 = document.getElementById("email_validate").value;
        var ValInput10 = input10.match(
            /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{4,}[.][a-zA-Z0-9\.]{2,12}$/
        );
        if (ValInput10 == null) {
            alert("Correo no valido, por favor revise");
            $("#email_validate").focus();
            $("#email_validate").val("");
        }
    });

    /*============================================================================
    =            input de  telefono fijo      minlength = 7
                id = "number_validate"        maxlength =10
    ============================================================================*/

    $("#number_validate").change(function () {
        var input8 = document.getElementById("number_validate").value;
        var ValInput8 = input8.match(/^[0-9]{7,10}$/);
        if (ValInput8 == null) {
            alert(
                "No se permiten letras, caracteres especiales o menos de siete(7) digitos ni más de diez(10) digitos"
            );
            $("#number_validate").focus();
            $("#number_validate").val("");
        }
    });

    /*============================================================================
    =            input de  telefono movil     minlength = 10
                id = "cel_validate"           maxlength =15
    ============================================================================*/

    $("#cel_validate").change(function () {
        var input8 = document.getElementById("cel_validate").value;
        var ValInput8 = input8.match(/^[0-9]{10,15}$/);
        if (ValInput8 == null) {
            alert(
                "No se permiten letras, caracteres especiales o menos de diez(10) digitos ni más de quince(15) digitos"
            );
            $("#cel_validate").focus();
            $("#cel_validate").val("");
        }
    });

    /*============================================================================
    =            FUNCION DE VALIDAR CONTRASEÑAS IGUALES    
                id = "pass_origin"               
                id = "confirmate_pass"               maxlength =20

    ============================================================================*/

    $("#confirmate_pass").change(function () {
        var password = document.getElementById("pass_origin").value;
        var password_confirmate =
            document.getElementById("confirmate_pass").value;

        if (password_confirmate !== password) {
            alert("Las contraseñas no coinciden, escribelas de nuevo");
            $("#pass_origin").val("");
            $("#confirmate_pass").val("");
            $("#pass_origin").focus();
        }
    });

    $("#btn-sugerencias").click(function () {
        $("#text-button").fadeToggle("slow", "swing");
        $("#text-area").focus();
    });

    $('[data-toggle="tooltip"]').tooltip();
    $("#modalidad").select2({
        width: "resolve",
        placeholder: "Seleccione una opción..",
    });

    $("#DD01").select2({
        width: "100%",
        placeholder: "Seleccione",
    });

    $("#DD03").select2({
        width: "100%",
        placeholder: "Seleccione letra",
    });

    $("#DD05").select2({
        width: "100%",
        placeholder: "Seleccione letra",
    });
    $("#DD07").select2({
        width: "100%",
        placeholder: "Seleccione letra",
    });
    $("#barrio").select2({
        width: "100%",
        placeholder: "Seleccione Barrio..",
    });
    $("#vereda").select2({
        width: "100%",
        placeholder: "Seleccione Vereda..",
    });

    $('#selectRoles').select2({
        width: "100%",
        multiple:"multiple",
        tags: true,
        tokenSeparators: [',', ' '] 
        

    });

    // renderizar direccion

    // $(document).on("change", function () {
    //     var dd01 = document.getElementById("DD01").value;
    //     var dd02 = document.getElementById("DD02").value;
    //     var dd03 = document.getElementById("DD03").value;
    //     var dd04 = document.getElementById("DD04").value;
    //     var dd05 = document.getElementById("DD05").value;
    //     var dd06 = document.getElementById("DD06").value;
    //     var dd07 = document.getElementById("DD07").value;
    //     var dd08 = document.getElementById("DD08").value;

    //     document.getElementById("DD00").value =
    //         dd01 +
    //         " " +
    //         dd02 +
    //         " " +
    //         dd03 +
    //         "# " +
    //         dd04 +
    //         dd05 +
    //         "- " +
    //         dd06 +
    //         dd07 +
    //         " " +
    //         dd08;
    // });

    $("#btnDireccion").click(function () {
        var direccion = $("#DD00").val();
        $("#ModalDirecciones").modal("hide");
        $("#direccion").val("");
        $("#direccion").val(direccion);
    });

    $("#matricula").change(function () {
        var input8 = document.getElementById("matricula").value;
        var ValInput8 = input8.match(/^[0-9]{5,30}$/);
        if (ValInput8 == null) {
            alert(
                "No se permiten letras, caracteres especiales o menos de cinco(5) digitos ni más de treinta(30) digitos"
            );
            $("#matricula").focus();
            $("#matricula").val("");
        }
    });

    // validaciones Formulario

    $("#nom_titular").change(function () {
        var input1 = document.getElementById("nom_titular").value;
        var ValInput1 = input1.match(
            /^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ ]{4,20}$/
        );
        if (ValInput1 == null) {
            alert(
                "No se permiten números, caracteres especiales, espacios o menos de cuatro(4) letras ni más de veinticinco(25) letras"
            );
            $("#nom_titular").focus();
            $("#nom_titular").val("");
        }
    });

    $("#ape_titular").change(function () {
        var input1 = document.getElementById("ape_titular").value;
        var ValInput1 = input1.match(
            /^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ ]{4,20}$/
        );
        if (ValInput1 == null) {
            alert(
                "No se permiten números, caracteres especiales, espacios o menos de cuatro(4) letras ni más de veinticinco(25) letras"
            );
            $("#ape_titular").focus();
            $("#ape_titular").val("");
        }
    });

    $("#identificacion_titular").change(function () {
        var input8 = document.getElementById("identificacion_titular").value;
        var ValInput8 = input8.match(/^[0-9]{6,15}$/);
        if (ValInput8 == null) {
            alert(
                "No se permiten letras, caracteres especiales o menos de seis(6) digitos ni más de quince(15) digitos"
            );
            $("#identificacion_titular").focus();
            $("#identificacion_titular").val("");
        }
    });

    $("#tel_titular").change(function () {
        var input8 = document.getElementById("tel_titular").value;
        var ValInput8 = input8.match(/^[0-9]{7,10}$/);
        if (ValInput8 == null) {
            alert(
                "No se permiten letras, caracteres especiales o menos de siete(7) digitos ni más de diez(10) digitos"
            );
            $("#tel_titular").focus();
            $("#tel_titular").val("");
        }
    });

    $("#email_titular").change(function () {
        var input10 = document.getElementById("email_titular").value;
        var ValInput10 = input10.match(
            /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{4,}[.][a-zA-Z0-9\.]{2,12}$/
        );
        if (ValInput10 == null) {
            alert("Correo no valido, por favor revise");
            $("#email_titular").focus();
            $("#email_titular").val("");
        }
    });

    $("#nom_profesional").change(function () {
        var input1 = document.getElementById("nom_profesional").value;
        var ValInput1 = input1.match(
            /^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ ]{4,20}$/
        );
        if (ValInput1 == null) {
            alert(
                "No se permiten números, caracteres especiales, espacios o menos de cuatro(4) letras ni más de veinticinco(25) letras"
            );
            $("#nom_profesional").focus();
            $("#nom_profesional").val("");
        }
    });

    $("#ape_profesional").change(function () {
        var input1 = document.getElementById("ape_profesional").value;
        var ValInput1 = input1.match(
            /^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ ]{4,20}$/
        );
        if (ValInput1 == null) {
            alert(
                "No se permiten números, caracteres especiales, espacios o menos de cuatro(4) letras ni más de veinticinco(25) letras"
            );
            $("#ape_profesional").focus();
            $("#ape_profesional").val("");
        }
    });

    $("#ide_profesional").change(function () {
        var input8 = document.getElementById("ide_profesional").value;
        var ValInput8 = input8.match(/^[0-9]{6,15}$/);
        if (ValInput8 == null) {
            alert(
                "No se permiten letras, caracteres especiales o menos de seis(6) digitos ni más de quince(15) digitos"
            );
            $("#ide_profesional").focus();
            $("#ide_profesional").val("");
        }
    });

    $("#matricula_profesional").change(function () {
        var input8 = document.getElementById("matricula_profesional").value;
        var ValInput8 = input8.match(/^[0-9]{4,15}$/);
        if (ValInput8 == null) {
            alert(
                "No se permiten letras, caracteres especiales o menos de cuatro(4) digitos ni más de quince(15) digitos"
            );
            $("#matricula_profesional").focus();
            $("#matricula_profesional").val("");
        }
    });

    $("#nom_responsable").change(function () {
        var input1 = document.getElementById("nom_responsable").value;
        var ValInput1 = input1.match(
            /^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ ]{4,20}$/
        );
        if (ValInput1 == null) {
            alert(
                "No se permiten números, caracteres especiales, espacios o menos de cuatro(4) letras ni más de veinticinco(25) letras"
            );
            $("#nom_responsable").focus();
            $("#nom_responsable").val("");
        }
    });

    $("#ape_responsable").change(function () {
        var input1 = document.getElementById("ape_responsable").value;
        var ValInput1 = input1.match(
            /^[a-zA-ZáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚñÑüÜ ]{4,20}$/
        );
        if (ValInput1 == null) {
            alert(
                "No se permiten números, caracteres especiales, espacios o menos de cuatro(4) letras ni más de veinticinco(25) letras"
            );
            $("#ape_responsable").focus();
            $("#ape_responsable").val("");
        }
    });

    $("#ide_profesional").change(function () {
        var input8 = document.getElementById("ide_profesional").value;
        var ValInput8 = input8.match(/^[0-9]{6,15}$/);
        if (ValInput8 == null) {
            alert(
                "No se permiten letras, caracteres especiales o menos de seis(6) digitos ni más de quince(15) digitos"
            );
            $("#ide_profesional").focus();
            $("#ide_profesional").val("");
        }
    });

    $("#tel_responsable").change(function () {
        var input8 = document.getElementById("tel_responsable").value;
        var ValInput8 = input8.match(/^[0-9]{7,10}$/);
        if (ValInput8 == null) {
            alert(
                "No se permiten letras, caracteres especiales o menos de siete(7) digitos ni más de diez(10) digitos"
            );
            $("#tel_responsable").focus();
            $("#tel_responsable").val("");
        }
    });

    $("#email_responsable").change(function () {
        var input10 = document.getElementById("email_responsable").value;
        var ValInput10 = input10.match(
            /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{4,}[.][a-zA-Z0-9\.]{2,12}$/
        );
        if (ValInput10 == null) {
            alert("Correo no valido, por favor revise");
            $("#email_responsable").focus();
            $("#email_responsable").val("");
        }
    });

    $("#email_responsable").change(function () {
        var input10 = document.getElementById("email_responsable").value;
        var ValInput10 = input10.match(
            /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{4,}[.][a-zA-Z0-9\.]{2,12}$/
        );
        if (ValInput10 == null) {
            alert("Correo no valido, por favor revise");
            $("#email_responsable").focus();
            $("#email_responsable").val("");
        }
    });

    $("#email_confirmado").change(function () {
        var input10 = document.getElementById("email_confirmado").value;
        var ValInput10 = input10.match(
            /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{4,}[.][a-zA-Z0-9\.]{2,12}$/
        );
        if (ValInput10 == null) {
            alert("Correo no valido, por favor revise");
            $("#email_confirmado").focus();
            $("#email_confirmado").val("");
        }
    });

    $("#email_confirmado").change(function () {
        var email_confirmado =
            document.getElementById("email_confirmado").value;
        var email = document.getElementById("email_responsable").value;

        if (email_confirmado !== email) {
            alert("Los correos  no coinciden, escribelos de nuevo");
            $("#email_confirmado").val("");
            $("#email_responsable").val("");
            $("#email_confirmado").focus();
        }
    });

    $("#dir_responsable").change(function () {
        var inputdd09 = document.getElementById("dir_responsable").value;
        var ValInputdd09 = inputdd09.match(/^[a-zA-Z0-9\-#\s]{7,120}$/);
        if (ValInputdd09 == null) {
            alert("Solo se permiten los caracteres especiales (#) (-)");
            $("#dir_responsable").focus();
            $("#dir_responsable").val("");
        }
    });

    // FILE INPUT

    $("#archivo_documento").fileinput({
        theme: "fas",
        language: "es",
        browseClass: "btn btn-primary",
        browseLabel: "Examinar",
        removeClass: "btn btn-danger",
        allowedFileExtensions: ["pdf"],
        overwriteInitial: true,
        maxFileSize: 10000,
    });

    $("#archivo_poder").fileinput({
        theme: "fas",
        language: "es",
        browseClass: "btn btn-primary",
        browseLabel: "Examinar",
        removeClass: "btn btn-danger",
        allowedFileExtensions: ["pdf"],
        overwriteInitial: true,
        maxFileSize: 10000,
    });

    $("#archivo_poder").fileinput({
        theme: "fas",
        language: "es",
        browseClass: "btn btn-primary",
        browseLabel: "Examinar",
        removeClass: "btn btn-danger",
        allowedFileExtensions: ["pdf"],
        overwriteInitial: true,
        maxFileSize: 10000,
    });

    $("#archivo_descripcion").fileinput({
        theme: "fas",
        language: "es",
        browseClass: "btn btn-primary",
        browseLabel: "Examinar",
        removeClass: "btn btn-danger",
        allowedFileExtensions: ["pdf"],
        overwriteInitial: true,
        maxFileSize: 10000,
    });

    $("#archivo_planos").fileinput({
        theme: "fas",
        language: "es",
        browseClass: "btn btn-primary",
        browseLabel: "Examinar",
        removeClass: "btn btn-danger",
        allowedFileExtensions: ["pdf"],
        overwriteInitial: true,
        maxFileSize: 10000,
    });

    // DATATABLES

    $(".tablas").DataTable({
        language: {
            sProcessing: "Procesando...",
            sLengthMenu: "Mostrar _MENU_ registros",
            sZeroRecords: "No se encontraron resultados",
            sEmptyTable: "Ningún dato disponible en esta tabla",
            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
            sInfoPostFix: "",
            sSearch: "Buscar:",
            sUrl: "",
            sInfoThousands: ",",
            sLoadingRecords: "Cargando...",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior",
            },

            oAria: {
                sSortAscending:
                    ": Activar para ordenar la columna de manera ascendente",
                sSortDescending:
                    ": Activar para ordenar la columna de manera descendente",
            },
        },
        responsive:true,
        scrollX:        200,
        scrollCollapse: true,

    });

   

    // FUNCION PARA VALIDACION DE GROSERIAS

    $("#text-area").change(function () {
        var input8 = document.getElementById("text-area").value;
        const palabras = [
            "HPTA",
            "HPTS",
            "HP",
            "MIERDA",
            "IJUEPUTAS",
            "HIJOS DE PUTA",
            "HIJUEPUTAS",
            "GONORREA",
            "MALPARIOS",
            "SU PUTA",
            "MP",
            "FUCK",
            "HUEVON",
            "WEBON",
            "WEBA",
            "GARBIMBA",
            "SAPO",
            "PERRO",
            "LICHIGO",
            "VICHIRO",
            "TOCHE",
            "PINGO",
            "ZUNGA",
            "SUNGA",
            "PEGUELAGARTO",
            "BABOSO",
            "ARRASTRADO",
            "BOBO",
            "BOBALICON",
            "BAZOFIA",
            "CARE CHIMBA",
            "CARECHIMBA",
            "CAREMONDA",
            "CARE E MONDA",
            "CULIPRONTA",
            "COSCORRIA",
            "FUFA",
            "FUFURUFA",
            "FUFA",
            "SARNA",
            "GARNUPIA",
            "GONORRIENTO",
            "IDIOTA",
            "IMBECIL",
            "HUEVA",
            "WEBA",
            "GUEBA",
            "LAMBON",
            "JETON",
            "LOCA",
            "MALPARIDOS",
            "MORRONGA",
            "MARICON",
            "ÑERO",
            "MUERGANO",
            "PENDEJO",
            "PICHURRIA",
            "PIROBO",
            "PREPAGO",
            "PUTA",
            "TONTO",
            "TONTARRON",
            "ZORRA",
            "ZURIPANTA",
            "CULO",
            "TORTOLO",
            "CATRE",
            "DOBLE",
            "PEDORREO",
            "HIJO DE SU GRANDISIMA MADRE",
        ];
        var groseria;

        var ValInput9 = input8.match(/^[a-zA-Z0-9\.,\s]{5,300}$/);
        if (ValInput9 == null) {
            alert("Solo se permiten los caracteres especiales (#) (-)");
            $("#text-area").focus();
            $("#text-area").val("");
        }

        for (groseria of palabras) {
            var ValInput8 = input8.indexOf(groseria);
            console.log(ValInput8);
            if (ValInput8 > -1) {
                alert("Este lenguaje NO es adecuado para escribir sugerencias");
                window.location.href = "/";
            }
        }
    });

    // FUNCION DE EXPERIENCIA BOTON FACIL

    $("#Facil").click(function () {
        var facil = $("#Facil").val();
        var textArea = $("#text-area").val();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "POST",
            url: "/solicitud/experiencia",
            dataType: "json",
            data: {
                valor: facil,
                sugerencias: textArea,
            },
            success: function (response) {
                console.log(response);

                if (response.success) {
                    $("#Texto_sugerencias").fadeToggle();

                    setTimeout(function () {
                        $("#Texto_sugerencias").fadeToggle();
                    }, 5000);

                    $("#Facil").css("pointer-events", "none");
                    $("#Dificil").css("pointer-events", "none");
                    $("#facil").attr("disabled", true);
                    $("#Dificil").attr("disabled", true);
                    $("#btn-sugerencias").attr("disabled", true);
                    $("#text-button").css("display", "none");
                } else {
                    alert("Ha ocurrido un error al realizar la encuesta");
                }
            },
            error: function () {
                alert("error de petición ajax");
            },
        });
    });

    // FUNCION DE EXPERIENCIA BOTON DIFICIL

    $("#Dificil").click(function () {
        var Dificil = $("#Dificil").val();
        var textArea = $("#text-area").val();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "POST",
            url: "/solicitud/experiencia",
            dataType: "json",
            data: {
                valor: Dificil,
                sugerencias: textArea,
            },
            success: function (response) {
                console.log(response);

                if (response.success) {
                    $("#Texto_sugerencias").fadeToggle();

                    setTimeout(function () {
                        $("#Texto_sugerencias").fadeToggle();
                    }, 5000);

                    $("#Facil").css("pointer-events", "none");
                    $("#Dificil").css("pointer-events", "none");
                    $("#facil").attr("disabled", true);
                    $("#Dificil").attr("disabled", true);
                    $("#btn-sugerencias").attr("disabled", true);
                    $("#text-button").css("display", "none");
                } else {
                    alert("Ha ocurrido un error al realizar la encuesta");
                }
            },
            error: function () {
                alert("error de petición ajax");
            },
        });
    });

    // funcion checkbox

    $('.group_check1').on('change', function() {
        $('.group_check1').not(this).prop('checked', false);
     });

     // FUNCION PARA MOSTRAS CONTRASEÑA

     $('#checkboxPassword-govco').click(function(){

        if(this.checked){          
          $('#password_ldap').attr('type', 'text');
        }else{
           $('#password_ldap').attr('type', 'password');
        }
       });

       $("#estado").change(function () {
        var estado = document.getElementById("estado").value;        

        if (estado == 'PENDIENTE') {
          $('#documento_respuesta').attr('disabled', true); 
          $('#documento_respuesta').attr('required', false); 
                   
            
        }else if(estado == 'APROBADA'){
            $('#documento_respuesta').attr('disabled', false);
            $('#documento_respuesta').attr('required', true);

        }else if(estado == 'RECHAZADA'){
            $('#documento_respuesta').attr('disabled', false);
            $('#documento_respuesta').attr('required', true);
        }else{
            $('#documento_respuesta').attr('disabled', true);
            $('#documento_respuesta').attr('required', false);
        }
        $('#observaciones').focus();
        
    });
    $('#myForm').ready(function(){

        var estado_solicitud = $('#estado_sol').val();
        if(estado_solicitud == 'APROBADA' || estado_solicitud == 'RECHAZADA'){
            $('#myBtn').attr('disabled', true);
            $('#estado').attr('disabled', true);
            $('#observaciones').attr('disabled', true);
            



        }



    });

      






       
    
      
}); // FIN DOCUMENT READY
