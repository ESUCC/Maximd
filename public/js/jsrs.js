$(document).ready(function() {

   $(document).on("click", ".pwchange2", function () { 
          var title = $(this).attr("title");
          var url = $(this).attr("rel");
	  if (!$("div").is("#dialog2")) $("body").append('<div id="dialog2">');
          console.log(url);
          if (url != ""){
           $("#dialog2").load( url,
             function() {
              $("#dialog2").dialog({  show: { effect: "fade", duration: 500 }, 
                                            hide: {effect: "fade", duration: 500}, 
                                            width: 550, height: 400, resizable : true, 
                                            title: title, modal: true, beforeClose: function(event, ui) { $("#dialog").html(""); } } );
                                   });
          }
          return false;

        });


});