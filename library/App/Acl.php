<?php

class App_Acl extends Zend_Acl 
{
	public function __construct()
	{
		// resources
		$this->add(new Zend_Acl_Resource(App_Resources::ADMIN_SECTION));
		$this->add(new Zend_Acl_Resource(App_Resources::USER_SECTION));
		$this->add(new Zend_Acl_Resource(App_Resources::GUARDIAN_SECTION));
		$this->add(new Zend_Acl_Resource(App_Resources::TRANSLATION_SECTION));
		
		// roles
		$this->addRole(new Zend_Acl_Role(App_Roles::ADMIN));
		$this->addRole(new Zend_Acl_Role(App_Roles::USER));
		$this->addRole(new Zend_Acl_Role(App_Roles::GUARDIAN));
		
		// USER PAGES
		$this->allow(App_Roles::ADMIN , App_Resources::USER_SECTION);
		$this->allow(App_Roles::USER , App_Resources::USER_SECTION);
		$this->deny(App_Roles::GUARDIAN , App_Resources::USER_SECTION);
		
		// ADMIN PAGES
		$this->allow(App_Roles::ADMIN , App_Resources::ADMIN_SECTION);
		$this->deny(App_Roles::USER , App_Resources::ADMIN_SECTION);
		$this->deny(App_Roles::GUARDIAN , App_Resources::ADMIN_SECTION);
		
		// GUARDIAN PAGES
		$this->allow(App_Roles::ADMIN , App_Resources::GUARDIAN_SECTION);
		$this->allow(App_Roles::USER , App_Resources::GUARDIAN_SECTION);
		$this->allow(App_Roles::GUARDIAN , App_Resources::GUARDIAN_SECTION);
		
		// TRANSLATION PAGES
		$this->allow(App_Roles::ADMIN , App_Resources::TRANSLATION_SECTION);
		$this->allow(App_Roles::USER , App_Resources::TRANSLATION_SECTION);
		$this->deny(App_Roles::GUARDIAN , App_Resources::TRANSLATION_SECTION);
		
	}
}
