<?php

class My_Classes_class_neb_acl {
	
    var $defaultRoles = array('dev', 'sa', 'sm', 'esu', 'aesu', 'dm', 'adm');
    var $default_school_acl;
    var $default_state_acl;
    var $default_district_acl;
    var $class_dev = false;
    var $class_sa = false;

    
    public function __construct()
    {
        $this->acl = new Zend_Acl();
        
        $this->initDefaultRoles();

        $this->initStateResources();
        $this->initDistrictResources();
        $this->initEsuResources();

        $this->initStatePermissions();
        $this->initDistrictPermissions();
        $this->initEsuPermissions();

    }

    
    public function initDefaultRoles()
    {
            
        $this->acl->addRole(new Zend_Acl_Role('noaccess'));
        $this->acl->deny('noaccess', null, null); // no access
        
        $this->acl->addRole(new Zend_Acl_Role('sm'), 'noaccess');
        $this->acl->addRole(new Zend_Acl_Role('dm'), 'noaccess');
        $this->acl->addRole(new Zend_Acl_Role('esu'), 'noaccess');
                
    }

    public function initSchoolResources()
    {                    
        //
        // add resources
        //
        $this->default_school_acl = clone $this->acl;
        $this->default_school_acl->add(new Zend_Acl_Resource('school'));

        $this->default_school_acl->add(new Zend_Acl_Resource('school_view'), 'school');
        $this->default_school_acl->add(new Zend_Acl_Resource('school_edit'), 'school');
        $this->default_school_acl->add(new Zend_Acl_Resource('school_create'), 'school');
        $this->default_school_acl->add(new Zend_Acl_Resource('school_delete'), 'school');
            
    }
    
    public function initStateResources()
    {                    
        //
        // add resources
        //
        $this->default_state_acl = clone $this->acl;
        $this->default_state_acl->add(new Zend_Acl_Resource('state'));

        $this->default_state_acl->add(new Zend_Acl_Resource('state_view'), 'state');
        $this->default_state_acl->add(new Zend_Acl_Resource('state_edit'), 'state');
        $this->default_state_acl->add(new Zend_Acl_Resource('state_create'), 'state');
        $this->default_state_acl->add(new Zend_Acl_Resource('state_delete'), 'state');
            
        $this->default_state_acl->add(new Zend_Acl_Resource('esu'));

        $this->default_state_acl->add(new Zend_Acl_Resource('esu_view'), 'esu');
        $this->default_state_acl->add(new Zend_Acl_Resource('esu_edit'), 'esu');
        $this->default_state_acl->add(new Zend_Acl_Resource('esu_create'), 'esu');
        $this->default_state_acl->add(new Zend_Acl_Resource('esu_delete'), 'esu');

        $this->default_state_acl->add(new Zend_Acl_Resource('district'));

        $this->default_state_acl->add(new Zend_Acl_Resource('district_view'), 'district');
        $this->default_state_acl->add(new Zend_Acl_Resource('district_edit'), 'district');
        $this->default_state_acl->add(new Zend_Acl_Resource('district_create'), 'district');
        $this->default_state_acl->add(new Zend_Acl_Resource('district_delete'), 'district');
    }
    
    public function initDistrictResources()
    {                    
        //
        // add resources
        //
        $this->default_district_acl = clone $this->acl;
        $this->default_district_acl->add(new Zend_Acl_Resource('district'));

        $this->default_district_acl->add(new Zend_Acl_Resource('district_view'), 'district');
        $this->default_district_acl->add(new Zend_Acl_Resource('district_edit'), 'district');
        $this->default_district_acl->add(new Zend_Acl_Resource('district_create'), 'district');
        $this->default_district_acl->add(new Zend_Acl_Resource('district_delete'), 'district');
    }
    
    public function initEsuResources()
    {                    
        //
        // add resources
        //
        $this->default_esu_acl = clone $this->acl;
        $this->default_esu_acl->add(new Zend_Acl_Resource('esu'));

        $this->default_esu_acl->add(new Zend_Acl_Resource('esu_view'), 'esu');
        $this->default_esu_acl->add(new Zend_Acl_Resource('esu_edit'), 'esu');
        $this->default_esu_acl->add(new Zend_Acl_Resource('esu_create'), 'esu');
        $this->default_esu_acl->add(new Zend_Acl_Resource('esu_delete'), 'esu');

        $this->default_esu_acl->add(new Zend_Acl_Resource('district'));

        $this->default_esu_acl->add(new Zend_Acl_Resource('district_view'), 'district');
        $this->default_esu_acl->add(new Zend_Acl_Resource('district_edit'), 'district');
        $this->default_esu_acl->add(new Zend_Acl_Resource('district_create'), 'district');
        $this->default_esu_acl->add(new Zend_Acl_Resource('district_delete'), 'district');
    }
    
    public function initStatePermissions()
    {                    
        $this->perms_states = array();
        $this->perms_states['0'] = serialize($this->default_state_acl);
    }
    
    public function initDistrictPermissions()
    {                    
        $this->perms_districts = array();
        $this->perms_districts['00_0000'] = serialize($this->default_district_acl);
    }
    
    public function initEsuPermissions()
    {                    
        $this->perms_esu = array();
        $this->perms_esu['00'] = serialize($this->default_esu_acl);
    }
    public function insertUserPermissions($perms)
    {
        /*      privileges
            dev     - developer                     (system wide)
            sa      - System Administrator          (system wide)
            adm     - Associate District Manager    (at a district)
            dm      - District Manager              (at a district)
            sm      - School Manager                (at a school)
            asm     - Associate School Manager      (at a school)

        */
        //
        // loop through privilege records and allow access to specific resources
        //
        foreach($perms as $p)
        {
            //Zend_Debug::dump($p);
            if('dev' == $p['class'])
            {
                $this->class_dev = true;

            } elseif(1 == $p['class']) {
                $this->class_sa = true;
                //Zend_Debug::dump('set SA');

            } elseif('sm' == $p['class']) {
                // state manager

                // get default acl for this resource
                $acl_temp = $this->default_state_acl;                
                                
                // update specific resource acl with access 
                $acl_temp->allow('sm',
                        array('state_view', 'state_edit', 'state_create',
                              'esu_view', 'esu_edit',
                              'district_view', 'district_edit', 'district_create', 'district_delete',
                             ),
                        array('state', 'esu', 'district'));
                
                $this->perms_states['state'] = serialize($acl_temp);
                
            } elseif('dm' == $p['class']) {
                // district manager
                
                // HOW DO WE DEFINE SCHOOL ACCESS FOR DISTRICT MANAGERS
                
                // get default acl for this resource
                $acl_temp = $this->default_district_acl;
                
                // make sure required fields exist for this resource
                // county, district, district exists
                if(!isset($p['id_county']) || !isset($p['id_district']) ) echo "ERROR<BR>";
                
                // build primary key for this resource
                $cd_id = $p['id_county'] . '_' . $p['id_district'];
                
                // update specific resource acl with access 
                $acl_temp->allow('dm',
                        array('district_view', 'district_edit',
                              //'school_view', 'school_edit', 'school_create', 'school_delete'
                             ),
                        array('district'));//, 'school'
                
                $this->perms_districts[$cd_id] = serialize($acl_temp);
                
            } elseif('esu' == $p['class']) {
                //echo " esu manager<BR>";
                
                // HOW DO WE DEFINE SCHOOL ACCESS FOR DISTRICT MANAGERS
                
                // get default acl for this resource
                $acl_temp = $this->default_esu_acl;
                
                // make sure required fields exist for this resource
                // county, district, district exists
                if(!isset($p['id_neb_esu']) ) echo "ERROR<BR>";
                
                // build primary key for this resource
                $cd_id = $p['id_neb_esu'];
                
                // update specific resource acl with access 
                $acl_temp->allow('esu',
                        array('esu_view', 'esu_edit',
                              'district_view', 'district_edit', 'district_create', 'district_delete',
                             ),
                        array('esu', 'district'));
                
                $this->perms_esu[$cd_id] = serialize($acl_temp);
                
            }
        }
    }

    public function getPermissions($userID)
    {
        
        $db = Zend_Registry::get('db');
        $userID = $db->quote($userID);
        $select = $db->select()
                     ->from( array('s' => 'iep_privileges'),
                             array('*')
                           )
                     ->where( "id_personnel = $userID and status = 'Active'" );
        $results = $db->fetchAll($select);
        
        if(count($results) == 0) return -1;
        return $results;

        
    }
    public function getEsuIdForDistrict($id_county, $id_district)
    {
        
        $db = Zend_Registry::get('db');
        $id_county = $db->quote($id_county);
        $id_district = $db->quote($id_district);
        $select = $db->select()
                     ->from( array('s' => 'neb_district'),
                             array('*')
                           )
                     ->where( "id_county = $id_county and id_district = $id_district" );
        $results = $db->fetchAll($select);
        
        if(count($results) == 0) return -1;
        return $results[0]['id_neb_esu'];

        
    }
    public function isAllowed($resourceKey = null, $action = null, $resourceName = null)
    {                    
        if('district' == $resourceName) {
            //
            // check for district mgr priv
            //
            $tmpAcl = $this->getDistrictAcl($resourceKey);
            if($tmpAcl->isAllowed('dm', $action, $resourceName)) return $tmpAcl->isAllowed('dm', $action, $resourceName);

            //
            // check for esu mgr priv
            //
            
            //
            // check for state
            //
            $tmpAcl = $this->getStateAcl('state');
            if($tmpAcl->isAllowed('sm', $action, $resourceName)) return $tmpAcl->isAllowed('sm', $action, $resourceName);
            

        } elseif('esu' == $resourceName) {
            $tmpAcl = $this->getEsuAcl($resourceKey);
            if($tmpAcl->isAllowed('esu', $action, $resourceName)) return $tmpAcl->isAllowed('esu', $action, $resourceName);

            //
            // check for state
            //
            $tmpAcl = $this->getStateAcl('state');
            if($tmpAcl->isAllowed('sm', $action, $resourceName)) return $tmpAcl->isAllowed('sm', $action, $resourceName);
            
        } elseif('state' == $resourceName) {
            $tmpAcl = $this->getStateAcl('state');
            if($tmpAcl->isAllowed('sm', $action, $resourceName)) return $tmpAcl->isAllowed('sm', $action, $resourceName);

        } elseif('state' == $resourceName) {
            $tmpAcl = $this->getStateAcl('state');
            if($tmpAcl->isAllowed('sm', $action, $resourceName)) return $tmpAcl->isAllowed('sm', $action, $resourceName);
        }
        return false;
    }

    public function getSchoolAcl($cds_id)
    {
        if(isset($this->perms_schools[$cds_id]))
        {
            return unserialize($this->perms_schools[$cds_id]);
        } else {
            // return the default acl
            return unserialize($this->perms_schools['00_0000_000']);
        }
        return false;
    }
    
    public function getDistrictAcl($cd_id)
    {        
        if(isset($this->perms_districts[$cd_id]))
        {
            return unserialize($this->perms_districts[$cd_id]);
        } else {
            // return the default acl
            return unserialize($this->perms_districts['00_0000']);
        }
        return false;
    }
    
    public function getEsuAcl($cd_id)
    {        
        if(isset($this->perms_esu[$cd_id]))
        {
            return unserialize($this->perms_esu[$cd_id]);
        } else {
            // return the default acl
            return unserialize($this->perms_esu['00']);
        }
        return false;
    }
    
    public function getStateAcl($cd_id)
    {        
        if(isset($this->perms_states[$cd_id]))
        {
            return unserialize($this->perms_states[$cd_id]);
        } else {
            // return the default acl
            return unserialize($this->perms_states['0']);
        }
        return false;
    }
    
    function getAllowedArray_esu()
    {
        $retArr = array();
        if(count($this->perms_esu) <= 1) return false;
        foreach($this->perms_esu as $key => $value)
        {
            if('00' == $key) continue;
            $retArr[] = array('id_esu' => $key);
        }
        return $retArr;
    }

    function getAllowedArray_district()
    {
        $retArr = array();
        if(count($this->perms_districts) <= 1) return false;

        foreach($this->perms_districts as $key => $value)
        {
            if('00_0000' == $key) continue;
            list($id_county, $id_district) = explode('_', $key);
            $retArr[] = array('id_county'=>$id_county, 'id_district'=>$id_district);
        }
        return $retArr;
    }

    function getAllowedArray_state()
    {
        $retArr = array();
        if(!isset($this->perms_states) || count($this->perms_states) <= 1) return false;
        foreach($this->perms_states as $key => $value)
        {
            if('0' == $key) continue;
            $retArr[] = array('id_state' => $key);
        }
        return $retArr;
    }

}
