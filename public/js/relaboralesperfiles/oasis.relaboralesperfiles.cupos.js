function cargarCupos(a,r,t,i){var o="",e=1,s=0,n=0,c=[],d="",u=0;$("#tbody_cupos").html(""),$.ajax({url:"/ubicaciones/listgruposporcupos",type:"POST",datatype:"json",async:!1,cache:!1,data:{id_ubicacion:a,id_perfillaboral:r,fecha_ini:t,fecha_fin:i},success:function(a){var r=jQuery.parseJSON(a);0<r.length&&($.each(r,function(a,r){o="";var t=0;d="n",u=0,null!=r.id_cupoturno&&(t=r.cupo,d="r",u=r.id_cupoturno),o+="<tr id='"+d+"_"+r.id+"_"+u+"' class='tr_ubicaciones'><td class='text-center'>"+e+"</td><td class='text-left'>"+r.ubicacion+"</td><td class='text-left'>"+r.estacion+"</td>",o+="<td style='background-color: "+r.color+";'><input id='sliderCupo"+r.id+"' class='sliderCupo' type='text' data-slider-min='0' data-slider-max='200' data-slider-step='1' data-slider-value='"+t+"'></td>",o+="<td class='text-center'><span id='spanSlider"+r.id+"' class='spanSlider'>"+t+"</span></td>",o+="</tr>",e++,c.push(r.id),s+=t,$("#tbody_cupos").append(o),new Slider("#sliderCupo"+r.id).on("slide",function(a){$("#spanSlider"+r.id).html(""),$("#spanSlider"+r.id).text(a.value),n=0,$.each(c,function(a,r){n+=parseFloat($("#spanSlider"+r).text()),$("#spanTotalCupo").html(""),$("#spanTotalCupo").text(n)})})}),o="<tr><td colspan='4' class='text-right'><b>Total:</b></td><td class='text-center'><span id='spanTotalCupo'>"+s+"</span></td></tr>",$("#tbody_cupos").append(o))}})}function validaFormularioCupos(){var a=!0,r="";return $(".msjs-alert").hide(),$(".sliderCupo").each(function(){if($(this).val()<0||null==$(this).val())return a=!1}),a||(r="Debe seleccionar un cupo para al menos una ubicaci&oacute;n y/o estaci&óacute;n.",$("#divMsjePorError").html(""),$("#divMsjePorError").append(r),$("#divMsjeNotificacionError").jqxNotification("open")),a}function guardaFormularioCupos(o,a,e,s){var n=!0;return 0<o&&""!=e&&""!=s&&$(".tr_ubicaciones").each(function(){var a=(this.id+"").split("_"),r=(a[0],a[1]),t=a[2],i=parseFloat($("#spanSlider"+r).text());0<i?guardarCupoPorPerfilUbicacionRango(t,o,r,e,s,i,"")||(n=!1):0<t&&descartarCupoTurno(t)}),n}function guardarCupoPorPerfilUbicacionRango(a,r,t,i,o,e,s){var n=!1;return 0<r&&0<t&&""!=i&&""!=o&&$.ajax({url:"/cuposturnos/save/",type:"POST",datatype:"json",async:!1,cache:!1,data:{id:a,id_perfillaboral:r,id_ubicacion:t,fecha_ini:i,fecha_fin:o,cupo:e,observacion:s},success:function(a){var r=jQuery.parseJSON(a);if(1==r.result)n=!0;else{n=!1;var t=r.msj;$("#divMsjePorError").html(""),$("#divMsjePorError").append(t),$("#divMsjeNotificacionError").jqxNotification("open")}}}),n}function descartarCupoTurno(a){var i=!1;return 0<a&&$.ajax({url:"/cuposturnos/down/",type:"POST",datatype:"json",async:!1,cache:!1,data:{id:a},success:function(a){var r=jQuery.parseJSON(a);if(1==r.result)i=!0;else{i=!1;var t=r.msj;$("#divMsjePorError").html(""),$("#divMsjePorError").append(t),$("#divMsjeNotificacionError").jqxNotification("open")}}}),i}