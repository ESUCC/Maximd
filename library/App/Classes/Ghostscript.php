<?php

class App_Classes_Ghostscript {
    
	/*
	 * convert pdf to pdf 1.4 for print merging 
	 */
	static function convertToPdf14($filePath) {
		
		$exportName=basename($filePath, '.pdf').'-c14.pdf';
		$exportPath=dirname($filePath)."/$exportName";
		 
		$execStr = "ps2pdf14 '$filePath' '$exportPath'";
		exec($execStr,$output, $returnValue);

		if(0===$returnValue) {
			return $exportPath;
		}
	  	return false;
	}
	
}


