$().ready(function(){
	$("#HistorialSplitter").jqxSplitter({theme:"oasis",width:"100%",height:480,panels:[{size:"8%"},{size:"92%"}]}),
	cargaDatosRelaboral($("#hdnIdRelaboralUsuario").val(),$("#hdnIdPersonaUsuario").val(),$("#hdnNombresUsuario").val(),
	$("#hdnCiUsuario").val()),$("#btnImprimirHistorial").on("click",function(){$("#HistorialSplitter").printArea({mode:"popup",popClose:!1})})
});
var rownumberrenderer=function(a,r,e,t,o,i){
	return"<div align='center'>"+(a+1)+"</div>"
};

	function obtenerRutaFoto(a,r){
		var e="/images/perfil-profesional.jpg";
		return""!=a&&$.ajax({url:"/relaborales/obtenerrutafoto/",type:"POST",datatype:"json",async:!1,cache:!1,data:{ci:a,num_complemento:r},
			success:function(a){var r=jQuery.parseJSON(a);1==r.result&&(e=r.ruta)},error:function(){alert("Se ha producido un error Inesperado")}}),e
	}
	function fechaHoy(a,r){
		""==a&&(a="-");
		var e=new Date,t=e.getDate().toString(),o=(e.getMonth()+1).toString(),i=1===t.length?"0"+t:t,n=1===o.length?"0"+o:o;
		
		if("dd-mm-yyyy"==r)
			var l=i+a+n+a+e.getFullYear();
		else if("mm-dd-yyyy"==r)
			l=n+a+i+a+e.getFullYear();
		else l=e;
		return l
	}
	var cellclass=function(a,r,e){
		return"ACTIVO"==e?"verde":"EN PROCESO"==e?"amarillo":"PASIVO"==e?"rojo":""
	};
	function cargaDatosRelaboral(a,r,e,t){$(".msjs-alert").hide(),
		$("#hdnIdPersonaHistorial").val(r),$("#tabFichaPersonal").jqxTabs({theme:"oasis",width:"100%",height:"100%",position:"top"}),
		$("#tabFichaPersonal").jqxTabs({selectedItem:0}),$("#ddNombres").html(e);var o=obtenerRutaFoto(t,"");$("#imgFotoPerfilContactoPer").attr("src",o),
		$("#imgFotoPerfilContactoInst").attr("src",o),$("#imgFotoPerfil").attr("src",o),cargarPersonasContactos(r),$("#hdnIdRelaboralVista").val(a),
		$("#hdnSwPrimeraVistaHistorial").val(0),cargarGestionesHistorialRelaboral(r),cargarHistorialRelacionLaboral(r,0,1),$("#divContent_"+a).focus().select()
	}
