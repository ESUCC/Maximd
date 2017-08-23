<?php

/**
 * Form018 model
 *
 */
class Model_Form018 extends Model_AbstractForm
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
	
	
	public function writevar1($var1,$var2) {
	
	    ob_start();
	    var_dump($var1);
	    $data = ob_get_clean();
	    $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
	    $fp = fopen("/tmp/textfile.txt", "a");
	    fwrite($fp, $data2);
	    fclose($fp);
	}
	public function find($id, $accessMode = "view", $page =1, $versionNumber = 1, $checkout = 0)
	{
		if(false === parent::buildDbForm($id, $accessMode, $page, $versionNumber, $checkout))
		{
			return false;
		}

				
        // if date_mdt is empty, update with date from most recent final form 11
//        if('' == $this->db_form_data['date_mdt'] && 1 == $page)
//        {
//            $form004Obj = new Model_Table_Form004;
//            $form004 = $form004Obj->mostRecentFinalForm($id_student);
//            $this->db_form_data['date_mdt'] = $form004['date_notice'];
//        }
        
        if('all' == $page || 1 == $page)
        {
            
            
            // GOALS
            $select         = $this->db_form->select()->where("lower(status) != 'deleted' OR status is null")->order('timestamp_created ASC');
            $subformRecords     = $this->db_form->findDependentRowset('Model_Table_Form018Goal', 'Model_Table_Form018', $select);
            $this->db_form_data['iep_form_018_goal']['count'] = count($subformRecords);
            $this->db_form_data['iep_form_018_goal']['subformTitle'] = 'Part 1: Measurable Postsecondary Goals';
            $rownum = 1;
            foreach($subformRecords as $db_row)
            {
                // add the db data for this subform to the main form data array
                $this->db_form_data['iep_form_018_goal_'.$rownum] = $db_row->toArray();
                // add a row number based on sort position - just used for user display
                $this->db_form_data['iep_form_018_goal_'.$rownum]['rownumber'] = $rownum;
                $rownum++;
            }
            $this->subformIndexToModel['iep_form_018_goal'] = "Model_Table_Form018Goal";
            
            // AGENCY
            $select         = $this->db_form->select()->where("lower(status) != 'deleted' OR status is null")->order('timestamp_created ASC');
         //  $this->writevar1($select,'this is the select statement');
           
            $subformRecords     = $this->db_form->findDependentRowset('Model_Table_Form018Agency', 'Model_Table_Form018', $select);
          //  $this->writevar1($subformRecords,'this is sub form records');           
            
            $this->db_form_data['iep_form_018_agency']['count'] = count($subformRecords);
            $this->db_form_data['iep_form_018_agency']['subformTitle'] = 'Part 3: Community Contacts';
            $rownum = 1;
            foreach($subformRecords as $db_row)
            {
                $this->writevar1($db_row,'thjis is where we are supposed to add it');
                
                // add the db data for this subform to the main form data array
                $this->db_form_data['iep_form_018_agency_'.$rownum] = $db_row->toArray();
                // add a row number based on sort position - just used for user display
                $this->db_form_data['iep_form_018_agency_'.$rownum]['rownumber'] = $rownum;
                $rownum++;
            }
            $this->subformIndexToModel['iep_form_018_agency'] = "Model_Table_Form018Agency";
            
            // TEAM MEMBERS
            $select         = $this->db_form->select()->where("lower(status) != 'deleted' OR status is null")->order('timestamp_created ASC');
            $subformRecords     = $this->db_form->findDependentRowset('Model_Table_Form018TeamMember', 'Model_Table_Form018', $select);
            $this->db_form_data['iep_form_018_team_member']['count'] = count($subformRecords);
            $this->db_form_data['iep_form_018_team_member']['subformTitle'] = 'Team Member(s) Contributing to this Summary:';
            $rownum = 1;
            foreach($subformRecords as $db_row)
            {
                // add the db data for this subform to the main form data array
                $this->db_form_data['iep_form_018_team_member_'.$rownum] = $db_row->toArray();
                // add a row number based on sort position - just used for user display
                $this->db_form_data['iep_form_018_team_member_'.$rownum]['rownumber'] = $rownum;
                $rownum++;
            }
            $this->subformIndexToModel['iep_form_018_team_member'] = "Model_Table_Form018TeamMember";
        }

        if('all' == $page || 2 == $page)
        {
            // SUPPLEMENTAL FORMS
            $select         = $this->db_form->select()->where("lower(status) != 'deleted' OR status is null")->order('timestamp_created ASC');
            $subformRecords     = $this->db_form->findDependentRowset('Model_Table_Form018SupplementalForm', 'Model_Table_Form018', $select);
            $this->db_form_data['iep_form_018_supp']['count'] = count($subformRecords);
            $this->db_form_data['iep_form_018_supp']['subformTitle'] = 'Supplemental Forms';
            $rownum = 1;
            foreach($subformRecords as $db_row)
            {
                // add the db data for this subform to the main form data array
                $this->db_form_data['iep_form_018_supp_'.$rownum] = $db_row->toArray();
                // add a row number based on sort position - just used for user display
                $this->db_form_data['iep_form_018_supp_'.$rownum]['rownumber'] = $rownum;
                $rownum++;
            }
            $this->subformIndexToModel['iep_form_018_supp'] = "Model_Table_Form018SupplementalForm";
        }            
		return $this->db_form_data;
	}	

}
