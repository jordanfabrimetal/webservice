console.log("hola desde login");
$(document).ajaxStart(function () {
	$('#loadingDiv').show();
}).ajaxStop(function () {
	$('#loadingDiv').hide();
});
$("#frmAcceso").on('submit', function(e){
	$('#loadingDiv').show();
	//bootbox.dialog({closeButton: false,message: '<h2 class="text-center m-0"><i class="fa fa-spin fa-spinner"></i> Cargando...</h2>'});
	e.preventDefault();
	username = $("#username").val();
	password = $("#password").val();
	$.post("../ajax/usuario.php?op=verificar",{"username_form": username, "password_form": password}, function(data){
	    
		if(data!="null"){
			var obj = $.parseJSON(data);
			console.log("1°PASO--: y el JSON de la data: "+obj);
			if (obj.status == 'sinfirma') {
				$(location).attr("href", "digitalsignature.php?status=" + obj.status);
				return;
			}

			$(location).attr("href", "estado.php");
			// $(location).attr("href", "estado.php?status=" + obj.status);
		}else{
			bootbox.alert("Usuario o Password Incorrectos")
		}

	})

})

$("#frmAccesoGuia").on('submit', function(e){
	e.preventDefault();
	username = $("#username").val();
	password = $("#password").val();
	$.post("../../ajax/usuario.php?op=verificar",{"username_form": username, "password_form": password}, function(data){
		if(data!="null"){
			$(location).attr("href", "guia.php");
		}else{
			bootbox.alert("Usuario o Password Incorrectos")
		}

	})

})