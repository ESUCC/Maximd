/*
 * 20110202 jlavere
 * backButtonWarning
 * loaded in layout.html for edit pages
 * works with the modified() and modFlag
 * which is update and cleard with modified()
 * and saveCallback()
 */
function backButtonWarning() {
	// before leaving, alert the user if page is modified
	window.onbeforeunload = function (e) {
		
		// a global var named ie8KillBackButtonWarning
		// should be set to true if you want to stop this dialog from appearing
		
		// focus on submit button and blur to ensure we're not in a field
		// this ensures that if we were in a field that the 
		// modified function has been called
		// changed to nav select because this was failing on ie
		// when the save button was disabled
		var activeElement = dojo.byId('navPage3placeholder');
		if(activeElement) {
			try
			  {
				//Run some code here
				activeElement.focus();
				activeElement.blur();
			  }
			catch(err)
			  {
			  //Handle errors here
			  }			
		}
		if (typeof modFlag === 'undefined') {
		    // variable is undefined
		} else if(1 == modFlag && ie8KillBackButtonWarning != true) {
			// if page has beed modified, alert the user
			return "You have not saved your document yet.  If you continue, your work will not be saved...";
		}
	}
}
dojo.addOnLoad(backButtonWarning);
