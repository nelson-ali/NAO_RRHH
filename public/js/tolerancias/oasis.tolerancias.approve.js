function aprobarRegistroTolerancia(r){var e=!1;return $.ajax({url:"/tolerancias/approve/",type:"POST",datatype:"json",async:!1,data:{id:r},success:function(r){var i=jQuery.parseJSON(r);$(".msjes").hide(),1==i.result?($("#divMsjePorSuccess").html(""),$("#divMsjePorSuccess").append(i.msj),$("#divMsjeNotificacionSuccess").jqxNotification("open"),$("#jqxgridtolerancias").jqxGrid("updatebounddata"),e=!0):0==i.result?($("#divMsjePorWarning").html(""),$("#divMsjePorWarning").append(i.msj),$("#divMsjeNotificacionWarning").jqxNotification("open")):($("#divMsjePorError").html(""),$("#divMsjePorError").append(i.msj),$("#divMsjeNotificacionError").jqxNotification("open"))},error:function(){alert("Se ha producido un error Inesperado")}}),e}