<?php

/**
 * Form010 model
 *
 */
class Model_Form010 extends Model_AbstractForm
{

    /**
     * $subformIndexToModel - array
     *
     * @var array
     */
	var $subformIndexToModel = array();
	
    /**
     * $db_form_data - array
     *
     * @var array
     */
	var $db_form_data = array();
	
	public function find($id, $accessMode = "view", $page =1, $versionNumber = 1, $checkout = 0)
	{
	/*
	     * Mike replaced this with the catch try
	    if(false === parent::buildDbForm($id, $accessMode, $page, $versionNumber, $checkout))
	    {
	        
	        return false;
	    }
	    */
	    
		try { 
			
			    $this->buildDbForm($id, $accessMode, $page, $versionNumber, $checkout);
			
			
			
			}
			catch (App_Exception_Checkout $e) {
			    $this->writevar1($e->getMessage(),'this is the error message');
			//    $this->view->scott=$e->getMessage();
			$t[0]['message']=$e->getMessage();
			    return $t;
			}

        
        // get the related IEP and then progress reports
        if('all' == $page || 1 == $page)
        {
            $iepObj = new Model_Table_Form004();
            $iep = $iepObj->find($this->db_form_data['id_form_004'])->current();
            
            // make sure we have an iep and get the related progress reports
            if(null != $iep) {
	            $subformDbRows  = $iep->findDependentRowset('Model_Table_Form010')->toArray();
	            $this->db_form_data['progress_reports']['count'] = count($subformDbRows); 
	            sort($subformDbRows);
	            /**
	              * loop through progress reports (form 010)
	             */
	            $rownum = 1;
	            foreach($subformDbRows as $db_row)
	            {
	                $this->db_form_data['progress_reports_'.$rownum] = $db_row;
	                $this->db_form_data['progress_reports_'.$rownum]['rownumber'] = $rownum;
	                
	                if(isset($prevPR) && $this->db_form_data['id_form_010'] == $db_row['id_form_010']) {
	                    $this->db_form_data['prevFormData'] = $prevPR;
	                }
	                
	                /**
	                  * store the PR in case it is needed for filling in the next report
	                 */
	                $prevPR = $this->db_form_data['progress_reports_'.$rownum];
	
	                /**
	                  * get progress for the previous report in case it's needed
	                 */
	                $goalProgressObj = new Model_Table_Form004GoalProgress();
	                $progSubformDbRows = $goalProgressObj->getWhere('id_form_010', $db_row['id_form_010'], 'timestamp_created');//$goalProgressObj->find($db_row['id_form_010']);
	                $x = 1;
	                // loop through progress
	                foreach($progSubformDbRows as $prog_row)
	                {                        
	                    // get the goal for reference
	                    $prevPR['goal_progress'.'_'.$x] = $prog_row->toArray();
	                    $x++;
	                }
	                
	                $rownum++;
	            }
            }
        } 
        
        // build sub forms		
		if('all' == $page || 1 == $page)
		{
			// GOAL PROGRESS
		    $translate = Zend_Registry::get('Zend_Translate');
			$modelName		= 'Model_Table_Form004GoalProgress';
			$subformName 	= 'goal_progress'; 
			$select 		= $this->db_form->select()->order('timestamp_created ASC');
			$subformDbRows 	= $this->db_form->findDependentRowset($modelName, 'Model_Table_Form010', $select);
			$this->db_form_data[$subformName]['count'] = count($subformDbRows);
			$this->db_form_data[$subformName]['subformTitle'] = $translate->_('Goal Progress');
			$rownum = 1;
			foreach($subformDbRows as $db_row)
			{
			    //echo "processing $rownum: {$db_row['id_form_010']}<BR>";
				$this->db_form_data[$subformName.'_'.$rownum] = $db_row->toArray();
				$this->db_form_data[$subformName.'_'.$rownum]['rownumber'] = $rownum;
				
				// get the goal for reference
				$goalDbRow = $db_row->findDependentRowset('Model_Table_Form004Goal', 'Model_Table_Form004GoalProgress');
				$this->db_form_data[$subformName.'_'.$rownum]['goal'] = $goalDbRow->current()->toArray();
				
				$rownum++;
			}
			$this->subformIndexToModel[$subformName] = $modelName;
			
            // get the last form 004 goal's progress
            $form010Obj = new Model_Table_Form010;
            $form010 = $form010Obj->mostRecentFinalForm($this->db_form['id_student']);

            if(null != $form010) {
    	        $modelform = new Model_Table_Form004GoalProgress();
		        $select = $modelform->select()->where("id_form_010 = '{$form010['id_form_010']}'")->order('timestamp_created ASC');
	    	    $this->db_form_data['last_form_010']['goals'] = $modelform->fetchAll($select)->toArray();
            }
	        
		} 
		return $this->db_form_data;
	}	
}
