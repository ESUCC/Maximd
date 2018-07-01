<?php

	class My_View_Helper_Form002page3Helper extends Zend_View_Helper_Abstract {

		public function getSubform($noDisabilityName, $noDisabiliyValue, $satDate, $satContact, 
							$verificationReqName, $verifReqValue) {
			
			$text = '<table class="formInput" style="margin-top:1px;">';
			
			$text .= '	<tr>
							<td><input type="radio" name="'.$noDisabilityName.'" 
							id="'.$noDisabilityName.'" value="'.$noDisabiliyValue.'" 
							onFocus="javascript:modified(\'\', \'\', \'\', \'\', \'\', \'\');"/>
							A. No disability verified.<br/>';
			
			$text .= '	If no disability is verified refer student to SAT (Student Assistance Team) or 
						problem-solving team and provide MDT information to SAT.';
			
			$text .= '	<table class="formInput" style="margin-top:1px;">
							<tr>
								<td>Date Referred to SAT:</td>
								<td><input type="text" name="'.$satDate.'" id="'.$satDate.'" 
								onFocus="javascript:modified(\'\', \'\', \'\', \'\', \'\', \'\');"/></td>
							</tr>
							<tr>
								<td>SAT Contact Person:</td>
								<td><input type="text" name="'.$satContact.'" id="'.$satContact.'" 
								onFocus="javascript:modified(\'\', \'\', \'\', \'\', \'\', \'\');"/></td>
							</tr>
						</table>';

			$text .= '	<tr>
							<td><input type="radio" name="'.$verificationReqName.'" 
							id="'.$verificationReqName.'" value="'.$verifReqValue.'" 
							onFocus="javascript:modified(\'\', \'\', \'\', \'\', \'\', \'\');"/>
							B. The child has met the written verification requirements as per one 
							or more of the following:<br/>
							<td/>
						<tr>';
			
			return $text;
		}
	}