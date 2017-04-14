<?php

class Zend_View_Helper_ParentSignature extends Zend_View_Helper_Abstract {
	
	public function ParentSignature($consentDate, $signatureOnFile, $dateDistrictReceived, $withDateDistrict) {
		
		$text='<table class="formInput" style="margin-top: 1px;">
				<tr>
					<td>
					<table class="formInput" style="margin-top: 1px;">
						<tr>
							<td>Parent Signature: ____________________________</td>
					        <td>'.$signatureOnFile .'(check here to
							indicate that signature is on file)</td>
						</tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr>
							<td>'.$consentDate.'</td>';
		if($withDateDistrict == true) {				
			$text.=			'<td>'.$dateDistrictReceived.'</td>';
		} else {
			$text.=			'<td>&nbsp;</td>';
		}
		$text.=   		'</tr>
					</table>
					</td>
				</tr>
			</table>';
		
		return $text;
	}
}