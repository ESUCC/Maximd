<?php

require_once (APPLICATION_PATH . '/../library/App/Filter/Htmlpurifier/HTMLPurifier.auto.php');

class My_Form_Filter_StringReplaceWordFormattingUniversal implements Zend_Filter_Interface
{
    public function makeTabs($matches)
    {
    	$count = substr_count($matches[1], ' ');
	    return str_replace(' ', "<span>&nbsp;</span>", $matches[1]);
    	return str_replace(' ', "&nbsp;", $matches[1]);
    }
	
    public function filter($value)
    {
    	$sessPure = new Zend_Session_Namespace('htmlpurifier');
    	
		//make sure _all_ html entities are converted to the plain ascii equivalents - it appears
        //in some MS headers, some html entities are encoded and some aren't
        // TABS CAN BE DESTROYED - because &nbsp; will be converted to spaces
    	$value = html_entity_decode($value, ENT_QUOTES, "utf-8" );
		
    	// replace known multi-byte MS word codes
    	// with single byte equivalents
		$value = $this->sanitizeString($value);
		
		// limit to locale values
		$value = $this->limitToLocale($value);

		// purify the text with htmlPurifier
        // TABS CAN BE DESTROYED STILL! - because &nbsp; 
        // will be converted to spaces by purifier
		$value = $this->purify($value);

        // attempt to find tab spans
//		$value = preg_replace_callback(
//		    '/<span>( +)<\/span>/',
//		    'My_Form_Filter_StringReplaceWordFormattingUniversal::makeTabs',
//		    $value
//		);
		
    	// remove additional class, style and align tags
    	// that are not picked up by purifier
		$value = $this->filterHelper('/\sclass\=(\'|\")(.*?)(\1)/im', '', $value);
		$value = $this->filterHelper('/\scellpadding\=(\'|\")(.*?)(\1)/im', '', $value);
//        $value = $this->filterHelper('/vAlign\=(\'|\")(.*?)(\1)/im', '', $value);
//        $value = $this->filterHelper('/\sstyle\=(\'|\")(.*?)(\1)/im', '', $value);
//		$value = $this->filterHelper('/font-size:.+?;/im', '', $value);

        // condense multiple whitespaces
        $value = $this->filterHelper('/ +/', ' ', $value);
        
        // remove comments
        $value = $this->filterHelper("/<!--.*?-->/ms", '', $value);

        // replace <p> </p> with <BR />
//        $value = $this->filterHelper("/<p>\s+?<\/p>/im", '<br />', $value);
        
		$value = trim($value);
		return $value;
    }
	
    public function purify($value)
    {
    	// will convert align tags to style tags
    	// will convert font tags to style tags
		$purifier = new HTMLPurifier();
	    $purifier->config->set('HTML.TidyLevel', 'heavy');
		$clean_html = $purifier->purify( $value );
		return $clean_html;
    }
    public function filterHelper($matchPattern, $replace = '', $value)
    {
		if(isset($this->debug) && $this->debug) {
			preg_match_all($matchPattern, $value, $matches);
			Zend_Debug::dump($matches[0], $matchPattern);
		}
		
		return preg_replace($matchPattern, $replace, $value);
    }
    function limitToLocale($value) {
    	// limit to allowable characters  
    	$zaplist = array();
        $array = $this->mb_str_split($value); // split the string into it's multibyte characters
		$uniqueCharacters = array_unique($array);
		
		if(count($uniqueCharacters) > 1) {
	    	foreach ( $uniqueCharacters as $char ){
	        	if (!ctype_alnum($char) && 
	        		!ctype_cntrl ($char) && 
	        		!ctype_print ($char) && 
	        		!ctype_cntrl ($char) && 
	        		!ctype_cntrl ($char) 
	        		){
					$zaplist[] = $char;	
					//$this->dumpChar($char); // view list of chars removed
	        	}
	        	
			}
			if(count($zaplist) > 0 ) {
				$value = str_replace ($zaplist,'',$value);
			}
		}
		return $value;
    }
	/**
	* Remove unwanted MS Word high characters from a string
	*
	* @param string $string
	* @return string $string
	*/
	function sanitizeString($string = null) {
		if (is_null ( $string ))
			return false;
		
		//-> Replace all of those weird MS Word quotes and other high characters
		$badwordchars = array ("\xe2\x80\x98", // left single quote
			"\xe2\x80\x99", // right single quote
			"\xe2\x80\x9c", // left double quote
			"\xe2\x80\x9d", // right double quote
			"\xe2\x80\x94", // em dash
			"\xe2\x80\x93", // em dash 2
			"\xe2\x80\xa6", // elipses
			"\xc2\xa0"		// space
			)
		;
		$fixedwordchars = array ("'", "'", '"', '"', '&mdash;', '&mdash;', '...', ' ');
		return  ( str_replace ( $badwordchars, $fixedwordchars, $string ) );
	}
	
	function mb_str_split( $string ) {
	    # Split at all position not after the start: ^
	    # and not before the end: $
	    return preg_split('/(?<!^)(?!$)/u', $string );
	} 	
	function dumpChar($value) {
		$unpack=unpack("H*",$value);
		$value = '\x'.implode(str_split($unpack[1],2),'\x'); 
		Zend_Debug::dump($value, 'bad character detected'); 
	}
//    public function replacePTag(&$matches)
//    {
//        if (strlen(trim($matches[1])) > 0)
//            return $matches[1] . '<br />';
//    }
//
//
//	function convert_to ( $source, $target_encoding )
//    {
//	    // detect the character encoding of the incoming file
//	    $encoding = mb_detect_encoding( $source, "auto" );
//	      
//	    // escape all of the question marks so we can remove artifacts from
//	    // the unicode conversion process
//	    $target = str_replace( "?", "[question_mark]", $source );
//	      
//	    // convert the string to the target encoding
//	    $target = mb_convert_encoding( $target, $target_encoding, $encoding);
//	      
//	    // remove any question marks that have been introduced because of illegal characters
//	    $target = str_replace( "?", "", $target );
//	      
//	    // replace the token string "[question_mark]" with the symbol "?"
//	    $target = str_replace( "[question_mark]", "?", $target );
//	  
//	    return $target;
//    }

	
        // massage multiple returns into one return
//        $value = $this->filterHelper("|(\s*<br />)+(\n*)(\r*)|im", '<br />', $value);
//        $value = $this->filterHelper("|(\s*<br />)+(\n*)(\r*)|im", '<br />', $value);
		
	
}
