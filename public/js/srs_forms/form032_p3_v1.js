function toggleOutsideAgency(on_off_checkbox_page_4) {
	
//	console.debug('on_off_checkbox_page_4', on_off_checkbox_page_4);
	
	if(on_off_checkbox_page_4 == 1) {
		 dojo.byId("on_off_descpt").innerHTML = "Uncheck box to remove outside agencies.";
         dojo.byId("subformToChange").style.display = "inline";
         dojo.byId("subformToChange2").style.display = "inline";
	} else {
	     dojo.byId("on_off_descpt").innerHTML = "Check the box to report outside agencies.";
         dojo.byId("subformToChange").style.display = "none";
         dojo.byId("subformToChange2").style.display = "block";
	}
}

