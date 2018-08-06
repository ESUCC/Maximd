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
			throw new Exception( 'You do not have permission to enter this area.' );
			return false;
		}
	}

    protected function setupStudentSearch()
    {
        $this->view->collectionEditing = true;
//Mike maade this false
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

           // Mike changed this 7-30-2018 SRS-269 to prevent Original or New decision
          //  if ('srs' == $this->view->currentSearchPref && 'iepweb03' != APPLICATION_ENV) {
               if ('srs1' == $this->view->currentSearchPref && 'iepweb03' != APPLICATION_ENV) {
                // redirect to ZF
                header("Location:https://iepdev.nebraskacloud.org/srs.php?area=student&sub=list");
              //  header("Location:https://iepweb02dev.nebraskacloud.org/student/search");
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

        if (isset($userSess->user->searchCacheKey)) {
            $cacheKey = $userSess->user->searchCacheKey;
        } else {
            $cacheKey = null;
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

     /*    if (2 != $privCheck->getMinPriv() && 1 != $privCheck->getMinPriv()) {
           Mike changed this for jira SRS-14 on APril 18th for the live site.
    */
            if($privCheck->getMinPriv() > 3 ){
                $this->view->showAll = true;
            } else {
                $this->view->showAll = false;
            }
        } else {
            $this->view->showAll = true ;
        }
        //$this->setJqueryLayout(true);
        $this->view->hideLeftBar = true;
        $this->view->headLink()->appendStylesheet('/css/student_search.css');
        $searchForm = new Form_SearchStudent();
        $format = new Model_Table_FormatColumns();
        $formats = $format->getFormatPairs(new Zend_Session_Namespace('user'));
        $searchForm->getElement('format')->addMultiOptions($formats);
        $searchForm->getElement('format')->addMultiOptions(array('custom' => '(Create Custom Format)'));
        $searchForm->getElement('format')->addMultiOptions(array('delete' => '(Delete Searches)'));
        $this->view->searchForm = $searchForm;
        $this->view->searchFormsForm = new Form_SearchForm();
    }

}