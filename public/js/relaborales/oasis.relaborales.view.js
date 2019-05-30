/*
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  28-10-2014
 */

function cargarPersonasContactos(idPersona){
    var ok=$.ajax({
        url:'/relaborales/personascontacto/',
        type:'POST',
        datatype: 'json',
        async:false,
        data:{id:idPersona},
        success: function(data) {  //alert(data);
            var res = jQuery.parseJSON(data);
            if(res.length>0){
                $.each( res, function( key, val ) {
                    if(verificarVisibilidad("dtTipoDeDocumento")){
                        if(val.tipo_documento!=null)$("#dtTipoDeDocumento").html(val.tipo_documento+":");
                        else $("#dtTipoDeDocumento").html("CI:");
                        $("#ddNumeroDeDocumento").html("");
                        $("#ddNumeroDeDocumento").append(val.ci+" "+val.expd);
                        if(val.num_complemento!=""&&val.num_complemento!=null)
                            $("#ddNumeroDeDocumento").append(" "+val.num_complemento);
                    }else{
                        $("#dtTipoDeDocumento").html("");
                        $("#ddNumeroDeDocumento").html("");
                    }
                    if(verificarVisibilidad("dtNacionalidad")){
                        $("#ddNacionalidad").html(val.nacionalidad+"&nbsp;");
                    }else{
                        $("#dtNacionalidad").html("");
                        $("#ddNacionalidad").html("");
                    }
                    if(verificarVisibilidad("dtLugarDeNacimiento")){
                        $("#ddLugarDeNacimiento").html(val.lugar_nac+"&nbsp;");
                    }else{
                        $("#dtLugarDeNacimiento").html("");
                        $("#ddLugarDeNacimiento").html("");
                    }
                    if(verificarVisibilidad("dtFechaDeNacimiento")){
                        $("#ddFechaDeNacimiento").html(val.fecha_nac+"&nbsp;");
                    }else{
                        $("#dtFechaDeNacimiento").html("");
                        $("#ddFechaDeNacimiento").html("");
                    }
                    if(verificarVisibilidad("dtDireccion")){
                        $("#ddDireccion").html(val.direccion_dom+"&nbsp;");
                    }else{
                        $("#dtDireccion").html("");
                        $("#ddDireccion").html("");
                    }
                    if(verificarVisibilidad("dtTelefonoFijo")){
                        $("#ddTelefonoFijo").html(val.telefono_fijo+"&nbsp;");
                    }else{
                        $("#dtTelefonoFijo").html("");
                        $("#ddTelefonoFijo").html("");
                    }
                    if(verificarVisibilidad("dtTelefonoInst")){
                        $("#ddTelefonoInst").html(val.telefono_inst+"&nbsp;");
                    }else{
                        $("#dtTelefonoInst").html("");
                        $("#ddTelefonoInst").html("");
                    }
                    if(verificarVisibilidad("dtCelularPer")){
                        $("#ddCelularPer").html(val.celular_per+"&nbsp;");
                    }else {
                        $("#ddCelularPer").html("");
                        $("#dtCelularPer").html("");
                    }
                    if(verificarVisibilidad("dtCelularInst")){
                        $("#ddCelularInst").html(val.celular_inst+"&nbsp;");
                    }else{
                        $("#dtCelularInst").html("");
                        $("#ddCelularInst").html("");
                    }
                    if(verificarVisibilidad("dtTelefonoFax")){
                        $("#ddTelefonoFax").html(val.telefono_fax+"&nbsp;");
                    }else{
                        $("#dtTelefonoFax").html(val.telefono_fax+"&nbsp;");
                        $("#ddTelefonoFax").html(val.telefono_fax+"&nbsp;");
                    }
                    if(verificarVisibilidad("dtInternoInst")){
                        $("#ddInternoInst").html(val.interno_inst+"&nbsp;");
                    }else{
                        $("#dtInternoInst").html("");
                        $("#ddInternoInst").html("");
                    }
                    if(verificarVisibilidad("dtEmailPer")){
                        $("#ddEmailPer").html(val.e_mail_per+"&nbsp;");
                    }else {
                        $("#dtEmailPer").html("");
                        $("#ddEmailPer").html("");
                    }
                    if(verificarVisibilidad("dtEmailInst")){
                        $("#ddEmailInst").html(val.e_mail_inst+"&nbsp;");
                    }else {
                        $("#dtEmailInst").html("");
                        $("#ddEmailInst").html("");
                    }
                });
            }
        }, //mostramos el error
        error: function() { alert('Se ha producido un error Inesperado'); }
    });
}
/**
 * Función para evaluar la visibilidad de un campo específico.
 * @param id Identificador del campo a Mostrarse/Ocultarse.
 * @returns {boolean} True: Mostrar; False: No Mostrar
 */
function verificarVisibilidad(id){
    var ok=true;
    return ok;
}