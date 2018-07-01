curl https://sandbox.nebraskacloud.org/ng/api/oauth/token -H "Content-Type: application/json" -d "{'Client_id':'76SHlPF7oBjl','Client_secret':'LUBEeA5SmBKwWvi6Ov9lsvpJ','Code':'7d91d93f465e4f6cad149102b8b2ec21','Grant_type':'authorization_code'}"
~                                                                                                           
$link2="https://sandbox.nebraskacloud.org/ng/api/oauth/token";
     $client2= new Zend_Http_Client($link2);
   
 $client2->setParameterGet(array(
         ''=>' -H ',
         'Content-Type:'=>'application/json',
         ' '=>' -d ',
         '  '=>' {',
         'Client_id'=>'76SHlPF7oBjl',
         'Client_secret'=>'LUBEeA5SmBKwWvi6Ov9lsvpJ',
         'Code'=>$resp['code'],
         'Grant_type'=>'authorization_code',
         '   '=>'}'
     ));