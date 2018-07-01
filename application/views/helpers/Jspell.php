<?php
/**
 * Helper for configuring jSpell (javascriptspellchecker)
 * 
 * @uses      Zend_View_Helper_Abstract
 * @author    Jesse LaVere <jlavere@soliantconsulting.com> 
 * @version   $Id: $
 */
class Zend_View_Helper_Jspell extends Zend_View_Helper_Abstract
{

    /**
     * Return script/button to initialize javaspell checker
     * 
     * @return string
     */
    public function jspell($buttonId="jSpellCheckButton")
    {

        // /js/JavaScriptSpellCheck/include.js is FORCE INCLUDED IN LAYOUT
		// $this->view->headScript()->appendFile('/js/JavaScriptSpellCheck/include.js');
		
		// modalbox config
// 		$this->view->headScript()->appendFile('/js/JavaScriptSpellCheck/extensions/modalbox/modalbox.combined.js');
// 		$this->view->headLink()->appendStylesheet('/js/JavaScriptSpellCheck/extensions/modalbox/modalbox.css', 'screen');
    	
		// modalbox config
		$this->view->headScript()->appendFile('/js/JavaScriptSpellCheck/extensions/fancybox/jquery.fancybox-1.3.4.pack.js');
		$this->view->headLink()->appendStylesheet('/js/JavaScriptSpellCheck/extensions/fancybox/jquery.fancybox-1.3.4.css', 'screen');
		
		/*
		 * build the script call and button to be 
		 */
		$this->view->placeholder('foo')->captureStart();
    	?>
	    	<script type='text/javascript'>$Spelling.PopUpStyle="fancybox";$</script>
	    	<script type='text/javascript'>
		        try {
			    	$(document).ready(function() {
				    	// add functionality to the button below once the page loads
			    	    $('#<?= $buttonId; ?>').click(function() {
				    	    /*
				    	     * get the ids of the iframes(editors)
				    	     * into a JS array and pass to the jSpell app
				    	    */
				    		var myFrames = new Array();
				    		var counter = 0;
				    		$('iframe').each(function(){
				    			if(undefined!=$(this).attr('id')) {
				    				myFrames[counter] = $(this).attr('id');
				    				counter++;
				    			}
				    		});
				    		o = $Spelling.SpellCheckInWindow(myFrames);

				    	    /*
				    	     * When returning from the spell checker
				    	     * check each iframe(editor) and if it's been modified
				    	     * process and updated the hidden editor field
				    	    */
				    		o.onDialogClose = function(){
					    		$('iframe').each(function(){
					    			if(undefined!=$(this).attr('id')) {
					    				//console.debug('id', $(this).attr('id'));
							    		// run processor and update editors/hidden fields
							    		// defined in srs_forms/common_form_functions.js
							    		var item = $(this).attr('id');
							    		var iframeId = item.replace("_iframe", "");
							    		var hiddenElementId = item.replace("-Editor_iframe", "");
							    		//console.debug($('#'+hiddenElementId).val(), $('#'+item).contents().find('body').html());
							    		if($('#'+hiddenElementId).val() != $('#'+item).contents().find('body').html()) {
								    		//console.debug('change detected in editor - process and update');

							    			content = $('#'+item).contents().find('body').html();
							    			
							    			// strip bad container code
											contentWrapperPrefix = '<DIV style="ZOOM: 1" id=dijitEditorBody contentEditable=true unselectable="off">';
											contentWrapperSuffix = '</DIV>';
											if(contentWrapperPrefix==content.substring(0,contentWrapperPrefix.length) && contentWrapperSuffix==content.substring(content.length-contentWrapperSuffix.length) ) {
												content = content.substring(contentWrapperPrefix.length); // remove prefuix
												content = content.substring(0, content.length-contentWrapperSuffix.length); // remove suffix
											}

							    			updateInlineValueTest(iframeId, content);
							    			modified();
							    		}
							    				
					    			}
					    		});
				   			}
			    	    });
			    	});
		        } catch (error) {
		        	//execute this block if error
		        	console.debug('An error occured while trying to initialize/run the spell checker.');
		        }
	    	</script>
	    	<button class="dijitReset dijitInline dijitButtonNode" id="<?= $buttonId; ?>" name="<?= $buttonId; ?>" type="button">Spell Check</button>
    	<?php
    	$this->view->placeholder('sidebar')->captureEnd();
        
    	return $this->view->placeholder('sidebar');
    }
    
	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
