/*
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  04-03-2015
 */

function cargarPersonasContactosHorariosYMarcaciones(opcion,idPersona){
    var sufijo = ""
    if(opcion==2){
        sufijo = "TurnAndExcept"
    }
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
                        if(verificarVisibilidad("dtTipoDeDocumento"+sufijo)){
                            if(val.tipo_documento!=null)$("#dtTipoDeDocumento"+sufijo).html(val.tipo_documento+":");
                            else $("#dtTipoDeDocumento"+sufijo).html("CI:");
                        $("#ddNumeroDeDocumento"+sufijo).html("");
                        $("#ddNumeroDeDocumento"+sufijo).append(val.ci+" "+val.expd);
                        if(val.num_complemento!=""&&val.num_complemento!=null)
                            $("#ddNumeroDeDocumento"+sufijo).append(" "+val.num_complemento);
                        }else{
                            $("#dtTipoDeDocumento"+sufijo).html("");
                            $("#ddNumeroDeDocumento"+sufijo).html("");
                        }
                        if(verificarVisibilidad("dtNacionalidad"+sufijo)){
                            $("#ddNacionalidad"+sufijo).html(val.nacionalidad+"&nbsp;");
                        }else{
                            $("#dtNacionalidad"+sufijo).html("");
                            $("#ddNacionalidad"+sufijo).html("");
                        }
                        if(verificarVisibilidad("dtLugarDeNacimiento"+sufijo)){
                            $("#ddLugarDeNacimiento"+sufijo).html(val.lugar_nac+"&nbsp;");
                        }else{
                            $("#dtLugarDeNacimiento"+sufijo).html("");
                            $("#ddLugarDeNacimiento"+sufijo).html("");
                        }
                        if(verificarVisibilidad("dtFechaDeNacimiento"+sufijo)){
                            $("#ddFechaDeNacimiento"+sufijo).html(val.fecha_nac+"&nbsp;");
                        }else{
                            $("#dtFechaDeNacimiento"+sufijo).html("");
                            $("#ddFechaDeNacimiento"+sufijo).html("");
                        }
                        if(verificarVisibilidad("dtDireccion"+sufijo)){
                            $("#ddDireccion"+sufijo).html(val.direccion_dom+"&nbsp;");
                        }else{
                            $("#dtDireccion"+sufijo).html("");
                            $("#ddDireccion"+sufijo).html("");
                        }
                        if(verificarVisibilidad("dtTelefonoFijo"+sufijo)){
                            $("#ddTelefonoFijo"+sufijo).html(val.telefono_fijo+"&nbsp;");
                        }else{
                            $("#dtTelefonoFijo"+sufijo).html("");
                            $("#ddTelefonoFijo"+sufijo).html("");
                        }
                        if(verificarVisibilidad("dtTelefonoInst"+sufijo)){
                            $("#ddTelefonoInst"+sufijo).html(val.telefono_inst+"&nbsp;");
                        }else{
                            $("#dtTelefonoInst"+sufijo).html("");
                            $("#ddTelefonoInst"+sufijo).html("");
                        }
                        if(verificarVisibilidad("dtCelularPer"+sufijo)){
                            $("#ddCelularPer"+sufijo).html(val.celular_per+"&nbsp;");
                        }else {
                            $("#ddCelularPer"+sufijo).html("");
                            $("#dtCelularPer"+sufijo).html("");
                        }
                        if(verificarVisibilidad("dtCelularInst"+sufijo)){
                            $("#ddCelularInst"+sufijo).html(val.celular_inst+"&nbsp;");
                        }else{
                            $("#dtCelularInst"+sufijo).html("");
                            $("#ddCelularInst"+sufijo).html("");
                        }
                        if(verificarVisibilidad("dtTelefonoFax"+sufijo)){
                            $("#ddTelefonoFax"+sufijo).html(val.telefono_fax+"&nbsp;");
                        }else{
                            $("#dtTelefonoFax"+sufijo).html(val.telefono_fax+"&nbsp;");
                            $("#ddTelefonoFax"+sufijo).html(val.telefono_fax+"&nbsp;");
                        }
                        if(verificarVisibilidad("dtInternoInst"+sufijo)){
                            $("#ddInternoInst"+sufijo).html(val.interno_inst+"&nbsp;");
                        }else{
                            $("#dtInternoInst"+sufijo).html("");
                            $("#ddInternoInst"+sufijo).html("");
                        }
                        if(verificarVisibilidad("dtEmailPer"+sufijo)){
                            $("#ddEmailPer"+sufijo).html(val.e_mail_per+"&nbsp;");
                        }else {
                            $("#dtEmailPer"+sufijo).html("");
                            $("#ddEmailPer"+sufijo).html("");
                        }
                        if(verificarVisibilidad("dtEmailInst"+sufijo)){
                            $("#ddEmailInst"+sufijo).html(val.e_mail_inst+"&nbsp;");
                        }else {
                            $("#dtEmailInst"+sufijo).html("");
                            $("#ddEmailInst"+sufijo).html("");
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