<?php
$statements = array();


$statements[] =<<<EOQ
delete from neb_user;
EOQ;

//The password for all accounts is : zend123
//
//USERNAME        PRIV TYPE
//zdm                        District Manager
//zadm                      Assoc. District Manager
//zsm                         School Manager
//zasm                       Assoc. School Manager
//zcm                         Case Manager
//zspecialist              Specialist/Consultant
//zteacher                 School Staff / Teacher
//zeicm                      Early Intervention Case Manager / Service Provider
//jbickal                     parent

$statements[] =<<<EOQ
    insert into iep_personnel (
    	id_personnel,
    	class,
    	name_first, 
    	name_last, 
    	id_county, 
    	id_district, 
    	id_school, 
    	user_name, 
    	email_address, 
    	password) 
	VALUES (
		'9999999',
		'99',
		'zdm', 
		'zdm', 
		'99', 
		'9999', 
		'999', 
		'zdm', 
		'jlavere@jlavere.com', 
		'test'
	);
EOQ;
$statements[] =<<<EOQ
    insert into iep_privileges (
    	id_author, 
    	id_author_last_mod, 
    	class, 
    	id_personnel, 
    	id_county, 
    	id_district, 
    	id_school, 
    	status) 
	VALUES (
		'9999999', 
		'9999999', 
		'2', 
		'9999999', 
		'99', 
		'9999', 
		'', 
		'Active'
	);
EOQ;



$statements[] =<<<EOQ
    insert into iep_personnel (
    	id_personnel,
    	class,
    	name_first, 
    	name_last, 
    	id_county, 
    	id_district, 
    	id_school, 
    	user_name, 
    	email_address, 
    	password) 
	VALUES (
		'9999998',
		'99',
		'zcm', 
		'zcm', 
		'99', 
		'9999', 
		'999', 
		'zcm', 
		'jlavere@jlavere.com', 
		'test'
	);
EOQ;

$statements[] =<<<EOQ
    insert into iep_privileges (
    	id_author, 
    	id_author_last_mod, 
    	class, 
    	id_personnel, 
    	id_county, 
    	id_district, 
    	id_school, 
    	status) 
	VALUES (
		'9999998', 
		'9999998', 
		'6', 
		'9999998', 
		'99', 
		'9999', 
		'999', 
		'Active'
	);
EOQ;



return $statements;