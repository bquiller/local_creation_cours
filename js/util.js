//    alert('test');
// jQuery.noConflict();
//    $(function () {
$(function ($) {
	// jQuery.noConflict();
        $("#oaide").click(function () {
            $("#popaide").modal().show();
        });
        $("#faide").click(function () {
            $("#popaide").modal().hide();
        });
        $("#oannul").click(function () {
            $("#popannul").modal.show();
        });
        $("#fannul").click(function () {
            $("#popannul").modal.hide();
        });
});

function disableOptionsNoBackup() {
        $("#id_template_hyb_them").parent().hide();
        $("#id_template_hyb_tuiles").parent().hide();
        $("#id_template_presenrichi_tuiles").parent().hide();
        $("#id_template_presenrichi_them").parent().hide();
        /*
	document.getElementById("id_template_hyb_them").setAttribute("disabled","disabled");
        document.getElementById("id_template_hyb_tuiles").setAttribute("disabled","disabled");
        document.getElementById("id_template_presenrichi_tuiles").setAttribute("disabled","disabled");
        document.getElementById("id_template_presenrichi_them").setAttribute("disabled","disabled");
	*/
}
function enableOptionsNoBackup() {
        $("#id_template_hyb_them").parent().show();
        $("#id_template_hyb_tuiles").parent().show();
        $("#id_template_presenrichi_tuiles").parent().show();
        $("#id_template_presenrichi_them").parent().show();
        /*
        document.getElementById("id_template_hyb_them").removeAttribute("disabled");
        document.getElementById("id_template_hyb_tuiles").removeAttribute("disabled");
        document.getElementById("id_template_presenrichi_tuiles").removeAttribute("disabled");
        document.getElementById("id_template_presenrichi_them").removeAttribute("disabled");
	*/
}

