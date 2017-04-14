/* $().ready(function() {
    $('.dijitTabPaneWrapper').each(function() {
        var id = $(this).attr('id');
        alert("hello2 from id "+id);
        $(this+' radio').change( function(){ 
        if ($(this+' radio').val() == 1)   
            $('#'+id+'short_term_obj_colorme').attr('class', 'colorme errored');
        else
            $('#'+id+'short_term_obj_colorme').attr('class', 'colorme');
        });
    });
}); */


try {
	$(document).ready(function() {
	    $('.dijitTabPane').each(function() {
	        var id = $(this).attr('id');
	        //for each radio button, on change:
	        $('#'+id+' .radio').change( function(){
	            if ($(this).attr('value') == 1) {
	                if ($('#'+id+'-short_term_obj').val().length == 0){
	                    $('#'+id+'-short_term_obj-colorme').attr('class', 'colorme errored');
	                }
	            }
	            else $('#'+id+'-short_term_obj-colorme').attr('class', 'colorme');
	        });
	    });
	}); 
} catch (e) {
	console.log (e.message);    //this executes if jQuery isn't loaded
}
	
	
	