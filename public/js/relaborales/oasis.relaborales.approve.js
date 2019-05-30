/*
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  08-11-2014
 */
/**
 * Función para aprobar un registro de relación laboral.
 */
function aprobarRegistroRelabolar(idRelaboral){
    var ok = false;
    $.ajax({
        url:'/relaborales/approve/',
        type:'POST',
        datatype: 'json',
        async:false,
        data:{id:idRelaboral},
        success: function(data) {

            var res = jQuery.parseJSON(data);
            /**
             * Si se ha realizado correctamentela aprobación del registro de la relación laboral
             */
            $(".msjes").hide();
            if(res.result==1){
                $("#divMsjeExito").show();
                $("#divMsjeExito").addClass('alert alert-success alert-dismissable');
                $("#aMsjeExito").html(res.msj);
                $("#jqxgrid").jqxGrid("updatebounddata");
                ok=true;
            } else if(res.result==0){
                /**
                 * En caso de haberse presentado un error al momento de modificar el estado del registro de relación laboral, siendo que su estado no haya estado EN PROCESO.
                 */
                $("#divMsjePeligro").show();
                $("#divMsjePeligro").addClass('alert alert-warning alert-dismissable');
                $("#aMsjePeligro").html(res.msj);
            }else{
                /**
                 * En caso de haberse presentado un error crítico al momento de modificar el estado el registro de la relación laboral
                 */
                $("#divMsjeError").show();
                $("#divMsjeError").addClass('alert alert-danger alert-dismissable');
                $("#aMsjeError").html(res.msj);
            }

        }, //mostramos el error
        error: function() { alert('Se ha producido un error Inesperado'); }
    });
    return ok;
}