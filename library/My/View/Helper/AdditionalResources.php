<?php

	class My_View_Helper_AdditionalResources extends Zend_View_Helper_Abstract {
		
		public function additionalResources($class = "formTitleHead", $additionalClasses = null) {
			
			$text = '<table class="'.$class.' ' .$additionalClasses .'" cellspacing="0" cellpadding="0">
					   <tr>
					        <td>'.$this->view->translate('Additional Resources').'</td>
					    </tr>
					</table>
					<table class="formInput" style="margin-top:1px;">
						<tr>
							<td>
							'.$this->view->translate('You may contact any of the following').':
							<p>&nbsp;&nbsp;&nbsp;Nebraska Parent Training Center: 800-284-8520 or 402-346-0525</p>
							<p>&nbsp;&nbsp;&nbsp;Nebraska Advocacy Services: 800-422-6091 or 402-474-3183</p>
							</td>
					</table>';
			
			return $text;
		}
	}
