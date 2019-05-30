/**
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  13-09-2016
 */
$().ready(function () {
    $("#divProgressBar").hide();
    /**
     * Inicialmente se habilita solo la pestaña del listado
     */
    $('#divTabControlMarcaciones').jqxTabs('theme', 'oasis');
    $('#divTabControlMarcaciones').jqxTabs('enableAt', 0);
    $('#divTabControlMarcaciones').jqxTabs('disableAt', 1);

    var fecha = new Date();
    var anio = fecha.getFullYear();
    cargarGestionesRelaborales(anio);
    definirGrillaParaListaRelaborales($("#lstGestion").val());
    $("#lstGestion").off();
    $("#lstGestion").on("change", function () {
        definirGrillaParaListaRelaborales($("#lstGestion").val());
    });
    /**
     * Control del evento de solicitud de guardar el registro de la excepción de control.
     */
    $("#btnAplicarRegistroMasivo").off();
    $("#btnAplicarRegistroMasivo").on("click", function () {
        $("#divErrores").hide();
        var ok = validaFormularioRegistroMasivo()
        if (ok) {
            var rows = $("#divGridRelaborales").jqxGrid('selectedrowindexes');
            var cantidadTotal = rows.length;
            var contadorCorrectos = 0;
            var listIdRelaboralesCorrectos = "";
            var contadorErroneos = 0;
            var contador = 0;
            var porcentaje = 0;
            if (cantidadTotal > 0) {
                var msjErrorCruceTotal = "";
                var grillaErrores = "";
                var numeracion = 0;
                var sep = "\n";
                $('.progress-bar', '.bars-container').each(function () {
                    var random = porcentaje + '%';
                    $(this).css('width', random).html(random + " [" + contador + ":" + cantidadTotal + "]");
                });
                $("#divProgressBar").show();
                $("#divGrillaErrores").html("");
                for (var m = 0; m < cantidadTotal; m++) {
                    contador++;
                    var dataRecord = $("#divGridRelaborales").jqxGrid('getrowdata', rows[m]);
                    var idRelaboral = dataRecord.id_relaboral;
                    var nombres = dataRecord.nombres;
                    var ci = dataRecord.ci;
                    var expd = dataRecord.expd;
                    var id_genero = $("#lstExcepcion" + " option:selected").data("id_genero");
                    var genero = dataRecord.genero;
                    var imageSrc = dataRecord.image_src;
                    var cargo = dataRecord.cargo;
                    var idExcepcion = $("#lstExcepcion").val();
                    var fechaIni = $("#txtFechaIni").val();
                    var horaIni = $("#txtHoraIni").val();
                    var fechaFin = $("#txtFechaFin").val();
                    var horaFin = $("#txtHoraFin").val();
                    var horario = $("#lstExcepcion option:selected").data("horario");
                    var justificacion = $("#txtJustificacionRegistroMasivo").val();

                    var msjErrorCruce = verificaCruceDeHorariosYExcesoEnUso(0, idRelaboral, idExcepcion, fechaIni, horaIni, fechaFin, horaFin, horario, justificacion);
                    var msjeErrorFrecuencia = verificaFrecuencia(0, idRelaboral, idExcepcion, fechaIni, horaIni, fechaFin, horaFin, horario);
                    var okGenero = verificaGenero(genero, id_genero);
                    if (msjeErrorFrecuencia != "" || msjErrorCruce != "" || !okGenero) {
                        ok = false;
                        if (grillaErrores == "") {
                            grillaErrores += "<table class='table table-striped table-vcenter'>";
                            grillaErrores += "<thead><tr>";
                            grillaErrores += "<th class='text-center'>Nro.</th>";
                            grillaErrores += "<th class='text-center'><i class='gi gi-user'></i></th>";
                            grillaErrores += "<th class='text-center'>Nombres</th>";
                            grillaErrores += "<th class='text-center'>CI</th>";
                            grillaErrores += "<th class='text-center'>Cargo</th>";
                            grillaErrores += "<th class='text-center'>Cruce</th>";
                            grillaErrores += "<th class='text-center'>Frecuencia</th>";
                            grillaErrores += "<th class='text-center'>G&eacute;nero</th>";
                            grillaErrores += "</tr>";
                            grillaErrores += "</thead>";
                            grillaErrores += "<tbody>";
                        }
                        numeracion++;
                        grillaErrores += "<tr>";
                        grillaErrores += "<td class='text-center'>" + numeracion + "</td>";
                        grillaErrores += "<td  class='text-center'><img class='img-circle' width='60px;' height='60px;' alt='" + nombres + "' src='" + imageSrc + "'></td>";
                        grillaErrores += "<td>" + nombres + "</td>";
                        grillaErrores += "<td>" + ci + " " + expd + "</td>";
                        grillaErrores += "<td>" + cargo + "</td>";
                        if (msjErrorCruce == "") {
                            grillaErrores += "<td class='text-center'><i class='fa fa-check fa-2x text-success'></i></td>"
                        } else {
                            grillaErrores += "<td>" + msjErrorCruce + "</td>";
                        }
                        if (msjeErrorFrecuencia == "") {
                            grillaErrores += "<td class='text-center'><i class='fa fa-check fa-2x text-success'></i></td>"
                        } else {
                            grillaErrores += "<td>" + msjeErrorFrecuencia + "</td>";
                        }
                        if (okGenero) {
                            grillaErrores += "<td class='text-center'><i class='fa fa-check fa-2x text-success'></i></td>"
                        } else {
                            grillaErrores += "<td class='text-center'><i class='fa fa-ban fa-2x text-danger'></i></td>"
                        }
                        grillaErrores += "</tr>";
                    } else {
                        ok = true;
                    }
                    if (ok) {
                        contadorCorrectos++;
                        listIdRelaboralesCorrectos += idRelaboral + ",";
                    } else {
                        contadorErroneos++;
                    }
                    var porcentaje = parseFloat((100 * contador) / cantidadTotal);
                    porcentaje = porcentaje.toFixed(0);
                    $('.progress-bar', '.bars-container').each(function () {
                        var random = porcentaje + '%';
                        $(this).css('width', random).html(random + " [" + contador + ":" + cantidadTotal + "]");
                    });
                }
                if (grillaErrores != "") {
                    grillaErrores += "</tbody>";
                    grillaErrores += "</table>";
                }
                setTimeout(function () {
                    $("#divProgressBar").hide();
                }, 3000);
                if (listIdRelaboralesCorrectos != '') {
                    listIdRelaboralesCorrectos += ",";
                    listIdRelaboralesCorrectos = listIdRelaboralesCorrectos.replace(",,", "");
                }
                if (grillaErrores != "") {
                    $("#divErrores").show();
                    $("#divGrillaErrores").html("");
                    $("#divGrillaErrores").html(grillaErrores);
                    var registrosCorrectos = contador - numeracion;
                    if (registrosCorrectos > 0) {
                        bootbox.confirm("Existen " + registrosCorrectos + " registros admitidos de un total de " + contador + ", ¿desea solicitar su registro? Tenga en consideraci&oacute;n que no se registrar&aacute;n " + numeracion + " registros.", function (f) {
                            if (f) {
                                guardarRegistrosMasivos(listIdRelaboralesCorrectos);
                                $("#popupFormularioRegistroMasivo").modal("hide");
                            }
                        });
                    }
                } else {
                    bootbox.confirm("Ha solicitado " + contador + " registros, ¿confirma su solicitud?", function (f) {
                        if (f) {
                            guardarRegistrosMasivos(listIdRelaboralesCorrectos);
                            $("#popupFormularioRegistroMasivo").modal("hide");
                        }
                    });
                }
            }
        }
    });
    $("#chkAllCols").click(function () {
        if (this.checked == true) {
            $("#jqxlistbox").jqxListBox('checkAll');
        } else {
            $("#jqxlistbox").jqxListBox('uncheckAll');
        }
    });
    $("#liList,#btnCancelarTurnAndExcept").click(function () {
        $('#divTabControlMarcaciones').jqxTabs('enableAt', 0);
        $('#divTabControlMarcaciones').jqxTabs({selectedItem: 0});
        $('#divTabControlMarcaciones').jqxTabs('disableAt', 1);
    });
    /*
     *   Función para la inserción obligatoria de datos numéricos en los campos de clase.
     */
    $('.numeral').keyup(function (event) {
        if ($(this).val() != '') {
            $(this).val($(this).val().replace(/[^0-9]/g, ""));
        }
    });

    /*
     *   Función para la inserción obligatoria de letras distintas a números en los campos de clase.
     */
    $('.literal').keyup(function (event) {
        if ($(this).val() != '') {
            $(this).val($(this).val().replace(/[^A-Z,a-z,ñ,Ñ, ]/g, ""));
        }
    });
    $("#divMsjeNotificacionError").jqxNotification({
        width: '100%', position: "bottom-right", opacity: 0.9,
        autoOpen: false, animationOpenDelay: 800, autoClose: true, autoCloseDelay: 7000, template: "error"
    });

    $("#divMsjeNotificacionWarning").jqxNotification({
        width: '100%', position: "bottom-right", opacity: 0.9,
        autoOpen: false, animationOpenDelay: 800, autoClose: true, autoCloseDelay: 7000, template: "warning"
    });
    $("#divMsjeNotificacionSuccess").jqxNotification({
        width: '100%', position: "bottom-right", opacity: 0.9,
        autoOpen: false, animationOpenDelay: 800, autoClose: true, autoCloseDelay: 7000, template: "success"
    });

    $(document).keypress(OperaEvento);
    $(document).keyup(OperaEvento);
});
/**
 * Función para instanciar un objeto de tipo parámetro.
 */
function getParametroVacio() {
    var objParametro = {
        idOrganigrama: 0,
        idArea: 0,
        idUbicacion: 0,
        idMaquina: 0,
        idRelaboral: 0,
        fechaIni: '',
        fechaFin: ''
    }
    return objParametro;
}
/**
 * Función para definir la grilla principal (listado) para la gestión de relaciones laborales.
 */
function definirGrillaParaListaRelaborales(gestionConsulta) {
    var source =
        {
            datatype: "json",
            datafields: [
                {name: 'nro_row', type: 'integer'},
                {name: 'fecha_nac', type: 'string'},
                {name: 'edad', type: 'integer'},
                {name: 'lugar_nac', type: 'integer'},
                {name: 'genero', type: 'integer'},
                {name: 'e_civil', type: 'integer'},
                {name: 'id_relaboral', type: 'integer'},
                {name: 'id_persona', type: 'integer'},
                {name: 'tiene_contrato_vigente', type: 'integer'},
                {name: 'id_fin_partida', type: 'integer'},
                {name: 'finpartida', type: 'string'},
                {name: 'ubicacion', type: 'string'},
                {name: 'id_condicion', type: 'integer'},
                {name: 'condicion', type: 'string'},
                {name: 'item', type: 'integer'},
                {name: 'tiene_item', type: 'integer'},
                {name: 'id_cargo', type: 'integer'},
                {name: 'cargo_codigo', type: 'string'},
                {name: 'cargo_resolucion_ministerial_id', type: 'integer'},
                {name: 'cargo_resolucion_ministerial', type: 'string'},
                {name: 'estado', type: 'integer'},
                {name: 'estado_descripcion', type: 'string'},
                {name: 'nombres', type: 'string'},
                {name: 'ci', type: 'string'},
                {name: 'expd', type: 'string'},
                {name: 'image_src', type: 'string'},
                {name: 'id_organigrama', type: 'integer'},
                {name: 'gerencia_administrativa', type: 'string'},
                {name: 'departamento_administrativo', type: 'string'},
                {name: 'id_area', type: 'integer'},
                {name: 'area', type: 'string'},
                {name: 'id_ubicacion', type: 'integer'},
                {name: 'ubicacion', type: 'string'},
                {name: 'num_contrato', type: 'string'},
                {name: 'fin_partida', type: 'string'},
                {name: 'partida', type: 'integer'},
                {name: 'id_procesocontratacion', type: 'integer'},
                {name: 'proceso_codigo', type: 'string'},
                {name: 'nivelsalarial', type: 'string'},
                {name: 'nivelsalarial_resolucion', type: 'string'},
                {name: 'cargo', type: 'string'},
                {name: 'sueldo', type: 'numeric'},
                {name: 'fecha_ini', type: 'date'},
                {name: 'fecha_incor', type: 'date'},
                {name: 'fecha_fin', type: 'date'},
                {name: 'fecha_baja', type: 'date'},
                {name: 'motivo_baja', type: 'string'},
                {name: 'relaboral_previo_id', type: 'integer'},
                {name: 'observacion', type: 'string'},
                {name: 'fecha_ing', type: 'date'}
            ],
            url: '/relaborales/listactivosengestion?gestion=' + gestionConsulta,
            cache: false
        };
    var dataAdapter = new $.jqx.dataAdapter(source);
    cargarRegistrosDeRelacionesLaborales();
    function cargarRegistrosDeRelacionesLaborales() {
        var theme = prepareSimulator("grid");
        $("#divGridRelaborales").jqxGrid(
            {
                theme: theme,
                width: '100%',
                height: '590px',
                source: dataAdapter,
                sortable: true,
                altRows: true,
                groupable: true,
                columnsresize: true,
                pageable: true,
                pagerMode: 'advanced',
                showfilterrow: true,
                filterable: true,
                showtoolbar: true,
                autorowheight: true,
                selectionmode: 'checkbox',
                rendertoolbar: function (toolbar) {
                    var me = this;
                    var container = $("<div></div>");
                    toolbar.append(container);
                    container.append("<button title='Generar boletas.' id='regrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-pencil-square-o fa-2x text-success' title='Generar boletas.'/></i></button>");
                    container.append("<button title='Ver calendario de turnos y permisos de manera global para la persona.' id='turnrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-calendar fa-2x text-success' title='Vista Turnos Laborales por relaci&oacute;n laboral.'/></i></button>");
                    container.append("<button title='Refrescar Grilla' id='refreshbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-repeat fa-2x text-default' title='Refrescar grilla.'/></i></button>");
                    container.append("<button title='Desagrupar.' id='cleargroupsrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-chain-broken fa-2x text-default' title='Desagrupar.'/></i></button>");
                    container.append("<button title='Desfiltrar.' id='clearfiltersrowbutton' class='btn btn-sm btn-primary' type='button'><i class='gi gi-sorting fa-2x text-default' title='Desfiltrar.'/></i></button>");

                    $("#regrowbutton").jqxButton();
                    $("#turnrowbutton").jqxButton();
                    $("#refreshbutton").jqxButton();
                    $("#cleargroupsrowbutton").jqxButton();
                    $("#clearfiltersrowbutton").jqxButton();

                    $("#hdnIdRelaboralVista").val(0);
                    $("#divCalculoFechas").hide();
                    /**
                     * Refrescar la grilla
                     */
                    $("#refreshbutton").on('click', function () {
                        $("#divGridRelaborales").jqxGrid("updatebounddata");
                    });
                    /**
                     * Desagrupar
                     */
                    $("#cleargroupsrowbutton").off();
                    $("#cleargroupsrowbutton").on('click', function () {
                        $("#divGridRelaborales").jqxGrid('cleargroups');
                    });
                    /**
                     * Desfiltrar
                     */
                    $("#clearfiltersrowbutton").off();
                    $("#clearfiltersrowbutton").on('click', function () {
                        $("#divGridRelaborales").jqxGrid('clearfilters');
                    });

                    /**
                     * Registrar boletas
                     */
                    $("#regrowbutton").off();
                    $("#regrowbutton").on('click', function () {
                        $("#divProgressBar").hide();
                        $("#divErrores").hide();
                        $("#divGrillaErrores").html("");
                        var rows = $("#divGridRelaborales").jqxGrid('selectedrowindexes');
                        if (rows.length > 0) {
                            limpiarMensajesErrorPorValidacionFormularioRegistroMasivo();
                            cargarModalRegistroMasivoBoletas();
                            $("#popupFormularioRegistroMasivo").on('shown.bs.modal', function () {
                                $("#lstExcepcion").focus();
                            });
                            $("#popupFormularioRegistroMasivo").modal("show");
                        } else {
                            var msje = "Debe al menos seleccionar un registro para solicitar la generaci&oacute;n de las boletas de manera masiva.";
                            $("#divMsjePorError").html("");
                            $("#divMsjePorError").append(msje);
                            $("#divMsjeNotificacionError").jqxNotification("open");
                        }
                    });
                    /** Ver registro.**/
                    $("#turnrowbutton").off();
                    $("#turnrowbutton").on('click', function () {
                        var selectedrowindex = $("#divGridRelaborales").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var dataRecord = $('#divGridRelaborales').jqxGrid('getrowdata', selectedrowindex);
                            if (dataRecord != undefined) {

                                var rows = $("#divGridRelaborales").jqxGrid('selectedrowindexes');
                                if (rows.length == 1) {
                                    $('#divTabControlMarcaciones').jqxTabs('enableAt', 0);
                                    $('#divTabControlMarcaciones').jqxTabs('enableAt', 1);

                                    $('#divTabControlMarcaciones').jqxTabs({selectedItem: 1});

                                    $("#spanPrefijoCalendarioLaboral").html("");
                                    $("#spanSufijoCalendarioLaboral").html(" Vs. Calendario de Excepciones (Individual)");
                                    var date = new Date();
                                    var defaultDia = date.getDate();
                                    var defaultMes = date.getMonth();
                                    var defaultGestion = date.getFullYear();
                                    if (dataRecord.estado == 0) {
                                        defaultDia = (dataRecord.fecha_baja).getDate();
                                        defaultMes = (dataRecord.fecha_baja).getMonth();
                                        defaultGestion = (dataRecord.fecha_baja).getFullYear();
                                    }
                                    var contadorPerfiles = 0;
                                    var idPerfilLaboral = 0;
                                    var tipoHorario = 3;
                                    $("#calendar").html("");
                                    /**
                                     * Los horarios son cargados al momento de desplegarse el calendario.
                                     * @type {Array}
                                     */
                                    var arrHorariosRegistrados = [];
                                    var arrFechasPorSemana = iniciarCalendarioLaboralPorRelaboralTurnosYExcepcionesParaVerAsignaciones(dataRecord, dataRecord.id_relaboral, 5, idPerfilLaboral, tipoHorario, arrHorariosRegistrados, defaultGestion, defaultMes, defaultDia);
                                    sumarTotalHorasPorSemana(arrFechasPorSemana);
                                } else {
                                    var msje = "Debe seleccionar un s&oacute;lo registro para la obtención de la vista de turnos laborales correspondientes.";
                                    $("#divMsjePorError").html("");
                                    $("#divMsjePorError").append(msje);
                                    $("#divMsjeNotificacionError").jqxNotification("open");
                                }
                            } else {
                                var msje = "Para acceder a la vista del registro, la persona debe haber tenido al menos un registro de relaci&oacute,n laboral que implica un estado ACTIVO o PASIVO.";
                                $("#divMsjePorError").html("");
                                $("#divMsjePorError").append(msje);
                                $("#divMsjeNotificacionError").jqxNotification("open");
                            }
                            $('#tabFichaPersonalTurnAndExcept').jqxTabs({
                                theme: 'oasis',
                                width: '100%',
                                height: '100%',
                                position: 'top'
                            });
                            $('#tabFichaPersonalTurnAndExcept').jqxTabs({selectedItem: 0});
                            $(".ddNombresTurnAndExcept").html(dataRecord.nombres + "&nbsp;");
                            $(".ddCIAndNumComplementoExpdTurnAndExcept").html(dataRecord.ci + dataRecord.num_complemento + " " + dataRecord.expd + "&nbsp;");
                            $("#ddCargoTurnAndExcept").html(dataRecord.cargo + "&nbsp;");
                            $("#ddProcesoTurnAndExcept").html(dataRecord.proceso_codigo + "&nbsp;");
                            $("#ddFinanciamientoTurnAndExcept").html(dataRecord.condicion + " (Partida " + dataRecord.partida + ")");
                            $("#ddGerenciaTurnAndExcept").html(dataRecord.gerencia_administrativa + "&nbsp;");
                            if (dataRecord.departamento_administrativo != "" && dataRecord.departamento_administrativo != null) {
                                $("#ddDepartamentoTurnAndExcept").show();
                                $("#dtDepartamentoTurnAndExcept").show();
                                $("#ddDepartamentoTurnAndExcept").html(dataRecord.departamento_administrativo + "&nbsp;");
                            }
                            else {
                                $("#dtDepartamentoTurnAndExcept").hide();
                                $("#ddDepartamentoTurnAndExcept").hide();
                            }
                            $("#ddUbicacionTurnAndExcept").html(dataRecord.ubicacion + "&nbsp;");

                            switch (dataRecord.tiene_item) {
                                case 1:
                                    $("#dtItemTurnAndExcept").show();
                                    $("#ddItemTurnAndExcept").show();
                                    $("#ddItemTurnAndExcept").html(dataRecord.item + "&nbsp;");
                                    break;
                                case 0:
                                    $("#dtItemTurnAndExcept").hide();
                                    $("#ddItemTurnAndExcept").hide();
                                    break;
                            }
                            $("#ddNivelSalarialTurnAndExcept").html(dataRecord.nivelsalarial + "&nbsp;");
                            $("#ddHaberTurnAndExcept").html(dataRecord.sueldo + "&nbsp;");
                            $("#ddFechaIngTurnAndExcept").html(fechaConvertirAFormato(dataRecord.fecha_ing, "-") + "&nbsp;");
                            if (dataRecord.fecha_incor != null) {
                                var fechaIncor = fechaConvertirAFormato(dataRecord.fecha_incor, "-");
                                $("#dtFechaIncorTurnAndExcept").show();
                                $("#ddFechaIncorTurnAndExcept").show();
                                $("#ddFechaIncorTurnAndExcept").html(fechaIncor + "&nbsp;");
                            } else {
                                $("#dtFechaIncorTurnAndExcept").hide();
                                $("#ddFechaIncorTurnAndExcept").hide();
                            }
                            $("#ddFechaIniTurnAndExcept").html(fechaConvertirAFormato(dataRecord.fecha_ini, "-") + "&nbsp;");
                            switch (dataRecord.tiene_item) {
                                case 1:
                                    $("#dtFechaFinTurnAndExcept").hide();
                                    $("#ddFechaFinTurnAndExcept").hide();
                                    break;
                                case 0:
                                    $("#dtFechaFinTurnAndExcept").show();
                                    $("#ddFechaFinTurnAndExcept").show();
                                    $("#ddFechaFinTurnAndExcept").html(fechaConvertirAFormato(dataRecord.fecha_fin, "-") + "&nbsp;");
                                    break;
                            }
                            $("#ddEstadoDescripcionTurnAndExcept").html(dataRecord.estado_descripcion + "&nbsp;");
                            if (dataRecord.estado == 0) {
                                $("#dtFechaBajaTurnAndExcept").show();
                                $("#ddFechaBajaTurnAndExcept").show();
                                $("#ddFechaBajaTurnAndExcept").html(fechaConvertirAFormato(dataRecord.fecha_baja, "-") + "&nbsp;");
                                $("#dtMotivoBajaTurnAndExcept").show();
                                $("#ddMotivoBajaTurnAndExcept").show();
                                $("#ddMotivoBajaTurnAndExcept").html(dataRecord.motivo_baja + "&nbsp;");
                            } else {
                                $("#dtFechaBajaTurnAndExcept").hide();
                                $("#ddFechaBajaTurnAndExcept").hide();
                                $("#dtMotivoBajaTurnAndExcept").hide();
                                $("#ddMotivoBajaTurnAndExcept").hide();
                            }

                            $("#ddNombresTurnAndExcept").html(dataRecord.nombres);
                            var rutaImagen = obtenerRutaFoto(dataRecord.ci, dataRecord.num_complemento);
                            $("#imgFotoPerfilTurnAndExceptRelaboral").attr("src", rutaImagen);
                            $("#imgFotoPerfilContactoPerTurnAndExcept").attr("src", rutaImagen);
                            $("#imgFotoPerfilContactoInstTurnAndExcept").attr("src", rutaImagen);
                            $("#imgFotoPerfilTurnAndExcept").attr("src", rutaImagen);
                            cargarPersonasContactosHorariosYMarcaciones(2, dataRecord.id_persona);

                        } else {
                            var msje = "Debe seleccionar un registro necesariamente.";
                            $("#divMsjePorError").html("");
                            $("#divMsjePorError").append(msje);
                            $("#divMsjeNotificacionError").jqxNotification("open");
                        }
                    });
                },
                columns: [
                    {
                        text: 'Nro.',
                        sortable: false,
                        filterable: false,
                        editable: false,
                        groupable: false,
                        draggable: false,
                        resizable: false,
                        columntype: 'number',
                        pinned: true,
                        width: 50,
                        cellsalign: 'center',
                        align: 'center',
                        cellsrenderer: rownumberrenderer
                    },
                    {
                        text: 'Nombres y Apellidos',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'nombres',
                        pinned: true,
                        width: 215,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'CI',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'ci',
                        pinned: true,
                        width: 90,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Exp',
                        filtertype: 'checkedlist',
                        filteritems: ["F", "M"],
                        datafield: 'expd',
                        pinned: true,
                        width: 40,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'G&eacute;nero',
                        filtertype: 'checkedlist',
                        datafield: 'genero',
                        pinned: true,
                        width: 40,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Ubicaci&oacute;n',
                        filtertype: 'checkedlist',
                        datafield: 'ubicacion',
                        width: 150,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Condici&oacute;n',
                        filtertype: 'checkedlist',
                        datafield: 'condicion',
                        width: 150,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Estado',
                        filtertype: 'checkedlist',
                        datafield: 'estado_descripcion',
                        width: 100,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: false,
                        cellclassname: cellclass
                    },
                    {
                        text: 'Gerencia',
                        filtertype: 'checkedlist',
                        datafield: 'gerencia_administrativa',
                        width: 220,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Departamento',
                        filtertype: 'checkedlist',
                        datafield: 'departamento_administrativo',
                        width: 220,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: '&Aacute;rea',
                        filtertype: 'checkedlist',
                        datafield: 'area',
                        width: 220,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Proceso',
                        filtertype: 'checkedlist',
                        datafield: 'proceso_codigo',
                        width: 220,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Fuente',
                        filtertype: 'checkedlist',
                        datafield: 'fin_partida',
                        width: 220,
                        cellsalign: 'center',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Nivel Salarial',
                        filtertype: 'checkedlist',
                        datafield: 'nivelsalarial',
                        width: 220,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Cargo',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'cargo',
                        width: 215,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Haber',
                        filtertype: 'checkedlist',
                        datafield: 'sueldo',
                        width: 100,
                        cellsalign: 'right',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Fecha Ingreso',
                        datafield: 'fecha_ing',
                        filtertype: 'range',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Fecha Inicio',
                        datafield: 'fecha_ini',
                        filtertype: 'range',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Fecha Incor.',
                        datafield: 'fecha_incor',
                        filtertype: 'range',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Fecha Fin',
                        datafield: 'fecha_fin',
                        filtertype: 'range',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Fecha Baja',
                        datafield: 'fecha_baja',
                        filtertype: 'range',
                        width: 100,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: false
                    },
                    {text: 'Motivo Baja', datafield: 'motivo_baja', width: 100, hidden: false},
                    {text: 'Observacion', datafield: 'observacion', width: 100, hidden: false},
                ]
            });
        var listSource = [
            {label: 'Ubicaci&oacute;n', value: 'ubicacion', checked: true},
            {label: 'Condici&oacute;n', value: 'condicion', checked: true},
            {label: 'Estado', value: 'estado_descripcion', checked: true},
            {label: 'Nombres y Apellidos', value: 'nombres', checked: true},
            {label: 'CI', value: 'ci', checked: true},
            {label: 'Exp', value: 'expd', checked: true},
            {label: 'G&eacute;nero', value: 'genero', checked: true},
            /*{label: 'N/C', value: 'num_complemento', checked: false},*/
            {label: 'Gerencia', value: 'gerencia_administrativa', checked: true},
            {label: 'Departamento', value: 'departamento_administrativo', checked: true},
            {label: '&Aacute;rea', value: 'area', checked: true},
            {label: 'proceso', value: 'proceso_codigo', checked: true},
            {label: 'Fuente', value: 'fin_partida', checked: true},
            {label: 'Nivel Salarial', value: 'nivelsalarial', checked: true},
            {label: 'Cargo', value: 'cargo', checked: true},
            {label: 'Haber', value: 'sueldo', checked: true},
            {label: 'Fecha Ingreso', value: 'fecha_ing', checked: true},
            {label: 'Fecha Inicio', value: 'fecha_ini', checked: true},
            {label: 'Fecha Incor.', value: 'fecha_incor', checked: true},
            {label: 'Fecha Fin', value: 'fecha_fin', checked: true},
            {label: 'Fecha Baja', value: 'fecha_baja', checked: true},
            {label: 'Motivo Baja', value: 'motivo_baja', checked: true},
            {label: 'Observacion', value: 'observacion', checked: true},
        ];
        $("#jqxlistbox").jqxListBox({source: listSource, width: "100%", height: 560, checkboxes: true});
        $("#jqxlistbox").on('checkChange', function (event) {
            $("#divGridRelaborales").jqxGrid('beginupdate');
            if (event.args.checked) {
                $("#divGridRelaborales").jqxGrid('showcolumn', event.args.value);
            }
            else {
                $("#divGridRelaborales").jqxGrid('hidecolumn', event.args.value);
            }
            $("#divGridRelaborales").jqxGrid('endupdate');
        });
    }
}
var rownumberrenderer = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
    var nro = row + 1;
    return "<div align='center'>" + nro + "</div>";
}
/**
 * Función para el almacenamiento de registro de control de excepción de manera masiva para los registros de relación laboral sin problemas.
 * @param listIdRelaboralesCorrectos
 */
function guardarRegistrosMasivos(listIdRelaboralesCorrectos) {
    var ok = false;
    var lugar = 0;
    if ($("#lstExcepcion option:selected").data("lugar") != null) {
        lugar = $("#lstExcepcion option:selected").data("lugar");
    }
    var idExcepcion = $("#lstExcepcion").val();

    var descuento = $("#lstExcepcion option:selected").data("descuento");
    var compensatoria = $("#lstExcepcion option:selected").data("compensatoria");
    var genero_id = $("#lstExcepcion option:selected").data("genero_id");
    var cantidad = $("#lstExcepcion option:selected").data("cantidad");
    var unidad = $("#lstExcepcion option:selected").data("unidad");
    var fraccionamiento = $("#lstExcepcion option:selected").data("fraccionamiento");
    var horario = $("#lstExcepcion option:selected").data("horario");
    var refrigerio = $("#lstExcepcion option:selected").data("refrigerio");
    var fechaIni = $("#txtFechaIni").val();
    var horaIni = $("#txtHoraIni").val();
    var fechaFin = $("#txtFechaFin").val();
    var horaFin = $("#txtHoraFin").val();
    var justificacion = $("#txtJustificacionRegistroMasivo").val();
    var destino = $("#txtDestino").val();
    var observacion = $("#txtObservacionRegistroMasivo").val();
    var turno = $("#lstCompensacionTurno").val();
    var entradaSalida = $("#lstCompensacionEntradaSalida").val();
    if (listIdRelaboralesCorrectos != '') {
        $.ajax({
            url: '/controlexcepciones/savemasivebyrelaborales/',
            type: "POST",
            datatype: 'json',
            async: false,
            cache: false,
            data: {
                ids: listIdRelaboralesCorrectos,
                excepcion_id: idExcepcion,
                fecha_ini: fechaIni,
                hora_ini: horaIni,
                fecha_fin: fechaFin,
                hora_fin: horaFin,
                justificacion: justificacion,
                destino: destino,
                turno: turno,
                entrada_salida: entradaSalida,
                horario: horario,
                observacion: observacion
            },
            success: function (data) {
                var res = jQuery.parseJSON(data);
                if (res.result == 1) {
                    ok = true;
                    $("#divMsjePorSuccess").html("");
                    $("#divMsjePorSuccess").append(res.msj);
                    $("#divMsjeNotificacionSuccess").jqxNotification("open");
                } else if (res.result == 0) {
                    $("#divMsjePorWarning").html("");
                    $("#divMsjePorWarning").append(res.msj);
                    $("#divMsjeNotificacionWarning").jqxNotification("open");
                } else {
                    $("#divMsjePorError").html("");
                    $("#divMsjePorError").append(res.msj);
                    $("#divMsjeNotificacionError").jqxNotification("open");
                }
            },
            error: function () {
                $("#divMsjePorError").html("");
                $("#divMsjePorError").append("Se ha producido un error Inesperado");
                $("#divMsjeNotificacionError").jqxNotification("open");
            }
        });
    }
    return ok;
}
/*
 * Función para controlar la ejecución del evento esc con el teclado.
 */
function OperaEvento(evento) {
    if ((evento.type == "keyup" || evento.type == "keydown") && evento.which == "27") {
        $('#divTabControlMarcaciones').jqxTabs('enableAt', 0);
        $('#divTabControlMarcaciones').jqxTabs('disableAt', 1);
        $('#divTabControlMarcaciones').jqxTabs('disableAt', 2);
        $('#divTabControlMarcaciones').jqxTabs('disableAt', 3);
        /**
         * Saltamos a la pantalla principal en caso de presionarse ESC
         */
        $('#divTabControlMarcaciones').jqxTabs({selectedItem: 0});
        $("#divGridRelaborales").jqxGrid("updatebounddata");
    }
}
/**
 * Función para convertir un texto con el formato dd-MM-yyyy al formato MM/dd/yyyy
 * @param date Cadena con la fecha
 * @param sep Separador
 * @returns {number}
 */
function procesaTextoAFecha(date, sep) {
    var parts = date.split(sep);
    var date = new Date(parts[1] + "/" + parts[0] + "/" + parts[2]);
    return date.getTime();
}
/**
 *
 * Función para la obtención de la ruta en la cual reside la fotografía correspondiente de la persona.
 * @param numDocumento Número de documento, comunmente el número de CI.
 * @param numComplemento Número de complemento.
 * @returns {string} Ruta de ubicación de la fotografía a mostrarse.
 */
function obtenerRutaFoto(numDocumento, numComplemento) {
    var resultado = "/images/perfil-profesional.jpg";
    if (numDocumento != "") {
        $.ajax({
            url: '/relaborales/obtenerrutafoto/',
            type: "POST",
            datatype: 'json',
            async: false,
            cache: false,
            data: {ci: numDocumento, num_complemento: numComplemento},
            success: function (data) {
                var res = jQuery.parseJSON(data);
                if (res.result == 1) {
                    resultado = res.ruta;
                }
            }, //mostramos el error
            error: function () {
                alert('Se ha producido un error Inesperado');
            }
        });
    }
    return resultado;
}
/**
 * Función para obtener la fecha de este día
 * @param separador
 * @returns {*}
 * @author JLM
 */
function fechaHoy(separador, format) {
    if (separador == '') separador = "-";
    var fullDate = new Date()
    var dia = fullDate.getDate().toString();
    var mes = (fullDate.getMonth() + 1).toString();
    var twoDigitDay = (dia.length === 1 ) ? '0' + dia : dia;
    var twoDigitMonth = (mes.length === 1 ) ? '0' + mes : mes;
    if (format == "dd-mm-yyyy")
        var currentDate = twoDigitDay + separador + twoDigitMonth + separador + fullDate.getFullYear();
    else if (format == "mm-dd-yyyy") {
        var currentDate = twoDigitMonth + separador + twoDigitDay + separador + fullDate.getFullYear();
    } else {
        var currentDate = fullDate;
    }
    return currentDate;
}
/**
 * Función anónima para la aplicación de clases a celdas en particular dentro de las grillas.
 * @param row
 * @param columnfield
 * @param value
 * @returns {string}
 * @author JLM
 */
var cellclass = function (row, columnfield, value) {
    if (value == 'ACTIVO') {
        return 'verde';
    }
    else if (value == 'EN PROCESO') {
        return 'amarillo';
    }
    else if (value == 'PASIVO') {
        return 'rojo';
    }
    else return ''
}
/**
 * Función para la obtención del listado de horarios laborales registrados en el calendario laboral para un determinado registro de relación laboral.
 * @param idRelaboral
 * @param tipoHorario
 * @param editable
 * @param fechaIni
 * @param fechaFin
 * @param contadorPerfiles
 * @returns {Array}
 */
function obtenerTodosHorariosRegistradosEnCalendarioRelaboralParaVerAsignaciones(idRelaboral, idPerfilLaboral, tipoHorario, editable, fechaIni, fechaFin, contadorPerfiles) {

    var arrHorariosRegistrados = [];
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var ctrlAllDay = true;
    switch (tipoHorario) {
        case 1:
        case 2:
            ctrlAllDay = true;
            break;
    }
    $.ajax({
        url: '/calendariolaboral/listallregisteredbyrelaboralmixto',
        type: 'POST',
        datatype: 'json',
        async: false,
        cache: false,
        data: {id: idRelaboral, id_perfillaboral: idPerfilLaboral, fecha_ini: fechaIni, fecha_fin: fechaFin},
        success: function (data) {
            var res = jQuery.parseJSON(data);
            if (res.length > 0) {
                $.each(res, function (key, val) {
                    var idHorarioLaboral = 0;
                    var horaEnt = '00:00:00';
                    var horaSal = '24:00:00';
                    var color = '#000000';
                    var horario_nombre = 'DESCANSO';
                    var perfil_laboral = val.perfil_laboral;
                    var grupo = val.perfil_laboral_grupo;
                    if (grupo != '') perfil_laboral += " - " + grupo;
                    if (val.id_horariolaboral != null) {
                        idHorarioLaboral = val.id_horariolaboral;
                        /*if(val.grupo!="")
                         horario_nombre = val.horario_nombre +" ("+perfil_laboral+")";*/
                        horario_nombre = val.horario_nombre
                        horaEnt = val.hora_entrada.split(":");
                        horaSal = val.hora_salida.split(":");
                        color = val.color;
                    } else {
                        horaEnt = horaEnt.split(":");
                        horaSal = horaSal.split(":");
                    }
                    //color  = colors[contadorPerfiles];
                    var fechaIni = val.calendario_fecha_ini.split("-");
                    var yi = fechaIni[0];
                    var mi = fechaIni[1] - 1;
                    var di = fechaIni[2];

                    var he = horaEnt[0];
                    var me = horaEnt[1];
                    var se = horaEnt[2];

                    var fechaFin = val.calendario_fecha_fin.split("-");
                    var yf = fechaFin[0];
                    var mf = fechaFin[1] - 1;
                    var df = fechaFin[2];

                    var hs = horaSal[0];
                    var ms = horaSal[1];
                    var ss = horaSal[2];
                    var prefijo = "r_";
                    if (idHorarioLaboral == 0) {
                        prefijo = "d_";
                    }
                    var borde = color;
                    if (!editable) {
                        borde = "#000000";
                        prefijo = "b_";//Se modifica para que d: represente a los horarios bloqueados
                    }
                    arrHorariosRegistrados.push({
                        id: val.id_calendariolaboral,
                        className: prefijo + idHorarioLaboral,
                        title: horario_nombre,
                        start: new Date(yi, mi, di, he, me),
                        end: new Date(yf, mf, df, hs, ms),
                        allDay: ctrlAllDay,
                        color: color,
                        editable: editable,
                        borderColor: borde,
                        horas_laborales: val.horas_laborales,
                        dias_laborales: val.dias_laborales,
                        hora_entrada: val.hora_entrada,
                        hora_salida: val.hora_salida
                    });
                });
            }
        }
    });
    return arrHorariosRegistrados;
}
/**
 * Función para verificar la existencia de una imagen
 * @param url
 * @returns {boolean}
 * @constructor
 */
function ImageExist(url) {
    var img = new Image();
    img.src = url;
    return img.height != 0;
}
/**
 * Función para la obtención de la fecha enviada como parámetro en formato dd-mm-yyyy
 * @param fecha
 * @returns {string}
 */
function fechaConvertirAFormato(fecha, separador) {
    if (separador == '') separador = '-';
    var formattedDate = fecha;
    var d = formattedDate.getDate();
    var m = formattedDate.getMonth();
    m += 1;  // Los meses en JavaScript son 0-11
    var y = formattedDate.getFullYear();
    var ceroDia = "";
    var ceroMes = "";
    if (d < 10) ceroDia = "0";
    if (m < 10) ceroMes = "0";
    var fechaResultado = ceroDia + d + separador + ceroMes + m + separador + y;
    return fechaResultado;
}
/**
 * Función para iniciar el calendario laboral de acuerdo al registro de relación laboral, turnos, excepciones y rango de fechas seleccionado.
 * Se despliega la totalidad de horarios para el registro de relación laboral por lo que se muestran los botones de navegación del calendario.
 * @param idRelaboral
 * @param accion
 * @param tipoHorario
 * @param arrHorariosRegistrados
 * @param defaultGestion
 * @param defaultMes
 * @param defaultDia
 * @returns {Array}
 */
function iniciarCalendarioLaboralPorRelaboralTurnosYExcepcionesParaVerAsignaciones(dataRecord, idRelaboral, accion, idPerfilLaboral, tipoHorario, arrHorariosRegistrados, defaultGestion, defaultMes, defaultDia) {
    tipoHorario = parseInt(tipoHorario);
    var arrFechasPorSemana = [];
    var contadorPorSemana = 0;
    var diasSemana = 7;
    var calendarEvents = $('.calendar-events');
    /* Inicializa la funcionalidad de eventos: arrastrar y soltar */
    //initEvents();

    /* Initialize FullCalendar */
    var optLeft = 'prev,next';
    /*var optRight = 'year,month,agendaWeek,agendaDay';*/
    var optRight = 'year';
    var optEditable = true;
    var optDroppable = true;
    var optSelectable = true;
    var optVerFinesDeSemana = true;
    var optVerTotalizadorHorasPorSemana = true;
    //weekends
    switch (accion) {
        case 1:/*Nuevo*/
            switch (tipoHorario) {
                case 1:
                case 2:
                    break;
                case 3:
                    optLeft = '';
                    optRight = 'year';
                    break;
            }
            break;
        case 2:/*Edición*/
            switch (tipoHorario) {
                case 1:
                case 2:
                    break;
                case 3:
                    optLeft = '';
                    optRight = 'year';
                    break;
            }
            break;
        case 3:/*Aprobación*/
            switch (tipoHorario) {
                case 1:
                case 2:
                    break;
                case 3:
                    optLeft = '';
                    optRight = 'year';
                    break;
            }
            break;
        case 4:/*Eliminación*/
            break;
        case 5:/*Vista*/
            optEditable = false;
            optDroppable = false;
            optSelectable = false;
            switch (tipoHorario) {
                case 1:
                case 2:
                    break;
                case 3:
                    break;
            }
            break;
    }
    switch (tipoHorario) {
        case 1:
        case 2:
            optVerFinesDeSemana = false;
            diasSemana = 5;
            optVerTotalizadorHorasPorSemana = false;
            break;
        case 3:
            break;
    }
    $('#calendar').fullCalendar({
        header: {
            left: optLeft,
            center: 'title',
            right: optRight
        },
        year: defaultGestion,
        month: defaultMes,
        date: defaultDia,
        firstDay: 1,
        weekends: optVerFinesDeSemana,
        editable: optEditable,
        droppable: optDroppable,
        selectable: optSelectable,
        weekNumbers: true,
        weekNumberTitle: "#S",
        timeFormat: 'H(:mm)', // Mayusculas H de 24-horas
        drop: function (date, allDay) {

            /**
             * Controlando cuando se introduce un nuevo evento u horario en el calendario
             * @type {*|jQuery}
             */

                // Recuperar almacenado de objeto del evento del elemento caído
            var originalEventObject = $(this).data('eventObject');

            // Tenemos que copiarlo, de modo que múltiples eventos no tienen una referencia al mismo objeto
            var copiedEventObject = $.extend({}, originalEventObject);

            // Asignarle la fecha que fue reportado
            copiedEventObject.start = date;


            // Hacer que el evento en el calendario
            // El último argumento `true` determina si el evento "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

            /**
             * Si se introduce un nuevo horario en el calendario se recalcula los totales por semana.
             */
            sumarTotalHorasPorSemana(arrFechasPorSemana);

        }
        ,
        eventDrop: function (event, dayDelta, minuteDelta, allDay, revertFunc) {
            /**
             * Si un horario se ha movido, es necesario calcular los totales de horas por semana
             */
            sumarTotalHorasPorSemana(arrFechasPorSemana);
        },
        events: arrHorariosRegistrados,
        /**
         * Controlando el evento de clik sobre el horario.
         * @param calEvent
         * @param jsEvent
         * @param view
         */
        eventClick: function (calEvent, jsEvent, view) {

            var clase = calEvent.className + "";
            var arrClass = clase.split("_");
            var idTipoHorario = arrClass[1];
            clase = arrClass[0];
            var idTurno = 0;
            if (calEvent.id != undefined) {
                idTurno = calEvent.id;
            }
            var fechaIni = fechaConvertirAFormato(calEvent.start, '-');
            var fechaFin = fechaIni;
            var calEventEnd = calEvent.start;
            if (calEvent.end != null && calEvent.end != "") {
                fechaFin = fechaConvertirAFormato(calEvent.end, '-');
                calEventEnd = calEvent.end;
            }
            var startDate = calEvent.start;
            var FromEndDate = calEventEnd;
            var ToEndDate = calEventEnd;
            //ToEndDate.setDate(ToEndDate.getDate()+900);

            $("#txtHorarioFechaIni").datepicker('setDate', calEvent.start);
            //$('#txtHorarioFechaIni').datepicker('setStartDate', calEvent.start);
            $("#txtHorarioFechaFin").datepicker('setDate', calEventEnd);
            //$('#txtHorarioFechaFin').datepicker('setEndDate', calEventEnd);
            $('#txtHorarioFechaIni').datepicker({
                format: 'dd-mm-yyyy',
                default: calEvent.start,
                weekStart: 1,
                startDate: startDate,
                endDate: FromEndDate,
                autoclose: true
            })
                .on('changeDate', function (selected) {
                    startDate = new Date(selected.date.valueOf());
                    startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                    $('#txtHorarioFechaFin').datepicker('setStartDate', startDate);
                });
            $('#txtHorarioFechaFin').datepicker({
                default: calEventEnd,
                weekStart: 1,
                startDate: startDate,
                endDate: ToEndDate,
                autoclose: true
            })
                .on('changeDate', function (selected) {
                    FromEndDate = new Date(selected.date.valueOf());
                    FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                    $('#txtHorarioFechaIni').datepicker('setEndDate', FromEndDate);
                });
            if (idTipoHorario > 0) {
                var ok = cargarModalHorario(idTipoHorario);
                if (ok) {
                    /**
                     * Si la clase del horario esta bloqueada no se la puede eliminar
                     */
                    if (clase == "b") {
                        $("#btnDescartarHorario").hide();
                        $("#btnGuardarModificacionHorario").hide();
                        $("#txtHorarioFechaIni").prop("disabled", "disabled");
                        $("#txtHorarioFechaFin").prop("disabled", "disabled");
                    } else {
                        $("#btnDescartarHorario").show();
                        $("#txtHorarioFechaIni").prop("disabled", false);
                        $("#txtHorarioFechaFin").prop("disabled", false);
                    }
                    $('#popupDescripcionHorario').modal('show');
                    $("#btnDescartarHorario").off();
                    $("#btnDescartarHorario").on("click", function () {
                        switch (clase) {
                            case "r":
                            case "d":
                                var okBaja = bajaTurnoEnCalendario(idTurno);
                                if (okBaja) {
                                    $('#calendar').fullCalendar('removeEvents', calEvent._id);
                                    $('#popupDescripcionHorario').modal('hide');
                                }
                                break;
                            case "n":
                                $('#calendar').fullCalendar('removeEvents', calEvent._id);
                                $('#popupDescripcionHorario').modal('hide');
                                break;
                        }
                        /**
                         * Si se ha eliminado un horario, es necesario recalcular las horas por semana
                         */
                        sumarTotalHorasPorSemana(arrFechasPorSemana);
                    });
                    /**
                     * Acción efectuada cuando se hace click sobre el botón para Guardar Modifificación de Fechas.
                     */
                    $("#btnGuardarModificacionHorario").off();
                    $("#btnGuardarModificacionHorario").on("click", function () {
                        switch (clase) {
                            case "r":
                            case "n":
                                if (fechaIni != $("#txtHorarioFechaIni").val() || fechaFin != $("#txtHorarioFechaFin").val()) {
                                    /*Inicialmente borramos el evento y lo reingresamos*/
                                    $('#calendar').fullCalendar('removeEvents', calEvent._id);
                                    $('#popupDescripcionHorario').modal('hide');
                                    var fechaInicio = $("#txtHorarioFechaIni").val();
                                    var fechaFinalizacion = $("#txtHorarioFechaFin").val();
                                    var arrFechaInicio = fechaInicio.split("-");
                                    var arrFechaFinalizacion = fechaFinalizacion.split("-");
                                    fechaInicio = arrFechaInicio[2] + "-" + arrFechaInicio[1] + "-" + arrFechaInicio[0];
                                    fechaFinalizacion = arrFechaFinalizacion[2] + "-" + arrFechaFinalizacion[1] + "-" + arrFechaFinalizacion[0];
                                    addEvent = {
                                        id: calEvent.id,
                                        title: calEvent.title,
                                        className: calEvent.className,
                                        start: fechaInicio,
                                        end: fechaFinalizacion,
                                        color: calEvent.color,
                                        editable: true,
                                        hora_entrada: calEvent.hora_entrada,
                                        hora_salida: calEvent.hora_salida

                                    }
                                    $('#calendar').fullCalendar('renderEvent', addEvent, true);
                                }
                                $('#popupDescripcionHorario').modal('hide');
                                break;
                            case "d":
                                break;
                        }
                        /**
                         * Si se ha eliminado un horario, es necesario recalcular las horas por semana
                         */
                        sumarTotalHorasPorSemana(arrFechasPorSemana);
                    });
                } else alert("Error al determinar los datos del horario.");
            } else {
                alert("El registro corresponde a un periodo de excepción o salida");
            }
        }
        ,
        eventResize: function (event, delta, revertFunc) {
            /**
             * Cuando un horario es modificado en cuanto a su duración, se debe calcular nuevamente los totales de horas por semana
             */
            sumarTotalHorasPorSemana(arrFechasPorSemana);

        }
        ,
        /*dayRender: function (date, cell) {},*/
        viewRender: function (view) {

            switch (view.name) {
                case "month": {
                    removerColumnaSumaTotales();
                    agregarColumnaSumaTotales(diasSemana);
                    var primeraFechaCalendario = "";
                    var segundaFechaCalendario = "";
                    arrFechasPorSemana = [];
                    var gestionInicial = 0;
                    var contP = 0;
                    var arrDias = ["mon", "tue", "wed", "thu", "fri", "sat", "sun"];
                    $.each(arrDias, function (k, dia) {
                        contP = 0;
                        $("td.fc-" + dia).map(function (index, elem) {
                            contP++;
                            var fecha = $(this).data("date");
                            var fechaAux = $(this).data("date");
                            if (fecha != undefined) {
                                var arrFecha = fecha.split("-");
                                fecha = arrFecha[2] + "-" + arrFecha[1] + "-" + arrFecha[0];
                                gestionInicial = arrFecha[0];
                                switch (contP) {
                                    case 1: {
                                        if (primeraFechaCalendario == "") primeraFechaCalendario = fecha;
                                        arrFechasPorSemana.push({semana: 1, fecha: fecha});
                                    }
                                        break;
                                    case 2:
                                        arrFechasPorSemana.push({semana: 2, fecha: fecha});
                                        break;
                                    case 3:
                                        arrFechasPorSemana.push({semana: 3, fecha: fecha});
                                        break;
                                    case 4:
                                        arrFechasPorSemana.push({semana: 4, fecha: fecha});
                                        break;
                                    case 5:
                                        arrFechasPorSemana.push({semana: 5, fecha: fecha});
                                        break;
                                    case 6: {
                                        segundaFechaCalendario = fecha;
                                        arrFechasPorSemana.push({semana: 6, fecha: fecha});
                                    }
                                        break;
                                }
                                var check = fechaAux;
                                var today = $.fullCalendar.formatDate(new Date(), 'yyyy-MM-dd');
                                if (check < today) {
                                    $(this).css("background-color", "#efefef");
                                }
                            }
                        });
                    });
                    var fechaInicialCalendario = "";
                    var fechaFinalCalendario = "";
                    var moment = $('#calendar').fullCalendar('getDate');
                    fechaInicialCalendario = fechaConvertirAFormato(moment, '-');
                    var arrFechaInicial = fechaInicialCalendario.split("-");
                    fechaInicialCalendario = "01-" + arrFechaInicial[1] + "-" + arrFechaInicial[2];
                    fechaFinalCalendario = obtenerUltimoDiaMes(fechaInicialCalendario);
                    $("#hdnFechaInicialCalendario").val(fechaInicialCalendario);
                    $("#hdnFechaFinalCalendario").val(fechaFinalCalendario);
                    cargarGrillaAsignacionIndividualFechasUbicacionEstacion(idPerfilLaboral, idRelaboral, primeraFechaCalendario, segundaFechaCalendario);
                    var arrExcepciones = obtenerArrExcepcionesEnCalendarioPorRango(dataRecord, view.name, diasSemana, primeraFechaCalendario, segundaFechaCalendario);
                    /**
                     * Asignación de horarios por mes, inicialmente se borra todos los eventos registrados
                     * a objeto de no repetir su renderización
                     */
                    $("#calendar").fullCalendar('removeEvents');
                    var arrHorariosRegistradosEnMes = obtenerTodosHorariosRegistradosEnCalendarioRelaboralParaVerAsignaciones(idRelaboral, 0, tipoHorario, false, primeraFechaCalendario, segundaFechaCalendario, 0);
                    $("#calendar").fullCalendar('addEventSource', arrHorariosRegistradosEnMes);
                    $("#calendar").fullCalendar('addEventSource', arrExcepciones);

                    var arrFeriados = obtenerFeriadosRangoFechas(0, 0, gestionInicial, primeraFechaCalendario, segundaFechaCalendario);
                    $.each(arrDias, function (k, dia) {
                        contP = 0;
                        $("td.fc-" + dia).map(function (index, elem) {
                            contP++;
                            var fechaCalAux = $(this).data("date");
                            var fechaCal = $(this).data("date");
                            var fechaIni = "";
                            var fechaFin = "";
                            var celda = $(this);
                            $.each(arrFeriados, function (key, val) {

                                fechaIni = val.fecha_ini;
                                fechaFin = val.fecha_fin;
                                var sep = "-";
                                if (procesaTextoAFecha(fechaCal, "-") <= procesaTextoAFecha(fechaFin, "-") && procesaTextoAFecha(fechaCal, "-") >= procesaTextoAFecha(fechaIni, "-")) {
                                    var elem = $(".fc-day-content");
                                    celda.append("<h6>(f) " + val.feriado + "</h6>");
                                    celda.css("background-color", "orange");
                                }
                            });
                        });
                    });
                    sumarTotalHorasPorSemana(arrFechasPorSemana);
                }
                    break;
                case "agendaWeek":
                    fechaInicialCalendario = $('#calendar').fullCalendar('getView').start;
                    fechaInicialCalendario = fechaConvertirAFormato(fechaInicialCalendario, "-");
                    fechaFinalCalendario = obtenerFechaMasDias(fechaInicialCalendario, diasSemana - 1);
                    $("#hdnFechaInicialCalendario").val(fechaInicialCalendario);
                    $("#hdnFechaFinalCalendario").val(fechaFinalCalendario);
                    cargarGrillaAsignacionIndividualFechasUbicacionEstacion(idPerfilLaboral, idRelaboral, fechaInicialCalendario, fechaFinalCalendario);
                    break;
                case "agendaDay":
                    var moment = $('#calendar').fullCalendar('getDate');
                    var fechaInicialCalendario = fechaConvertirAFormato(moment, '-');
                    fechaFinalCalendario = fechaInicialCalendario;
                    $("#hdnFechaInicialCalendario").val(fechaInicialCalendario);
                    $("#hdnFechaFinalCalendario").val(fechaFinalCalendario);
                    cargarGrillaAsignacionIndividualFechasUbicacionEstacion(idPerfilLaboral, idRelaboral, fechaInicialCalendario, fechaFinalCalendario);
                    break;
            }
        }
    });
    return arrFechasPorSemana;
}
/**
 * Función para calcular el total de horas por semana.
 */
function sumarTotalHorasPorSemana(arrFechasPorSemana) {
    var arr = $("#calendar").fullCalendar('clientEvents');
    var horasSemana1 = 0;
    var horasSemana2 = 0;
    var horasSemana3 = 0;
    var horasSemana4 = 0;
    var horasSemana5 = 0;
    var horasSemana6 = 0;
    $("#spSumaSemana1").html(0);
    $("#spSumaSemana2").html(0);
    $("#spSumaSemana3").html(0);
    $("#spSumaSemana4").html(0);
    $("#spSumaSemana5").html(0);
    $("#spSumaSemana6").html(0);
    $("#tdSumaSemana1").css("background-color", "white");
    $("#tdSumaSemana2").css("background-color", "white");
    $("#tdSumaSemana3").css("background-color", "white");
    $("#tdSumaSemana4").css("background-color", "white");
    $("#tdSumaSemana5").css("background-color", "white");
    $("#tdSumaSemana6").css("background-color", "white");
    $.each(arr, function (key, turno) {
        var fechaIni = $.fullCalendar.formatDate(turno.start, 'dd-MM-yyyy');
        var fechaFin = $.fullCalendar.formatDate(turno.end, 'dd-MM-yyyy');
        if (fechaFin == "") fechaFin = fechaIni;
        var sep = '-';
        $.each(arrFechasPorSemana, function (clave, valor) {

            //alert(fechaIni+"<= "+valor.semana+"::"+valor.fecha+"<="+fechaFin);
            /**
             * Esto porque en algunos casos el horario no tiene fecha de finalización debido a que
             * su existencia es producto de haber jalado de la lista de horarios disponibles sobre el calendario
             */
            if (valor.semana == 1) {
                if (procesaTextoAFecha(fechaIni, sep) <= procesaTextoAFecha(valor.fecha, sep)
                    && procesaTextoAFecha(valor.fecha, sep) <= procesaTextoAFecha(fechaFin, sep)) {
                    horasSemana1 += parseFloat(turno.horas_laborales);
                    /*alert(turno.title+" entro en la semana 1 =>"+fechaIni+"<="+valor.fecha+"<="+fechaFin+" horas: "+turno.horas_laborales);*/

                }
            }
            if (valor.semana == 2) {
                if (procesaTextoAFecha(fechaIni, sep) <= procesaTextoAFecha(valor.fecha, sep)
                    && procesaTextoAFecha(valor.fecha, sep) <= procesaTextoAFecha(fechaFin, sep)) {
                    horasSemana2 += parseFloat(turno.horas_laborales);
                    /*alert(turno.title+" entro en la semana 2 =>"+fechaIni+"<="+valor.fecha+"<="+fechaFin+" horas: "+turno.horas_laborales);*/
                }
            }
            if (valor.semana == 3) {
                //alert(procesaTextoAFecha(fechaIni,sep)+"<="+procesaTextoAFecha(valor.fecha,sep)+"\n && "+procesaTextoAFecha(valor.fecha,sep)+"<="+procesaTextoAFecha(fechaFin,sep));
                if (procesaTextoAFecha(fechaIni, sep) <= procesaTextoAFecha(valor.fecha, sep)
                    && procesaTextoAFecha(valor.fecha, sep) <= procesaTextoAFecha(fechaFin, sep)) {
                    horasSemana3 += parseFloat(turno.horas_laborales);
                    /*alert(turno.title+" entro en la semana 3 =>"+fechaIni+"<="+valor.fecha+"<="+fechaFin+" horas: "+turno.horas_laborales);*/
                }
            }
            if (valor.semana == 4) {
                if (procesaTextoAFecha(fechaIni, sep) <= procesaTextoAFecha(valor.fecha, sep)
                    && procesaTextoAFecha(valor.fecha, sep) <= procesaTextoAFecha(fechaFin, sep)) {
                    horasSemana4 += parseFloat(turno.horas_laborales);
                    /*alert(turno.title+" entro en la semana 4 =>"+fechaIni+"<="+valor.fecha+"<="+fechaFin+" horas: "+turno.horas_laborales);*/
                }
            }
            if (valor.semana == 5) {
                if (procesaTextoAFecha(fechaIni, sep) <= procesaTextoAFecha(valor.fecha, sep)
                    && procesaTextoAFecha(valor.fecha, sep) <= procesaTextoAFecha(fechaFin, sep)) {
                    horasSemana5 += parseFloat(turno.horas_laborales);
                    /*alert(turno.title+" entro en la semana 5 =>"+fechaIni+"<="+valor.fecha+"<="+fechaFin+" horas: "+turno.horas_laborales);*/
                }
            }
            if (valor.semana == 6) {
                if (procesaTextoAFecha(fechaIni, sep) <= procesaTextoAFecha(valor.fecha, sep)
                    && procesaTextoAFecha(valor.fecha, sep) <= procesaTextoAFecha(fechaFin, sep)) {
                    horasSemana6 += parseFloat(turno.horas_laborales);
                    /*alert(turno.title+" entro en la semana 6 =>"+fechaIni+"<="+valor.fecha+"<="+fechaFin+" horas: "+turno.horas_laborales);*/
                }
            }
        });
    });

    $("#spSumaSemana1").html(horasSemana1.toFixed(2));
    $("#spSumaSemana2").html(horasSemana2.toFixed(2));
    $("#spSumaSemana3").html(horasSemana3.toFixed(2));
    $("#spSumaSemana4").html(horasSemana4.toFixed(2));
    $("#spSumaSemana5").html(horasSemana5.toFixed(2));
    $("#spSumaSemana6").html(horasSemana6.toFixed(2));
    var promedioSumaTresSemanas = (horasSemana2 + horasSemana3 + horasSemana4) / 3;
    $("#spSumaPromedioTresSemanas").html(promedioSumaTresSemanas.toFixed(2));
    //var tipoJornadaLaboral = $("#lstJornadasLaborales").val();
    var horasSemanalesPermitidas = 48;
    var horasDiaPermitidas = 8;
    var horasNochePermitidas = 7;
    var idJornadaLaboral = 1;
    if (idJornadaLaboral != 0) {
        /*var arrJornadaLaboral = tipoJornadaLaboral.split("::");
         idJornadaLaboral = arrJornadaLaboral[0];
         horasSemanalesPermitidas = arrJornadaLaboral[1];
         horasDiaPermitidas = arrJornadaLaboral[2];
         horasNochePermitidas = arrJornadaLaboral[3];*/
        /**
         * Control de exceso de horas en la semana
         */
        if (horasSemana1 > horasSemanalesPermitidas) {
            $("#tdSumaSemana1").css("background-color", "#FF4000");
        } else $("#tdSumaSemana1").css("background-color", "white");
        if (horasSemana2 > horasSemanalesPermitidas) {
            $("#tdSumaSemana2").css("background-color", "#FF4000");
        } else $("#tdSumaSemana2").css("background-color", "#efefef");
        if (horasSemana3 > horasSemanalesPermitidas) {
            $("#tdSumaSemana3").css("background-color", "#FF4000");
        } else $("#tdSumaSemana3").css("background-color", "#efefef");
        if (horasSemana4 > horasSemanalesPermitidas) {
            $("#tdSumaSemana4").css("background-color", "#FF4000");
        } else $("#tdSumaSemana4").css("background-color", "#efefef");
        if (horasSemana5 > horasSemanalesPermitidas) {
            $("#tdSumaSemana5").css("background-color", "#FF4000");
        } else $("#tdSumaSemana5").css("background-color", "white");
        if (horasSemana6 > horasSemanalesPermitidas) {
            $("#tdSumaSemana6").css("background-color", "#FF4000");
        } else $("#tdSumaSemana6").css("background-color", "white");
        /**
         * Control del promedio de horas en tres semanas del mes
         */
        if (promedioSumaTresSemanas > horasSemanalesPermitidas) {
            $("#tdSumaPromedioTresSemanas").css("background-color", "red");
        } else $("#tdSumaPromedioTresSemanas").css("background-color", "white");

    }
}

/**
 *  Función para agregar la columna de totales al calendario.
 * @param diasSemana
 */
function agregarColumnaSumaTotales(diasSemana) {
    $(".fc-border-separate tr:first").append("<th style='width: 87px;' id='thColumnaTotales' class='thColumnaTotales'> Hrs Semana </th>");
    var sufijo = 0;
    $(".fc-border-separate tr.fc-week").each(function (key, val) {
        sufijo++;
        $(this).append("<td id='tdSumaSemana" + sufijo + "' class='tdSumaSemana fc-last'><div style='min-height: 67px;align-content: center;'><div id='divSumaSemana" + sufijo + "' class='fc-day-suma-horas-semana'><span id='spSumaSemana" + sufijo + "' class='spSumaSemana'>100</span></div></div></td>");
    });
    var diasSemanaMasContadorSemanas = diasSemana + 1;
    $(".fc-border-separate tr:last").after("<tr id=''><td style='text-align: right;' colspan='" + diasSemanaMasContadorSemanas + "' class=''><b>Promedio semanal de horas (3 Semanas marcadas):</b></td><td id='tdSumaPromedioTresSemanas' class='tdSumaPromedioTresSemanas fc-first fc-day fc-last'><div style='min-height: 67px;align-content: center;'><div id='divSumaPromedioTresSemanas' class='fc-suma-promedio-horas-3-semanas'><span id='spSumaPromedioTresSemanas'>0</span></div></div></td></tr>");
}
/**
 * Funcion para remover la columna de suma de totales al calendario.
 */
function removerColumnaSumaTotales() {
    $("#thColumnaTotales").remove();
    $("#tdSumaSemana1").remove();
    $("#tdSumaSemana2").remove();
    $("#tdSumaSemana3").remove();
    $("#tdSumaSemana4").remove();
    $("#tdSumaSemana5").remove();
    $("#tdSumaSemana6").remove();
    $("#trSumaPromedioTresSemanas").remove();
}
/**
 * Función para calcular el número de horas que representa la hora.
 * @param hora
 * @returns {*}
 */
function numeroHoras(hora) {
    if (hora != "") {
        var arrHora = hora.split(":");
        var hEnt = parseFloat(arrHora[0]);
        var mEnt = parseFloat(arrHora[1]);
        var sEnt = parseFloat(arrHora[2]);
        var sEnMin = 0;
        var mEnHor = 0;
        if (sEnt > 0) {
            sEnMin = sEnt / 60;
        }
        mEnt = mEnt + sEnMin;
        if (mEnt > 0) {
            mEnHor = mEnt / 60;
        }
        hEnt = hEnt + mEnHor;
        return hEnt;
    }
    else return 0;
}
/**
 * Función para la carga de la grilla de asignación individual para un registro de relación laboral, un perfil y una rango de fechas.
 * @param idPerfilLaboral
 * @param dataRecord
 */
function cargarGrillaAsignacionIndividualFechasUbicacionEstacion(idPerfilLaboral, idRelaboral, fechaIni, fechaFin) {
    var source =
        {
            datatype: "json",
            datafields: [
                {name: 'nro_row', type: 'integer'},
                {name: 'perfil_laboral', type: 'string'},
                {name: 'perfil_laboral_grupo', type: 'string'},
                {name: 'calendario_fecha_ini', type: 'date'},
                {name: 'calendario_fecha_fin', type: 'date'},
                {name: 'horario_nombre', type: 'string'},
                {name: 'relaboralperfilmaquina_ubicacion_entrada', type: 'string'},
                {name: 'relaboralperfilmaquina_estacion_entrada', type: 'string'},
                {name: 'relaboralperfilmaquina_ubicacion_salida', type: 'string'},
                {name: 'relaboralperfilmaquina_estacion_salida', type: 'string'},
                {name: 'relaboralperfilmaquina_tipo_marcacion_entrada_descripcion', type: 'string'},
                {name: 'relaboralperfilmaquina_tipo_marcacion_salida_descripcion', type: 'string'},
                {name: 'relaboralperfil_observacion', type: 'string'}
            ],
            url: '/calendariolaboral/getlistallregisteredbyrelaboral?id=' + idRelaboral + '&fecha_ini=' + fechaIni + '&fecha_fin=' + fechaFin,
            cache: false
        };
    var dataAdapter = new $.jqx.dataAdapter(source);
    cargarRegistrosDeAsignacionesDeUbicacion();
    function cargarRegistrosDeAsignacionesDeUbicacion() {
        var theme = prepareSimulator("grid");
        $("#divGridCalendario").jqxGrid(
            {
                theme: theme,
                width: '100%',
                height: '530px',
                source: dataAdapter,
                sortable: true,
                altRows: true,
                columnsresize: true,
                pageable: true,
                pagerMode: 'advanced',
                showfilterrow: true,
                filterable: true,
                autorowheight: true,
                columns: [
                    {
                        text: 'Nro.',
                        filterable: false,
                        columntype: 'number',
                        width: 40,
                        cellsalign: 'center',
                        align: 'center',
                        cellsrenderer: rownumberrenderer
                    },
                    {
                        text: 'Perfil',
                        filtertype: 'checkedlist',
                        datafield: 'perfil_laboral',
                        width: 100,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Grupo',
                        filtertype: 'checkedlist',
                        datafield: 'perfil_laboral_grupo',
                        width: 100,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Fecha Inicio',
                        datafield: 'calendario_fecha_ini',
                        filtertype: 'range',
                        width: 80,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Fecha Fin',
                        datafield: 'calendario_fecha_fin',
                        filtertype: 'range',
                        width: 80,
                        cellsalign: 'center',
                        cellsformat: 'dd-MM-yyyy',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Horario',
                        filtertype: 'checkedlist',
                        datafield: 'horario_nombre',
                        width: 100,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Ubicaci&oacute;n Entrada',
                        filtertype: 'checkedlist',
                        datafield: 'relaboralperfilmaquina_ubicacion_entrada',
                        width: 130,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Estaci&oacute;n Entrada',
                        filtertype: 'checkedlist',
                        datafield: 'relaboralperfilmaquina_estacion_entrada',
                        width: 100,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Tipo Entrada',
                        filtertype: 'checkedlist',
                        datafield: 'relaboralperfilmaquina_tipo_marcacion_entrada_descripcion',
                        width: 190,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Ubicaci&oacute;n Salida',
                        filtertype: 'checkedlist',
                        datafield: 'relaboralperfilmaquina_ubicacion_salida',
                        width: 100,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Estaci&oacute;n Salida',
                        filtertype: 'checkedlist',
                        datafield: 'relaboralperfilmaquina_estacion_salida',
                        width: 100,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Tipo Salida',
                        filtertype: 'checkedlist',
                        datafield: 'relaboralperfilmaquina_tipo_marcacion_salida_descripcion',
                        width: 190,
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Observaci&oacute;n',
                        filtertype: 'checkedlist',
                        datafield: 'relaboralperfil_observacion',
                        width: 130,
                        align: 'center',
                        hidden: false
                    }
                ]
            });
    }
}
/**
 * Función para obtener la fecha del último día de un determinado mes en una determinada gestión.
 * @param fecha
 * @returns {Array}
 */
function obtenerUltimoDiaMes(fecha) {
    var fecha = $.ajax({
        url: '/perfileslaborales/getultimafechames/',
        type: "POST",
        datatype: 'json',
        async: false,
        cache: false,
        data: {fecha: fecha},
        success: function (data) {
        }
    }).responseText;
    return fecha;
}
/**
 * Función para obtener una fecha en consideración a la adición de una cantidad concreta de días a la fecha enviada como parámetro.
 * @param fecha
 * @returns {Array}
 */
function obtenerFechaMasDias(fecha, dias) {
    var fecha = $.ajax({
        url: '/perfileslaborales/getfechamasdias/',
        type: "POST",
        datatype: 'json',
        async: false,
        cache: false,
        data: {fecha: fecha, dias: dias},
        success: function (data) {
        }
    }).responseText;
    return fecha;
}
/**
 * Función para cerrar un mensaje dispuesto en la parte superior del formulario.
 * @param tipo
 */
function cerrarMensaje(tipo) {
    alert("llego: " + tipo);
}

/**
 * Función para la carga de gestiones disponibles para la generación de marcaciones previstas y efectivas.
 * @param g
 */
function cargarGestionesDisponiblesParaGeneracionMarcaciones(g) {
    var lista = "";
    $("#lstGestionGeneracionMarcaciones").html("");
    $("#lstGestionGeneracionMarcaciones").append("<option value=''>Seleccionar</option>");
    $("#lstGestionGeneracionMarcaciones").prop("disabled", false);
    var selected = "";
    $.ajax({
        url: '/perfileslaborales/getgestiones/',
        type: "POST",
        datatype: 'json',
        async: false,
        cache: false,
        data: {id_perfillaboral: 0},
        success: function (data) {
            var res = jQuery.parseJSON(data);
            if (res.length > 0) {
                $.each(res, function (key, gestion) {
                    if (g == gestion) selected = "selected";
                    else selected = "";
                    lista += "<option value='" + gestion + "' " + selected + ">" + gestion + "</option>";
                });
            }
        }
    });
    if (lista != '') $("#lstGestionGeneracionMarcaciones").append(lista);
    else $("#lstGestionGeneracionMarcaciones").prop("disabled", true);
}
/**
 * Función para la obtención del listado de meses disponibles para la generación de marcaciones previstas y efectivas.
 * @param gestion
 * @param m
 */
function cargarMesesDisponiblesParaGeneracionMarcaciones(gestion, m) {
    $("#lstMesGeneracionMarcaciones").html("");
    $("#lstMesGeneracionMarcaciones").append("<option value=''>Seleccionar</option>");
    $("#lstMesGeneracionMarcaciones").prop("disabled", false);
    var lista = "";
    var selected = "";
    if (gestion > 0) {
        $.ajax({
            url: '/horariosymarcaciones/getmeses/',
            type: "POST",
            datatype: 'json',
            async: false,
            cache: false,
            data: {gestion: gestion},
            success: function (data) {
                var res = jQuery.parseJSON(data);
                if (res.length > 0) {
                    $.each(res, function (key, val) {
                        if (m == val.mes) selected = "selected";
                        else selected = "";
                        lista += "<option value='" + val.mes + "' " + selected + ">" + val.mes_nombre + "</option>";
                    });
                }
            }
        });
        if (lista != '') $("#lstMesGeneracionMarcaciones").append(lista);
        else $("#lstMesGeneracionMarcaciones").prop("disabled", true);
    } else {
        $("#lstMesGeneracionMarcaciones").prop("disabled", true);
    }
}
/**
 * Función para la obtención del listado de tipos disponibles para la generación de marcaciones (Previstas y/o efectivas).
 * @param gestion
 * @param mes
 * @param t
 */
function cargarTiposDisponiblesParaGeneracionMarcaciones(gestion, mes, t) {
    $("#lstTipoGeneracionMarcaciones").html("");
    $("#lstTipoGeneracionMarcaciones").append("<option value=''>Seleccionar</option>");
    $("#lstTipoGeneracionMarcaciones").prop("disabled", false);
    var lista = "";
    var selected = "";
    if (gestion > 0 && mes > 0) {
        $.ajax({
            url: '/horariosymarcaciones/gettiposgeneracion/',
            type: "POST",
            datatype: 'json',
            async: false,
            cache: false,
            data: {gestion: gestion, mes: mes},
            success: function (data) {
                var res = jQuery.parseJSON(data);
                if (res.length > 0) {
                    $.each(res, function (key, val) {
                        if (t == val.tipo) selected = "selected";
                        else selected = "";
                        lista += "<option value='" + val.tipo + "' " + selected + ">" + val.tipo_descripcion + "</option>";
                    });
                }
            }
        });
        if (lista != '') $("#lstTipoGeneracionMarcaciones").append(lista);
        else $("#lstTipoGeneracionMarcaciones").prop("disabled", true);
    } else {
        $("#lstTipoGeneracionMarcaciones").prop("disabled", true);
    }
}
/**
 * Función para la obtención de la fecha de inicio y finalización para un calendario laboral de acuerdo a una gestión y mes correspondientes.
 * @param gestion
 * @param mes
 * @returns {{fechaIni: string, fechaFin: string}}
 */
function obtenerFechaIniFinCalendario(gestion, mes) {
    var fechaIni = '';
    var fechaFin = '';
    if (gestion > 0 && mes > 0) {
        $.ajax({
            url: '/calendariolaboral/obtenerfechainifincalendario/',
            type: "POST",
            datatype: 'json',
            async: false,
            cache: false,
            data: {gestion: gestion, mes: mes},
            success: function (data) {
                var res = jQuery.parseJSON(data);
                /**
                 * Si se ha realizado correctamente el registro de la relación laboral y la movilidad
                 */
                if (res.result == 1) {
                    fechaIni = res.fecha_ini;
                    fechaFin = res.fecha_fin;
                }
            }
        });
    }
    var objFechas = {fecha_ini: fechaIni, fecha_fin: fechaFin};
    return objFechas;
}
/**
 * Función para obtener el listado de gestiones para el registro de relaciones laborales.
 * @returns {string}
 */
function cargarGestionesRelaborales(gest) {
    var selection = "";
    $("#lstGestion").html("");
    $("#lstGestion").append("<option value='0'>TODAS</option>");
    $.ajax({
        url: '/relaborales/getgestionesrelaborales/',
        type: "POST",
        datatype: 'json',
        async: false,
        cache: false,
        success: function (data) {
            var res = jQuery.parseJSON(data);
            $.each(res, function (index, value) {
                if (gest == value) {
                    selection = "selected";
                } else {
                    selection = "";
                }
                $("#lstGestion").append("<option value='" + value + "' " + selection + ">" + value + "</option>");
            });
        }
        ,
        error: function () {
            alert('Se ha producido un error Inesperado');
        }
    })
    ;
}
/**
 * Función para limpiar el formulario de registro másivo de boletas.
 */
function limpiarMensajesErrorPorValidacionFormularioRegistroMasivo() {
    $("#divExcepcion").removeClass("has-error");
    $("#helpErrorExcepcion").html("");
    $("#divFechaIni").removeClass("has-error");
    $("#helpErrorFechaIni").html("");
    $("#divHoraIni").removeClass("has-error");
    $("#helpErrorHoraIni").html("");
    $("#divFechaFin").removeClass("has-error");
    $("#helpErrorFechaFin").html("");
    $("#divHoraFin").removeClass("has-error");
    $("#helpErrorHoraFin").html("");
    $("#divJustificacionRegistroMasivo").removeClass("has-error");
    $("#helpErrorJustificacionRegistroMasivo").html("");
    $("#divObservacionRegistroMasivo").removeClass("has-error");
    $("#helpErrorObservacionRegistroMasivo").html("");
    $("#divCompensacionTurno").removeClass("has-error");
    $("#helpErrorCompensacionTurno").html("");
    $("#divCompensacionEntradaSalida").removeClass("has-error");
    $("#helpErrorCompensacionEntradaSalida").html("");
    $("#divDestino").removeClass("has-error");
    $("#helpErrorDestino").html("");
}
/**
 * Función para el registro masivo de boletas.
 */
function cargarModalRegistroMasivoBoletas() {
    $("#txtFechaIni").datepicker("update", "");
    $("#txtFechaIni").val("").datepicker('update');

    $("#txtFechaFin").datepicker("update", "");
    $("#txtFechaFin").val("").datepicker('update');

    $("#txtHoraIni").val("");
    $("#txtHoraFin").val("");

    $("#txtJustificacionRegistroMasivo").val("");
    $("#txtDestino").val("");
    $("#divDestino").hide();
    $("#txtObservacionRegistroMasivo").val("");

    var d = new Date();
    var month = d.getMonth() + 1;
    var day = d.getDate();
    var fechaHoy = (day < 10 ? '0' : '') + day + '-' + (month < 10 ? '0' : '') + month + '-' + d.getFullYear();
    $("#txtFechaIni").datepicker("update", fechaHoy);
    $("#txtFechaFin").datepicker("update", fechaHoy);

    $("#txtFechaIni").datepicker("hiden");
    $("#txtFechaFin").datepicker("hiden");

    var inputIni = $("#txtHoraIni").clockpicker({
        placement: "bottom",
        align: "left",
        autoclose: true,
        'default': "now"
    }).on('changeDate', function (ev) {
        $(this).hide();
    });
    $("#aHoraIni").off();
    $("#aHoraIni").on("click", function (e) {
        e.stopPropagation();
        inputIni.clockpicker('show');
    });
    var inputFin = $("#txtHoraFin").clockpicker({
        placement: "bottom",
        align: "left",
        autoclose: true,
        'default': "now"
    }).on('changeDate', function (ev) {
        $(this).hide();
    });
    $("#aHoraFin").off();
    $("#aHoraFin").on("click", function (e) {
        e.stopPropagation();
        inputFin.clockpicker('show');
    });
    cargaListaDeExcepcionesAdministrador();
    $("#divCompensacion").hide();
    $("#divHorarios").hide();
    cargarCompensacionTurnos();
    cargarCompensacionEntradaSalida();

    $("#lstExcepcion").off();
    $("#lstExcepcion").on("change", function () {
        if (this.value != '') {
            var lugar = 0;
            if ($("#lstExcepcion" + " option:selected").data("lugar") != '' && $("#lstExcepcion" + " option:selected").data("lugar") != null)
                lugar = $("#lstExcepcion" + " option:selected").data("lugar");
            if (lugar == 1) {
                $("#divDestino").show();
            } else {
                $("#divDestino").hide();
            }
            var nombreExcepcion = $("#lstExcepcion" + " option:selected").text();
            var codigo = $("#lstExcepcion" + " option:selected").data("codigo");
            var color = $("#lstExcepcion" + " option:selected").data("color");
            var descuento = $("#lstExcepcion" + " option:selected").data("descuento");
            var descuento_descripcion = $("#lstExcepcion" + " option:selected").data("descuento_descripcion");
            var compensatoria = $("#lstExcepcion" + " option:selected").data("compensatoria");
            var compensatoria_descripcion = $("#lstExcepcion" + " option:selected").data("compensatoria_descripcion");
            var genero = $("#lstExcepcion" + " option:selected").data("genero");
            var id_genero = $("#lstExcepcion" + " option:selected").data("id_genero");
            var cantidad = $("#lstExcepcion" + " option:selected").data("cantidad");
            var unidad = $("#lstExcepcion" + " option:selected").data("unidad");
            var fraccionamiento = $("#lstExcepcion" + " option:selected").data("fraccionamiento");
            var horario = $("#lstExcepcion" + " option:selected").data("horario");
            var horario_descripcion = $("#lstExcepcion" + " option:selected").data("horario_descripcion");
            var refrigerio = $("#lstExcepcion" + " option:selected").data("refrigerio");
            var refrigerio_descripcion = $("#lstExcepcion" + " option:selected").data("refrigerio_descripcion");
            var frecuencia_descripcion = "&nbsp;";
            if ($("#lstExcepcion" + " option:selected").data("frecuencia_descripcion") != '' && $("#lstExcepcion" + " option:selected").data("frecuencia_descripcion") != null)
                frecuencia_descripcion = $("#lstExcepcion" + " option:selected").data("frecuencia_descripcion");
            var redondeo = $("#lstExcepcion" + " option:selected").data("redondeo");
            var redondeo_descripcion = "&nbsp;";
            if ($("#lstExcepcion" + " option:selected").data("redondeo_descripcion") != '' && $("#lstExcepcion" + " option:selected").data("redondeo_descripcion") != null)
                redondeo_descripcion = $("#lstExcepcion" + " option:selected").data("redondeo_descripcion");
            var observacion = $("#lstExcepcion" + " option:selected").data("observacion");
            var detalleExcepcion = "<div class='form-group'><label class='col-md-3 control-label'>Excepci&oacute;n</label><div class='col-md-9'><p class='form-control-static'>" + nombreExcepcion + "</p></div></div></br>";
            detalleExcepcion += "<div class='form-group'><label class='col-md-3 control-label'>Tipo</label><div class='col-md-9'><p class='form-control-static'>" + lugar + "</p></div></div></br>";
            detalleExcepcion += "<div class='form-group'><label class='col-md-3 control-label'>C&oacute;digo</label><div class='col-md-9'><p class='form-control-static'>" + codigo + "</p></div></div></br>";
            detalleExcepcion += "<div class='form-group'><label class='col-md-3 control-label'>Color</label><div class='col-md-9' style='background: " + color + ";'><p class='form-control-static'>&nbsp;</p></div></div></br>";
            detalleExcepcion += "<div class='form-group'><label class='col-md-3 control-label'>Descuento</label><div class='col-md-9'><p class='form-control-static'>" + descuento_descripcion + "</p></div></div></br>";
            detalleExcepcion += "<div class='form-group'><label class='col-md-3 control-label'>Compensatoria</label><div class='col-md-9'><p class='form-control-static'>" + compensatoria_descripcion + "</p></div></div></br>";
            detalleExcepcion += "<div class='form-group'><label class='col-md-3 control-label'>G&eacute;nero</label><div class='col-md-9'><p class='form-control-static'>" + genero + "</p></div></div></br>";
            detalleExcepcion += "<div class='form-group'><label class='col-md-3 control-label'>Horario</label><div class='col-md-9'><p class='form-control-static'>" + horario_descripcion + "</p></div></div></br>";
            detalleExcepcion += "<div class='form-group'><label class='col-md-3 control-label'>Refrigerio</label><div class='col-md-9'><p class='form-control-static'>" + refrigerio_descripcion + "</p></div></div></br>";
            detalleExcepcion += "<div class='form-group'><label class='col-md-3 control-label'>Frecuencia</label><div class='col-md-9'><p class='form-control-static'>" + frecuencia_descripcion + "</p></div></div></br>";
            detalleExcepcion += "<div class='form-group'><label class='col-md-3 control-label'>Disgregado</label><div class='col-md-9'><p class='form-control-static'>" + redondeo_descripcion + "</p></div></div></br>";
            detalleExcepcion += "<div class='form-group'><label class='col-md-3 control-label'>Observaci&oacute;n</label><div class='col-md-9'><p class='form-control-static'>" + observacion + "</p></div></div></br>";
            $("#divModalBody").html(detalleExcepcion);
            /**
             * En caso de que el valor implique la determinación de compensación de horas es necesario establecer en que turno se realizará y si será a la entrada o a la salida.
             */
            if (compensatoria == 1) {
                $("#divCompensacion").show();
                cargarCompensacionTurnos();
                cargarCompensacionEntradaSalida();
            } else {
                $("#divCompensacion").hide();
            }
            if (horario == 1) {
                $("#divHorarios").show();
            } else {
                $("#divHorarios").hide();
            }
            $(".iExcepcion").on("click", function () {
                var idExcepcion = $("#lstExcepcion").val();
                if (idExcepcion > 0) {
                    $("#divModalDetalleExcepcion").modal("show");
                } else {
                    alert("Debe seleccionar un tipo de Excepción o Salida inicialmente");
                }
            });
            $("#txtFechaIni").focus();
        }
    });
}
/**
 * Función para la obtención del listado de excepciones definidas en el sistema.
 * @param tipoGenero -- Variable para dar a conocer si se requiere sólo excepciones para el genero.
 */
function cargaListaDeExcepcionesAdministrador() {
    $("#lstExcepcion").html("");
    $("#lstExcepcion").append("<option value=''>Seleccionar..</option>");
    $("#lstExcepcion").prop("disabled", true);
    var frecuencia = "";
    $.ajax({
        url: '/excepciones/list/',
        type: "POST",
        datatype: 'json',
        async: false,
        cache: false,
        success: function (data) {  //alert(data);
            var res = jQuery.parseJSON(data);
            if (res.length > 0) {
                $("#lstExcepcion").prop("disabled", false);
                $.each(res, function (key, val) {
                    if (val.frecuencia_descripcion != '' && val.frecuencia_descripcion != null) frecuencia = "(M&Aacute;XIMO " + val.frecuencia_descripcion + ")";
                    else frecuencia = "";
                    $("#lstExcepcion").append("<option value='" + val.id + "' data-tipo_excepcion='" + val.tipo_excepcion + "' data-codigo='" + val.codigo + "' data-color='" + val.color + "' data-descuento='" + val.descuento + "' data-descuento_descripcion='" + val.descuento_descripcion + "' data-compensatoria='" + val.compensatoria + "' data-compensatoria_descripcion='" + val.compensatoria_descripcion + "' data-id_genero='" + val.genero_id + "' data-genero='" + val.genero + "' data-cantidad='" + val.cantidad + "' data-unidad='" + val.unidad + "' data-fraccionamiento='" + val.fraccionamiento + "' data-frecuencia_descripcion='" + val.frecuencia_descripcion + "' data-horario='" + val.horario + "' data-horario_descripcion='" + val.horario_descripcion + "' data-refrigerio='" + val.refrigerio + "' data-refrigerio_descripcion='" + val.refrigerio_descripcion + "'  data-lugar='" + val.lugar + "' data-lugar_descripcion='" + val.refrigerio_descripcion + "' data-redondeo='" + val.redondeo + "' data-redondeo_descripcion='" + val.redondeo_descripcion + "' data-observacion='" + val.observacion + "'>" + val.excepcion + " " + frecuencia + "</option>");
                });
            }
        }, //mostramos el error
        error: function () {
            $("#divMsjePorError").html("");
            $("#divMsjePorError").append("Se ha producido un error Inesperado");
            $("#divMsjeNotificacionError").jqxNotification("open");
        }
    });
}
/**
 * Función para el establecimiento del listado disponible de turnos para compensar en caso de que deba hacerse.
 */
function cargarCompensacionTurnos() {
    var lista = "";
    $("#lstCompensacionTurno").html("");
    $("#lstCompensacionTurno").append("<option value=''>Seleccionar..</option>");
    $("#lstCompensacionTurno").prop("disabled", false);
    for (var c = 1; c <= 2; c++) {
        lista += "<option value='" + c + "'>" + c + "°</option>";
    }
    $("#lstCompensacionTurno").append(lista);
}
/**
 * Función para el despliegue de los dos tipos de Marcación posible en un turno: Entrada (0) o Salida (1)
 */
function cargarCompensacionEntradaSalida() {
    var lista = "";
    $("#lstCompensacionEntradaSalida").html("");
    $("#lstCompensacionEntradaSalida").append("<option value=''>Seleccionar..</option>");
    $("#lstCompensacionEntradaSalida").prop("disabled", false);
    lista += "<option value='0'>ENTRADA</option>";
    lista += "<option value='1'>SALIDA</option>";
    $("#lstCompensacionEntradaSalida").append(lista);
}
/**
 * Función para la validación del formulario de registro masivo de boletas.
 * @returns {boolean}
 */
function validaFormularioRegistroMasivo() {
    var ok = true;
    var idControlExcepcion = 0;
    var msje = "";
    limpiarMensajesErrorPorValidacionFormularioRegistroMasivo();
    var enfoque = null;

    var lugar = 0;
    if ($("#lstExcepcion option:selected").data("lugar") != null)
        lugar = $("#lstExcepcion option:selected").data("lugar");

    var lstExcepcion = $("#lstExcepcion");
    var divExcepcion = $("#divExcepcion");
    var helpErrorExcepcion = $("#helpErrorExcepcion");
    var idExcepcion = $("#lstExcepcion").val();

    var descuento = $("#lstExcepcion option:selected").data("descuento");
    var compensatoria = $("#lstExcepcion option:selected").data("compensatoria");
    var genero_id = $("#lstExcepcion option:selected").data("genero_id");
    var cantidad = $("#lstExcepcion option:selected").data("cantidad");
    var unidad = $("#lstExcepcion option:selected").data("unidad");
    var fraccionamiento = $("#lstExcepcion option:selected").data("fraccionamiento");
    var horario = $("#lstExcepcion option:selected").data("horario");
    var refrigerio = $("#lstExcepcion option:selected").data("refrigerio");

    var txtFechaIni = $("#txtFechaIni");
    var divFechaIni = $("#divFechaIni");
    var helpErrorFechaIni = $("#helpErrorFechaIni");
    var fechaIni = $("#txtFechaIni").val();

    var txtHoraIni = $("#txtHoraIni");
    var divHoraIni = $("#divHoraIni");
    var helpErrorHoraIni = $("#helpErrorHoraIni");
    var horaIni = $("#txtHoraIni").val();

    var txtFechaFin = $("#txtFechaFin");
    var divFechaFin = $("#divFechaFin");
    var helpErrorFechaFin = $("#helpErrorFechaFin");
    var fechaFin = $("#txtFechaFin").val();

    var txtHoraFin = $("#txtHoraFin");
    var divHoraFin = $("#divHoraFin");
    var helpErrorHoraFin = $("#helpErrorHoraFin");
    var horaFin = $("#txtHoraFin").val();

    var txtJustificacion = $("#txtJustificacionRegistroMasivo");
    var divJustificacion = $("#divJustificacionRegistroMasivo");
    var helpErrorJustificacion = $("#helpErrorJustificacionRegistroMasivo");
    var justificacion = $("#txtJustificacionRegistroMasivo").val();

    var txtDestino = $("#txtDestino");
    var divDestino = $("#divDestino");
    var helpErrorDestino = $("#helpErrorDestino");
    var destino = $("#txtDestino").val();

    var txtObservacion = $("#txtObservacionRegistroMasivo");
    var divObservacion = $("#divObservacionRegistroMasivo");
    var helpErrorObservacion = $("#helpErrorObservacionRegistroMasivo");
    var observacion = $("#txtObservacionRegistroMasivo").val();

    var lstTurno = $("#lstCompensacionTurno");
    var divTurno = $("#divCompensacionTurno");
    var helpErrorTurno = $("#helpErrorCompensacionTurno");
    var turno = $("#lstCompensacionTurno").val();

    var lstEntradaSalida = $("#lstCompensacionEntradaSalida");
    var divEntradaSalida = $("#divCompensacionEntradaSalida");
    var helpErrorEntradaSalida = $("#helpErrorCompensacionEntradaSalida");
    var entradaSalida = $("#lstCompensacionEntradaSalida").val();

    if (idExcepcion == '' || idExcepcion == 0) {
        ok = false;
        var msje = "Debe seleccionar la excepci&oacute;n necesariamente.";
        divExcepcion.addClass("has-error");
        helpErrorExcepcion.html(msje);
        if (enfoque == null) enfoque = lstExcepcion;
    }
    if (fechaIni == '') {
        ok = false;
        var msje = "Debe seleccionar la fecha de inicio de la excepci&oacute;n.";
        divFechaIni.addClass("has-error");
        helpErrorFechaIni.html(msje);
        if (enfoque == null) enfoque = txtFechaIni;
    }
    if (fechaFin == '') {
        ok = false;
        var msje = "Debe seleccionar la fecha de finalizaci&oacute;n de la excepci&oacute;n.";
        divFechaFin.addClass("has-error");
        helpErrorFechaFin.html(msje);
        if (enfoque == null) enfoque = txtFechaFin;
    }
    var sep = "-";
    if (procesaTextoAFecha(fechaFin, sep) < procesaTextoAFecha(fechaIni, sep)) {
        ok = false;
        msje = "La fecha de inicio no puede ser superior a la fecha de finalizaci&oacute;n.";
        $("#divFechaIni").show();
        $("#divFechaIni").addClass("has-error");
        $("#helpErrorFechaIni").html(msje);
        $("#divFechaFin").show();
        $("#divFechaFin").addClass("has-error");
        $("#helpErrorFechaFin").html(msje);
        if (enfoque == null) enfoque = $("#txtFechaFin" + sufijo);
    }
    if (horario == 1) {
        if (horaIni == '') {
            ok = false;
            var msje = "Debe seleccionar la hora de inicio.";
            divHoraIni.addClass("has-error");
            helpErrorHoraIni.html(msje);
            if (enfoque == null) enfoque = txtHoraIni;
        }
        if (horaFin == '') {
            ok = false;
            var msje = "Debe seleccionar la hora de finalizaci&oacute;n.";
            divHoraFin.addClass("has-error");
            helpErrorHoraFin.html(msje);
            if (enfoque == null) enfoque = txtHoraFin;
        }
        /**
         * Si la solicitud es para un sólo día se controla que las horas solicitadas sean congruentes. Es decir, la hora de inicio no puede ser mayor a la hora de finalización.
         */
        if (fechaIni == fechaFin && fechaIni != '' && horaIni != '' && horaFin != '') {
            var resultado = horaEsMayorAhora(horaIni, horaFin);
            if (resultado > 0) {
                ok = false;
                var msje = "Debe modificar la hora de finalizaci&oacute;n debido a inconsistencia.";
                divHoraFin.addClass("has-error");
                helpErrorHoraFin.html(msje);
                if (enfoque == null) enfoque = txtHoraFin;
            }
        }
    }
    if (justificacion == '') {
        ok = false;
        var msje = "Debe introducir la justificaci&oacute;n para solicitar la excepci&oacute;n.";
        divJustificacion.addClass("has-error");
        helpErrorJustificacion.html(msje);
        if (enfoque == null) enfoque = txtJustificacion;
    }
    if (compensatoria == 1) {
        if (turno == 0 || turno == undefined) {
            ok = false;
            var msje = "Debe seleccionar el turno en el cual se compensar&aacute; el permiso.";
            divTurno.addClass("has-error");
            helpErrorTurno.html(msje);
            if (enfoque == null) enfoque = lstTurno;
        }
        if (entradaSalida < 0 || entradaSalida == '' || entradaSalida == undefined) {
            ok = false;
            var msje = "Debe seleccionar si lo compensaci&oacute;n se efecturar&aacute; en la Entrada o en la Salida del turno.";
            divEntradaSalida.addClass("has-error");
            helpErrorEntradaSalida.html(msje);
            if (enfoque == null) enfoque = lstEntradaSalida;
        }
    }
    if (lugar == 1) {
        if ($("#txtDestino").val() == "") {
            ok = false;
            var msje = "Debe especificar el lugar de asignaci&oacute;n de la excepci&oacute;n.";
            divDestino.addClass("has-error");
            helpErrorDestino.html(msje);
            if (enfoque == null) enfoque = txtDestino;
        }
    }
    if (enfoque != null) {
        enfoque.focus();
    }
    return ok;
}
/**
 * Función para verificar la cantidad de Dias entre dos horas.
 * @param horaIni
 * @param horaFin
 * @returns {string|*|string}
 */
function horaEsMayorAhora(horaIni, horaFin) {
    var resultado = $.ajax({
        url: '/controlexcepciones/horaesmayorahora/',
        type: "POST",
        datatype: 'html',
        async: false,
        cache: false,
        data: {hora_ini: horaIni, hora_fin: horaFin},
        success: function (data) {  //alert(data);
        }
    }).responseText;
    return resultado;
}
/**
 * Función para la verificación de la no existencia de cruce de horarios en cuanto a la aplicación de las excepciones para una determinada persona.
 * Considerando adicionalmente que sea aplicable el permiso controlando que no se haya excedido la cantidad permitida en un lapso de tiempo.
 * @param idControlExcepcion
 * @param idRelaboral
 * @param idExcepcion
 * @param fechaIni
 * @param horaIni
 * @param fechaFin
 * @param horaFin
 * @param horario
 * @param justificacion
 */
function verificaCruceDeHorariosYExcesoEnUso(idControlExcepcion, idRelaboral, idExcepcion, fechaIni, horaIni, fechaFin, horaFin, horario, justificacion) {
    var msjs = "";
    $.ajax({
        url: '/controlexcepciones/verificacruceexcesouso/',
        type: "POST",
        datatype: 'json',
        async: false,
        cache: false,
        data: {
            id: idControlExcepcion,
            relaboral_id: idRelaboral,
            excepcion_id: idExcepcion,
            excepcion_id: idExcepcion,
            fecha_ini: fechaIni,
            hora_ini: horaIni,
            fecha_fin: fechaFin,
            hora_fin: horaFin,
            horario: horario,
            justificacion: justificacion
        },
        success: function (data) {  //alert(data);
            var res = jQuery.parseJSON(data);
            if (res.result == 0) {

            } else if (res.result == 1) {
                msjs = res.msj;
            } else {
                msjs = res.msj;
            }

        },
        error: function () {
            msjs = "Se ha producido un error Inesperado";
        }
    });
    return msjs;
}
/**
 * Función para la verificación del cumplimiento de la frecuencia de un determinado registro de control de excepción.
 * @param idControlExcepcion
 * @param idRelaboral
 * @param idExcepcion
 * @param fechaIni
 * @param horaIni
 * @param fechaFin
 * @param horaFin
 * @param horario
 * @param justificacion
 * @returns {boolean}
 */
function verificaFrecuencia(idControlExcepcion, idRelaboral, idExcepcion, fechaIni, horaIni, fechaFin, horaFin, horario) {
    var msjs = "";
    $.ajax({
        url: '/controlexcepciones/verificafrecuencia/',
        type: "POST",
        datatype: 'json',
        async: false,
        cache: false,
        data: {
            id_controlexcepcion: idControlExcepcion,
            id_relaboral: idRelaboral,
            id_excepcion: idExcepcion,
            fecha_ini: fechaIni,
            hora_ini: horaIni,
            fecha_fin: fechaFin,
            hora_fin: horaFin,
            horario: horario
        },
        success: function (data) {  //alert(data);
            var res = jQuery.parseJSON(data);
            if (res.result == 1 || res.result == 2) {

            } else if (res.result == 1) {
                msjs = res.msj;
            } else {
                msjs = res.msj;
            }

        },
        error: function () {
            msjs = "Se ha producido un error Inesperado";
        }
    });
    return msjs;
}
/**
 * Función para verificar la compatibilidad de la configuración de la boleta y la aplicabilidad para la persona en específico.
 * @param genero
 * @param id_genero
 */
function verificaGenero(genero, id_genero) {
    var ok = true;
    switch (id_genero) {
        case 1:
            if (genero != 'F') {
                ok = false;
            }
            break;
        case 2:
            if (genero != 'M') {
                ok = false;
            }
            break;
    }
    return ok;
}