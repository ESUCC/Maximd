<?php
$statements = array();




$statements[] =<<<EOQ
delete from neb_user;
EOQ;

$statements[] =<<<EOQ
    insert into neb_user (name_first, name_last, id_county, id_district, id_school, user_name, email_address, password) VALUES ('jesse', 'lavere', '99', '9999', '999', 'jlavere', 'jlavere@jlavere.com', 'vere');
EOQ;









//
// county
//
$statements[] =<<<EOQ
delete from neb_county;
EOQ;

$statements[] =<<<EOQ
insert into neb_county (id_county,
                        name_county
                        ) VALUES (
                        '99', 
                        'Test County'
                        );
EOQ;


//
// district
//
$statements[] =<<<EOQ
delete from neb_district;
EOQ;

$statements[] =<<<EOQ
insert into neb_district (id_county,
                        id_district,
                        name_district
                        ) VALUES (
                        '99', 
                        '9999',
                        'Test District'
                        );
EOQ;



$statements[] =<<<EOQ
delete from neb_school;
EOQ;

$statements[] =<<<EOQ
insert into neb_school (id_county, 
                        id_district, 
                        id_school, 
                        name_school, 
                        status, 
                        address_street1, 
                        address_city, 
                        address_state, 
                        address_zip
                       ) VALUES (
                        '99', 
                        '9999', 
                        '999', 
                        'Test School', 
                        'Active', 
                        '123 any street', 
                        'Portland', 
                        'OR', 
                        '97206'
                       );
EOQ;




return $statements;