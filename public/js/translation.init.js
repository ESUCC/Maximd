$(document).ready(function() {
	$('#form').change(function() {
		window.location.href = '/translation/manual-key-update/form/'+$(this).val()+'/page/'+$('#page').val();
	});
	
	$('#page').change(function() {
		window.location.href = '/translation/manual-key-update/form/'+$('#form').val()+'/page/'+$(this).val();
	});
});