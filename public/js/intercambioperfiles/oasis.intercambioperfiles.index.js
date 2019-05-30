/**
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  2.0.3
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  07-09-2016
 */
$().ready(function () {
    $("#lstSoloEstasPersonas").select2({
        placeholder: 'Seleccione usuarios',
        ajax: {
            url: '/intercambioperfiles/getusersforselect',
            dataType: 'json',
            delay: 250,
            type: "POST",
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: false
        },
        minimumInputLength: 2,
        templateResult: formatStateUser
    });
    limpiarValoresDeFormulario();
    definirGrillaParaListaPerfilesLaborales();
    $("#btnGuardarIntercambio").on("click", function () {
        limpiarMensajesErrorPorValidacion();
        var okv = validarFormularioIntercambio();
        if (okv) {
            var oks = aplicarIntercambio();
            if (oks) {
                limpiarValoresDeFormulario();
            }
        }

    });
    $("#btnLimpiarFormularioIntercambio").on("click", function () {
        limpiarMensajesErrorPorValidacion();
        limpiarValoresDeFormulario();
    });
    $("#lstTipoIntercambio").on("change", function () {
        switch (parseInt(this.value)) {
            case 1:
                $("#divIdOrganigrama").hide();
                $("#divGenero").hide();
                $("#divSoloEstasPersonas").show();
                $("#lstSoloEstasPersonas").focus();
                break;
            case 2:
                listarOrganigramasVigentes();
                $("#divIdOrganigrama").show();
                $("#lstIdOrganigrama").focus();
                $("#divGenero").show();
                $("#divSoloEstasPersonas").hide();
                break;
            default:
                $("#divIdOrganigrama").hide();
                $("#divGenero").hide();
                $("#divSoloEstasPersonas").hide();
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

function OperaEvento(evento) {
    if ((evento.type == "keyup" || evento.type == "keydown") && evento.which == "27") {
        $("#divGridPerfilesLaborales").jqxGrid('beginupdate');
        limpiarValoresDeFormulario();
    }
}

/**
 * Función para limpiar los valores registrados en cada campo del formulario.
 */
function limpiarValoresDeFormulario() {
    $("#divGrillaOrigen").jqxGrid("clear");
    $("#divGrillaDestino").jqxGrid("clear");
    $("#lstIdOrganigrama").val("");
    $("#lstGenero").val("0");
    $("#lstSoloEstasPersonas").html("");
    $("#txtFechaIntercambio").val("");
    $("#txtObservacion").val("");
    $("#lstTipoIntercambio").val("0");
    $("#divIdOrganigrama").hide();
    $("#divGenero").hide();
    $("#divSoloEstasPersonas").hide();
    $("#chkRetorno").prop("checked", false);
    $("#lstTodaMarcacion").val("0");

}

/**
 * Función para limpiar los mensajes de error en el formulario de intercambio de perfiles.
 */
function limpiarMensajesErrorPorValidacion() {
    $("#divPerfilOrigen").removeClass("has-error");
    $("#helpErrorPerfilOrigen").html("");

    $("#divPerfilDestino").removeClass("has-error");
    $("#helpErrorPerfilDestino").html("");

    $("#divIdOrganigrama").removeClass("has-error");
    $("#helpErrorIdOrganigrama").html("");

    $("#divGenero").removeClass("has-error");
    $("#helpErrorGenero").html("");

    $("#divSoloEstasPersonas").removeClass("has-error");
    $("#helpErrorSoloEstasPersonas").html("");

    $("#divFechaIntercambio").removeClass("has-error");
    $("#helpErrorFechaIntercambio").html("");

    $("#divObservacion").removeClass("has-error");
    $("#helpErrorObservacion").html("");
}

/**
 * Función para la validación del formulario de intercambio de perfiles laborales.
 * @returns {boolean}
 */
function validarFormularioIntercambio() {
    var ok = true;
    var enfoque = null;
    var divPerfilOrigen = $("#divPerfilOrigen");
    var helpErrorPerfilOrigen = $("#helpErrorPerfilOrigen");
    var divGrillaOrigen = $("#divGrillaOrigen");

    var divPerfilDestino = $("#divPerfilDestino");
    var helpErrorPerfilDestino = $("#helpErrorPerfilDestino");
    var divGrillaDestino = $("#divGrillaDestino");

    var divIdOrganigrama = $("#divIdOrganigrama");
    var helpErrorIdOrganigrama = $("#helpErrorIdOrganigrama");
    var lstIdOrganigrama = $("#lstIdOrganigrama");
    var idOrganigrama = $("#lstIdOrganigrama").val();

    var divGenero = $("#divGenero");
    var helpErrorGenero = $("#helpErrorGenero");
    var lstGenero = $("#lstGenero");
    var genero = $("#lstGenero").val();

    var divSoloEstasPersonas = $("#divSoloEstasPersonas");
    var helpErrorSoloEstasPersonas = $("#helpErrorSoloEstasPersonas");
    var lstSoloEstasPersonas = $("#lstSoloEstasPersonas");

    var divFechaIntercambio = $("#divFechaIntercambio");
    var helpErrorFechaIntercambio = $("#helpErrorFechaIntercambio");
    var txtFechaIntercambio = $("#txtFechaIntercambio");
    var fechaIntercambio = $("#txtFechaIntercambio").val();

    var divObservacion = $("#divObservacion");
    var helpErrorObservacion = $("#helpErrorObservacion");
    var txtObservacion = $("#txtObservacion");
    var observacion = $("#txtObservacion").val();

    var dataRecordDestino = $('#divGrillaDestino').jqxGrid('getrowdata', 0);
    var idPerfilDestino = 0;
    if (dataRecordDestino != null) {
        idPerfilDestino = dataRecordDestino.id
    }
    var dataRecordOrigen = $('#divGrillaOrigen').jqxGrid('getrowdata', 0);
    var idPerfilOrigen = 0;
    if (dataRecordOrigen != null) {
        idPerfilOrigen = dataRecordOrigen.id
    }
    if (idPerfilOrigen == 0) {
        ok = false;
        var msje = "Debe seleccionar el perfil de origen necesariamente.";
        divPerfilOrigen.addClass("has-error");
        helpErrorPerfilOrigen.html(msje);
        if (enfoque == null) enfoque = divGrillaOrigen;
    }
    if (idPerfilDestino == 0) {
        ok = false;
        var msje = "Debe seleccionar el perfil de destino necesariamente.";
        divPerfilDestino.addClass("has-error");
        helpErrorPerfilDestino.html(msje);
        if (enfoque == null) enfoque = divGrillaDestino;
    }
    if (idPerfilOrigen != 0 && idPerfilDestino != 0 && idPerfilOrigen == idPerfilDestino) {
        ok = false;
        var msje = "Debe seleccionar los perfiles de origen y destino necesariamente.";
        divPerfilOrigen.addClass("has-error");
        helpErrorPerfilOrigen.html(msje);
        divPerfilDestino.addClass("has-error");
        helpErrorPerfilDestino.html(msje);
        if (enfoque == null) enfoque = divGrillaOrigen;
    }
    if (parseInt($("#lstTipoIntercambio").val()) == 1) {
        var idRelaborales = "";
        var arrSoloEstasPersonas = [];

        $('#lstSoloEstasPersonas :selected').each(function (i, sel) {
            idRelaborales += $(sel).val() + ",";
            arrSoloEstasPersonas.push($(sel).val());
        });
        if (idRelaborales != "") {
            idRelaborales += ",";
        }
        idRelaborales = idRelaborales.replace(",,", "");
        if (idRelaborales == '') {
            ok = false;
            var msje = "Para aplicar &eacute;ste tipo de intercambio debe seleccionar a las personas en espec&iacute;fico.";
            divSoloEstasPersonas.addClass("has-error");
            helpErrorSoloEstasPersonas.html(msje);
            if (enfoque == null) enfoque = lstSoloEstasPersonas;
        }
    }
    if (parseInt($("#lstTipoIntercambio").val()) == 2) {
        if ((idOrganigrama == 0 || idOrganigrama == '') && (genero == 0 || genero == '')) {
            ok = false;
            var msje = "Para aplicar &eacute;ste tipo de intercambio debe espeficar el organigrama y/o G&eacute;nero necesariamente.";
            divIdOrganigrama.addClass("has-error");
            helpErrorIdOrganigrama.html(msje);
            divGenero.addClass("has-error");
            helpErrorGenero.html(msje);
            if (enfoque == null) enfoque = lstIdOrganigrama;
        }
    }
    if (fechaIntercambio == '') {
        ok = false;
        var msje = "Debe seleccionar la fecha para el intercambio.";
        divFechaIntercambio.addClass("has-error");
        helpErrorFechaIntercambio.html(msje);
        if (enfoque == null) enfoque = txtFechaIntercambio;
    }
    if (observacion == '') {
        ok = false;
        var msje = "Debe justificar el intercambio de perfiles necesariamente.";
        divObservacion.addClass("has-error");
        helpErrorObservacion.html(msje);
        if (enfoque == null) enfoque = txtObservacion;
    }
    if (enfoque != null) {
        enfoque.focus();
    }
    return ok;
}

/**
 * Función para aplicar el intercambio de perfiles de acuerdo a los datos configurados.
 */
function aplicarIntercambio() {
    var ok = false;

    var dataRecordDestino = $('#divGrillaDestino').jqxGrid('getrowdata', 0);
    var idPerfilDestino = 0;
    if (dataRecordDestino != null) {
        idPerfilDestino = dataRecordDestino.id
    }
    var dataRecordOrigen = $('#divGrillaOrigen').jqxGrid('getrowdata', 0);
    var idPerfilOrigen = 0;
    if (dataRecordOrigen != null) {
        idPerfilOrigen = dataRecordOrigen.id
    }
    var tipoIntercambio = $("#lstTipoIntercambio").val();
    var idOrganigrama = 0;
    if ($("#lstIdOrganigrama").val() != '' && $("#lstIdOrganigrama").val() > 0) {
        idOrganigrama = $("#lstIdOrganigrama").val();
    }
    var genero = 0;
    if ($("#lstGenero").val() != '' && $("#lstGenero").val() > 0) {
        genero = $("#lstGenero").val();
    }
    var lstCiPersonas = "";
    var arrSoloEstasPersonas = [];

    $('#lstSoloEstasPersonas :selected').each(function (i, sel) {
        lstCiPersonas += $(sel).val() + ",";
        arrSoloEstasPersonas.push($(sel).val());
    });
    if (lstCiPersonas != "") {
        lstCiPersonas += ",";
    }
    lstCiPersonas = lstCiPersonas.replace(",,", "");

    var fechaIntercambio = $("#txtFechaIntercambio").val();
    var observacion = $("#txtObservacion").val();
    var retorno = 0;
    if ($("#chkRetorno").prop("checked")) {
        retorno = 1;
    }
    var todaMarcacion = 0;
    if ($("#lstTodaMarcacion").val() > 0) {
        todaMarcacion = $("#lstTodaMarcacion").val();
    }
    if (idPerfilOrigen > 0 && idPerfilDestino > 0 && fechaIntercambio != '' && observacion != '') {
        $.ajax({
            url: '/intercambioperfiles/apply/',
            type: "POST",
            datatype: 'json',
            async: false,
            cache: false,
            data: {
                id_perfil_origen: idPerfilOrigen,
                id_perfil_destino: idPerfilDestino,
                tipo_intercambio: tipoIntercambio,
                id_organigrama: idOrganigrama,
                genero: genero,
                ci_personas: lstCiPersonas,
                fecha_int: fechaIntercambio,
                retorno: retorno,
                toda_marcacion: todaMarcacion,
                observacion: observacion
            },
            success: function (data) {  //alert(data);
                var res = jQuery.parseJSON(data);
                /**
                 * Si se ha realizado correctamente el registro de excepción
                 */
                $(".msjes").hide();
                if (res.result == 1) {
                    ok = true;
                    $("#divMsjePorSuccess").html("");
                    $("#divMsjePorSuccess").append(res.msj);
                    $("#divMsjeNotificacionSuccess").jqxNotification("open");
                } else if (res.result == 0) {
                    /**
                     * En caso de presentarse un error subsanable
                     */
                    $("#divMsjePorWarning").html("");
                    $("#divMsjePorWarning").append(res.msj);
                    $("#divMsjeNotificacionWarning").jqxNotification("open");
                } else {
                    /**
                     * En caso de haberse presentado un error crítico al momento de registrarse la relación laboral
                     */
                    $("#divMsjePorError").html("");
                    $("#divMsjePorError").append(res.msj);
                    $("#divMsjeNotificacionError").jqxNotification("open");
                }

            }, //mostramos el error
            error: function () {
                $("#divMsjePorError").html("");
                $("#divMsjePorError").append("Se ha producido un error Inesperado");
                $("#divMsjeNotificacionError").jqxNotification("open");
            }
        });
    }
    return ok;
}

/**
 * Función para dar formato a la salida de cada tag de usuarios.
 * @param state
 * @returns {*}
 */
function formatStateUser(state) {
    if (!state.id) {
        return state.text;
    }
    var expd = '';
    if (state.expd != undefined && state.expd != null) {
        expd = state.expd;
    }
    var gerencia_administrativa = '';
    if (state.gerencia_administrativa != undefined && state.gerencia != null) {
        gerencia_administrativa = state.gerencia;
    }
    var departamento_administrativo = '';
    if (state.departamento_administrativo != undefined && state.departamento != null) {
        departamento_administrativo = state.departamento;
    }
    var filaDepartamento = '';
    if (departamento_administrativo != '') {
        filaDepartamento = '<tr><th>' + state.departamento_administrativo + '</th></tr>';
    }
    var $state = $(
        '<table><tr><th><table><tr><td><span><img width="80px" height="80px" src="' + state.image_src + '" class="img-flag" /></span></td></tr><tr><td>' + state.ci + ' ' + expd + '</td></tr></table></th><th>&nbsp;</th><th><table><tr><th>' + state.text + '</th></tr><tr><th>' + gerencia_administrativa + '</th></tr>' + filaDepartamento + '<tr><th>' + state.cargo + '</th></tr></table></th></tr></table>'
    );
    return $state;
};

/**
 * Funcion para listar las áreas administrativas.
 */
function listarOrganigramasVigentes() {
    $("#lstIdOrganigrama").html("");
    $.ajax({
        url: '/organigramas/listactives',
        type: 'POST',
        datatype: 'json',
        async: false,
        cache: false,
        success: function (data) {
            var res = jQuery.parseJSON(data);
            if (res.length > 0) {
                var listado = "";
                $("#lstIdOrganigrama").append("<option value=''>Seleccionar</option>");
                $.each(res, function (key, val) {
                    listado += "<option value='" + val.id + "'>" + val.unidad_administrativa + "</option>";
                });
                $("#lstIdOrganigrama").append(listado);
            }
        }
    });
}

/**
 * Función para definir la grilla principal (listado) para la gestión de relaciones laborales.
 */
function definirGrillaParaListaPerfilesLaborales() {
    var source =
        {
            datatype: "json",
            datafields: [
                {name: 'nro_row', type: 'integer'},
                {name: 'id', type: 'integer'},
                {name: 'perfil_laboral', type: 'string'},
                {name: 'grupo', type: 'string'},
                {name: 'tipo_horario', type: 'integer'},
                {name: 'tipo_horario_descripcion', type: 'string'},
                {name: 'estado', type: 'integer'},
                {name: 'estado_descripcion', type: 'string'},
                {name: 'agrupador', type: 'integer'},
                {name: 'observacion', type: 'string'},
            ],
            url: '/perfileslaborales/list',
            cache: false
        };
    var dataAdapter = new $.jqx.dataAdapter(source);
    cargarRegistrosDePerfilesLaborales();

    function cargarRegistrosDePerfilesLaborales() {
        $("#divGridPerfilesLaborales").jqxGrid(
            {
                width: '100%',
                height: '700px',
                source: dataAdapter,
                sortable: true,
                altRows: true,
                groupable: true,
                columnsresize: true,
                pageable: true,
                pagerMode: 'advanced',
                showfilterrow: true,
                filterable: true,
                pagesize: 20,
                autorowheight: true,
                showtoolbar: true,
                rendergridrows: function () {
                    return dataAdapter.records;
                }
                ,
                rendertoolbar: function (toolbar) {
                    var me = this;
                    var container = $("<div></div>");
                    toolbar.append(container);
                    container.append("<button title='Refrescar Grilla' id='refreshbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-repeat fa-2x text-default' title='Refrescar grilla.'/></i></button>");
                    container.append("<button title='Desagrupar.' id='cleargroupsrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-chain-broken fa-2x text-default' title='Desagrupar.'/></i></button>");
                    container.append("<button title='Desfiltrar.' id='clearfiltersrowbutton' class='btn btn-sm btn-primary' type='button'><i class='gi gi-sorting fa-2x text-default' title='Desfiltrar.'/></i></button>");


                    $("#refreshbutton").jqxButton();
                    $("#cleargroupsrowbutton").jqxButton();
                    $("#clearfiltersrowbutton").jqxButton();

                    /**
                     * Refrescar la grilla
                     */
                    $("#refreshbutton").off();
                    $("#refreshbutton").on('click', function () {
                        $("#divGridPerfilesLaborales").jqxGrid("updatebounddata");
                    });
                    /**
                     * Desagrupar
                     */
                    $("#cleargroupsrowbutton").off();
                    $("#cleargroupsrowbutton").on('click', function () {
                        $("#divGridPerfilesLaborales").jqxGrid('cleargroups');
                    });
                    /**
                     * Desfiltrar
                     */
                    $("#clearfiltersrowbutton").off();
                    $("#clearfiltersrowbutton").on('click', function () {
                        $("#divGridPerfilesLaborales").jqxGrid('clearfilters');
                    });
                }
                ,
                rendered: function () {
                    $('#divGridPerfilesLaborales').jqxDragDrop({disabled: true});
                    $('.jqx-grid-cell-filter-row').jqxDragDrop({disabled: true});
                    var gridCells = $('#divGridPerfilesLaborales').find('.jqx-grid-cell');
                    if ($('#divGridPerfilesLaborales').jqxGrid('groups').length > 0) {
                        gridCells = $('#divGridPerfilesLaborales').find('.jqx-grid-group-cell');
                    }
                    gridCells.jqxDragDrop({
                        appendTo: 'body', dragZIndex: 99999,
                        dropAction: 'none',
                        initFeedback: function (feedback) {
                            feedback.height(70);
                            feedback.width(220);
                        }
                    });
                    var rows = $('#divGridPerfilesLaborales').jqxGrid('getdatainformation').rowscount;
                    gridCells.off('dragStart');
                    gridCells.on('dragStart', function (event) {
                        var value = $(this).text();
                        var position = $.jqx.position(event.args);
                        var cell = $("#divGridPerfilesLaborales").jqxGrid('getcellatposition', position.left, position.top);
                        $(this).jqxDragDrop('data', $("#divGridPerfilesLaborales").jqxGrid('getrowdata', cell.row));
                        var groupslength = $('#divGridPerfilesLaborales').jqxGrid('groups').length;
                        var feedback = $(this).jqxDragDrop('feedback');

                        var feedbackContent = $(this).parent().clone();
                        var table = '<table>';
                        $.each(feedbackContent.children(), function (index) {
                            if ((index - 4) < groupslength) {
                                table += '<tr>';
                                table += '<td>';
                                table += '</td>';
                                table += '<td>';
                                table += $(this).text();
                                table += '</td>';
                                table += '</tr>';
                            } else {
                                return true;
                            }
                        });
                        table += '</table>';
                        feedback.html(table);

                    });
                    gridCells.off('dragEnd');
                    gridCells.on('dragEnd', function (event) {
                        var value = $(this).jqxDragDrop('data');
                        var position = $.jqx.position(event.args);
                        var pageX = position.left;
                        var pageY = position.top;

                        var $destination = $("#divGrillaOrigen");
                        var targetX = $destination.offset().left;
                        var targetY = $destination.offset().top;
                        var width = $destination.width();
                        var height = $destination.height();
                        if (pageX >= targetX && pageX <= targetX + width) {
                            if (pageY >= targetY && pageY <= targetY + height) {
                                var dataRecordDestino = $('#divGrillaDestino').jqxGrid('getrowdata', 0);
                                var idPerfilDestino = 0;
                                if (dataRecordDestino != null) {
                                    idPerfilDestino = dataRecordDestino.id
                                }
                                if (idPerfilDestino != value.id) {
                                    $('#divGrillaOrigen').jqxGrid('clear');
                                    $destination.jqxGrid('addrow', null, value);
                                } else {
                                    alert("No puede asignar como destino y origen un mismo perfil");
                                }
                            }
                        }
                        var $destinationB = $("#divGrillaDestino");
                        var targetX = $destinationB.offset().left;
                        var targetY = $destinationB.offset().top;
                        var width = $destinationB.width();
                        var height = $destinationB.height();
                        if (pageX >= targetX && pageX <= targetX + width) {
                            if (pageY >= targetY && pageY <= targetY + height) {
                                var dataRecordOrigen = $('#divGrillaOrigen').jqxGrid('getrowdata', 0);
                                var idPerfilOrigen = 0;
                                if (dataRecordOrigen != null) {
                                    idPerfilOrigen = dataRecordOrigen.id
                                }
                                if (idPerfilOrigen != value.id) {
                                    $('#divGrillaDestino').jqxGrid('clear');
                                    $destinationB.jqxGrid('addrow', null, value);
                                } else {
                                    alert("No puede asignar como destino y origen un mismo perfil");
                                }


                            }
                        }
                    });
                }
                ,
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
                        width: 50,
                        cellsalign: 'center',
                        align: 'center',
                        cellsrenderer: rownumberrenderer
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
                        text: 'Perfil',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'perfil_laboral',
                        width: 200,
                        cellsalign: 'justify',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Grupo',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'grupo',
                        width: 200,
                        cellsalign: 'justify',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Tipo Horario',
                        filtertype: 'checkedlist',
                        datafield: 'tipo_horario_descripcion',
                        width: 150,
                        cellsalign: 'justify',
                        align: 'center',
                        hidden: false
                    },
                    {
                        text: 'Observaci&oacute;n',
                        columntype: 'textbox',
                        filtertype: 'input',
                        datafield: 'observacion',
                        width: 300,
                        align: 'center',
                        cellsalign: 'justify',
                        hidden: false
                    },
                ]
            })
        ;

        $("#divGrillaOrigen").jqxGrid(
            {
                width: '100%',
                height: 80,
                theme: 'custom',
                showaggregates: true,
                columnsresize: true,
                selectionmode: 'index',
                columns: [
                    {text: 'id', dataField: 'id', width: '6%', align: 'center'},
                    {
                        text: 'Perfil',
                        editable: false,
                        dataField: 'perfil_laboral',
                        width: '200',
                        align: 'center',
                        cellsalign: 'left',
                        cellsformat: 'c2'
                    },
                    {
                        text: 'Grupo',
                        editable: false,
                        dataField: 'grupo',
                        align: 'center',
                        cellsalign: 'left',
                        width: '200'
                    },
                    {
                        text: '',
                        width: '50px',
                        align: 'center',
                        cellsalign: 'center',
                        sortable: false,
                        showfilterrow: false,
                        filterable: false,
                        columntype: 'number',
                        cellsrenderer: function (rowline) {
                            var id = $("#divGrillaOrigen").jqxGrid('getrowid', rowline);
                            return "<div style='text-align: center; margin-top: -2px;'>" + "<a onclick='eliminarRegistro(0," + id + ");' title='Eliminar' class='btn btn-link btn-sm text-danger'><i class='fa fa-times fa-2x text-danger'></i></a>" + "</div>";

                        }
                    }
                ],
            });
        $("#divGrillaDestino").jqxGrid(
            {
                width: '100%',
                height: 80,
                theme: 'custom',
                showaggregates: true,
                columnsresize: true,
                selectionmode: 'index',
                columns: [
                    {text: 'id', dataField: 'id', width: '6%', align: 'center'},
                    {
                        text: 'Perfil',
                        editable: false,
                        dataField: 'perfil_laboral',
                        width: '200',
                        align: 'center',
                        cellsalign: 'left',
                        cellsformat: 'c2'
                    },
                    {
                        text: 'Grupo',
                        editable: false,
                        dataField: 'grupo',
                        align: 'center',
                        cellsalign: 'left',
                        width: '200'
                    },
                    {
                        text: '',
                        width: '50px',
                        align: 'center',
                        cellsalign: 'center',
                        sortable: false,
                        showfilterrow: false,
                        filterable: false,
                        columntype: 'number',
                        cellsrenderer: function (rowline) {
                            var id = $("#divGrillaDestino").jqxGrid('getrowid', rowline);
                            return "<div style='text-align: center; margin-top: -2px;'>" + "<a onclick='eliminarRegistro(1," + id + ");' title='Eliminar' class='btn btn-link btn-sm text-danger'><i class='fa fa-times fa-2x text-danger'></i></a>" + "</div>";

                        }
                    }
                ],
            });

    }
}

var rownumberrenderer = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
    var nro = row + 1;
    return "<div align='center'>" + nro + "</div>";
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
 * Función para la eliminación de registro de la grilla de selección de perfiles laborales.
 * @param opcion
 * @param id
 */
function eliminarRegistro(opcion, id) {
    if (opcion == 0) {
        $("#divGrillaOrigen").jqxGrid('deleterow', id);
    } else {
        $("#divGrillaDestino").jqxGrid('deleterow', id);
    }

}