<?php

class Zend_View_Helper_SignatureConsentRadioButton extends Zend_View_Helper_Abstract
{

	public function SignatureConsentRadioButton($consentFieldName, $consentFieldValue, $reasonFieldName, 
												$reasonFieldValue)
	{
//		Zend_debug::dump($consentFieldValue);

		$retText = "";
		$retText .= "<table class=\"formSectionHead\">";
		$retText .= "    <tr>";
		$retText .= "        <td class=\"p2424\">Give Consent for Initial Evaluation</td>";
		$retText .= "    </tr>";
		$retText .= "</table>";

		$retText .= "<table class=\"formInput\">";
		$retText .= "    <tr>";
		$retText .= "        <td>";
		if($consentFieldValue == 1)
		{
			
			$retText .= "            <input type=\"radio\" checked=\"true\" name=\"$consentFieldName\" 
							id=\"$consentFieldName\" value=\"1\" 
							onFocus=\"javascript:modified('', '', '', '', '', '');\"/>&nbsp;</td>";
		} else {
			$retText .= "            <input type=\"radio\" name=\"$consentFieldName\" id=\"$consentFieldName\" 
							value=\"1\" onFocus=\"javascript:modified('', '', '', '', '', '');\"/>&nbsp;</td>";
		}
		$retText .= "        <td class=\"p2424\" >";
		$retText .= "            I have received a copy of the Notice of this proposed evaluation and my 
								parental rights, understand the content of the Notice and <span class=\"btsb\">
								give consent</span> for the multidisciplinary evaluation specified in this 
								Notice. I understand this consent is voluntary and may be revoked at any time.";
		$retText .= "        </td>";
		$retText .= "    </tr>";
		$retText .= "</table>";

		$retText .= "<table class=\"formSectionHead\">";
		$retText .= "    <tr>";
		$retText .= "        <td class=\"p2424\">Do Not Give Consent for Initial Evaluation</td>";
		$retText .= "    </tr>";
		$retText .= "</table>";

		$retText .= "<table class=\"formInput\">";
		$retText .= "    <tr>";
		$retText .= "        <td >";
		if($consentFieldValue == 0)
		{
			$retText .= "		 	 <input type=\"radio\" checked=\"true\" name=\"$consentFieldName\" 
										id=\"$consentFieldName\" value=\"0\" 
										onFocus=\"javascript:modified('', '', '', '', '', '');\"/>&nbsp;";
		} else {
			$retText .= "		 	 <input type=\"radio\" name=\"$consentFieldName\" 
										id=\"$consentFieldName\" value=\"0\" 
										onFocus=\"javascript:modified('', '', '', '', '', '');\"/>&nbsp;";
		}
			
		$retText .= "		 </td>";
		$retText .= "        <td class=\"p2424\" >";
		$retText .= "        I have received a copy of the Notice of this proposed evaluation and my 
							parental rights, understand the content of the Notice and <span class=\"btsb\">
							do not give consent</span> for the multidisciplinary evaluation specified in 
							this Notice. The reason for not giving consent to the evaluation is:";
		$retText .= "        </td>";
		$retText .= "    </tr>";
		$retText .= "    <tr>";
		$retText .= "        <td colspan=\"2\" class=\"p8888\"><textarea name=\"$reasonFieldName\" 
								id=\"$reasonFieldName\" rows=\"2\"  
								onFocus=\"javascript:modified('', '', '', '', '', '');\">$reasonFieldValue</textarea></td>";
		$retText .= "    </tr>";
		$retText .= "</table>";
		
		return $retText;
	}

}
