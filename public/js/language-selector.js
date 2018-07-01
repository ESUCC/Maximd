$().ready(function() {
	$('#languages').change(function() {
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: '/language/set-locale/locale/'+$(this).val(),
			success: function(json) {
				window.location.reload();
			}
		});
	});
});