function validaFormularioPorEditarRegistroPerfilLaboral(){var r=!0,a="";$(".msjs-alert").hide(),limpiarMensajesErrorPorValidacionEditarRegistro();var e=$("#txtPerfilLaboralEditar").val(),l=$("#hdnIdPerfilLaboralEditar").val(),i="";0!=l&&null!=l||(r=!1,$("#divMsjeError").show(),$("#divMsjeError").addClass("alert alert-danger alert-dismissable"),i+="Se requiere seleccionar un registro de perfil laboral inicialmente."),""!=i&&$("#aMsjeError").html(i);var s=null;return null!=e&&""!=e||(r=!1,a="Debe introducir el nombre del perfil necesariamente.",$("#divPerfilLaboralEditar").addClass("has-error"),$("#helpErrorPerfilLaboralEditar").html(a),null==s&&(s=$("#txtPerfilLaboralEditar"))),null!=s&&s.focus(),r}function limpiarMensajesErrorPorValidacionEditarRegistro(){$("#divPerfilLaboralEditar").removeClass("has-error"),$("#divObservacionPerfilLaboralEditar").removeClass("has-error"),$("#helpErrorPerfilLaboralEditar").html(""),$("#helpErrorObservacionPerfilLaboralEditar").html("")}function guardarRegistroEditadoPerfilLaboral(){var r=$("#hdnIdPerfilLaboralEditar").val(),a=$("#txtPerfilLaboralEditar").val(),e=$("#txtGrupoPerfilLaboralEditar").val(),l=$("#lstTipoHorarioPerfilLaboralEditar").val(),i=$("#txtObservacionPerfilLaboralEditar").val();return 0<r&&$.ajax({url:"/perfileslaborales/save/",type:"POST",datatype:"json",async:!1,data:{id:r,perfil_laboral:a,grupo:e,tipo_horario:l,observacion:i},success:function(r){var a=jQuery.parseJSON(r);$(".msjes").hide(),1==a.result?($("#divMsjeExito").show(),$("#divMsjeExito").addClass("alert alert-success alert-dismissable"),$("#aMsjeExito").html(a.msj),$("#jqxTabsPerfilesLaborales").jqxTabs("enableAt",0),$("#jqxTabsPerfilesLaborales").jqxTabs("disableAt",1),$("#jqxTabsPerfilesLaborales").jqxTabs("disableAt",2),$("#jqxTabsPerfilesLaborales").jqxTabs("disableAt",3),$("#divGridPerfilesLaborales").jqxGrid("updatebounddata")):0==a.result?($("#divMsjePeligro").show(),$("#divMsjePeligro").addClass("alert alert-warning alert-dismissable"),$("#aMsjePeligro").html(a.msj)):($("#divMsjeError").show(),$("#divMsjeError").addClass("alert alert-danger alert-dismissable"),$("#aMsjeError").html(a.msj))},error:function(){alert("Se ha producido un error Inesperado")}})}