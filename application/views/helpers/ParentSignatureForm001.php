<?php

class Zend_View_Helper_ParentSignatureForm001 extends Zend_View_Helper_Abstract {
	
	public function parentSignatureForm001() {
		
		$retString = '<table class="bts" cellpadding="0" cellspacing="0" style="border: 1px solid #333;border-spacing:5px;page-break-inside: avoid;margin-top:25px;margin-bottom:25px;">';
		$retString .= '    <tr>';
		$retString .= '        <td align="center"><font size="+1">GIVE CONSENT FOR INITIAL EVALUATION</font></td>';
		$retString .= '    </tr>';
		$retString .= '    <tr>';
		$retString .= '        <td>I have received a copy of the Notice of this proposed evaluation and my parental rights, ';
		$retString .= '            understand the content of the Notice and <span class="btsb">give consent</span> for the ';
		$retString .= '            multidisciplinary evaluation specified in this Notice. I understand this consent is voluntary ';
		$retString .= '            and may be revoked at any time.';
		$retString .= '        </td>';
		$retString .= '    </tr>';
		$retString .= '    <tr>';
		$retString .= '        <td align="center">';
		$retString .= '            <table style="width:100%;">';
		$retString .= '                <tr>';
		$retString .= '                    <td style="width:70%;border-bottom: 1px solid #000; height:30px;">&nbsp;</td>';
		$retString .= '                    <td style="border-bottom: 1px solid #000;">&nbsp;</td>';
		$retString .= '                </tr>';
		$retString .= '                <tr>';
		$retString .= '                    <td style="width:70%;">Signature of Parents</td>';
		$retString .= '                    <td style="">Date</td>';
		$retString .= '                </tr>';
		$retString .= '            </table>';
		$retString .= '        </td>';
		$retString .= '    </tr>';
		$retString .= '    <tr>';
		$retString .= '        <td align="center" style="height:30px;"><font size="+1">DO NOT GIVE CONSENT FOR INITIAL EVALUATION</font></td>';
		$retString .= '    </tr>';
		$retString .= '    <tr>';
		$retString .= '        <td>I have received a copy of the Notice of this proposed evaluation and my parental rights, understand ';
		$retString .= '            the content of the Notice and <span class="btsb">do not give consent</span> for the multidisciplinary ';
		$retString .= '            evaluation specified in this Notice. The reason for not giving consent to the evaluation is:';
		$retString .= '        </td>';
		$retString .= '    </tr>';
		$retString .= '    <tr>';
		$retString .= '        <td align="center">';
		$retString .= '            <table style="width:100%;">';
		$retString .= '                <tr>';
		$retString .= '                    <td style="width:100%;border-bottom: 1px solid #000; height:20px;" colspan="2">&nbsp;</td>';
		$retString .= '                </tr>';
		$retString .= '                <tr>';
		$retString .= '                    <td style="width:100%;border-bottom: 1px solid #000; height:20px;" colspan="2">&nbsp;</td>';
		$retString .= '                </tr>';
		$retString .= '                <tr>';
		$retString .= '                    <td style="width:100%;border-bottom: 1px solid #000; height:20px;" colspan="2">&nbsp;</td>';
		$retString .= '                </tr>';
		$retString .= '                <tr>';
		$retString .= '                    <td style="width:70%;border-bottom: 1px solid #000; height:30px;;">&nbsp;</td>';
		$retString .= '                    <td style="border-bottom: 1px solid #000;">&nbsp;</td>';
		$retString .= '                </tr>';
		$retString .= '                <tr>';
		$retString .= '                    <td style="width:70%;">Signature of Parents</td>';
		$retString .= '                    <td style="">Date</td>';
		$retString .= '                </tr>';
		$retString .= '            </table>';
		$retString .= '        </td>';
		$retString .= '    </tr>';
		$retString .= '</table>';
						
		return $retString;
	}
}