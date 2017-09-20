<?php

/**
 * 
 * Ceate Json to post for specific Edfi API Models
 *
 * @author odiaz@doublelinepartners.com
 * @version 1.0
 *
 */ 

class Model_EdfiJsonModels{

    function __construct() {

	}


   /*Return a studentSpecialEducationProgramAssociation JSON */
    public function createStudentSpecialEducationProgramAssociation($id,$educationOrganizationId,$studentUniqueId,$beginDate,$reasonExitedDescriptor,$specialEducationSettingDescriptor,$levelOfProgramParticipationDescriptor,$placementTypeDescriptor,$specialEducationPercentage,$toTakeAlternateAssessment,$endDate,$services,$disabilities){

              
        $jsonstring='{
                "id": "%id%",
                "educationOrganizationReference": {
                "educationOrganizationId": %eoid%,
                "link": {
                "rel": "EducationOrganization",
                "href": "/educationOrganizations?educationOrganizationId=%eoid%"
                    }
                },
                "programReference": {
                    "educationOrganizationId": %eoid%,
                    "type": "Special Education",
                    "name": "Special Education",
                    "link": {
                        "rel": "Program",
                        "href": "/programs?educationOrganizationId=%eoid%&type=Special+Education&name=Special+Education"
                    }
                },
                "studentReference": {
                     "studentUniqueId": "%euid%",
                    "link": {
                    "rel": "Student",
                     "href": "/students?studentUniqueId=%euid%"
                     }
                },
                "beginDate": "%begindate%",
                "endDate": "%endate%",
                "reasonExitedDescriptor": "%rexitdesc%",
                "specialEducationSettingDescriptor": "%sesettingdesc%",
                "levelOfProgramParticipationDescriptor": "%leopartdesc%",
                "placementTypeDescriptor": "%ptdesc%",
                "specialEducationPercentage": %spedpercent%,
                "toTakeAlternateAssessment": %ttaltassessment%,
                "services": [
                    %jsonServices%
                ],
                "disabilities": %iddisabilities%,
                 "serviceProviders": []
            }';


        $jsonstring=str_replace('%id%', $id, $jsonstring);
        $jsonstring=str_replace('%eoid%', $educationOrganizationId, $jsonstring);
        $jsonstring=str_replace('%euid%', $studentUniqueId, $jsonstring);
        $jsonstring=str_replace('%begindate%', $beginDate, $jsonstring);
        
        $jsonstring=str_replace('%rexitdesc%', $reasonExitedDescriptor, $jsonstring);
        $jsonstring=str_replace('%sesettingdesc%', $specialEducationSettingDescriptor, $jsonstring);
        $jsonstring=str_replace('%leopartdesc%', $levelOfProgramParticipationDescriptor, $jsonstring);
        $jsonstring=str_replace('%ptdesc%', $placementTypeDescriptor, $jsonstring);
        $jsonstring=str_replace('%spedpercent%', $specialEducationPercentage, $jsonstring);
        $jsonstring=str_replace('%ttaltassessment%', $toTakeAlternateAssessment, $jsonstring);
        $jsonstring=str_replace('%iddisabilities%',$disabilities,$jsonstring);
       
      //  if($endDate!=""){
       //     $endDate= '"endDate": "%' . $endDate .  '%",';
       // }
        $jsonstring=str_replace('%endate%', $endDate, $jsonstring);

        //Generate services for student association
        $servicesJson="";
        foreach ($services as $service){
            if($servicesJson!=""){
                    $servicesJson= $servicesJson . ",";
            }
            $servicesJson = $servicesJson . '{"serviceDescriptor": "' . $service[0] . '","serviceBeginDate": "' . $service[1] . '"}';
        }
        
        $jsonstring=str_replace('%jsonServices%', $servicesJson, $jsonstring);



        return $jsonstring;
        
    } 
    



} 

?>