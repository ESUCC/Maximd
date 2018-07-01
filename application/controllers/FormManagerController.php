<?php
//
//require_once(APPLICATION_PATH . '/../library/My/Classes/srs_managed_form.php');
///**
// * FormManagerController
// *
// * @uses      Zend_Controller_Action
// * @package   Paste
// * @license   New BSD {@link http://framework.zend.com/license/new-bsd}
// * @version   $Id: $
// */
//class FormManagerController extends Zend_Controller_Action
//{
//	/**
//	 * Home page; display site entrance form
//	 *
//	 * @return void
//	 */
//	public function indexAction()
//	{
//		//		Zend_debug::dump(APP_PATH);
//		$pathName = APPLICATION_PATH . '/controllers/';//$_POST['path'];
//		$formNumArr = array();
//		
//		$this->view->cmd_line_build = "";
//		if($pathName){
//
//			$request = $this->getRequest();
//			$response = $this->getResponse();
//			$dir = new DirectoryIterator($pathName);
//
//			foreach($dir as $fileInfo) {
//				if($fileInfo->isDot()) {
//					// do nothing
//				} else {
//					$controllerName = $fileInfo->__toString();
//					$prefix = strlen($controllerName);
//					if(21 == strlen($controllerName) && 'Form' == substr($controllerName, 0, 4) && 'Controller.php' == substr($controllerName, -14, 14))
//					{
//						$formNum = substr($controllerName, 4, 3);
//						$formManager = new srs_managed_form($request, $response, $controllerName, $formNum);
//						$formNumArr[$formNum] = $formManager;
//						
//					}
//
//
//
//				}
//
//			}
//
//		}
//		$this->view->formNumArr = $formNumArr;
//	}
//
////	function createjsAction()
////	{
////        //
////        // get incoming data
////        //        
////        $request = $this->getRequest();
////
////        //
////        // confirm keys exist
////        //
////        if (!$request->getParam('formNum')) {
////            //$this->_redirect('/survey/list');
////            throw new exception('You have not supplied the required parameters to perform this action');
////            return;
////        } else {
////        	$formNum = $request->formNum;
////        }
////        if (!$request->getParam('pageNum')) {
////            $pageNum = 1;
////        } else {
////        	$pageNum = $request->pageNum;
////        }
////        if (!$request->getParam('versionNum')) {
////            $versionNum = 1;
////        } else {
////        	$versionNum = $request->versionNum;
////        }
////        
////        $createFileName = 'form'.$formNum.'_p'.$pageNum.'_v'.$versionNum.'.js';
////        $createFilePath =  APPLICATION_PATH . '/../public/js/srs_forms/' . $createFileName;
////        
////        $fileContents = file_get_contents(APPLICATION_PATH . '/../library/My/File/Js/srs_form_page.js');
//////		Zend_debug::dump($fileContents);
////		Zend_debug::dump($createFilePath);
////		if(file_exists($createFilePath))
////		{
////            throw new exception('That file already exists.');
////		} else {
////			
////			file_put_contents(APPLICATION_PATH . '/../library/My/File/Js/srs_form_page.js', $fileContents);
////		} 
////		die();
////	}
//}
