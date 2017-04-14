<?php
//require_once (APPLICATION_PATH . '/../library/simplehtmldom/simple_html_dom.php');

class App_Filter_TestWordFilter implements Zend_Filter_Interface {

	
// 	private function limitToAttributes($str, $tag, $allowed) {
// 		// use simplehtmldom
// 		$html = str_get_html($str);
// 		foreach($html->find('table') as $e) {
// 			foreach ($e->getAttributes() as $att) {
// 				App_Form_FormHelper::zdebugUsageToFile($att, "att", APPLICATION_PATH."/../temp/buildSrsFormAction.txt");
// 			}
// 		}
// 		$str = $html;
// 		return $str;
// 	}
// 	private function removeAttributes($str, $tag, $remove) {
// 		// use simplehtmldom
// 		$html = new simple_html_dom();
// 		$html->load($str);
// 		foreach($html->find($tag) as $e) {
// 			foreach ($remove as $att) {
// 				if(isset($e->$att)) {
// 					$e->$att = null;
// 				}
// 			}
// 		}
// 		$str = $html;
// 		return $str;
// 	}
	
	private function setTabs($callback) {
		return "<span class=\"tab{$callback[2]}\"></span>";
	}
	
	private function removeInnerSpans($callback) {
		return "<p class=\"Mso{$callback[1]}\">{$callback[4]}</p>";
	}
	
	private function removeInnerBUISpans($callback) {
		return "<p class=\"Mso{$callback[1]}\">{$callback[3]}{$callback[5]}{$callback[6]}</p>";
	}
	
	private function removeInnerUSpans($callback) {
		return "<p class=\"Mso{$callback[1]}\"><u>{$callback[5]}</u></p>";
	}
	private function removeComments($callback) {
//		print_r($callback);
		return "";
	}
	private function removeLastComment($callback) {
//		print_r($callback);
		return "";
	}
	/*
	 * grrrr
	 */
	private function massageFontTags($callback) {
// 		Zend_Debug::dump($callback);
		return "<font";
	}
	private function recursivePregReplace($search, $replace, $value, $callback=null) {
// 		Zend_Debug::dump($search, 'search');
// 		Zend_Debug::dump($replace, 'replace');
// 		Zend_Debug::dump($callback, 'callback');
		
		foreach (array_combine($search, $replace) as $searchPattern => $replaceValue) {
			while ( preg_match ( $searchPattern, $value, $matches) > 0 ) {
// 				Zend_Debug::dump($matches, 'recursivePregReplace');
				$value = preg_replace( $searchPattern, $replaceValue, $value );
			}
		}
		return $value;
	}
	
	public function filter($value) {
		$possibleEmptySpace = '(\s| |)*';
		
		
		/*
         * Convert any multibyte characters to HTML equivilient.
         */
		$value = mb_convert_encoding ( $value, 'HTML-ENTITIES', 'UTF-8' );

		
// 		App_Form_FormHelper::zdebugUsageToFile($value, 'Start:', APPLICATION_PATH."/../temp/buildSrsFormAction.txt");
		
		
		/* ================================================================================================================================
		 * STORE PERIOD SPACE SPACE
		 * Replace period and two spaces with a code
		* restore the period and spaces later
		*/
 		$value = preg_replace ( '/&nbsp; /ism', '____PER_NB_SP_____', $value );
		/* ================================================================================================================================ */
		
		/*
        * Get rid of all breaking spaces and inline styles
        */
		$value = preg_replace ( '/&nbsp;/ism', '', $value );

		//$value = preg_replace ( '/'.$possibleEmptySpace.'&amp;'.$possibleEmptySpace.'apos;/ism', '\'', $value );
		
		// replace screwy txt
		// &amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;lt; with &lt;
		$htmlSpecialEntities = array(
			'lt',
			'gt',
			'rsquo',
			'quot',
			'ldquo',
			'rdquo',
			'ndash',
			'mdash',
		);
		foreach($htmlSpecialEntities as $entity) {
			$value = preg_replace ( '/&(amp;)+'.$entity.';/ism', '&'.$entity.';', $value );
		}
		
		// remove comments
		$grepPattern = "(<|&lt;)";  	// two possible opening brackets
		$grepPattern .= "!--\[if.*?\]"; // if comment
		$grepPattern .= ".*?"; 			// inside if tag
		$grepPattern .= "\[endif\]--";  // end if tag
		$grepPattern .= "(>|&gt;)"; 	// two possible ending brackets
		
		$value = preg_replace_callback('/'.$grepPattern.'/ism', array (&$this, "removeComments" ), $value );
		if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) {
  			print 'Backtrack limit was exhausted!';
		}
		
		// somehow users haev figured out how to get half comments into the db
		// remove comments
		$grepPattern = "(<|&lt;)";  	// two possible opening brackets
		$grepPattern .= "!--\[if.*?\]"; // if comment
		$grepPattern .= ".*?"; 			// inside if tag
		$grepPattern .= "\Z"; 			// end of string
		
		// try to match comment ending of the document
		$value = preg_replace_callback('/'.$grepPattern.'/ism', array (&$this, "removeLastComment" ), $value );
		if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) {
  			print 'Backtrack limit was exhausted!';
		}
		
		/*
        * remove <script>content</script> tags
        */
		$value = preg_replace ( '/<script' . $possibleEmptySpace . '>' . '[^<]*?' . '<\/script>/', '', $value );
		$value = preg_replace ( '/<noscript' . $possibleEmptySpace . '>' . '[^<]*?' . '<\/noscript>/', '', $value );
		
		/*
        * remove <style>content</style> tags
        */
		$value = preg_replace ( '/<style' . $possibleEmptySpace . '>' . '[^<]*?' . '<\/style>/', '', $value );
		
		/*
        * Set tab spacing.
        */
		$value = preg_replace_callback ( '/<span style="mso-tab-count:' . $possibleEmptySpace . '([0-9]{1,10})">' . $possibleEmptySpace . '<\/span>/', array (&$this, "setTabs" ), $value );
		
		/*
        * Remove Style Tags
        * jlavere 20110615 added /m
        */
		//       	$value = preg_replace('/style="(.+?)"/ism', '', $value);
		$value = preg_replace ( '/style="(.*?)"/ism', '', $value );
		
		/*
        * Remove ID Tags
        * jlavere 2011927 added /m
        */
		$value = preg_replace ( '/id="(.*?)"/ism', '', $value );
		
		//       $value = preg_replace('/&lt;!--(.*?)/ism', '----replaced-----', $value);
		

		/*
        * Remove oddly tagged bold tags.
       while(preg_match('/<b .+?><\/b>/ism', $value) > 0)
       	 	$value = preg_replace('/<b .+?><\/b>/ism', '<b></b>', $value);
        */
		while ( preg_match ( '/<b .*?>(.*?)<\/b>/ism', $value ) > 0 )
			$value = preg_replace ( '/<b .*?>(.*?)<\/b>/ism', "<b>$1</b>", $value );
			
		
		
		
		/* ============= FONT =========================================================================================== 
		 * remove face and size from font tags
		 */
		$clearFontAttributes = array(
			'/<font( face="[^"]*")+/ism' => '<font', // notice the space - finds font tags with only the face attribute
			'/<font( size="[^"]*")+/ism' => '<font',
		);
		$value = $this->recursivePregReplace(array_keys($clearFontAttributes), array_values($clearFontAttributes), $value);
		$emptyFontTags = array(
			'/<font>\r*<\/font>/ism' => '', // notice the space - finds font tags with only the face attribute
			'/<font>\n*<\/font>/ism' => '',
			'/<font>(.+?)<\/font>/ism' => '$1',
		);
		$value = $this->recursivePregReplace(array_keys($emptyFontTags), array_values($emptyFontTags), $value);
		
		/* 
		 * ============= END FONT =======================================================================================
		*/
		/* ============= IE - WORD Specific Filtering ================================================================================== 
		 */
		$ieJunk = array(
			'/<st1:(.+?)>/ism' => '', // notice the space - finds font tags with only the face attribute
			'/<\/st1:(.+?)>/ism' => '',
			'/<o:p><\/o:p>/ism' => '',
		);
		$value = $this->recursivePregReplace(array_keys($ieJunk), array_values($ieJunk), $value);
		/* 
		 * ============= END IE - WORD Specific Filtering ==================================================================================
		*/
		/* ============= IE - WORDPAD Specific Filtering ================================================================================== 
		 */
// 		App_Form_FormHelper::zdebugUsageToFile($value."\n\n", "pre pure", APPLICATION_PATH."/../temp/buildSrsFormAction.txt");

		// table attribute processing
// 		$value = $this->removeAttributes($value, 'table', array('bordercolor', 'dir'));
// 		$value = $this->removeAttributes($value, 'td', array('bgcolor', 'height', 'width'));
// 		$value = $this->removeAttributes($value, 'span', array('lang'));
// 		$value = $this->limitToAttributes($value, 'table', array('border'));
		
// 		App_Form_FormHelper::zdebugUsageToFile($ret, "post pure", APPLICATION_PATH."/../temp/buildSrsFormAction.txt");
		/* 
		 * ============= END IE - WORDPAD Specific Filtering ==================================================================================
		*/
		
		/*
        * Remove blank span tags with lang="EN"
        */
		while ( preg_match ( '/<span lang="EN"(\s| ||\sstyle="")>' . $possibleEmptySpace . '<\/span>/ism', $value ) > 0 )
			$value = preg_replace ( '/<span lang="EN"(\s| ||\sstyle="")>' . $possibleEmptySpace . '<\/span>/ism', '', $value );
			
		/*
        * After replacing blank p's we wind up with more blank p's 
        * so loop until the count is 0.
        */
// 		while ( preg_match ( '/<p>' . $possibleEmptySpace . '<\/p>/ism', $value ) > 0 )
// 			$value = preg_replace ( '/<p>' . $possibleEmptySpace . '<\/p>/ism', '', $value );
		/* ============= empty tags ==================================================================================
		 */
		$emptyTags = array(
			'/<p>' . $possibleEmptySpace . '<\/p>/ism' => '',
			'/<span' . $possibleEmptySpace . '>' . $possibleEmptySpace . '<\/span>/ism' => '',
			'/<(b|u|i)' . $possibleEmptySpace . '>' . $possibleEmptySpace . '<\/(b|u|i)' . $possibleEmptySpace . '>/ism' => '',
		);
		$value = $this->recursivePregReplace(array_keys($emptyTags), array_values($emptyTags), $value);
		/*
		 * ============= end empty tags ==================================================================================
		*/
		
		/*
        * After replacing blank spans we wind up with more blank spans 
        * so loop until the count is 0.
        */
// 		while ( preg_match ( '/<span' . $possibleEmptySpace . '>' . $possibleEmptySpace . '<\/span>/ism', $value ) > 0 )
// 			$value = preg_replace ( '/<span' . $possibleEmptySpace . '>' . $possibleEmptySpace . '<\/span>/ism', '', $value );
			
		/*
        * Replace blank b u and i tags
        */
// 		while ( preg_match ( '/<(b|u|i)' . $possibleEmptySpace . '>' . $possibleEmptySpace . '<\/(b|u|i)' . $possibleEmptySpace . '>/ism', $value ) > 0 )
// 			$value = preg_replace ( '/<(b|u|i)' . $possibleEmptySpace . '>' . $possibleEmptySpace . '<\/(b|u|i)' . $possibleEmptySpace . '>/ism', '', $value );
			
		
		/*
		 * convert all Mso* class names to MsoNormal
		 */
		$msoClasses = array(
			'/<p class="Mso[^Normal](.+?)"/ism' => '<p class="MsoNormal"',
		);
		$value = $this->recursivePregReplace(array_keys($msoClasses), array_values($msoClasses), $value);
		
		
		/*
		 * Remove the extra span tags inside of p tags.
		*  this is killing some large text fields
		*/
		$value = preg_replace_callback ( '/<p class="Mso(.+?)"' . $possibleEmptySpace . '><span' . $possibleEmptySpace . '>(.+?)<\/span><\/p>/', array (&$this, "removeInnerSpans" ), $value );
// 		try {
// 			$tmpVal = preg_replace_callback ( '/<p class="Mso(.+?)"' . $possibleEmptySpace . '><span' . $possibleEmptySpace . '>(.+?)<\/span><\/p>/', array (&$this, "removeInnerSpans" ), $value );
// 			App_Form_FormHelper::zdebugUsageToFile($tmpVal." SUCCESS\n\n", "preg_replace_callback", APPLICATION_PATH."/../temp/buildSrsFormAction.txt");
// 			$value = $tmpVal;
// 		} catch (Exception $e) {
// 			// error
// 			App_Form_FormHelper::zdebugUsageToFile("ERROR\n\n", "preg_replace_callback", APPLICATION_PATH."/../temp/buildSrsFormAction.txt");
// 		}
		
		/*
        * Remove the extra span tags inside of p tags.
        */
		//$value = preg_replace_callback('/<p class="Mso(.+?)"(\s| |)>(.+?)<span(\s| |)>(.+?)<\/span>(.+?)<\/p>/',
		//						 array(&$this, "removeInnerBUISpans"), $value);
		

		/*
        * The <p class="MsoNormal" ></p> instances are left after taking
        * &nbsp, p, and span tags out of them.  These are then the Word 
        * equivilent of the <br /> tag.
        */
		$value = preg_replace ( '/<p class="MsoNormal"' . $possibleEmptySpace . '>' . $possibleEmptySpace . '<\/p>/ism', "<br />", $value );
		
		/* ================================================================================================================================
		 * RESTORE PERIOD SPACE SPACE
		* Replace period and two spaces with a code
		* restore the period and spaces later
		*/
// 		$value = preg_replace ( '/Xxxx______STORED_PERIOD_SPACE_SPACE_______xxxX/ism', ".&nbsp; ", $value );
		$value = preg_replace ( '/____PER_NB_SP_____/ism', '&nbsp; ', $value );
		$value = trim($value);
// 		App_Form_FormHelper::zdebugUsageToFile($value."\n\n", "End", APPLICATION_PATH."/../temp/buildSrsFormAction.txt");
		return $value;
	}
}
