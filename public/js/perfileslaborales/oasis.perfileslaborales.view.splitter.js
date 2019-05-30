function obtenerGestionesParaHistorial(d){var a=[];return $("#HistorialSplitter").jqxSplitter("expand"),$.ajax({url:"/relaborales/listgestionesporpersona",type:"POST",datatype:"json",async:!1,cache:!1,data:{id:d},success:function(d){var i=jQuery.parseJSON(d);0<i.length&&$.each(i,function(d,i){a.push(i.gestion)})}}),a}function cargarGestionesHistorialRelaboral(d){$("#listboxGestiones").off();var r=obtenerGestionesParaHistorial(d);$("#HistorialSplitter").jqxSplitter("expand"),$("#listboxGestiones").jqxListBox({width:200,source:r,checkboxes:!0,height:"100%"}),0<r.length&&$.each(r,function(d,i){$("#hdnSwPrimeraVistaHistorial").val(0),$("#listboxGestiones").jqxListBox("checkIndex",d),$("#hdnSwPrimeraVistaHistorial").val(0)}),1==r.length&&$("#HistorialSplitter").jqxSplitter("collapse");var l=$("#hdnIdPersonaHistorial").val();$("#listboxGestiones").on("checkChange",function(d){var i=0,a=(d.args,$("#listboxGestiones").jqxListBox("getCheckedItems")),e="";if($.each(a,function(d){d<a.length-1?e+=this.label+", ":e+=this.label,i++}),i==r.length)cargarHistorialRelacionLaboral(l,0,1);else if($("#HistorialTimeLine").html(""),0<i){var o=e.split(",");$.each(o,function(d,i){cargarHistorialRelacionLaboral(l,i,0)})}$("#hdnSwPrimeraVistaHistorial").val(1)})}function cargarHistorialRelacionLaboral(d,i,a){1==a&&$("#HistorialTimeLine").html("");var e="";$.ajax({url:"/relaborales/listhistorial",type:"POST",datatype:"json",async:!1,cache:!1,data:{id:d,gestion:i},success:function(d){var i=jQuery.parseJSON(d);0<i.length&&($.each(i,function(d,i){switch(1==i.estado?(e+="<li class='active'>",e+="<div class='timeline-icon'>"):2==i.estado?(e+="<li>",e+="<div class='timeline-icon'>"):(e+="<li class='active'>",e+="<div class='timeline-icon themed-background-fire themed-border-fire'>"),e+="<i class='fa fa-file-text' title='Registro de Relaci&oacute;n Laboral'></i></div>",e+="<div class='timeline-time'><a href='#' id='divContent_"+i.id_relaboral+"'>"+i.fecha_ini+"</a><strong></strong></div>",e+="<div class='timeline-content'>",e+="<p class='push-bit'><strong id='strCargo_"+i.id_relaboral+"'>"+i.cargo+"</strong></p>",e+="<dl class='dl-horizontal'>",e+="<dt id='dtProceso_"+i.id_relaboral+"'>Proceso:</dt><dd id='ddProceso_"+i.id_relaboral+"'>"+i.proceso_codigo+"</dd>",e+="<dt id='dtFinPartida_"+i.id_relaboral+"'>Financiamiento:</dt><dd id='ddFinPartida_"+i.id_relaboral+"'>"+i.condicion+" (Partida "+i.partida+")</dd>",e+="<dt id='dtGerencia_"+i.id_relaboral+"'>Gerencia:</dt><dd id='ddGerencia_"+i.id_relaboral+"'>"+i.gerencia_administrativa+"</dd>",""!=i.departamento_administrativo&&(e+="<dt id='dtDepartamento_"+i.id_relaboral+"'>Departamento:</dt><dd id='ddDepartamento_"+i.id_relaboral+"'>"+i.departamento_administrativo+"</dd>"),0<i.id_area&&(e+="<dt id='dtArea_"+i.id_relaboral+"'>&Aacute;rea:</dt><dd id='ddArea_"+i.id_relaboral+"'>"+i.area+"</dd>"),e+="<dt id='dtUbicacion_"+i.id_relaboral+"'>Ubicaci&oacute;n:</dt><dd id='ddUbicacion_"+i.id_relaboral+"'>"+i.ubicacion+"</dd>",i.condicion){case"PERMANENTE":e+="<dt id='dtItem_"+i.id_relaboral+"'>&Iacute;tem:</dt><dd id='ddItem_"+i.id_relaboral+"'>"+i.cargo_codigo+"</dd>";break;case"EVENTUAL":case"CONSULTOR":var a="&nbsp;";null!=i.num_contrato&&(a=i.num_contrato),e+="<dt id='dtNumContrato_"+i.id_relaboral+"'>Nro. de Contrato:</dt><dd id='ddNumContrato_"+i.id_relaboral+"'>"+a+"</dd>"}switch(e+="<dt id='dtNivelSalarial_"+i.id_relaboral+"'>Nivel Salarial:</dt><dd id='ddNivelSalarial_"+i.id_relaboral+"'>"+i.nivelsalarial+"</dd>",e+="<dt id='dtHaber_"+i.id_relaboral+"'>Haber:</dt><dd id='ddHaber_"+i.id_relaboral+"'>"+i.sueldo+"</dd>",e+="<dt id='dtFechaIni_"+i.id_relaboral+"'>Fecha Inicio:</dt><dd id='ddFechaIni_"+i.id_relaboral+"'>"+i.fecha_ini+"</dd>",e+="<dt id='dtFechaIncor_"+i.id_relaboral+"'>Fecha Incor:</dt><dd id='ddFechaIncor_"+i.id_relaboral+"'>"+i.fecha_incor+"</dd>",i.condicion){case"PERMANENTE":break;case"EVENTUAL":case"CONSULTOR":e+="<dt id='dtFechaFin_"+i.id_relaboral+"'>Fecha Fin:</dt><dd id='ddFechaFin_"+i.id_relaboral+"'>"+i.fecha_fin+"</dd>"}0==i.estado&&(e+="<dt id='dtFechaBaja_"+i.id_relaboral+"'>Fecha Baja:</dt><dd id='ddFechaBaja_"+i.id_relaboral+"'>"+i.fecha_baja+"</dd>",e+="<dt id='dtMotivoBaja_"+i.id_relaboral+"'>Motivo Baja:</dt><dd id='ddMotivoBaja_"+i.id_relaboral+"'>"+i.motivo_baja+"</dd>"),e+="<dt id='dtContratoEstado_"+i.id_relaboral+"'>Estado:</dt>",e+="<dd id='ddContratoEstado_"+i.id_relaboral+"'>",e+="<strong>"+i.estado_descripcion+"</strong></dd>",e+="<dt id='dtObservacion_"+i.id_relaboral+"'>Observaciones:</dt><dd id='ddObservacion_"+i.id_relaboral+"'>"+i.observacion+"</dd>",e+="</dl>",e+=cargarHistorialRelacionLaboralMovilidad(i.id_relaboral)}),$("#HistorialTimeLine").append(e))}})}function cargarHistorialRelacionLaboralMovilidad(d){var a="";return $.ajax({url:"/relaborales/listhistorialmovilidad",type:"GET",datatype:"json",async:!1,cache:!1,data:{id:d},success:function(d){var i=jQuery.parseJSON(d);0<i.length&&$.each(i,function(d,i){1==i.estado?(a+="<li class='active'>",a+="<div class='timeline-icon'>"):(a+="<li class='active'>",a+="<div class='timeline-icon themed-background-fire themed-border-fire'>"),4==i.id_tipomemorandum?a+="<i class='gi gi-airplane' title='Memor&aacute;ndum'></i></div>":5==i.id_tipomemorandum?a+="<i class='hi hi-tree_conifer' title='Memor&aacute;ndum'></i></div>":a+="<i class='fa fa-tag' title='Memor&aacute;ndum'></i></div>",a+="<div class='timeline-time'><a href='#' id='divContentMovilidad_"+i.id_relaboralmovilidad+"'>"+i.fecha_ini+"</a><strong></strong></div>",a+="<div class='timeline-content'>",a+="<p class='push-bit'>"+i.tipo_memorandum+": <strong id='strCargoMotivoMovilidad_"+i.id_relaboralmovilidad+"'>",null!=i.cargo?a+=i.cargo:""!=i.motivo&&(a+=i.motivo),a+="</strong></p>",a+="<dl class='dl-horizontal'>",null!=i.gerencia_administrativa&&""!=i.gerencia_administrativa&&(a+="<dt id='dtGerenciaMovilidad_"+i.id_relaboralmovilidad+"'>Gerencia:</dt><dd id='ddGerenciaMovilidad_"+i.id_relaboralmovilidad+"'>"+i.gerencia_administrativa+"</dd>"),null!=i.departamento_administrativo&&""!=i.departamento_administrativo&&(a+="<dt id='dtDepartamentoMovilidad_"+i.id_relaboralmovilidad+"'>Departamento:</dt><dd id='ddDepartamentoMovilidad_"+i.id_relaboralmovilidad+"'>"+i.departamento_administrativo+"</dd>"),0<i.id_area&&(a+="<dt id='dtAreaMovilidad_"+i.id_relaboralmovilidad+"'>&Aacute;rea:</dt><dd id='ddAreaMovilidad_"+i.id_relaboralmovilidad+"'>"+i.area+"</dd>"),null!=i.ubicacion&&""!=i.ubicacion&&(a+="<dt id='dtUbicacionMovilidad_"+i.id_relaboralmovilidad+"'>Ubicaci&oacute;n:</dt><dd id='ddUbicacionMovilidad_"+i.id_relaboralmovilidad+"'>"+i.ubicacion+"</dd>"),a+="<dt id='dtFechaIniMovilidad_"+i.id_relaboralmovilidad+"'>Fecha Inicio:</dt><dd id='ddFechaIniMovilidad_"+i.id_relaboralmovilidad+"'>"+i.fecha_ini+"</dd>",null!=i.hora_ini&&""!=i.hora_ini&&(a+="<dt id='dtHoraIniMovilidad_"+i.id_relaboralmovilidad+"'>Hora Ini:</dt><dd id='ddHoraIniMovilidad_"+i.id_relaboralmovilidad+"'>"+i.hora_ini+"</dd>"),""!=i.fecha_fin&&(a+="<dt id='dtFechaFinMovilidad_"+i.id_relaboralmovilidad+"'>Fecha Fin:</dt><dd id='ddFechaFinMovilidad_"+i.id_relaboralmovilidad+"'>"+i.fecha_fin+"</dd>"),null!=i.hora_fin&&""!=i.hora_fin&&(a+="<dt id='dtHoraFinMovilidad_"+i.id_relaboralmovilidad+"'>Hora Fin:</dt><dd id='ddHoraFinMovilidad_"+i.id_relaboralmovilidad+"'>"+i.hora_fin+"</dd>"),a+="<dt id='dtMemorandumMovilidad_"+i.id_relaboralmovilidad+"'>Memor&aacute;ndum:</dt><dd id='ddMemorandumMovilidad_"+i.id_relaboralmovilidad+"'>"+i.memorandum_correlativo+"/"+i.memorandum_gestion+" de "+i.fecha_mem+"</dd>",4!=i.id_tipomemorandum&&5!=i.id_tipomemorandum||(null!=i.pais&&""!=i.pais&&(a+="<dt id='dtPaisMovilidad_"+i.id_relaboralmovilidad+"'>Pa&iacute;s:</dt><dd id='ddPaisMovilidad_"+i.id_relaboralmovilidad+"'>"+i.pais+"</dd>"),null!=i.departamento&&""!=i.departamento&&(a+="<dt id='dtCiudadMovilidad_"+i.id_relaboralmovilidad+"'>Ciudad:</dt><dd id='ddPaisMovilidad_"+i.id_relaboralmovilidad+"'>"+i.departamento+"</dd>"),null!=i.lugar&&""!=i.lugar&&(a+="<dt id='dtLugarMovilidad_"+i.id_relaboralmovilidad+"'>Lugar:</dt><dd id='ddLugarMovilidad_"+i.id_relaboralmovilidad+"'>"+i.lugar+"</dd>")),a+="<dt id='dtObservacionMovilidad_"+i.id_relaboralmovilidad+"'>Observaciones:</dt><dd id='ddObservacionMovilidad_"+i.id_relaboralmovilidad+"'>"+i.observacion+"</dd>",a+="</dl>"})}}),a}