$(document).ready(function () {
    $("#estado_civil").change(function () {
        "CASADO(A)" == $(this).val() ? $("#div_a_casada").show() : $("#div_a_casada").hide()
    }), $("input#ci").blur(function () {
        "" == $("#id").val() && "" != $("#ci").val() && $.ajax({
            url: "/personas/ci",
            type: "POST",
            datatype: "json",
            data: {ci: $("#ci").val()},
            success: function (a) {
                0 < (a = jQuery.parseJSON(a)).existe && (bootbox.alert("<strong>¡Error!</strong> " + a.mensaje), $("#ci").val("").focus())
            },
            error: function () {
                alert("Se ha producido un error Inesperado")
            }
        })
    }), $("input#email_i").blur(function () {
        "" != $("#email_i").val() && $.ajax({
            url: "/personas/email",
            type: "POST",
            datatype: "json",
            data: {id: $("#id").val(), email_i: $("#email_i").val()},
            success: function (a) {
                0 < (a = jQuery.parseJSON(a)).existe && (bootbox.alert("<strong>¡Error!</strong> " + a.mensaje), $("#email_i").val("@viasbolivia.gob.bo").focus())
            },
            error: function () {
                alert("Se ha producido un error Inesperado")
            }
        })
    }), $("#f_caducidad,#fecha_nacimiento").datepicker(), $("#boton_foto").load("/personas/subirfoto/" + $("#link_foto").val())
});