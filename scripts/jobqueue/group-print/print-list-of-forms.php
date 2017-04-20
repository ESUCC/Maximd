<?php
error_reporting(E_ALL);

/**
 * general setup of the application
 */
require_once('../../cmd_line_helper.php');
bootCli(APPLICATION_PATH, APPLICATION_ENV);

$vars = ZendJobQueue::getCurrentJobParams();

//if(!isset($vars['formList'])) {
//    $vars = array (
//        "formList" => array (
//            0 => array (
//                "id" => 1173613,
//                "version_number" => 3,
//                "id_student" => 1001077,
//                "id_county" => "99",
//                "id_district" => "9999",
//                "id_school" => "996",
//                "formNum" => "004",
//                "sessIdUser" => 1000254,
//                "summary" => 1,
//                "PHPSESSID" => "866aruom838t3gvj449vlff6r3"
//            ),
//        ),
//        "filePath" => APPLICATION_PATH.'/../tmp_printing/1000254' .'-'.strtotime('now').'.pdf'
//
//    );
////    Zend_Debug::dump($vars);
//}

if(isset($vars['formList'])) {

    $config = Zend_Registry::get ( 'config' );

    $httpParams = array(
        'maxredirects' => 5,
        'timeout'  => 600,
    );

    // new HTTP request to new
//    Zend_Debug::dump($config->DOC_ROOT);
    $newSiteClient = new Zend_Http_Client($config->DOC_ROOT.'login', $httpParams);
    $newSiteClient->setMethod(Zend_Http_Client::POST);
    $newSiteClient->setCookieJar();
    $newSiteClient->setParameterPost('email', 'archiver');
    $newSiteClient->setParameterPost('password', 'thisIsTheLoginForTheArchiver123');
    $newSiteClient->setParameterPost('submit', 'Continue');
    $newSiteClient->setParameterPost('agree', 't');
    $response = $newSiteClient->request();

    $dom = new Zend_Dom_Query($response->getBody());
    if($dom->query('#agree')->count()>=1) {
        // login failed
        Zend_Debug::dump('login failed to '.$config->DOC_ROOT);
    }

    // new HTTP request to old site
    $oldSiteClient = new Zend_Http_Client('https://iep.esucc.org/logon.php?option=1', $httpParams);
    $oldSiteClient->setMethod(Zend_Http_Client::POST);
    $oldSiteClient->setCookieJar();
    $oldSiteClient->setParameterPost('userName', 'archiver');
    $oldSiteClient->setParameterPost('password', 'thisIsTheLoginForTheArchiver123');
    $oldSiteClient->setParameterPost('ferpa', '1');
    $oldSiteClient->setParameterPost('count', '1');
    $response = $oldSiteClient->request();

    $dom = new Zend_Dom_Query($response->getBody());
    if($dom->query('#ferpa')->count()>=1) {
        // login failed
        Zend_Debug::dump('login failed on iep.esucc.org');
    }
    $initialPathList = array();
    /**
     * try to print each form
     */
     $mergedPdf = new Zend_Pdf();
     foreach ($vars['formList'] as $formPrintConfig) {
         $body = getPrintHtml($oldSiteClient, $newSiteClient, $formPrintConfig);
         if(false != $body) {
             $pdf = Zend_Pdf::parse($body);
             try {
                 foreach ($pdf->pages as $x => $page) {
                     $mergedPdf->pages [] = clone $pdf->pages [$x];
                 }
             } catch (Exception $e) {
                 // could not add pdf to form
                 echo "could not add pages to pdf";
                 die();
             }
         }
     }
     $mergedPdf->save($vars['filePath']);
}
echo "complete";

function getPrintHtml($oldSiteClient, $newSiteClient, $formPrintConfig) {
    $config = Zend_Registry::get ( 'config' );

    $logger = new Zend_Log(new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log_jobqueue.txt'));
#    $logger->info("\n");
    $logger->info("\n============getPrintHtml==========");
#    $logger->info(var_export($_REQUEST, true));
    $logger->info($formPrintConfig);

    $formNumber = $formPrintConfig['formNum'];
    $summary = $formPrintConfig['summary'];
    $document = $formPrintConfig['id'];

    if(!$document) {
        return false;
    }
    // be sure to get the pdf from the right place
    if($formPrintConfig['version_number'] >= 9) {
        // zf site
        $url = $config->DOC_ROOT.'form'.$formNumber.'/print/document/'.$document.'/page/';
        if($summary) {
            $url .= '/summary/1';
        }
        
        $newSiteClient->setUri($url);
        $response = $newSiteClient->request();
//        Zend_Debug::dump($response, 'new');
    } else {
        // old site
        $url = 'https://iep.esucc.org/form_print.php?form=form_'.$formNumber.'&document='.$document;
        if($summary) {
            $url .= '&summary=1';
        }
        $oldSiteClient->setUri($url);
        $response = $oldSiteClient->request();
    }
    $logger->info("URL:" . $url);
//    $logger->info($response->getBody());


    $body = $response->getBody();
    if('<html>'==substr($body, 0, 6) || '<!DOCTYPE html'==substr($body, 0, 14)) {
        // error - not a pdf
        return false;
    } else {
        return $body;
    }
}

