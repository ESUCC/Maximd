<?php

class App_Zend_Controller_Action_Abstract extends Zend_Controller_Action
{
    public function preDispatch()
    {
        /**
         * set the default email based on the config file.
         */
        $config = Zend_Registry::get ( 'config' );
        $transport = new Zend_Mail_Transport_Smtp($config->email->host, $config->email->toArray());
        Zend_Mail::setDefaultTransport($transport);

    }

	public function restoreMessage()
	{
        //
        // if redirectWithMessage was called
        // restore that message to the view here
		$session = new Zend_Session_Namespace('nebsrsmessage');
		
        if ($session->message) {
            $this->view->message = $session->message;
            unset($session->message);
        }
		
	}
    protected function redirectWithMessage($redirectto, $message)
    {
        $session = new Zend_Session_Namespace('nebsrsmessage');
        $session->message = $message;
        return $this->_redirect($redirectto);
    }


	protected function limitToAdminAccess() {
		$sessUser = new Zend_Session_Namespace ( 'user' );
		if (1000254 == $sessUser->sessIdUser || 1010818 == $sessUser->sessIdUser || 1019331 == $sessUser->sessIdUser) {
			// allow code to continue
			return true;
		} else {
			throw new Exception( 'You do not have permissions to enter this area.' );
			return false;
		}
	}
 
  
	function writevar1($var1,$var2) {
	
	    ob_start();
	    var_dump($var1);
	    $data = ob_get_clean();
	    $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
	    $fp = fopen("/tmp/textfile.txt", "a");
	    fwrite($fp, $data2);
	    fclose($fp);
	}   
    
    protected function setupStudentSearch()
    {
        
        $this->view->collectionEditing = true;

        if ($this->usersession->parent) {
            /**
             * not implemented for parents
             */
        } else {
            /**
             * check if personnel has a site pref for search
             */
            $personnelObj = new Model_Table_PersonnelTable();
            $personnel = $personnelObj->fetchRow(
                "id_personnel = " . $this->usersession->sessIdUser
            );
            
            $this->view->currentSearchPref = $personnel->pref_student_search_location;
            $t=$this->view->currentSearchPref;
         //   $this->writevar1($t,'this is t'); // this returns srs-zf.
         
            
        //    $this->writevar1($personnel,'this is the personnel'); 
           // taken from iep_personnel.  put it into this object.
           // this does return an arry of the user info
            
            if ('srs' == $this->view->currentSearchPref && 'maxim' != APPLICATION_ENV) {
                // redirect to ZF
               header("Location:https://iepd.nebraskacloud.org/srs.php?area=student&sub=list");
                exit;
            }
        }
        /**
         * END --- update personnel if user changes pref
         */

        /*
         * Check to see if the user has a cache key of results.  If they do
         * default the search to the result list.
         */
        $userSess = new Zend_Session_Namespace('user');
      //  $this->writevar1($userSess,'this is the session userSess'); //sets the anamespace to user to 'user'
       
        if (isset($userSess->user->searchCacheKey)) {
            $cacheKey = $userSess->user->searchCacheKey;
         //   $this->writevar1($cacheKey,'this is the cache key'); //returns something like bup7cadkqzk
            
        } else {
            $cacheKey = null;
          //  $this->writevar1($cacheKey,'this is the cache key'); 
        }
       
        if (!empty($cacheKey)) {
            if (Model_CacheManager::isCached(Zend_Registry::get('searchCache'), $cacheKey)) {
                $this->view->cacheKey = $cacheKey;
                $this->view->searchPage = $userSess->user->searchPage;
            } else
                $this->view->cacheKey = false;
        } else
            $this->view->cacheKey = false;

        /*
        * Check to make sure the user is a District Manager. If
        * they are, don't showAll.  Else show all users by default.
        */
         
        if (false == $this->usersession->parent) {
            $privCheck = new My_Classes_privCheck($this->usersession->user->privs);
            // Mike changed this 3-3-2017 so that these people would not see all the students every time
            // they hit the student button.
            
         //  if (2 != $privCheck->getMinPriv() && 1 != $privCheck->getMinPriv()) {
            if($privCheck->getMinPriv() > 3 ){ 
                $this->view->showAll = true;
            } else {
                $this->view->showAll = false;
            }
        } else {
            $this->view->showAll = true;
        }
        
   //    writevar($this->view->showAll,'this is the true or false of the value');  This appears to be true for all
        //$this->setJqueryLayout(true);
        $this->view->hideLeftBar = true;
        $this->view->headLink()->appendStylesheet('/css/student_search.css');
        $searchForm = new Form_SearchStudent();
        $format = new Model_Table_FormatColumns();
        
        $formats = $format->getFormatPairs(new Zend_Session_Namespace('user'));
       // $this->writevar1($format,'this is the table headings.');// Dont know what this is
        $searchForm->getElement('format')->addMultiOptions($formats);
        $searchForm->getElement('format')->addMultiOptions(array('custom' => '(Create Custom Format)'));
        $searchForm->getElement('format')->addMultiOptions(array('delete' => '(Delete Searches)'));
        $this->view->searchForm = $searchForm;
      //  $this->writevar1($searchForm,'this is the search form');
        $this->view->searchFormsForm = new Form_SearchForm();
    }

}
