function getParametroVacio() {
    return {idOrganigrama: 0, idArea: 0, idUbicacion: 0, idMaquina: 0, idRelaboral: 0, fechaIni: "", fechaFin: ""}
}

function cargarGestionesRelaborales(t) {
    var i = "";
    $("#lstGestion").html(""), $("#lstGestion").append("<option value='0'>TODAS</option>"), $.ajax({
        url: "/relaborales/getgestionesrelaborales/",
        type: "POST",
        datatype: "json",
        async: !1,
        cache: !1,
        success: function (a) {
            var e = jQuery.parseJSON(a);
            $.each(e, function (a, e) {
                i = t == e ? "selected" : "", $("#lstGestion").append("<option value='" + e + "' " + i + ">" + e + "</option>")
            })
        },
        error: function () {
            alert("Se ha producido un error Inesperado")
        }
    })
}

function definirGrillaParaListaRelaboralesParaMarcaciones(a) {
    var e = {
        datatype: "json",
        datafields: [{name: "nro_row", type: "integer"}, {name: "fecha_nac", type: "string"}, {
            name: "edad",
            type: "integer"
        }, {name: "lugar_nac", type: "integer"}, {name: "genero", type: "integer"}, {
            name: "e_civil",
            type: "integer"
        }, {name: "id_relaboral", type: "integer"}, {
            name: "id_persona",
            type: "integer"
        }, {name: "tiene_contrato_vigente", type: "integer"}, {
            name: "id_fin_partida",
            type: "integer"
        }, {name: "finpartida", type: "string"}, {name: "ubicacion", type: "string"}, {
            name: "id_condicion",
            type: "integer"
        }, {name: "condicion", type: "string"}, {name: "item", type: "integer"}, {
            name: "tiene_item",
            type: "integer"
        }, {name: "id_cargo", type: "integer"}, {
            name: "cargo_codigo",
            type: "string"
        }, {name: "cargo_resolucion_ministerial_id", type: "integer"}, {
            name: "cargo_resolucion_ministerial",
            type: "string"
        }, {name: "estado", type: "integer"}, {name: "estado_descripcion", type: "string"}, {
            name: "nombres",
            type: "string"
        }, {name: "ci", type: "string"}, {name: "expd", type: "string"}, {
            name: "num_complemento",
            type: "string"
        }, {name: "id_organigrama", type: "integer"}, {
            name: "gerencia_administrativa",
            type: "string"
        }, {name: "departamento_administrativo", type: "string"}, {name: "id_area", type: "integer"}, {
            name: "area",
            type: "string"
        }, {name: "id_ubicacion", type: "integer"}, {name: "ubicacion", type: "string"}, {
            name: "num_contrato",
            type: "string"
        }, {name: "fin_partida", type: "string"}, {name: "partida", type: "integer"}, {
            name: "id_procesocontratacion",
            type: "integer"
        }, {name: "proceso_codigo", type: "string"}, {
            name: "nivelsalarial",
            type: "string"
        }, {name: "nivelsalarial_resolucion", type: "string"}, {name: "cargo", type: "string"}, {
            name: "sueldo",
            type: "numeric"
        }, {name: "fecha_ini", type: "date"}, {name: "fecha_incor", type: "date"}, {
            name: "fecha_fin",
            type: "date"
        }, {name: "fecha_baja", type: "date"}, {name: "motivo_baja", type: "string"}, {
            name: "relaboral_previo_id",
            type: "integer"
        }, {name: "observacion", type: "string"}, {name: "fecha_ing", type: "date"}],
        url: "/relaborales/listpaged?opcion=0&gestion=" + a,
        cache: !1,
        root: "Rows",
        beforeprocessing: function (a) {
            e.totalrecords = a[0].TotalRows
        },
        filter: function () {
            $("#divGridRelaborales").jqxGrid("updatebounddata", "filter")
        },
        sort: function () {
            $("#divGridRelaborales").jqxGrid("updatebounddata", "sort")
        }
    }, t = new $.jqx.dataAdapter(e);
    !function () {
        prepareSimulator("grid");
        $("#divGridRelaborales").jqxGrid({
            width: "100%",
            height: 600,
            source: t,
            sortable: !0,
            altRows: !0,
            groupable: !0,
            columnsresize: !0,
            pageable: !0,
            pagerMode: "advanced",
            virtualmode: !0,
            rendergridrows: function (a) {
                return a.data
            },
            showfilterrow: !0,
            filterable: !0,
            showtoolbar: !0,
            autorowheight: !0,
            enablebrowserselection: !0,
            rendertoolbar: function (a) {
                var e = $("<div></div>");
                a.append(e), e.append("<button id='listrowbutton' class='btn btn-sm btn-primary' type='button'  title='Listado de Horarios y Marcaciones por Relaci&oacute;n Laboral.'><i class='fa fa-list-alt fa-2x text-info' title='Listado de Horarios y Marcaciones por Relaci&oacute;n Laboral.'/></i></button>"), e.append("<button id='downloadrowbutton' class='btn btn-sm btn-primary' type='button'  title='Formulario para la descarga de marcaciones.'><i class='fa fa-download fa-2x text-primary' title='Formulario para la descarga de marcaciones.'/></i></button>"), e.append("<button title='Ver calendario de turnos y permisos de manera global para la persona.' id='turnrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-calendar fa-2x text-purple' title='Vista Turnos Laborales por relaci&oacute;n laboral.'/></i></button>"), e.append("<button title='Refrescar Grilla' id='refreshbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-repeat fa-2x text-default' title='Refrescar grilla.'/></i></button>"), e.append("<button title='Desagrupar.' id='cleargroupsrowbutton' class='btn btn-sm btn-primary' type='button'><i class='fa fa-chain-broken fa-2x text-default' title='Desagrupar.'/></i></button>"), e.append("<button title='Desfiltrar.' id='clearfiltersrowbutton' class='btn btn-sm btn-primary' type='button'><i class='gi gi-sorting fa-2x text-default' title='Desfiltrar.'/></i></button>"), $("#refreshbutton").jqxButton(), $("#cleargroupsrowbutton").jqxButton(), $("#clearfiltersrowbutton").jqxButton(), $("#listrowbutton").jqxButton(), $("#downloadrowbutton").jqxButton(), $("#turnrowbutton").jqxButton(), $("#hdnIdRelaboralControlMarcaciones").val(0), $("#refreshbutton").off(), $("#refreshbutton").on("click", function () {
                    $("#divGridRelaborales").jqxGrid("updatebounddata")
                }), $("#cleargroupsrowbutton").off(), $("#cleargroupsrowbutton").on("click", function () {
                    $("#divGridRelaborales").jqxGrid("cleargroups")
                }), $("#clearfiltersrowbutton").off(), $("#clearfiltersrowbutton").on("click", function () {
                    $("#divGridRelaborales").jqxGrid("clearfilters")
                }), $("#listrowbutton").off(), $("#listrowbutton").on("click", function () {
                    var a = $("#divGridRelaborales").jqxGrid("getselectedrowindex");
                    if (0 <= a) {
                        var e = $("#divGridRelaborales").jqxGrid("getrowdata", a);
                        if (null != e) {
                            var t = e.id_relaboral;
                            if ($(".msjs-alert").hide(), $("#hdnIdPersonaHistorial").val(e.id_persona), 0 <= e.tiene_contrato_vigente) {
                                $("#divTabControlMarcaciones").jqxTabs("enableAt", 0), $("#divTabControlMarcaciones").jqxTabs("disableAt", 2), $("#divTabControlMarcaciones").jqxTabs("disableAt", 3), $("#divTabControlMarcaciones").jqxTabs("enableAt", 1), $("#divTabControlMarcaciones").jqxTabs({selectedItem: 1}), $("#tabFichaPersonal").jqxTabs({
                                    theme: "oasis",
                                    width: "100%",
                                    height: "100%",
                                    position: "top"
                                }), $("#tabFichaPersonal").jqxTabs({selectedItem: 0}), $("#ddNombres").html(e.nombres);
                                var i = obtenerRutaFoto(e.ci, e.num_complemento);
                                $("#imgFotoPerfilContactoPer").attr("src", i), $("#imgFotoPerfilContactoInst").attr("src", i), $("#imgFotoPerfil").attr("src", i), cargarPersonasContactosMarcaciones(1, e.id_persona), $("#hdnIdRelaboralControlMarcaciones").val(t), $("#hdnSwPrimeraVistaHistorial").val(0), $("#divContent_" + e.id_relaboral).focus().select();
                                var r = "", n = "";
                                r = "" != e.fecha_incor ? e.fecha_incor : e.fecha_ini, n = "" != e.fecha_baja ? e.fecha_baja : e.fecha_fin, r = fechaConvertirAFormato(r, "-"), n = fechaConvertirAFormato(n, "-"), $("#txtFechaIniControlMarcaciones").val(r), $("#txtFechaFinControlMarcaciones").val(n);
                                var o = {
                                    opcion: 0,
                                    idOrganigrama: 0,
                                    idArea: 0,
                                    idUbicacion: 0,
                                    idMaquina: 0,
                                    idRelaboral: e.id_relaboral,
                                    fechaIni: r,
                                    fechaFin: n
                                };
                                definirGrillaParaListaControlMarcacionesPorIdRelaboral(o)
                            } else {
                                var c = "Para acceder a la vista del registro, la persona debe haber tenido al menos un registro de relaci&oacute,n laboral que implica un estado ACTIVO o PASIVO.";
                                $("#divMsjePorError").html(""), $("#divMsjePorError").append(c), $("#divMsjeNotificacionError").jqxNotification("open")
                            }
                        }
                    } else {
                        var c = "Debe seleccionar un registro necesariamente.";
                        $("#divMsjePorError").html(""), $("#divMsjePorError").append(c), $("#divMsjeNotificacionError").jqxNotification("open")
                    }
                }), $("#downloadrowbutton").off(), $("#downloadrowbutton").on("click", function () {
                    $("#divTabControlMarcaciones").jqxTabs("enableAt", 0), $("#divTabControlMarcaciones").jqxTabs("disableAt", 1), $("#divTabControlMarcaciones").jqxTabs("enableAt", 2), $("#divTabControlMarcaciones").jqxTabs({selectedItem: 2}), $("#divTabControlMarcaciones").jqxTabs("disableAt", 3), $("#txtFechaIniCalculo").datepicker("update", ""), $("#txtFechaIniCalculo").val("").datepicker("update"), $("#txtFechaFinCalculo").datepicker("update", ""), $("#txtFechaFinCalculo").val("").datepicker("update");
                    var a = new Date, e = a.getDate(), t = a.getMonth() + 1, i = a.getFullYear(),
                        r = e + "-" + t + "-" + i;
                    $("#txtFechaIniDescarga").datepicker("update", r), $("#txtFechaFinDescarga").datepicker("update", r);
                    var n = "", o = $("#divGridRelaborales").jqxGrid("getselectedrowindex");
                    if (0 <= o) {
                        var c = $("#divGridRelaborales").jqxGrid("getrowdata", o);
                        null != c && (n = c.ci)
                    }
                    $("#txtCiDescarga").val(n), $("#txtCiDescarga").select().focus();
                    var l = {
                        opcion: 0,
                        ci: n,
                        idOrganigrama: 0,
                        idArea: 0,
                        idUbicacion: 0,
                        idMaquina: 0,
                        idRelaboral: 0,
                        fechaIni: "",
                        fechaFin: ""
                    };
                    definirGrillaDescargaMarcacionesRango(l)
                }), $("#turnrowbutton").off(), $("#turnrowbutton").on("click", function () {
                    var a = $("#divGridRelaborales").jqxGrid("getselectedrowindex");
                    if (0 <= a) {
                        var e = $("#divGridRelaborales").jqxGrid("getrowdata", a);
                        if (null != e) {
                            var t = $("#divGridRelaborales").jqxGrid("selectedrowindexes");
                            if (1 == t.length) {
                                $("#divTabControlMarcaciones").jqxTabs("enableAt", 0), $("#divTabControlMarcaciones").jqxTabs("disableAt", 1), $("#divTabControlMarcaciones").jqxTabs("disableAt", 2), $("#divTabControlMarcaciones").jqxTabs("enableAt", 3), $("#divTabControlMarcaciones").jqxTabs({selectedItem: 3}), $("#spanPrefijoCalendarioLaboral").html(""), $("#spanSufijoCalendarioLaboral").html(" Vrs. Calendario de Excepciones (Individual)");
                                var i = new Date, r = i.getDate(), n = i.getMonth(), o = i.getFullYear();
                                0 == e.estado && (r = e.fecha_baja.getDate(), n = e.fecha_baja.getMonth(), o = e.fecha_baja.getFullYear());
                                $("#calendar").html("");
                                var c = iniciarCalendarioLaboralPorRelaboralTurnosYExcepcionesParaVerAsignaciones(e, e.id_relaboral, 5, 0, 3, [], o, n, r);
                                sumarTotalHorasPorSemana(c)
                            } else {
                                var l = "Debe seleccionar un s&oacute;lo registro para la obtención de la vista de turnos laborales correspondientes.";
                                $("#divMsjePorError").html(""), $("#divMsjePorError").append(l), $("#divMsjeNotificacionError").jqxNotification("open")
                            }
                        } else {
                            var l = "Para acceder a la vista del registro, la persona debe haber tenido al menos un registro de relaci&oacute,n laboral que implica un estado ACTIVO o PASIVO.";
                            $("#divMsjePorError").html(""), $("#divMsjePorError").append(l), $("#divMsjeNotificacionError").jqxNotification("open")
                        }
                        switch ($("#tabFichaPersonalTurnAndExcept").jqxTabs({
                            theme: "oasis",
                            width: "100%",
                            height: "100%",
                            position: "top"
                        }), $("#tabFichaPersonalTurnAndExcept").jqxTabs({selectedItem: 0}), $(".ddNombresTurnAndExcept").html(e.nombres + "&nbsp;"), $(".ddCIAndNumComplementoExpdTurnAndExcept").html(e.ci + e.num_complemento + " " + e.expd + "&nbsp;"), $("#ddCargoTurnAndExcept").html(e.cargo + "&nbsp;"), $("#ddProcesoTurnAndExcept").html(e.proceso_codigo + "&nbsp;"), $("#ddFinanciamientoTurnAndExcept").html(e.condicion + " (Partida " + e.partida + ")"), $("#ddGerenciaTurnAndExcept").html(e.gerencia_administrativa + "&nbsp;"), "" != e.departamento_administrativo && null != e.departamento_administrativo ? ($("#ddDepartamentoTurnAndExcept").show(), $("#dtDepartamentoTurnAndExcept").show(), $("#ddDepartamentoTurnAndExcept").html(e.departamento_administrativo + "&nbsp;")) : ($("#dtDepartamentoTurnAndExcept").hide(), $("#ddDepartamentoTurnAndExcept").hide()), $("#ddUbicacionTurnAndExcept").html(e.ubicacion + "&nbsp;"), e.tiene_item) {
                            case 1:
                                $("#dtItemTurnAndExcept").show(), $("#ddItemTurnAndExcept").show(), $("#ddItemTurnAndExcept").html(e.item + "&nbsp;");
                                break;
                            case 0:
                                $("#dtItemTurnAndExcept").hide(), $("#ddItemTurnAndExcept").hide()
                        }
                        if ($("#ddNivelSalarialTurnAndExcept").html(e.nivelsalarial + "&nbsp;"), $("#ddHaberTurnAndExcept").html(e.sueldo + "&nbsp;"), $("#ddFechaIngTurnAndExcept").html(fechaConvertirAFormato(e.fecha_ing, "-") + "&nbsp;"), null != e.fecha_incor) {
                            var s = fechaConvertirAFormato(e.fecha_incor, "-");
                            $("#dtFechaIncorTurnAndExcept").show(), $("#ddFechaIncorTurnAndExcept").show(), $("#ddFechaIncorTurnAndExcept").html(s + "&nbsp;")
                        } else $("#dtFechaIncorTurnAndExcept").hide(), $("#ddFechaIncorTurnAndExcept").hide();
                        switch ($("#ddFechaIniTurnAndExcept").html(fechaConvertirAFormato(e.fecha_ini, "-") + "&nbsp;"), e.tiene_item) {
                            case 1:
                                $("#dtFechaFinTurnAndExcept").hide(), $("#ddFechaFinTurnAndExcept").hide();
                                break;
                            case 0:
                                $("#dtFechaFinTurnAndExcept").show(), $("#ddFechaFinTurnAndExcept").show(), $("#ddFechaFinTurnAndExcept").html(fechaConvertirAFormato(e.fecha_fin, "-") + "&nbsp;")
                        }
                        $("#ddEstadoDescripcionTurnAndExcept").html(e.estado_descripcion + "&nbsp;"), 0 == e.estado ? ($("#dtFechaBajaTurnAndExcept").show(), $("#ddFechaBajaTurnAndExcept").show(), $("#ddFechaBajaTurnAndExcept").html(fechaConvertirAFormato(e.fecha_baja, "-") + "&nbsp;"), $("#dtMotivoBajaTurnAndExcept").show(), $("#ddMotivoBajaTurnAndExcept").show(), $("#ddMotivoBajaTurnAndExcept").html(e.motivo_baja + "&nbsp;")) : ($("#dtFechaBajaTurnAndExcept").hide(), $("#ddFechaBajaTurnAndExcept").hide(), $("#dtMotivoBajaTurnAndExcept").hide(), $("#ddMotivoBajaTurnAndExcept").hide()), $("#ddNombresTurnAndExcept").html(e.nombres);
                        var d = obtenerRutaFoto(e.ci, e.num_complemento);
                        $("#imgFotoPerfilTurnAndExceptRelaboral").attr("src", d), $("#imgFotoPerfilContactoPerTurnAndExcept").attr("src", d), $("#imgFotoPerfilContactoInstTurnAndExcept").attr("src", d), $("#imgFotoPerfilTurnAndExcept").attr("src", d), cargarPersonasContactosMarcaciones(2, e.id_persona)
                    } else {
                        var l = "Debe seleccionar un registro necesariamente.";
                        $("#divMsjePorError").html(""), $("#divMsjePorError").append(l), $("#divMsjeNotificacionError").jqxNotification("open")
                    }
                })
            },
            columns: [{
                text: "Nro.",
                sortable: !1,
                filterable: !1,
                editable: !1,
                groupable: !1,
                draggable: !1,
                resizable: !1,
                columntype: "number",
                width: 50,
                cellsalign: "center",
                align: "center",
                pinned: !0,
                cellsrenderer: rownumberrenderer
            }, {
                text: "Nombres y Apellidos",
                columntype: "textbox",
                filtertype: "input",
                datafield: "nombres",
                width: 215,
                align: "center",
                pinned: !0,
                hidden: !1
            }, {
                text: "CI",
                columntype: "textbox",
                filtertype: "input",
                datafield: "ci",
                width: 90,
                cellsalign: "center",
                align: "center",
                pinned: !0,
                hidden: !1
            }, {
                text: "Exp",
                filtertype: "checkedlist",
                datafield: "expd",
                width: 40,
                cellsalign: "center",
                align: "center",
                pinned: !0,
                hidden: !1
            }, {
                text: "Ubicaci&oacute;n",
                filtertype: "checkedlist",
                datafield: "ubicacion",
                width: 150,
                cellsalign: "center",
                align: "center",
                hidden: !1
            }, {
                text: "Condici&oacute;n",
                filtertype: "checkedlist",
                datafield: "condicion",
                width: 150,
                cellsalign: "center",
                align: "center",
                hidden: !1
            }, {
                text: "Estado",
                filtertype: "checkedlist",
                datafield: "estado_descripcion",
                width: 100,
                cellsalign: "center",
                align: "center",
                hidden: !1,
                cellclassname: cellclass
            }, {
                text: "Gerencia",
                filtertype: "checkedlist",
                datafield: "gerencia_administrativa",
                width: 220,
                align: "center",
                hidden: !1
            }, {
                text: "Departamento",
                filtertype: "checkedlist",
                datafield: "departamento_administrativo",
                width: 220,
                align: "center",
                hidden: !1
            }, {
                text: "&Aacute;rea",
                filtertype: "checkedlist",
                datafield: "area",
                width: 220,
                align: "center",
                hidden: !1
            }, {
                text: "Proceso",
                filtertype: "checkedlist",
                datafield: "proceso_codigo",
                width: 220,
                cellsalign: "center",
                align: "center",
                hidden: !1
            }, {
                text: "Fuente",
                filtertype: "checkedlist",
                datafield: "fin_partida",
                width: 220,
                cellsalign: "center",
                align: "center",
                hidden: !1
            }, {
                text: "Nivel Salarial",
                filtertype: "checkedlist",
                datafield: "nivelsalarial",
                width: 220,
                align: "center",
                hidden: !1
            }, {
                text: "Cargo",
                columntype: "textbox",
                filtertype: "input",
                datafield: "cargo",
                width: 215,
                align: "center",
                hidden: !1
            }, {
                text: "Haber",
                filtertype: "checkedlist",
                datafield: "sueldo",
                width: 100,
                cellsalign: "right",
                align: "center",
                hidden: !1
            }, {
                text: "Fecha Ingreso",
                datafield: "fecha_ing",
                filtertype: "range",
                width: 100,
                cellsalign: "center",
                cellsformat: "dd-MM-yyyy",
                align: "center",
                hidden: !1
            }, {
                text: "Fecha Inicio",
                datafield: "fecha_ini",
                filtertype: "range",
                width: 100,
                cellsalign: "center",
                cellsformat: "dd-MM-yyyy",
                align: "center",
                hidden: !1
            }, {
                text: "Fecha Incor.",
                datafield: "fecha_incor",
                filtertype: "range",
                width: 100,
                cellsalign: "center",
                cellsformat: "dd-MM-yyyy",
                align: "center",
                hidden: !1
            }, {
                text: "Fecha Fin",
                datafield: "fecha_fin",
                filtertype: "range",
                width: 100,
                cellsalign: "center",
                cellsformat: "dd-MM-yyyy",
                align: "center",
                hidden: !1
            }, {
                text: "Fecha Baja",
                datafield: "fecha_baja",
                filtertype: "range",
                width: 100,
                cellsalign: "center",
                cellsformat: "dd-MM-yyyy",
                align: "center",
                hidden: !1
            }, {text: "Motivo Baja", datafield: "motivo_baja", width: 100, hidden: !1}, {
                text: "Observacion",
                datafield: "observacion",
                width: 100,
                hidden: !1
            }]
        });
        $("#jqxlistbox").jqxListBox({
            source: [{label: "Ubicaci&oacute;n", value: "ubicacion", checked: !0}, {
                label: "Condici&oacute;n",
                value: "condicion",
                checked: !0
            }, {label: "Estado", value: "estado_descripcion", checked: !0}, {
                label: "Nombres y Apellidos",
                value: "nombres",
                checked: !0
            }, {label: "CI", value: "ci", checked: !0}, {label: "Exp", value: "expd", checked: !0}, {
                label: "Gerencia",
                value: "gerencia_administrativa",
                checked: !0
            }, {label: "Departamento", value: "departamento_administrativo", checked: !0}, {
                label: "&Aacute;rea",
                value: "area",
                checked: !0
            }, {label: "proceso", value: "proceso_codigo", checked: !0}, {
                label: "Fuente",
                value: "fin_partida",
                checked: !0
            }, {label: "Nivel Salarial", value: "nivelsalarial", checked: !0}, {
                label: "Cargo",
                value: "cargo",
                checked: !0
            }, {label: "Haber", value: "sueldo", checked: !0}, {
                label: "Fecha Ingreso",
                value: "fecha_ing",
                checked: !0
            }, {label: "Fecha Inicio", value: "fecha_ini", checked: !0}, {
                label: "Fecha Incor.",
                value: "fecha_incor",
                checked: !0
            }, {label: "Fecha Fin", value: "fecha_fin", checked: !0}, {
                label: "Fecha Baja",
                value: "fecha_baja",
                checked: !0
            }, {label: "Motivo Baja", value: "motivo_baja", checked: !0}, {
                label: "Observacion",
                value: "observacion",
                checked: !0
            }], width: "100%", height: 430, checkboxes: !0
        }), $("#jqxlistbox").on("checkChange", function (a) {
            $("#divGridRelaborales").jqxGrid("beginupdate"), a.args.checked ? $("#divGridRelaborales").jqxGrid("showcolumn", a.args.value) : $("#divGridRelaborales").jqxGrid("hidecolumn", a.args.value), $("#divGridRelaborales").jqxGrid("endupdate")
        })
    }()
}

$().ready(function () {
    var i = {
        opcion: 1,
        idOrganigrama: 0,
        idArea: 0,
        idUbicacion: 0,
        idMaquina: 0,
        idRelaboral: 0,
        fechaIni: "",
        fechaFin: ""
    };
    $("#divTabControlMarcaciones").jqxTabs("theme", "oasis"), $("#divTabControlMarcaciones").jqxTabs("enableAt", 1), $("#divTabControlMarcaciones").jqxTabs("disableAt", 1), $("#divTabControlMarcaciones").jqxTabs("disableAt", 2), $("#divTabControlMarcaciones").jqxTabs("disableAt", 3), cargarGestionesRelaborales((new Date).getFullYear()), definirGrillaParaListaRelaboralesParaMarcaciones($("#lstGestion").val()), $("#btnDescargarMarcaciones").on("click", function () {
        var a = $("#txtCiDescarga").val(), e = $("#txtFechaIniDescarga").val(), t = $("#txtFechaFinDescarga").val();
        if ("" != e && "" != t) {
            var i = obtenerCantidadDeMesesEntreFechas(e, t);
            if (0 < i && i < 5) descargarMarcaciones(0, a, e, t), $("#btnBuscarMarcaciones").click(); else {
                var r = "Han transcurrido " + i + " meses entre la fecha de inicio y finalizaci&oacute;n del rango solicitado. La m&aacute;xima cantidad de meses admitida debe ser menor a dos meses.";
                $("#divMsjePorWarning").html(""), $("#divMsjePorWarning").append(r), $("#divMsjeNotificacionWarning").jqxNotification("open")
            }
        } else {
            r = "Debe seleccionar necesariamente la fecha de inicio y finalizaci&oacute;n para la descarga.";
            $("#divMsjePorWarning").html(""), $("#divMsjePorWarning").append(r), $("#divMsjeNotificacionWarning").jqxNotification("open"), "" == $("#txtFechaIniDescarga").val() ? $("#txtFechaIniDescarga").focus() : $("#txtFechaFinDescarga").focus()
        }
    }), $("#btnBuscarPorGestion").click(function () {
        definirGrillaParaListaRelaboralesParaMarcaciones($("#lstGestion").val())
    }), $("#btnExportarTodasMarcacionesRangoExcel").on("click", function () {
        var a = $("#txtFechaIniControlMarcaciones").val(), e = $("#txtFechaFinControlMarcaciones").val();
        if ("" != a && "" != e) exportarReporteControlMarcaciones(3, 0, a, e); else {
            if ("" != a && "" != e) var t = "Debe seleccionar la fecha de inicio y finalizaci&oacute;n necesariamente para la obtenci&oacute;n del reporte solicitado.";
            $("#divMsjePorWarning").html(""), $("#divMsjePorWarning").append(t), $("#divMsjeNotificacionWarning").jqxNotification("open"), $("#divListBoxMarcaciones").focus()
        }
    }), $("#btnGuardarControlExcepcionNew").on("click", function () {
        validaFormularioControlExcepciones(1) && (guardaMisControlExcepciones(1) && ($("#divTabControlMarcaciones").jqxTabs("enableAt", 0), $("#divTabControlMarcaciones").jqxTabs("enableAt", 1), $("#divTabControlMarcaciones").jqxTabs("disableAt", 2), $("#divTabControlMarcaciones").jqxTabs({selectedItem: 1}), $("#msjs-alert").hide()))
    }), $("#btnGuardarControlExcepcionEdit").on("click", function () {
        validaFormularioControlExcepciones(2) && (guardaMisControlExcepciones(2) && ($("#divTabControlMarcaciones").jqxTabs("enableAt", 0), $("#divTabControlMarcaciones").jqxTabs("enableAt", 1), $("#divTabControlMarcaciones").jqxTabs("disableAt", 2), $("#divTabControlMarcaciones").jqxTabs({selectedItem: 1}), $("#msjs-alert").hide()))
    }), $("#btnGuardarBaja").click(function () {
        validaFormularioPorBajaRegistro() && guardarRegistroBaja()
    }), $("#btnVolverDesdeMarcaciones").on("click", function () {
        $("#divTabControlMarcaciones").jqxTabs("enableAt", 0), $("#divTabControlMarcaciones").jqxTabs("disableAt", 1), $("#divTabControlMarcaciones").jqxTabs("disableAt", 2), $("#divTabControlMarcaciones").jqxTabs("disableAt", 3), $("#msjs-alert").hide()
    }), $("#btnExportarControlMarcacionesExcel").on("click", function () {
        var a = $("#divListBoxControlMarcaciones").jqxListBox("getCheckedItems"), t = 0;
        $.each(a, function (a, e) {
            t++
        });
        var e = $("#hdnIdRelaboralControlMarcaciones").val(), i = $("#txtFechaIniControlMarcaciones").val(),
            r = $("#txtFechaFinControlMarcaciones").val();
        if (0 < e && 0 < t && "" != i && "" != r) exportarReporteControlMarcaciones(1, e, i, r); else {
            var n = "";
            0 == t && (n = "Debe seleccionar al menos una columna para la obtención del reporte solicitado."), 0 == t && "" != i && "" != r && (n += "</br>"), "" != i && "" != r && (n += "Debe seleccionar la fecha de inicio y finalizaci&oacute;n necesariamente para la obtenci&oacute;n del reporte solicitado."), $("#divMsjePorWarning").html(""), $("#divMsjePorWarning").append(n), $("#divMsjeNotificacionWarning").jqxNotification("open"), $("#divListBoxMarcaciones").focus()
        }
    }), $("#btnExportarDescargasExcel").click(function () {
        var a = $("#divListBoxDescargasRango").jqxListBox("getCheckedItems"), t = 0;
        $.each(a, function (a, e) {
            t++
        });
        var e = $("#txtCiDescarga").val(), i = $("#txtFechaIniDescarga").val(), r = $("#txtFechaFinDescarga").val();
        if ("" != i && null != i && "" != r && null != r && 0 < t) exportarReporteDescargaMarcacionesRango(1, e, i, r); else {
            $("#divMsjePorWarning").html(""), $("#divMsjePorWarning").append("Debe seleccionar necesariamente la fecha de inicio y finalizaci&oacute;n para la obtención del reporte solicitado."), $("#divMsjeNotificacionWarning").jqxNotification("open"), $("#divListBoxCalculos").focus()
        }
    }), $("#btnExportarControlMarcacionesPDF").on("click", function () {
        var a = $("#divListBoxControlMarcaciones").jqxListBox("getCheckedItems"), t = 0;
        $.each(a, function (a, e) {
            t++
        });
        var e = $("#hdnIdRelaboralControlMarcaciones").val(), i = $("#txtFechaIniControlMarcaciones").val(),
            r = $("#txtFechaFinControlMarcaciones").val();
        if (0 < e && 0 < t) exportarReporteControlMarcaciones(2, e, i, r); else {
            $("#divMsjePorWarning").html(""), $("#divMsjePorWarning").append("Debe seleccionar al menos una columna para la obtención del reporte solicitado."), $("#divMsjeNotificacionWarning").jqxNotification("open"), $("#divListBoxMarcaciones").focus()
        }
    }), $("#btnExportarDescargasPDF").on("click", function () {
        var a = $("#divListBoxDescargasRango").jqxListBox("getCheckedItems"), t = 0;
        $.each(a, function (a, e) {
            t++
        });
        var e = $("#txtCiDescarga").val(), i = $("#txtFechaIniDescarga").val(), r = $("#txtFechaFinDescarga").val();
        if ("" != i && "" != r && 0 < t) exportarReporteDescargaMarcacionesRango(2, e, i, r); else {
            $("#divMsjePorWarning").html(""), $("#divMsjePorWarning").append("Debe seleccionar necesariamente la fecha de inicio y finalizaci&oacute;n para la obtención del reporte solicitado."), $("#divMsjeNotificacionWarning").jqxNotification("open"), $("#txtFechaIniDescarga").focus()
        }
    }), $("#btnBuscarControlMarcaciones").off(), $("#btnBuscarControlMarcaciones").on("click", function () {
        var a = $("#txtFechaIniControlMarcaciones").val(), e = $("#txtFechaFinControlMarcaciones").val(),
            t = $("#hdnIdRelaboralControlMarcaciones").val();
        if ("" != a && "" != e && 0 < t) i = {
            opcion: 1,
            ci: 0,
            idOrganigrama: 0,
            idArea: 0,
            idUbicacion: 0,
            idMaquina: 0,
            idRelaboral: t,
            fechaIni: a,
            fechaFin: e
        }, definirGrillaParaListaControlMarcacionesPorIdRelaboral(i); else {
            $("#divMsjePorWarning").html(""), $("#divMsjePorWarning").append("Debe seleccionar necesariamente la fecha de inicio y finalizaci&oacute;n para la obtención del reporte solicitado."), $("#divMsjeNotificacionWarning").jqxNotification("open"), $("#txtFechaIniControlMarcaciones").focus()
        }
    }), $("#btnBuscarMarcaciones").off(), $("#btnBuscarMarcaciones").on("click", function () {
        var a = $("#txtCiDescarga").val(), e = $("#txtFechaIniDescarga").val(), t = $("#txtFechaFinDescarga").val();
        if ("" != e && "" != t) i = {
            opcion: 1,
            ci: a,
            idOrganigrama: 0,
            idArea: 0,
            idUbicacion: 0,
            idMaquina: 0,
            idRelaboral: 0,
            fechaIni: e,
            fechaFin: t
        }, definirGrillaDescargaMarcacionesRango(i); else {
            $("#divMsjePorWarning").html(""), $("#divMsjePorWarning").append("Debe seleccionar necesariamente la fecha de inicio y finalizaci&oacute;n para la obtención del reporte solicitado."), $("#divMsjeNotificacionWarning").jqxNotification("open"), $("#txtFechaIniDescarga").focus()
        }
    }), $("#chkAllCols").click(function () {
        1 == this.checked ? $("#jqxlistbox").jqxListBox("checkAll") : $("#jqxlistbox").jqxListBox("uncheckAll")
    }), $("#btnImprimirCalendarioLaboralAndExcept").on("click", function () {
        $("#page-content").printArea({mode: "popup", popClose: !1})
    }), $("#btnImprimirCalendarioDetalle").on("click", function () {
        $("#divCalendarioDetalles").printArea({mode: "popup", popClose: !1})
    }), $("#chkAi").on("click", function () {
        var a = $("#txtCargoMovilidad").val();
        ("object" == jQuery.type(a) && (a = String(a.label)), null != (a += "") && "" != a) && (1 == this.checked ? a.indexOf("a.i.") < 0 && (a += " a.i.", $("#txtCargoMovilidad").val(a)) : 0 < a.indexOf("a.i.") && (a = a.replace("a.i.", "").trim(), $("#txtCargoMovilidad").val(a)))
    }), $("#btnCalcular").on("click", function () {
        var a = $("#txtFechaIniCalculo").val(), e = $("#txtFechaFinCalculo").val();
        if ("" != a && "" != e) definirGrillaMarcacionesYCalculos({
            idOrganigrama: 0,
            idArea: 0,
            idUbicacion: 0,
            idMaquina: 0,
            idRelaboral: 0,
            fechaIni: a,
            fechaFin: e
        }); else {
            $("#divMsjePorError").html(""), $("#divMsjePorError").append("Debe seleccionar las fechas para el rango en el cual se obtendr&aacute; el c&aacute;lculo."), $("#divMsjeNotificacionError").jqxNotification("open"), "" != a ? $("#txtFechaFinCalculo").focus() : $("#txtFechaIniCalculo").focus()
        }
    }), $("#liList,#btnVolverDesdeControlMarcaciones,#btnVolverDesdeDescargas,#btnCancelarTurnAndExcept").click(function () {
        $("#divTabControlMarcaciones").jqxTabs("enableAt", 0), $("#divTabControlMarcaciones").jqxTabs({selectedItem: 0}), $("#divTabControlMarcaciones").jqxTabs("disableAt", 1), $("#divTabControlMarcaciones").jqxTabs("disableAt", 2), $("#divTabControlMarcaciones").jqxTabs("disableAt", 3), $("#msjs-alert").hide()
    }), $("#liList").click(function () {
        $("#divTabControlMarcaciones").jqxTabs("enableAt", 0), $("#divTabControlMarcaciones").jqxTabs("disableAt", 1), $("#divTabControlMarcaciones").jqxTabs({selectedItem: 0}), $("#divTabControlMarcaciones").jqxTabs("disableAt", 2)
    }), $(".numeral").keyup(function (a) {
        "" != $(this).val() && $(this).val($(this).val().replace(/[^0-9]/g, ""))
    }), $(".literal").keyup(function (a) {
        "" != $(this).val() && $(this).val($(this).val().replace(/[^A-Z,a-z,ñ,Ñ, ]/g, ""))
    }), $("#divMsjeNotificacionError").jqxNotification({
        width: "100%",
        position: "bottom-right",
        opacity: .9,
        autoOpen: !1,
        animationOpenDelay: 800,
        autoClose: !0,
        autoCloseDelay: 7e3,
        template: "error"
    }), $("#divMsjeNotificacionWarning").jqxNotification({
        width: "100%",
        position: "bottom-right",
        opacity: .9,
        autoOpen: !1,
        animationOpenDelay: 800,
        autoClose: !0,
        autoCloseDelay: 7e3,
        template: "warning"
    }), $("#divMsjeNotificacionSuccess").jqxNotification({
        width: "100%",
        position: "bottom-right",
        opacity: .9,
        autoOpen: !1,
        animationOpenDelay: 800,
        autoClose: !0,
        autoCloseDelay: 7e3,
        template: "success"
    }), $(document).keypress(OperaEvento), $(document).keyup(OperaEvento)
});
var rownumberrenderer = function (a, e, t, i, r, n) {
    return "<div align='center'>" + (a + 1) + "</div>"
};

function OperaEvento(a) {
    "keyup" != a.type && "keydown" != a.type || "27" != a.which || ($("#divTabControlMarcaciones").jqxTabs("enableAt", 0), $("#divTabControlMarcaciones").jqxTabs("disableAt", 1), $("#divTabControlMarcaciones").jqxTabs("disableAt", 2), $("#divTabControlMarcaciones").jqxTabs("disableAt", 3), $("#divTabControlMarcaciones").jqxTabs("disableAt", 4), $("#divTabControlMarcaciones").jqxTabs("disableAt", 5), $("#divTabControlMarcaciones").jqxTabs({selectedItem: 0}))
}

function procesaTextoAFecha(a, e) {
    var t = a.split(e);
    return (a = new Date(t[1] + "/" + t[0] + "/" + t[2])).getTime()
}

function obtenerRutaFoto(a, e) {
    var t = "/images/perfil-profesional.jpg";
    return "" != a && $.ajax({
        url: "/relaborales/obtenerrutafoto/",
        type: "POST",
        datatype: "json",
        async: !1,
        cache: !1,
        data: {ci: a, num_complemento: e},
        success: function (a) {
            var e = jQuery.parseJSON(a);
            1 == e.result && (t = e.ruta)
        },
        error: function () {
            alert("Se ha producido un error Inesperado")
        }
    }), t
}

function fechaHoy(a, e) {
    "" == a && (a = "-");
    var t = new Date, i = t.getDate().toString(), r = (t.getMonth() + 1).toString(), n = 1 === i.length ? "0" + i : i,
        o = 1 === r.length ? "0" + r : r;
    if ("dd-mm-yyyy" == e) var c = n + a + o + a + t.getFullYear(); else if ("mm-dd-yyyy" == e) c = o + a + n + a + t.getFullYear(); else c = t;
    return c
}

var cellclass = function (a, e, t) {
    return "ACTIVO" == t ? "verde" : "EN PROCESO" == t ? "amarillo" : "PASIVO" == t ? "rojo" : ""
};

function obtenerTodosHorariosRegistradosEnCalendarioRelaboralParaVerAsignaciones(a, e, t, F, i, r, n) {
    var T = [], o = new Date, j = (o.getDate(), o.getMonth(), o.getFullYear(), !0);
    switch (t) {
        case 1:
        case 2:
            j = !0
    }
    return $.ajax({
        url: "/calendariolaboral/listallregisteredbyrelaboralmixto",
        type: "POST",
        datatype: "json",
        async: !1,
        cache: !1,
        data: {id: a, id_perfillaboral: e, fecha_ini: i, fecha_fin: r},
        success: function (a) {
            var e = jQuery.parseJSON(a);
            0 < e.length && $.each(e, function (a, e) {
                var t = 0, i = "00:00:00", r = "24:00:00", n = "#000000", o = "DESCANSO",
                    c = (e.perfil_laboral, e.perfil_laboral_grupo);
                null != e.id_horariolaboral ? (t = e.id_horariolaboral, o = e.horario_nombre, i = e.hora_entrada.split(":"), r = e.hora_salida.split(":"), n = e.color) : (i = i.split(":"), r = r.split(":"));
                var l = e.calendario_fecha_ini.split("-"), s = l[0], d = l[1] - 1, p = l[2], u = i[0], h = i[1],
                    b = (i[2], e.calendario_fecha_fin.split("-")), m = b[0], f = b[1] - 1, $ = b[2], g = r[0], v = r[1],
                    x = (r[2], "r_");
                0 == t && (x = "d_");
                var y = n;
                F || (y = "#000000", x = "b_"), T.push({
                    id: e.id_calendariolaboral,
                    className: x + t,
                    title: o,
                    start: new Date(s, d, p, u, h),
                    end: new Date(m, f, $, g, v),
                    allDay: j,
                    color: n,
                    editable: F,
                    borderColor: y,
                    horas_laborales: e.horas_laborales,
                    dias_laborales: e.dias_laborales,
                    hora_entrada: e.hora_entrada,
                    hora_salida: e.hora_salida
                })
            })
        }
    }), T
}

function ImageExist(a) {
    var e = new Image;
    return e.src = a, 0 != e.height
}

function fechaConvertirAFormato(a, e) {
    "" != e && null != e && null != e || (e = "-");
    var t = a, i = t.getDate(), r = t.getMonth(), n = "", o = "";
    return i < 10 && (n = "0"), (r += 1) < 10 && (o = "0"), n + i + e + o + r + e + t.getFullYear()
}

function iniciarCalendarioLaboralPorRelaboralTurnosYExcepcionesParaVerAsignaciones(h, b, a, m, f, e, t, i, r) {
    f = parseInt(f);
    var g = [], v = 7, n = ($(".calendar-events"), "prev,next"), o = "year", c = !0, l = !0, s = !0, d = !0;
    switch (a) {
        case 1:
        case 2:
        case 3:
            switch (f) {
                case 1:
                case 2:
                    break;
                case 3:
                    n = "", o = "year"
            }
            break;
        case 4:
            break;
        case 5:
            s = l = c = !1
    }
    switch (f) {
        case 1:
        case 2:
            d = !1, !(v = 5)
    }
    return $("#calendar").fullCalendar({
        header: {left: n, center: "title", right: o},
        year: t,
        month: i,
        date: r,
        firstDay: 1,
        weekends: d,
        editable: c,
        droppable: l,
        selectable: s,
        weekNumbers: !0,
        weekNumberTitle: "#S",
        timeFormat: "H(:mm)",
        drop: function (a, e) {
            var t = $(this).data("eventObject"), i = $.extend({}, t);
            i.start = a, $("#calendar").fullCalendar("renderEvent", i, !0), sumarTotalHorasPorSemana(g)
        },
        eventDrop: function (a, e, t, i, r) {
            sumarTotalHorasPorSemana(g)
        },
        events: e,
        eventClick: function (r, a, e) {
            var n = r.className + "", t = n.split("_"), i = t[1];
            n = t[0];
            var o = 0;
            null != r.id && (o = r.id);
            var c = fechaConvertirAFormato(r.start, "-"), l = c, s = r.start;
            null != r.end && "" != r.end && (l = fechaConvertirAFormato(r.end, "-"), s = r.end);
            var d = r.start, p = s, u = s;
            ($("#txtHorarioFechaIni").datepicker("setDate", r.start), $("#txtHorarioFechaFin").datepicker("setDate", s), $("#txtHorarioFechaIni").datepicker({
                format: "dd-mm-yyyy",
                default: r.start,
                weekStart: 1,
                startDate: d,
                endDate: p,
                autoclose: !0
            }).on("changeDate", function (a) {
                (d = new Date(a.date.valueOf())).setDate(d.getDate(new Date(a.date.valueOf()))), $("#txtHorarioFechaFin").datepicker("setStartDate", d)
            }), $("#txtHorarioFechaFin").datepicker({
                default: s,
                weekStart: 1,
                startDate: d,
                endDate: u,
                autoclose: !0
            }).on("changeDate", function (a) {
                (p = new Date(a.date.valueOf())).setDate(p.getDate(new Date(a.date.valueOf()))), $("#txtHorarioFechaIni").datepicker("setEndDate", p)
            }), 0 < i < 5) ? cargarModalHorario(i) ? ("b" == n ? ($("#btnDescartarHorario").hide(), $("#btnGuardarModificacionHorario").hide(), $("#txtHorarioFechaIni").prop("disabled", "disabled"), $("#txtHorarioFechaFin").prop("disabled", "disabled")) : ($("#btnDescartarHorario").show(), $("#txtHorarioFechaIni").prop("disabled", !1), $("#txtHorarioFechaFin").prop("disabled", !1)), $("#popupDescripcionHorario").modal("show"), $("#btnDescartarHorario").off(), $("#btnDescartarHorario").on("click", function () {
                switch (n) {
                    case"r":
                    case"d":
                        bajaTurnoEnCalendario(o) && ($("#calendar").fullCalendar("removeEvents", r._id), $("#popupDescripcionHorario").modal("hide"));
                        break;
                    case"n":
                        $("#calendar").fullCalendar("removeEvents", r._id), $("#popupDescripcionHorario").modal("hide")
                }
                sumarTotalHorasPorSemana(g)
            }), $("#btnGuardarModificacionHorario").off(), $("#btnGuardarModificacionHorario").on("click", function () {
                switch (n) {
                    case"r":
                    case"n":
                        if (c != $("#txtHorarioFechaIni").val() || l != $("#txtHorarioFechaFin").val()) {
                            $("#calendar").fullCalendar("removeEvents", r._id), $("#popupDescripcionHorario").modal("hide");
                            var a = $("#txtHorarioFechaIni").val(), e = $("#txtHorarioFechaFin").val(),
                                t = a.split("-"), i = e.split("-");
                            a = t[2] + "-" + t[1] + "-" + t[0], e = i[2] + "-" + i[1] + "-" + i[0], addEvent = {
                                id: r.id,
                                title: r.title,
                                className: r.className,
                                start: a,
                                end: e,
                                color: r.color,
                                editable: !0,
                                hora_entrada: r.hora_entrada,
                                hora_salida: r.hora_salida
                            }, $("#calendar").fullCalendar("renderEvent", addEvent, !0)
                        }
                        $("#popupDescripcionHorario").modal("hide")
                }
                sumarTotalHorasPorSemana(g)
            })) : alert("Error al determinar los datos del horario.") : alert("El registro corresponde a un periodo de excepción o salida")
        },
        eventResize: function (a, e, t) {
            sumarTotalHorasPorSemana(g)
        },
        viewRender: function (a) {
            switch (a.name) {
                case"month":
                    removerColumnaSumaTotales(), agregarColumnaSumaTotales(v);
                    var n = "", o = "";
                    g = [];
                    var c = 0, l = 0, e = ["mon", "tue", "wed", "thu", "fri", "sat", "sun"];
                    $.each(e, function (a, e) {
                        l = 0, $("td.fc-" + e).map(function (a, e) {
                            l++;
                            var t = $(this).data("date"), i = $(this).data("date");
                            if (null != t) {
                                var r = t.split("-");
                                switch (t = r[2] + "-" + r[1] + "-" + r[0], c = r[0], l) {
                                    case 1:
                                        "" == n && (n = t), g.push({semana: 1, fecha: t});
                                        break;
                                    case 2:
                                        g.push({semana: 2, fecha: t});
                                        break;
                                    case 3:
                                        g.push({semana: 3, fecha: t});
                                        break;
                                    case 4:
                                        g.push({semana: 4, fecha: t});
                                        break;
                                    case 5:
                                        g.push({semana: 5, fecha: t});
                                        break;
                                    case 6:
                                        o = t, g.push({semana: 6, fecha: t})
                                }
                                i < $.fullCalendar.formatDate(new Date, "yyyy-MM-dd") && $(this).css("background-color", "#efefef")
                            }
                        })
                    });
                    var t = "", i = "", r = $("#calendar").fullCalendar("getDate"),
                        s = (t = fechaConvertirAFormato(r, "-")).split("-");
                    i = obtenerUltimoDiaMes(t = "01-" + s[1] + "-" + s[2]), $("#hdnFechaInicialCalendario").val(t), $("#hdnFechaFinalCalendario").val(i), cargarGrillaAsignacionIndividualFechasUbicacionEstacion(m, b, n, o);
                    var d = obtenerArrExcepcionesEnCalendarioPorRango(h, a.name, v, n, o);
                    $("#calendar").fullCalendar("removeEvents");
                    var p = obtenerTodosHorariosRegistradosEnCalendarioRelaboralParaVerAsignaciones(b, 0, f, !1, n, o, 0);
                    $("#calendar").fullCalendar("addEventSource", p), $("#calendar").fullCalendar("addEventSource", d);
                    var u = obtenerFeriadosRangoFechas(0, 0, c, n, o);
                    $.each(e, function (a, e) {
                        l = 0, $("td.fc-" + e).map(function (a, e) {
                            l++;
                            $(this).data("date");
                            var t = $(this).data("date"), i = "", r = "", n = $(this);
                            $.each(u, function (a, e) {
                                i = e.fecha_ini, r = e.fecha_fin;
                                if (procesaTextoAFecha(t, "-") <= procesaTextoAFecha(r, "-") && procesaTextoAFecha(t, "-") >= procesaTextoAFecha(i, "-")) {
                                    $(".fc-day-content");
                                    n.append("<h6>(f) " + e.feriado + "</h6>"), n.css("background-color", "orange")
                                }
                            })
                        })
                    }), sumarTotalHorasPorSemana(g);
                    break;
                case"agendaWeek":
                    t = $("#calendar").fullCalendar("getView").start, i = obtenerFechaMasDias(t = fechaConvertirAFormato(t, "-"), v - 1), $("#hdnFechaInicialCalendario").val(t), $("#hdnFechaFinalCalendario").val(i), cargarGrillaAsignacionIndividualFechasUbicacionEstacion(m, b, t, i);
                    break;
                case"agendaDay":
                    r = $("#calendar").fullCalendar("getDate");
                    i = t = fechaConvertirAFormato(r, "-"), $("#hdnFechaInicialCalendario").val(t), $("#hdnFechaFinalCalendario").val(i), cargarGrillaAsignacionIndividualFechasUbicacionEstacion(m, b, t, i)
            }
        }
    }), g
}

function sumarTotalHorasPorSemana(e) {
    var a = $("#calendar").fullCalendar("clientEvents"), o = 0, c = 0, l = 0, s = 0, d = 0, p = 0;
    $("#spSumaSemana1").html(0), $("#spSumaSemana2").html(0), $("#spSumaSemana3").html(0), $("#spSumaSemana4").html(0), $("#spSumaSemana5").html(0), $("#spSumaSemana6").html(0), $("#tdSumaSemana1").css("background-color", "white"), $("#tdSumaSemana2").css("background-color", "white"), $("#tdSumaSemana3").css("background-color", "white"), $("#tdSumaSemana4").css("background-color", "white"), $("#tdSumaSemana5").css("background-color", "white"), $("#tdSumaSemana6").css("background-color", "white"), $.each(a, function (a, t) {
        var i = $.fullCalendar.formatDate(t.start, "dd-MM-yyyy"), r = $.fullCalendar.formatDate(t.end, "dd-MM-yyyy");
        "" == r && (r = i);
        var n = "-";
        $.each(e, function (a, e) {
            1 == e.semana && procesaTextoAFecha(i, n) <= procesaTextoAFecha(e.fecha, n) && procesaTextoAFecha(e.fecha, n) <= procesaTextoAFecha(r, n) && (o += parseFloat(t.horas_laborales)), 2 == e.semana && procesaTextoAFecha(i, n) <= procesaTextoAFecha(e.fecha, n) && procesaTextoAFecha(e.fecha, n) <= procesaTextoAFecha(r, n) && (c += parseFloat(t.horas_laborales)), 3 == e.semana && procesaTextoAFecha(i, n) <= procesaTextoAFecha(e.fecha, n) && procesaTextoAFecha(e.fecha, n) <= procesaTextoAFecha(r, n) && (l += parseFloat(t.horas_laborales)), 4 == e.semana && procesaTextoAFecha(i, n) <= procesaTextoAFecha(e.fecha, n) && procesaTextoAFecha(e.fecha, n) <= procesaTextoAFecha(r, n) && (s += parseFloat(t.horas_laborales)), 5 == e.semana && procesaTextoAFecha(i, n) <= procesaTextoAFecha(e.fecha, n) && procesaTextoAFecha(e.fecha, n) <= procesaTextoAFecha(r, n) && (d += parseFloat(t.horas_laborales)), 6 == e.semana && procesaTextoAFecha(i, n) <= procesaTextoAFecha(e.fecha, n) && procesaTextoAFecha(e.fecha, n) <= procesaTextoAFecha(r, n) && (p += parseFloat(t.horas_laborales))
        })
    }), $("#spSumaSemana1").html(o.toFixed(2)), $("#spSumaSemana2").html(c.toFixed(2)), $("#spSumaSemana3").html(l.toFixed(2)), $("#spSumaSemana4").html(s.toFixed(2)), $("#spSumaSemana5").html(d.toFixed(2)), $("#spSumaSemana6").html(p.toFixed(2));
    var t = (c + l + s) / 3;
    $("#spSumaPromedioTresSemanas").html(t.toFixed(2));
    48 < o ? $("#tdSumaSemana1").css("background-color", "#FF4000") : $("#tdSumaSemana1").css("background-color", "white"), 48 < c ? $("#tdSumaSemana2").css("background-color", "#FF4000") : $("#tdSumaSemana2").css("background-color", "#efefef"), 48 < l ? $("#tdSumaSemana3").css("background-color", "#FF4000") : $("#tdSumaSemana3").css("background-color", "#efefef"), 48 < s ? $("#tdSumaSemana4").css("background-color", "#FF4000") : $("#tdSumaSemana4").css("background-color", "#efefef"), 48 < d ? $("#tdSumaSemana5").css("background-color", "#FF4000") : $("#tdSumaSemana5").css("background-color", "white"), 48 < p ? $("#tdSumaSemana6").css("background-color", "#FF4000") : $("#tdSumaSemana6").css("background-color", "white"), 48 < t ? $("#tdSumaPromedioTresSemanas").css("background-color", "red") : $("#tdSumaPromedioTresSemanas").css("background-color", "white")
}

function agregarColumnaSumaTotales(a) {
    $(".fc-border-separate tr:first").append("<th style='width: 87px;' id='thColumnaTotales' class='thColumnaTotales'> Hrs Semana </th>");
    var t = 0;
    $(".fc-border-separate tr.fc-week").each(function (a, e) {
        t++, $(this).append("<td id='tdSumaSemana" + t + "' class='tdSumaSemana fc-last'><div style='min-height: 67px;align-content: center;'><div id='divSumaSemana" + t + "' class='fc-day-suma-horas-semana'><span id='spSumaSemana" + t + "' class='spSumaSemana'>100</span></div></div></td>")
    });
    var e = a + 1;
    $(".fc-border-separate tr:last").after("<tr id=''><td style='text-align: right;' colspan='" + e + "' class=''><b>Promedio semanal de horas (3 Semanas marcadas):</b></td><td id='tdSumaPromedioTresSemanas' class='tdSumaPromedioTresSemanas fc-first fc-day fc-last'><div style='min-height: 67px;align-content: center;'><div id='divSumaPromedioTresSemanas' class='fc-suma-promedio-horas-3-semanas'><span id='spSumaPromedioTresSemanas'>0</span></div></div></td></tr>")
}

function removerColumnaSumaTotales() {
    $("#thColumnaTotales").remove(), $("#tdSumaSemana1").remove(), $("#tdSumaSemana2").remove(), $("#tdSumaSemana3").remove(), $("#tdSumaSemana4").remove(), $("#tdSumaSemana5").remove(), $("#tdSumaSemana6").remove(), $("#trSumaPromedioTresSemanas").remove()
}

function numeroHoras(a) {
    if ("" == a) return 0;
    var e = a.split(":"), t = parseFloat(e[0]), i = parseFloat(e[1]), r = parseFloat(e[2]), n = 0, o = 0;
    return 0 < r && (n = r / 60), 0 < (i += n) && (o = i / 60), t += o
}

function cargarGrillaAsignacionIndividualFechasUbicacionEstacion(a, e, t, i) {
    var r, n = {
        datatype: "json",
        datafields: [{name: "nro_row", type: "integer"}, {
            name: "perfil_laboral",
            type: "string"
        }, {name: "perfil_laboral_grupo", type: "string"}, {
            name: "calendario_fecha_ini",
            type: "date"
        }, {name: "calendario_fecha_fin", type: "date"}, {
            name: "horario_nombre",
            type: "string"
        }, {
            name: "relaboralperfilmaquina_ubicacion_entrada",
            type: "string"
        }, {
            name: "relaboralperfilmaquina_ubicacion_salida",
            type: "string"
        }, {
            name: "relaboralperfilmaquina_tipo_marcacion_entrada_descripcion",
            type: "string"
        }, {
            name: "relaboralperfilmaquina_tipo_marcacion_salida_descripcion",
            type: "string"
        }, {name: "relaboralperfil_observacion", type: "string"}],
        url: "/calendariolaboral/getlistallregisteredbyrelaboral?id=" + e + "&fecha_ini=" + t + "&fecha_fin=" + i,
        cache: !1
    }, o = new $.jqx.dataAdapter(n);
    r = prepareSimulator("grid"), $("#divGridCalendario").jqxGrid({
        theme: r,
        width: "100%",
        height: "530px",
        source: o,
        sortable: !0,
        altRows: !0,
        columnsresize: !0,
        pageable: !0,
        pagerMode: "advanced",
        showfilterrow: !0,
        filterable: !0,
        autorowheight: !0,
        columns: [{
            text: "Nro.",
            filterable: !1,
            columntype: "number",
            width: 40,
            cellsalign: "center",
            align: "center",
            cellsrenderer: rownumberrenderer
        }, {
            text: "Perfil",
            filtertype: "checkedlist",
            datafield: "perfil_laboral",
            width: 100,
            align: "center",
            hidden: !1
        }, {
            text: "Grupo",
            filtertype: "checkedlist",
            datafield: "perfil_laboral_grupo",
            width: 100,
            align: "center",
            hidden: !1
        }, {
            text: "Fecha Inicio",
            datafield: "calendario_fecha_ini",
            filtertype: "range",
            width: 80,
            cellsalign: "center",
            cellsformat: "dd-MM-yyyy",
            align: "center",
            hidden: !1
        }, {
            text: "Fecha Fin",
            datafield: "calendario_fecha_fin",
            filtertype: "range",
            width: 80,
            cellsalign: "center",
            cellsformat: "dd-MM-yyyy",
            align: "center",
            hidden: !1
        }, {
            text: "Horario",
            filtertype: "checkedlist",
            datafield: "horario_nombre",
            width: 100,
            align: "center",
            hidden: !1
        }, {
            text: "Ubicaci&oacute;n Entrada",
            filtertype: "checkedlist",
            datafield: "relaboralperfilmaquina_ubicacion_entrada",
            width: 130,
            align: "center",
            hidden: !1
        }, {
            text: "Estaci&oacute;n Entrada",
            filtertype: "checkedlist",
            datafield: "relaboralperfilmaquina_estacion_entrada",
            width: 100,
            align: "center",
            hidden: !1
        }, {
            text: "Tipo Entrada",
            filtertype: "checkedlist",
            datafield: "relaboralperfilmaquina_tipo_marcacion_entrada_descripcion",
            width: 190,
            align: "center",
            hidden: !1
        }, {
            text: "Ubicaci&oacute;n Salida",
            filtertype: "checkedlist",
            datafield: "relaboralperfilmaquina_ubicacion_salida",
            width: 100,
            align: "center",
            hidden: !1
        }, {
            text: "Estaci&oacute;n Salida",
            filtertype: "checkedlist",
            datafield: "relaboralperfilmaquina_estacion_salida",
            width: 100,
            align: "center",
            hidden: !1
        }, {
            text: "Tipo Salida",
            filtertype: "checkedlist",
            datafield: "relaboralperfilmaquina_tipo_marcacion_salida_descripcion",
            width: 190,
            align: "center",
            hidden: !1
        }, {
            text: "Observaci&oacute;n",
            filtertype: "checkedlist",
            datafield: "relaboralperfil_observacion",
            width: 130,
            align: "center",
            hidden: !1
        }]
    })
}

function definirListaAsignados(a, e, t, i, r) {
}

function obtenerUltimoDiaMes(a) {
    return a = $.ajax({
        url: "/perfileslaborales/getultimafechames/",
        type: "POST",
        datatype: "json",
        async: !1,
        cache: !1,
        data: {fecha: a},
        success: function (a) {
        }
    }).responseText
}

function obtenerFechaMasDias(a, e) {
    return a = $.ajax({
        url: "/perfileslaborales/getfechamasdias/",
        type: "POST",
        datatype: "json",
        async: !1,
        cache: !1,
        data: {fecha: a, dias: e},
        success: function (a) {
        }
    }).responseText
}

function descargarMarcaciones(a, e, t, i) {
    return $.ajax({
        url: "/marcaciones/descargar/",
        type: "POST",
        datatype: "json",
        async: !1,
        cache: !1,
        data: {id_persona: a, ci: e, fecha_ini: t, fecha_fin: i},
        beforeSend: function (a) {
            $("#divCarga").css({display: "block"})
        },
        complete: function () {
            $("#divCarga").css("display", "none")
        },
        success: function (a) {
            var e = jQuery.parseJSON(a);
            if (0 < e.length) {
                var t = "";
                $.each(e, function (a, e) {
                    0 == e.result ? $.each(e, function (a, e) {
                        t += "ci:" + e.ci + ",idPersona:" + e.id_persona + ",maquina:" + e.codigo_maquina + ",idMaquina:" + id_maquina + "\n"
                    }) : e.result, 0
                })
            }
            return t
        }
    }).responseText
}

function obtenerCantidadDeMesesEntreFechas(a, e) {
    var t = 0;
    return "" != a && "" != e && (t = $.ajax({
        url: "/marcaciones/cantidadmesesentrefechas/",
        type: "POST",
        datatype: "html",
        async: !1,
        cache: !1,
        data: {fecha_ini: a, fecha_fin: e},
        success: function (a) {
        }
    }).responseText), t
}

function fechaConvertirAFormato(a, e) {
    "" == e && (e = "-");
    var t = a, i = t.getDate(), r = t.getMonth(), n = "", o = "";
    return i < 10 && (n = "0"), (r += 1) < 10 && (o = "0"), n + i + e + o + r + e + t.getFullYear()
}