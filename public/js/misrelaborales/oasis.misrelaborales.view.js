function cargarPersonasContactos(i){
	$.ajax({url:"/relaborales/personascontacto/",type:"POST",datatype:"json",async:!1,data:{id:i},
		success:function(i){
			var e=jQuery.parseJSON(i);0<e.length&&$.each(e,function(i,e){verificarVisibilidad("dtTipoDeDocumento")?(null!=e.tipo_documento?$("#dtTipoDeDocumento").html(e.tipo_documento+":"):
				$("#dtTipoDeDocumento").html("CI:"),$("#ddNumeroDeDocumento").html(""),$("#ddNumeroDeDocumento").append(e.ci+" "+e.expd),""!=e.num_complemento&&null!=e.num_complemento&&
				$("#ddNumeroDeDocumento").append(" "+e.num_complemento)):($("#dtTipoDeDocumento").html(""),$("#ddNumeroDeDocumento").html("")),verificarVisibilidad("dtNacionalidad")?
				$("#ddNacionalidad").html(e.nacionalidad+"&nbsp;"):($("#dtNacionalidad").html(""),$("#ddNacionalidad").html("")),verificarVisibilidad("dtLugarDeNacimiento")?
				$("#ddLugarDeNacimiento").html(e.lugar_nac+"&nbsp;"):($("#dtLugarDeNacimiento").html(""),$("#ddLugarDeNacimiento").html("")),verificarVisibilidad("dtFechaDeNacimiento")?
				$("#ddFechaDeNacimiento").html(e.fecha_nac+"&nbsp;"):($("#dtFechaDeNacimiento").html(""),$("#ddFechaDeNacimiento").html("")),verificarVisibilidad("dtDireccion")?
				$("#ddDireccion").html(e.direccion_dom+"&nbsp;"):($("#dtDireccion").html(""),$("#ddDireccion").html("")),verificarVisibilidad("dtTelefonoFijo")?
				$("#ddTelefonoFijo").html(e.telefono_fijo+"&nbsp;"):($("#dtTelefonoFijo").html(""),$("#ddTelefonoFijo").html("")),verificarVisibilidad("dtTelefonoInst")?
				$("#ddTelefonoInst").html(e.telefono_inst+"&nbsp;"):($("#dtTelefonoInst").html(""),$("#ddTelefonoInst").html("")),verificarVisibilidad("dtCelularPer")?
				$("#ddCelularPer").html(e.celular_per+"&nbsp;"):($("#ddCelularPer").html(""),$("#dtCelularPer").html("")),verificarVisibilidad("dtCelularInst")?
				$("#ddCelularInst").html(e.celular_inst+"&nbsp;"):($("#dtCelularInst").html(""),$("#ddCelularInst").html("")),verificarVisibilidad("dtTelefonoFax")||$("#dtTelefonoFax").html(e.telefono_fax+"&nbsp;"),$("#ddTelefonoFax").html(e.telefono_fax+"&nbsp;"),verificarVisibilidad("dtInternoInst")?
				$("#ddInternoInst").html(e.interno_inst+"&nbsp;"):($("#dtInternoInst").html(""),$("#ddInternoInst").html("")),verificarVisibilidad("dtEmailPer")?$("#ddEmailPer").html(e.e_mail_per+"&nbsp;"):($("#dtEmailPer").html(""),$("#ddEmailPer").html("")),verificarVisibilidad("dtEmailInst")?
				$("#ddEmailInst").html(e.e_mail_inst+"&nbsp;"):($("#dtEmailInst").html(""),$("#ddEmailInst").html(""))})},
				error:function(){alert("Se ha producido un error Inesperado")}
	})	
}

function verificarVisibilidad(i){return!0}