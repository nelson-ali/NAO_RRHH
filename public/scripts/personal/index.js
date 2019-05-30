$(document).ready(function () {
    function a() {
        var e = new $.jqx.dataAdapter({
            datatype: "json",
            datafields: [{name: "id"}, {name: "p_nombre"}, {name: "s_nombre"}, {name: "p_apellido"}, {name: "s_apellido"}, {
                name: "ci",
                type: "string"
            }, {
                name: "fecha_nac",
                format: "date"
            }, {name: "expd"}, {name: "foto"}, {name: "suma"}, {name: "e_civil"}, {name: "genero"}, {name: "nacionalidad"}, {
                name: "fecha_apr",
                format: "date"
            }, {name: "estado_actual", format: "string"}],
            url: "/personas/listajson",
            cache: !0
        });
        $("#jqxgrid").jqxGrid({
            width: "100%",
            source: e,
            sortable: !0,
            altRows: !0,
            showstatusbar: !0,
            statusbarheight: 25,
            pagerMode: "advanced",
            theme: "custom",
            showfilterrow: !0,
            filterable: !0,
            scrollmode: "deferred",
            renderstatusbar: function (e) {
            },
            enablebrowserselection: !0,
            scrollfeedback: function (e) {
                return '<table style="height: 150px;"><tr><td><img src="' + e.foto + '"  height="90"/></td></tr><tr><td>' + e.p_nombre + "</td></tr></table>"
            },
            rowsheight: 90,
            columns: [{
                text: "Image", datafield: "foto", width: 100, cellsrenderer: function (e, t, a) {
                    return '<img src="' + a + '" height="90"/>'
                }
            }, {text: "Nro", datafield: "id", width: "5%", hidden: !0}, {
                text: "Nombres",
                datafield: "p_nombre",
                width: "14%"
            }, {text: "Apellidos", datafield: "p_apellido", width: "14%"}, {
                text: "A. Casada",
                datafield: "s_apellido",
                width: "14%"
            }, {
                text: "Doc. Ident.",
                datafield: "ci",
                cellsalign: "center",
                align: "center",
                type: "string",
                width: "10%"
            }, {
                text: "EXP",
                datafield: "expd",
                cellsalign: "center",
                align: "center",
                type: "string",
                filtertype: "input",
                width: "5%"
            }, {
                text: "Fecha Nac.",
                datafield: "fecha_nac",
                cellsalign: "center",
                align: "center",
                format: "date",
                filtertype: "range",
                cellsformat: "yyyy",
                width: "8%"
            }, {text: "Estado Civil.", datafield: "e_civil", width: "8%"}, {
                text: "Genero",
                datafield: "genero",
                cellsalign: "center",
                align: "center",
                width: "5%"
            }, {
                text: "Nacionalidad",
                datafield: "nacionalidad",
                cellsalign: "center",
                align: "center",
                width: "8%"
            }, {text: "Estado", datafield: "estado_actual", cellsalign: "center", align: "center", width: "8%"}]
        }), $("#jqxgrid").bind("filter", function (e) {
            var t = $("#jqxgrid").jqxGrid("getrows").length;
            $("#statusbarjqxgrid").html("Total: <b>" + t + "</b>")
        }), $("#jqxgrid").bind("bindingcomplete", function (e) {
            var t = $("#jqxgrid").jqxGrid("getrows"), a = t.length;
            $.each(t, function (e, t) {
                t.suma
            }), $("#statusbarjqxgrid").html("Total: <b>" + a + "</b>"), $("#fecha").addClass("animated"), $("#fecha").addClass("fadeIn")
        })
    }

    a(), $("#imprimir").click(function () {
        columna = new Object, filtros = new Object, row_id = $("#jqxgrid").jqxGrid("getcolumn", "id"), p_nombre = $("#jqxgrid").jqxGrid("getcolumn", "p_nombre"), s_nombre = $("#jqxgrid").jqxGrid("getcolumn", "s_nombre"), p_apellido = $("#jqxgrid").jqxGrid("getcolumn", "p_apellido"), s_apellido = $("#jqxgrid").jqxGrid("getcolumn", "s_apellido"), ci = $("#jqxgrid").jqxGrid("getcolumn", "ci"), fecha_nac = $("#jqxgrid").jqxGrid("getcolumn", "fecha_nac"), expd = $("#jqxgrid").jqxGrid("getcolumn", "expd"), columna[p_nombre.datafield] = {
            text: p_nombre.text,
            hidden: p_nombre.hidden
        }, columna[row_id.datafield] = {
            text: row_id.text,
            hidden: row_id.hidden
        }, columna[s_nombre.datafield] = {
            text: s_nombre.text,
            hidden: s_nombre.hidden
        }, columna[p_apellido.datafield] = {
            text: p_apellido.text,
            hidden: p_apellido.hidden
        }, columna[s_apellido.datafield] = {
            text: s_apellido.text,
            hidden: s_apellido.hidden
        }, columna[ci.datafield] = {text: ci.text, hidden: ci.hidden}, columna[expd.datafield] = {
            text: expd.text,
            hidden: expd.hidden
        }, columna[fecha_nac.datafield] = {text: fecha_nac.text, hidden: fecha_nac.hidden};
        for (var e = $("#jqxgrid").jqxGrid("getrows"), t = $("#jqxgrid").jqxGrid("getfilterinformation"), a = 0, i = 0; i < t.length; i++) for (var d = t[i], n = d.filter.getfilters(), r = 0; r < n.length; r++) {
            0 < r && a++, filtros[i + a] = {
                columna: d.filtercolumn,
                valor: n[r].value,
                condicion: n[r].condition,
                tipo: n[r].type
            }
        }
        var l = e.length, o = JSON.stringify(filtros), c = JSON.stringify(columna);
        c = btoa(utf8_encode(c)), o = btoa(utf8_encode(o)), c = c.replace(/\+/g, "-").replace(/\//g, "_").replace(/\=+$/, ""), o = o.replace(/\+/g, "-").replace(/\//g, "_").replace(/\=+$/, ""), window.open("/personal/imprimir/" + l + "/" + c + "/" + o, "_blank")
    }), $("#edit").click(function () {
        var e = $("#jqxgrid").jqxGrid("getselectedrowindex");
        if (-1 < e) {
            var t = $("#jqxgrid").jqxGrid("getrowdata", e);
            document.location.href = "../../personas/editar/" + t.id
        } else bootbox.alert("<strong>¡Mensaje!</strong> Seleccionar un registro para editar.")
    }), $("#delete").click(function () {
        var e = $("#jqxgrid").jqxGrid("getselectedrowindex");
        if (-1 < e) {
            var t = $("#jqxgrid").jqxGrid("getrowdata", e);
            bootbox.confirm("<strong>¡Mensaje!</strong> Esta seguro de eliminar el registro.", function (e) {
                if (1 == e) $.ajax({
                    url: "/personas/delete/",
                    type: "POST",
                    datatype: "json",
                    data: {id: t.id},
                    success: function (e) {
                        a()
                    },
                    error: function () {
                        alert("Se ha producido un error Inesperado")
                    }
                })
            })
        } else bootbox.alert("<strong>¡Mensaje!</strong> Seleccionar un registro para eliminar.")
    })
});