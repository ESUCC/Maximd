<?php
//
//class PdfController extends Zend_Controller_Action {
//	
//	public function indexAction() {
//		
//		$preFileA = APPLICATION_PATH .'/../temp/form004-1000254.pdf';
//		$preFileB = APPLICATION_PATH .'/../temp/08-unit-testing.pdf';
//		$output = APPLICATION_PATH .'/../temp/output.pdf';
//		
//		// Load a PDF document from a file
//		$pdfA = Zend_Pdf::load ( $preFileA );
//		$pdfB = Zend_Pdf::load ( $preFileB );
//		
//		$pdf = new Zend_Pdf();
//		$page1 = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
//		$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER);
//		$page1->setFont($font, 36);
//		$page1->drawText('Some text...', 72, 720);
//		$pdf->pages[] = $page1;
//		$pdf->save($output);
//		die();
//		
////		$pdfBlank = Zend_Pdf::load();
////		$pdfBlank->pages[] = clone $pdfA->pages[1];
//		
////		foreach($pdfB->pages as $x => $page)
////		{
////			$pdfA->pages[] = clone $pdfB->pages[$x];
////		}
////		
////		
////		
////            /* IE HACK FOR SECURE SITE DOWNLOAD */
////            header("Cache-Control: public, must-revalidate");
////            header("Pragma: hack");
////            header("Content-Description: File Transfer");
////            header('Content-disposition: attachment;
////                   filename=jesse.pdf');
////            header("Content-Type: application/pdf");
////            header("Content-Transfer-Encoding: binary");
//////            header('Content-Length: '. filesize($file));
////            echo $pdfA->render();
////            exit();
//
//		
//		
//    $command = base64_encode($preFileA . ' ' . $preFileB . ' ' ); //encode and then decode the command string
//    $command = base64_decode($command);
//
//    $command = "pdftk $command output $output";
//echo $command;
//    passthru($command); //run the command
//    die(); 		
//	}
//}