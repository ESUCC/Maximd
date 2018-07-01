<?php

	class My_View_Helper_ContactNameNumber extends Zend_View_Helper_Abstract {
		
		public function contactNameNumber($contact_name, $contact_num) {
			
			$text ='<table class="formInput" style="margin-top:1px;">     
				    <tr>
						<td>
							Parents of children with disabilities have rights which are protected under 
							the procedural safeguards of the Individual with Disabilities Education Act 
							(IDEA). If you would like a copy of your procedural safeguards, or if you 
							have any questions regarding this notice or your rights, you may contact:
							<br/><br/>
							<div style="text-align:center;">
							Name: <input type="text" name="'.$contact_name.'" id="'.$contact_name.'" />
							Phone Number:<input type="text" name="'.$contact_num.'" id="'.$contact_num.'" />
							</div>
						</td>
				    </tr>
				</table>';
			
			return $text;
		}
	}