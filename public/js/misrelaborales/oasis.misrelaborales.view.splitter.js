function obtenerGestionesParaHistorial(d){
	var i=[];
	return $("#HistorialSplitter").jqxSplitter("expand"),
	$.ajax({url:"/relaborales/listgestionesporpersona",
		type:"POST",datatype:"json",async:!1,cache:!1,data:{id:d},
		success:function(d){
			var a=jQuery.parseJSON(d);
			0<a.length&&$.each(a,function(d,a){
				i.push(a.gestion)
				})
			}
		}),i
}

function cargarGestionesHistorialRelaboral(d){
	$("#listboxGestiones").off();
	var o=obtenerGestionesParaHistorial(d);
	$("#listboxGestiones").jqxListBox({width:200,source:o,checkboxes:!0,height:"100%"}),0<o.length&&$.each(o,function(d,a){
		$("#hdnSwPrimeraVistaHistorial").val(0),$("#listboxGestiones").jqxListBox("checkIndex",d),
		$("#hdnSwPrimeraVistaHistorial").val(0)}),1==o.length&&$("#HistorialSplitter").jqxSplitter("collapse");
	var l=$("#hdnIdPersonaHistorial").val();
	$("#listboxGestiones").on("checkChange",function(d){var a=0,i=(d.args,$("#listboxGestiones").jqxListBox("getCheckedItems")),e="";
	
	if($.each(i,function(d){
		d<i.length-1?e+=this.label+", ":e+=this.label,a++}),a==o.length)cargarHistorialRelacionLaboral(l,0,1);
	else if($("#HistorialTimeLine").html(""),0<a){
		var r=e.split(",");
		$.each(r,function(d,a){cargarHistorialRelacionLaboral(l,a,0)})
	}
	$("#hdnSwPrimeraVistaHistorial").val(1)})}function cargarHistorialRelacionLaboral(d,a,i){1==i&&$("#HistorialTimeLine").html("");
	var e="";$.ajax({url:"/relaborales/listhistorial",type:"POST",datatype:"json",async:!1,cache:!1,data:{id:d,gestion:a},
		success:function(d){
			var a=jQuery.parseJSON(d);
			0<a.length&&($.each(a,function(d,a){switch(1==a.estado?(e+="<li class='active'>",e+="<div class='timeline-icon'>"):2==a.estado?(e+="<li>",
					e+="<div class='timeline-icon'>"):(e+="<li class='active'>",e+="<div class='timeline-icon themed-background-fire themed-border-fire'>"),
					e+="<i class='fa fa-file-text' title='Registro de Relaci&oacute;n Laboral'></i></div>",
					e+="<div class='timeline-time'><a href='#' id='divContent_"+a.id_relaboral+"'>"+a.fecha_ini+"</a><strong></strong></div>",
					e+="<div class='timeline-content'>",e+="<p class='push-bit'><strong id='strCargo_"+a.id_relaboral+"'>"+a.cargo+"</strong></p>",
					e+="<dl class='dl-horizontal'>",e+="<dt id='dtProceso_"+a.id_relaboral+"'>Proceso:</dt><dd id='ddProceso_"+a.id_relaboral+"'>"+a.proceso_codigo+"</dd>",
					e+="<dt id='dtFinPartida_"+a.id_relaboral+"'>Financiamiento:</dt><dd id='ddFinPartida_"+a.id_relaboral+"'>"+a.condicion+" (Partida "+a.partida+")</dd>",
					e+="<dt id='dtGerencia_"+a.id_relaboral+"'>-</dt><dd id='ddGerencia_"+a.id_relaboral+"'>"+a.gerencia_administrativa+"</dd>",""!=a.departamento_administrativo&&(
					e+="<dt id='dtDepartamento_"+a.id_relaboral+"'>Dependencia:</dt><dd id='ddDepartamento_"+a.id_relaboral+"'>"+a.departamento_administrativo+"</dd>"),0<a.id_area&&(
					e+="<dt id='dtArea_"+a.id_relaboral+"'>&Aacute;rea:</dt><dd id='ddArea_"+a.id_relaboral+"'>"+a.area+"</dd>"),
					e+="<dt id='dtUbicacion_"+a.id_relaboral+"'>Ubicaci&oacute;n:</dt><dd id='ddUbicacion_"+a.id_relaboral+"'>"+a.ubicacion+"</dd>",a.tiene_item){case 1:
					e+="<dt id='dtItem_"+a.id_relaboral+"'>&Iacute;tem:</dt><dd id='ddItem_"+a.id_relaboral+"'>"+a.cargo_codigo+"</dd>";break;case 0:var i="&nbsp;";null!=a.num_contrato&&(i=a.num_contrato),
					e+="<dt id='dtNumContrato_"+a.id_relaboral+"'>Nro. de Contrato:</dt><dd id='ddNumContrato_"+a.id_relaboral+"'>"+i+"</dd>"}switch(
					e+="<dt id='dtNivelSalarial_"+a.id_relaboral+"'>Nivel Salarial:</dt><dd id='ddNivelSalarial_"+a.id_relaboral+"'>"+a.nivelsalarial+"</dd>",
					e+="<dt id='dtHaber_"+a.id_relaboral+"'>Haber:</dt><dd id='ddHaber_"+a.id_relaboral+"'>"+a.sueldo+"</dd>",null!=a.fecha_ing&&""!=a.fecha_ing?
					e+="<dt id='dtFechaIng_"+a.id_relaboral+"'>Fecha Ingreso:</dt><dd id='ddFechaIng_"+a.id_relaboral+"'>"+a.fecha_ing+"</dd>":
					e+="<dt id='dtFechaIng_"+a.id_relaboral+"'>Fecha Ingreso:</dt><dd id='ddFechaIng_"+a.id_relaboral+"'>&nbsp;</dd>",null!=a.fecha_ini&&""!=a.fecha_ini?e+="<dt id='dtFechaIni_"+a.id_relaboral+"'>Fecha Inicio:</dt><dd id='ddFechaIni_"+a.id_relaboral+"'>"+a.fecha_ini+"</dd>":
					e+="<dt id='dtFechaIni_"+a.id_relaboral+"'>Fecha Inicio:</dt><dd id='ddFechaIni_"+a.id_relaboral+"'>&nbsp;</dd>",null!=a.fecha_incor&&""!=a.fecha_incor?e+="<dt id='dtFechaIncor_"+a.id_relaboral+"'>Fecha Incor:</dt><dd id='ddFechaIncor_"+a.id_relaboral+"'>"+a.fecha_incor+"</dd>":
					e+="<dt id='dtFechaIncor_"+a.id_relaboral+"'>Fecha Incor:</dt><dd id='ddFechaIncor_"+a.id_relaboral+"'>&nbsp;</dd>",a.tiene_item){case 1:break;case 0:e+="<dt id='dtFechaFin_"+a.id_relaboral+"'>Fecha Fin:</dt><dd id='ddFechaFin_"+a.id_relaboral+"'>"+a.fecha_fin+"</dd>"}0==a.estado&&(
					e+="<dt id='dtFechaBaja_"+a.id_relaboral+"'>Fecha Baja:</dt><dd id='ddFechaBaja_"+a.id_relaboral+"'>"+a.fecha_baja+"</dd>",
					e+="<dt id='dtMotivoBaja_"+a.id_relaboral+"'>Motivo Baja:</dt><dd id='ddMotivoBaja_"+a.id_relaboral+"'>"+a.motivo_baja+"</dd>"),e+="<dt id='dtContratoEstado_"+a.id_relaboral+"'>Estado:</dt>",e+="<dd id='ddContratoEstado_"+a.id_relaboral+"'>",
					e+="<strong>"+a.estado_descripcion+"</strong></dd>",
					e+="<dt id='dtObservacion_"+a.id_relaboral+"'>Observaciones:</dt><dd id='ddObservacion_"+a.id_relaboral+"'>"+a.observacion+"</dd>",e+="</dl>",e+=cargarHistorialRelacionLaboralMovilidad(a.id_relaboral)}),$("#HistorialTimeLine").append(e))
			}
		})
	}
	
	function cargarHistorialRelacionLaboralMovilidad(d){
		var i="";
		return $.ajax({url:"/relaborales/listhistorialmovilidad",type:"GET",datatype:"json",async:!1,cache:!1,data:{id:d},success:function(d){
			var a=jQuery.parseJSON(d);
			0<a.length&&$.each(a,function(d,a){1==a.estado?(i+="<li class='active'>",i+="<div class='timeline-icon'>"):(i+="<li class='active'>",
					i+="<div class='timeline-icon themed-background-fire themed-border-fire'>"),
						4==a.id_tipomemorandum?i+="<i class='gi gi-airplane' title='Memor&aacute;ndum'></i></div>":
						5==a.id_tipomemorandum?i+="<i class='hi hi-tree_conifer' title='Memor&aacute;ndum'></i></div>":
					i+="<i class='fa fa-tag' title='Memor&aacute;ndum'></i></div>",i+="<div class='timeline-time'><a href='#' id='divContentMovilidad_"+a.id_relaboralmovilidad+"'>"+a.fecha_ini+"</a><strong></strong></div>",i+="<div class='timeline-content'>",	
					i+="<p class='push-bit'>"+a.tipo_memorandum+": <strong id='strCargoMotivoMovilidad_"+a.id_relaboralmovilidad+"'>",null!=a.cargo?i+=a.cargo:""!=a.motivo&&(i+=a.motivo),i+="</strong></p>",
					i+="<dl class='dl-horizontal'>",null!=a.gerencia_administrativa&&""!=a.gerencia_administrativa&&(i+="<dt id='dtGerenciaMovilidad_"+a.id_relaboralmovilidad+"'>Gerencia:</dt><dd id='ddGerenciaMovilidad_"+a.id_relaboralmovilidad+"'>"+a.gerencia_administrativa+"</dd>"),null!=a.departamento_administrativo&&""!=a.departamento_administrativo&&(
					i+="<dt id='dtDepartamentoMovilidad_"+a.id_relaboralmovilidad+"'>Dependencia:</dt><dd id='ddDepartamentoMovilidad_"+a.id_relaboralmovilidad+"'>"+a.departamento_administrativo+"</dd>"),0<a.id_area&&(
					i+="<dt id='dtAreaMovilidad_"+a.id_relaboralmovilidad+"'>&Aacute;rea:</dt><dd id='ddAreaMovilidad_"+a.id_relaboralmovilidad+"'>"+a.area+"</dd>"),null!=a.ubicacion&&""!=a.ubicacion&&(i+="<dt id='dtUbicacionMovilidad_"+a.id_relaboralmovilidad+"'>Ubicaci&oacute;n:</dt><dd id='ddUbicacionMovilidad_"+a.id_relaboralmovilidad+"'>"+a.ubicacion+"</dd>"),
					i+="<dt id='dtFechaIniMovilidad_"+a.id_relaboralmovilidad+"'>Fecha Inicio:</dt><dd id='ddFechaIniMovilidad_"+a.id_relaboralmovilidad+"'>"+a.fecha_ini+"</dd>",null!=a.hora_ini&&""!=a.hora_ini&&(
					i+="<dt id='dtHoraIniMovilidad_"+a.id_relaboralmovilidad+"'>Hora Ini:</dt><dd id='ddHoraIniMovilidad_"+a.id_relaboralmovilidad+"'>"+a.hora_ini+"</dd>"),""!=a.fecha_fin&&(i+="<dt id='dtFechaFinMovilidad_"+a.id_relaboralmovilidad+"'>Fecha Fin:</dt><dd id='ddFechaFinMovilidad_"+a.id_relaboralmovilidad+"'>"+a.fecha_fin+"</dd>"),null!=a.hora_fin&&""!=a.hora_fin&&(i+="<dt id='dtHoraFinMovilidad_"+a.id_relaboralmovilidad+"'>Hora Fin:</dt><dd id='ddHoraFinMovilidad_"+a.id_relaboralmovilidad+"'>"+a.hora_fin+"</dd>"),
					i+="<dt id='dtMemorandumMovilidad_"+a.id_relaboralmovilidad+"'>Memor&aacute;ndum:</dt><dd id='ddMemorandumMovilidad_"+a.id_relaboralmovilidad+"'>"+a.memorandum_correlativo+"/"+a.memorandum_gestion+" de "+a.fecha_mem+"</dd>",4!=a.id_tipomemorandum&&5!=a.id_tipomemorandum||(null!=a.pais&&""!=a.pais&&(i+="<dt id='dtPaisMovilidad_"+a.id_relaboralmovilidad+"'>Pa&iacute;s:</dt><dd id='ddPaisMovilidad_"+a.id_relaboralmovilidad+"'>"+a.pais+"</dd>"),null!=a.departamento&&""!=a.departamento&&(
					i+="<dt id='dtCiudadMovilidad_"+a.id_relaboralmovilidad+"'>Ciudad:</dt><dd id='ddPaisMovilidad_"+a.id_relaboralmovilidad+"'>"+a.departamento+"</dd>"),null!=a.lugar&&""!=a.lugar&&(i+="<dt id='dtLugarMovilidad_"+a.id_relaboralmovilidad+"'>Lugar:</dt><dd id='ddLugarMovilidad_"+a.id_relaboralmovilidad+"'>"+a.lugar+"</dd>")),i+="<dt id='dtObservacionMovilidad_"+a.id_relaboralmovilidad+"'>Observaciones:</dt><dd id='ddObservacionMovilidad_"+a.id_relaboralmovilidad+"'>"+a.observacion+"</dd>",i+="</dl>"})}}),i
	}