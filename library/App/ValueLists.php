<?php

class App_ValueLists
{
    static public function getLabelValues($type) {
        $arrLabel = array();
        $arrValue = array();
        switch($type) {
            case "recordsPerPage":
                $arrLabel = array(5,10,15,25,50,75,100);
                $arrValue = array(5,10,15,25,50,75,100);
                break;
            case "iepRole":
                $arrLabel = array("Parent", "Student", "Regular education teacher", "Special education teacher or provider", "School district representative", "Individual to interpret evaluation results", "Service agency representative", "Nonpublic representative", "Other agency representative", "Educator of the hearing impaired", "Other (Please Specify)");
                $arrValue = array("Parent", "Student", "Regular education teacher", "Special education teacher or provider", "School district representative", "Individual to interpret evaluation results", "Service agency representative", "Nonpublic representative", "Other agency representative", "Educator of the hearing impaired", "Other (Please Specify)");
                break;
            case "recordsPerPageHigh":
                $arrLabel = array(5,10,15,25,50,75,100, 250, 500, 1000);
                $arrValue = array(5,10,15,25,50,75,100, 250, 500, 1000);
                break;
            case "studentStatus":
                $arrLabel = array("Active", "Inactive", "Remove", "Never Qualified", "Transferred to Non-SRS District", "No Longer Qualifies");
                $arrValue = array("Active", "Inactive", "Remove", "Never Qualified", "Transferred to Non-SRS District", "No Longer Qualifies");
                break;
            case "statusAI":
                $arrLabel = array("Active", "Inactive", "Remove");
                $arrValue = array("Active", "Inactive", "Remove");
                break;
            case "schoolStatus":
                $arrLabel = array("Active", "Inactive");
                $arrValue = array("Active", "Inactive");
                break;
            case "yn":
                $arrLabel = array("Yes", "No");
                $arrValue = array("t", "f");
                break;
            // IEP specific
            case "relatedService":
                $arrLabel = array("Counseling", "Medical Diagnostic Services", "Interpreter", "Notetaker", "Occupational Therapy", "Parent Training", "Physical Therapy");
                $arrValue = array("001", "002", "003", "004", "005", "006", "007");
                break;

            // SESIS CODES ADDED - 040706 - JL
            case "serviceLocationBirthToTwo":
                #$arrLabel = array("Program Designed for Children with Developmental Delays or Disabilities", "Program Designed for Typically Developing Children", "Home", "Hospital", "Residential Facility", "Service Provider Location (Outpatient facility, clinic)", "Other Settings (Outpatient facility, clinic)");
                #$arrValue = array("04", "08", "10", "09", "22", "19", "21");
                /**
                 * IF ORDER OF ITEMS IS CHANGED, CHANGE CODE ON PG6 OF IFSP THAT REMOVES ELEMENT 2
                 */
                $arrLabel = array("Home", "Hospital", "Other Settings (Outpatient facility, clinic)","Program Designed for Children with Developmental Delays or Disabilities", "Program Designed for Typically Developing Children", "Residential Facility", "Service Provider Location (Outpatient facility, clinic)", );
                $arrValue = array("10", "09", "21","04", "08", "22", "19", );
                break;
            case "serviceLocationThreeToFive":
                $arrLabel = array("Early Childhood Setting. (e.g., Head Start, child care center, family childcare home)", "Early Childhood Special Education Setting (separate classroom for children with disabilities)", "Part-time childhood/part-time early childhood special education setting", "Home", "Hospital", "Residential Facility", "Separate School", "Public School (Kindergarten, Etc.)");
                $arrValue = array("11", "12", "13", "10", "09", "22", "23", "15");
                break;
            case "serviceLocationThreeToFive2010":
                $arrLabel = array(  "Regular Early Childhood Program, 10+ h/week; services at EC program",
                    "Regular Early Childhood Program, 10+ h/week; services outside EC program",
                    "Regular Early Childhood Program, <10 h/week; services at EC program",
                    "Regular Early Childhood Program, <10 h/week; services outside EC program",
                    "Early Childhood Special Education Setting (separate classroom for children with disabilities)",
                    "Part-time childhood/part-time early childhood special education setting",
                    "Home",
                    "Hospital",
                    "Residential Facility",
                    "Separate School",
//			                    "Public School (Kindergarten, Etc.)"
                );
                $arrValue = array(  "16",
                    "17",
                    "18",
                    "19",
                    "12",
                    "13",
                    "10",
                    "09",
                    "22",
                    "23",
//			                    "15"
                );
                break;

            case "code_4_conversion":
                $arrLabel = array(  "Regular Early Childhood Program, 10+ h/wk; Services at EC Program",
                    "Regular Early Childhood Program, 10+ h/wk; Services outside EC Program",
                    "Regular Early Childhood Program, <10 h/wk; Services at EC Program",
                    "Regular Early Childhood Program, <10 h/wk; Services outside EC Program",
                );
                $arrValue = array(  "16",
                    "17",
                    "18",
                    "19",
                );
                break;


            case "serviceLocationSixTo21":
                $arrLabel = array("Public School", "Public Separate Facility", "Public Residential Facility", "Private Separate Facility", "Private Residential Facility", "Home", "Hospital", "Correctional or Detention Facility", );
                $arrValue = array("01", "02", "03", "06", "07", "10", "09", "14", );
                break;
            case "serviceLocation":
                $arrLabel = array("Public School", "Public Separate School", "Public Residential Facility", "NonPublic School", "NonPublic Separate School", "NonPublic Residential Facility", "Hospital", "Home");
                $arrValue = array("01", "02", "03", "05", "06", "07", "09", "10");
                // values update 2/13/03 - JL SESIS
                //$arrLabel = array("Reg. Ed. Classroom", "Spec. Ed. Classroom", "Home", "Hospital", "Out of District Placement", "Work Experience", "Other");
                //$arrValue = array("C", "SE", "H", "HS", "ODP", "WE", "OTH");
                break;
            case "serviceLocationLPS":
                $arrLabel = array("Reg. Ed. Classroom", "Spec. Ed. Classroom", "Home", "Hospital", "Out of District Placement", "Work Experience", "Other");
                $arrValue = array("C", "SE", "H", "HS", "ODP", "WE", "OTH");
                break;
            case "serviceLocationMerge":
                $arrLabel = array("Reg. Ed. Classroom", "Spec. Ed. Classroom", "Home", "Hospital", "Out of District Placement", "Work Experience", "Other", "Public School", "Public Separate School", "Public Residential Facility", "NonPublic School", "NonPublic Separate School", "NonPublic Residential Facility", "Hospital", "Home");
                $arrValue = array("C", "SE", "H", "HS", "ODP", "WE", "OTH", "01", "02", "03", "05", "06", "07", "09", "10");
                // values update 2/13/03 - JL SESIS
                //$arrLabel = array("Reg. Ed. Classroom", "Spec. Ed. Classroom", "Home", "Hospital", "Out of District Placement", "Work Experience", "Other");
                //$arrValue = array("C", "SE", "H", "HS", "ODP", "WE", "OTH");
                break;
            case "serviceFrequency":
                $arrLabel = array("min/day", "hrs/day");
                $arrValue = array("m", "h");
                break;
            case "serviceFrequency2009":
                $arrLabel = array("min/day", "hrs/day", "hrs/month");
                $arrValue = array("m", "h", "mo");
                break;
            case "serviceFrequencyWeekMonth":
                $arrLabel = array("days/week", "days/month", "days/quarter", "days/semester", "days/year");
                $arrValue = array("w", "m", "q", "s", "y");
                break;
            case "serviceFrequencyWeekMonth2007":
                $arrLabel = array("days/week", "days/month", "days/3 month period", "days/6 month period", "days/year");
                $arrValue = array("w", "m", "q", "s", "y");
                break;
            case "presentLevPerfSource":
                $arrLabel = array("1", "2", "3", "4", "5", "6", "7");
                $arrValue = array("1", "2", "3", "4", "5", "6", "7");
                break;
            case "ab":
                $arrLabel = array("A", "B");
                $arrValue = array("A", "B");
                break;
            case "goalSchedule":
                $arrLabel = array("A - 6 Weeks", "B - 9 Weeks", "C - Semester", "D - Other");
                $arrValue = array("A", "B", "C", "D");
                break;
            case "goalProgress":
                $arrLabel = array("Goal Met", "Progress Made, Goal Not Met", "Little or No Progress", "Other, specify");
                $arrValue = array("A", "B", "C", "D");
                break;
            case "goalProcedures":
                $arrLabel = array("Teacher Observation", "Written Performance", "Oral Performance", "Criterion Reference Test", "Parent Report", "Time Sample", "Report Cards", "Other");
                $arrValue = array("A", "B", "C", "D", "E", "F", "G", "H");
                break;
            case "personResponsible":
                //$arrLabel = array("P - Parent", "CT - Classroom Teacher", "RT - SPED Teacher", "SLP - Speech-Language Pathologist", "PARA - Paraprofessional", "D/HH - Deaf/Hard of Hearing Specialist", "ECS - Early Childhood Specialist", "OT - Occupational Therapist", "PT - Physical Therapist", "AD - Audiologist", "O - Other");
                //$arrValue = array("P", "CT", "RT", "SLP", "PARA", "D/HH", "ECS", "OT", "PT", "AD", "O");
                //
                // jlavere 20080305 - removed para
                //
                $arrLabel = array("P - Parent", "CT - Classroom Teacher", "RT - SPED Teacher", "SLP - Speech-Language Pathologist", "D/HH - Deaf/Hard of Hearing Specialist", "ECS - Early Childhood Specialist", "OT - Occupational Therapist", "PT - Physical Therapist", "AD - Audiologist", "O - Other");
                $arrValue = array("P", "CT", "RT", "SLP", "D/HH", "ECS", "OT", "PT", "AD", "O");
                break;
            case "iepFormPages":
                $arrLabel = array("Signature Page (1)", "Special Considerations (2)", "Present Lev of Perf (3)", "Transition (4)", "Annual Goal (5)", "Services (6)", "Transportation (7)");
                $arrValue = array("1", "2", "3", "4", "5", "6", "7");
                break;
            case "transportationWhy":
                $arrLabel = array("Not Necessary", "Child is below age 5", "Child is required to attend a facility other than the normal attendance facility", "Nature of the child's disability is such that special education transportation is required");
                $arrValue = array("Not Necessary", "Child is below age 5", "Child is required to attend a facility other than the normal attendance facility", "Nature of the child's disability is such that special education transportation is required");
                break;
            case "true":
                $arrLabel = array("");
                $arrValue = array("t");
                break;
            case "summary":
                $arrLabel = array("");
                $arrValue = array("1");
                break;
            // MDT specific checkboxes
            case "transition1":
                $arrLabel = array("Curriculum&nbsp;Planning/General&nbsp;Education", "Communication&nbsp;Living&nbsp;Skills&nbsp;Checklist", "Daily&nbsp;Living&nbsp;Skills&nbsp;Checklist", "College&nbsp;Prep", "Social&nbsp;Skills&nbsp;Checklist", "Interest&nbsp;Inventories", "Vocational&nbsp;Education", "Disability&nbsp;Asareness", "Advanced&nbsp;Placement");
                $arrValue = array("Curriculum Planning/General Education", "Communication Living Skills Checklist", "Daily Living Skills Checklist", "College Prep", "Social Skills Checklist", "Interest Inventories", "Vocational Education", "Disability Asareness", "Advanced Placement");
                break;
            case "transition2":
                $arrLabel = array("Career&nbsp;Interest&nbsp;Inventories", "Vocational&nbsp;Assessment", "Job&nbsp;Interviewing&nbsp;Skills", "Related&nbsp;Services", "Aptitude&nbsp;Assessment", "Job&nbsp;Training&nbsp;(with&nbsp;coach)", "Self-Directed&nbsp;IEP's", "Job&nbsp;Shadowing", "Self-Advocacy&nbsp;Training", "Job&nbsp;Explorations");
                $arrValue = array("Career Interest Inventories", "Vocational Assessment", "Job Interviewing Skills", "Related Services", "Aptitude Assessment", "Job Training (with coach)", "Self-Directed IEP's", "Job Shadowing", "Self-Advocacy Training", "Job Explorations");
                break;
            case "disabilities":
                $arrLabel = array("Autism (AU)", "Behavioral Disorder (BD)", "Deaf Blindness (DB)", "Hearing Impairment (HI)", "Mental Handicap: Mild (MH:MI)", "Mental Handicap: Moderate (MH:MO)", "Mental Handicap: Severe/Profound (MH:S/P)", "Multiple Impairments (MULTI)", "Orthopedic Impairment (OI)", "Other Health Impairment (OHI)", "Specific Learning Disabled (SLD)");
                $arrValue = array("AU", "BD", "DB", "HI", "MH:MI", "MH:MO", "MH:S/P", "MULTI", "OI", "OHI", "SLD");
                break;
            case "tbi":
                $arrLabel = array("Traumatic Brain Injury (TBI)");
                $arrValue = array("TBI");
                break;
            case "dd":
                $arrLabel = array("Developmental Delay (DD)");
                $arrValue = array("DD");
                break;
            case "sli":
                $arrLabel = array("Language", "Articulation", "Voice", "Fluency");
                $arrValue = array("Language", "Articulation", "Voice", "Fluency");
                break;
            case "states":
                $arrLabel = array("AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "DC", "FL", "GA", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY");
                $arrValue = array("AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "DC", "FL", "GA", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY");
                break;
            case "servicesDrop":
                $arrLabel = array("Audiological Services", "Braile/LP/Recorded Material", "Counseling", "Medical Diagnostic Services", "Home School Liaison", "Interpreter", "Notetaker", "Occupational Therapy", "Parent Training", "Physical Therapy", "Psychological Services", "Reader", "Recreation", "School Health", "Speech/Language Therapy", "Transportation", "Vocational Training", "Assistive Technology Device", "Assistive Technology Service", "Other (Please Specify)");
                $arrValue = array("Audiological Services", "Braile/LP/Recorded Material", "Counseling", "Medical Diagnostic Services", "Home School Liaison", "Interpreter", "Notetaker", "Occupational Therapy", "Parent Training", "Physical Therapy", "Psychological Services", "Reader", "Recreation", "School Health", "Speech/Language Therapy", "Transportation", "Vocational Training", "Assistive Technology Device", "Assistive Technology Service", "Other (Please Specify)");
                break;
            case "servicesDropOverTwo":
                $arrLabel = array("Extended School Year");
                $arrValue = array("Extended School Year");
                break;
//		case "servicesDropBirthToTwo":
//			$arrLabel = array("Other (Please Specify)",);
//			$arrValue = array("Other",);
//			break;
//		case "sesisServicesRowsTwoPlus":
//			$arrLabel = array("Other-Transportation", "Other-Medicaid", "Other-WIC", "Other-Consultation", "Other-(Please specify)",);
//			$arrValue = array("Other-Transportation", "Other-Medicaid", "Other-WIC", "Other-Consultation", "Other",);
//			break;
            case "seisServicesDrop":
                $arrLabel = array(
                    "Assistive technology services/devices",
                    "Audiology",
                    "ECSE",
                    "Family training, counseling, home visits and other supports",
                    "Health services",
                    "Medical services (for diagnostic or evaluation purposes)",
                    "Nursing services",
                    "Nutrition services",
                    "Occupational Therapy Services",
                    "Physical Therapy",
                    "Psychological services",
                    "Respite care",
                    "Services coordination",
                    "Sign Language Interpreter",
                    "Social work services",
                    "Speech-language therapy",
                    "Teacher of the Hearing Impaired",
                    "Teacher of the Visually Impaired",
                    "Transportation",
                    "Vision Services",
                );
                $arrValue = array(
                    "Assistive technology services/devices",
                    "Audiology",
                    "ECSE",
                    "Family training, counseling, home visits and other supports",
                    "Health services",
                    "Medical services (for diagnostic or evaluation purposes)",
                    "Nursing services",
                    "Nutrition services",
                    "Occupational Therapy Services",
                    "Physical Therapy",
                    "Psychological services",
                    "Respite care",
                    "Services coordination",
                    "Sign Language Interpreter",
                    "Social work services",
                    "Speech-language therapy",
                    "Teacher of the Hearing Impaired",
                    "Teacher of the Visually Impaired",
                    "Transportation",
                    "Vision Services",
                );
                break;
            case "seisServicesDrop_v2":
                $arrLabel = array(
                    "Assistive technology services/devices",
                    "Audiology",
//				"ECSE",
                    "Family training, counseling, home visits and other supports",
                    "Health services",
                    "Interpreting Services",
                    "Medical services (for diagnostic or evaluation purposes)",
                    "Nursing services",
                    "Nutrition services",
                    "Occupational Therapy Services",
                    "Physical Therapy",
                    "Psychological services",
                    "Respite care",
                    "Services coordination",
                    "Special Instruction (Resource)",
                    "Social work services",
                    "Speech-language therapy",
                    "Teacher of the Hearing Impaired",
                    "Teacher of the Visually Impaired",
                    "Transportation",
                    "Vision Services",
                );
                $arrValue = array(
                    "Assistive technology services/devices",
                    "Audiology",
//				"ECSE",
                    "Family training, counseling, home visits and other supports",
                    "Health services",
                    "Interpreting Services",
                    "Medical services (for diagnostic or evaluation purposes)",
                    "Nursing services",
                    "Nutrition services",
                    "Occupational Therapy Services",
                    "Physical Therapy",
                    "Psychological services",
                    "Respite care",
                    "Services coordination",
                    "Special Instruction (Resource)",
                    "Social work services",
                    "Speech-language therapy",
                    "Teacher of the Hearing Impaired",
                    "Teacher of the Visually Impaired",
                    "Transportation",
                    "Vision Services",
                );
                break;
            case "partDrop":
                $arrLabel = array("Adaptive Physical Education", "Assistive Technology", "Audiologist", "Counselor", "Interpreter", "Notetaker", "Occupational Therapist", "Parent Trainer", "Physical  Therapist", "Physician", "Reader", "Recreational Therapist", "School Nurse", "Speech Language Pathologist", "Transportation Services", "Vocational Education", "Other (Please Specify)");
                $arrValue = array("Adaptive Physical Education", "Assistive Technology", "Audiologist", "Counselor", "Interpreter", "Notetaker", "Occupational Therapist", "Parent Trainer", "Physical  Therapist", "Physician", "Reader", "Recreational Therapist", "School Nurse", "Speech Language Pathologist", "Transportation Services", "Vocational Education", "Other (Please Specify)");
                break;
            case "sesisExitCodes":
                $arrLabel = array("Birth-3", "Completion of the IFSP prior to reaching maximum age for Part C.", "Part B. Eligible ", "Not Eligible for Part B, Exit to other program", "Not Eligible for Part B, Exit with no referral", "Part B eligibility not determined", "Deceased   ", "Moved Out of State", "Withdrawn by parent", "Attempts to contact parents unsuccessful", "3-21", "Transfer to another school district", "Returned to or entered in a full time regular education program", "Graduated with diploma", "Graduated with certificate of completion", "Reached maximum age (Age 21)", "Deceased", "Dropped out", "Expulsion", "Withdrawn by parent");
                $arrValue = array("disable", "10", "11", "12", "13", "14", "15", "16", "17", "18", "disable", "1", "2", "3", "4", "5", "6", "7", "8", "9");
                break;

            case "sesisExitCodes":
                $arrLabel = array(
                    "Birth-3",
                    "Completion of the IFSP prior to reaching maximum age for Part C.",
                    "Part B. Eligible ",
                    "Not Eligible for Part B, Exit to other program",
                    "Not Eligible for Part B, Exit with no referral",
                    "Part B eligibility not determined",
                    "Deceased   ",
                    "Moved Out of State",
                    "Withdrawn by parent",
                    "Attempts to contact parents unsuccessful",
                    "3-21",
                    "Transfer to another school district",
                    "Returned to or entered in a full time regular education program",
                    "Graduated with diploma",
                    "Graduated with certificate of completion",
                    "Reached maximum age (Age 21)",
                    "Deceased",
                    "Dropped out",
                    "Expulsion",
                    "Withdrawn by parent");
                $arrValue = array(
                    "disable",
                    "110",
                    "111",
                    "112",
                    "113",
                    "114",
                    "115",
                    "116",
                    "117",
                    "118",
                    "disable",
                    "101",
                    "102",
                    "103",
                    "104",
                    "105",
                    "106",
                    "107",
                    "108",
                    "109");
                break;

            case "sesisExitCodesBirthToTwo":
                $arrLabel = array(
                    "Birth to Two",
                    "Completion of the IFSP prior to reaching maximum age for Part C.",
                    "Not Eligible for Part B, Exit to other program",
                    "Not Eligible for Part B, Exit with no referral",
                    "Part B eligibility not determined",
                    "Deceased",
                    "Moved Out of State",
                    "Withdrawn by parent",
                    "Attempts to contact parents unsuccessful",
                    "Transferred to another School District",
                    "Duplicate or keying error",
                );
                $arrValue = array(
                    "disable",
                    "12",
                    "13",
                    "14",
                    "15",
                    "06",
                    "16",
                    "09",
                    "17",
                    "01",
                    "10",
                );
                break;

            case "sesisExitCodesThreeTo21":
                $arrLabel = array(
                    "Three to 21",
                    "Returned to Full-Time Regular Education Program",
                    "Graduated with a regular high school diploma",
                    "Graduated with a Certificate of Completion",
                    "Reached maximum age",
                    "Deceased",
                    "Dropped Out",
                    "Expulsion",
                    "Duplicate or keying error",
                    "Transferred to another School District",
                    "Moved known to be continuing",
                );
                $arrValue = array(
                    "disable",
                    "02",
                    "03",
                    "04",
                    "05",
                    "06",
                    "07",
                    "08",
                    "10",
                    "01",
                    "11",
                );
                break;
            case "sesisExitCodesThreeTo21_transfer": // changed transferred to another district code to 1 from 01
                $arrLabel = array(
                    "Three to 21",
                    "Returned to Full-Time Regular Education Program",
                    "Graduated with a regular high school diploma",
                    "Graduated with a Certificate of Completion",
                    "Reached maximum age",
                    "Deceased",
                    "Dropped Out",
                    "Expulsion",
                    "Duplicate or keying error",
                    "Transferred to another School District",
                    "Moved not known to be continuing",
                );
                $arrValue = array(
                    "disable",
                    "02",
                    "03",
                    "04",
                    "05",
                    "06",
                    "07",
                    "08",
                    "10",
                    "1",
                    "11",
                );
                break;

            case "sesisExitCodesOver21":
                $arrLabel = array("-- Over 22 --", "Reached Maximum Age (Age 22)"); //"Student is over 21 years old",
                $arrValue = array("disable", "18");
                break;

            case "progProvider":
                $arrLabel = array("Resident school district", "Another school district", "Agency/educational service unit");
                $arrValue = array("Resident school district", "Another school district", "Agency/educational service unit");
                break;
            case "progProviderLimited":
                $arrLabel = array("Resident school district", "Another school district");
                $arrValue = array("Resident school district", "Another school district");
                break;
            // IFSP specific checkboxes
            case "ifspType":
                $arrLabel = array("Interim", "Annual", "Initial", "Periodic", "Transition");
                $arrValue = array("Interim", "Annual", "Initial", "Periodic", "Transition");
                break;
            case "environment":
                $arrLabel = array("Group", "Individual", "Group and Individual");
                $arrValue = array("Group", "Individual", "Group and Individual");
                break;
            case "service_service":
                $arrLabel = array("Assistive Technology Services/Devices", "Audiology", "Family Training, home visits and other support", "Health Services", "Language translation", "Medical Services (for diagnostic or evaluation purposes)", "Nursing Services", "Nutrition Services", "Occupational Therapy Services", "Physical Therapy Services", "Sign Language Interpreter", "Psychological Services", "Respite Care", "Services Coordination", "Social Work Services", "Special Instruction", "Speech Language Therapy", "Extended School Year", "Transportation", "Other");
                $arrValue = array("Assistive Technology Services/Devices", "Audiology", "Family Training, home visits and other support", "Health Services", "Language translation", "Medical Services (for diagnostic or evaluation purposes)", "Nursing Services", "Nutrition Services", "Occupational Therapy Services", "Physical Therapy Services", "Sign Language Interpreter", "Psychological Services", "Respite Care", "Services Coordination", "Social Work Services", "Special Instruction", "Speech Language Therapy", "Extended School Year", "Transportation", "Other");
                break;
            case "service_where":
                $arrLabel = array("Head Start", "Child Care Center", "Family Child Care Home", "Separate classroom for children with disabilities", "Part-Time Early Childhood", "Part-Time Early Childhood Special Education Setting", "Home", "Hospital", "Residential Facility", "Service Provider Location", "Separate School", "Other Settings");
                $arrValue = array("Head Start", "Child Care Center", "Family Child Care Home", "Separate classroom for children with disabilities", "Part-Time Early Childhood", "Part-Time Early Childhood Special Education Setting", "Home", "Hospital", "Residential Facility", "Service Provider Location", "Separate School", "Other");
                break;
            case "service_pays":
                $arrLabel = array("CHIP (Cmp Health Ins Pool)", "DCP - Disabled Children's Program", "DPFS - Disabled Person's Family Support", "Early Development Network", "Family", "Health Insurance", "Medicaid Waivers", "Medicaid", "MHCP - Medically Handicapped Children's Program", "Respite Subsidy", "School district", "WIC (Women's/Infants & Children)", "Other", ); // other moved to end jlavere bug 4286 20051109
                $arrValue = array("CHIP (Cmp Health Ins Pool)", "DCP - Disabled Children's Program", "DPFS - Disabled Person's Family Support", "Early Development Network", "Family", "Health Insurance", "Medicaid Waivers", "Medicaid", "MHCP - Medically Handicapped Children's Program", "Respite Subsidy", "School district", "WIC (Women's/Infants & Children)", "Other", );
                break;
            case "service_responsible":
                $arrLabel = array("Audiologist", "Counselor", "Family", "Heath Care Provider", "Nurse", "Nutritionist", "Occupational therapist", "Other","Physical therapist", "Primary Service Provider", "Psychologist", "Respite Care Provider", "Services Coordinator", "Social worker", "Speech pathologist", "Teacher", "Vision Specialist", );
                $arrValue = array("Audiologist", "Counselor", "Family", "Heath Care Provider", "Nurse", "Nutritionist", "Occupational therapist", "Other","Physical therapist", "Primary Service Provider", "Psychologist", "Respite Care Provider", "Services Coordinator", "Social worker", "Speech pathologist", "Teacher", "Vision Specialist", );
                break;
            case "parentguardian":
                $arrLabel = array("Parent", "Guardian");
                $arrValue = array("Parent", "Guardian");
                break;
            case "team_role":
                $arrLabel = array("Parent", "Other Family Member", "Advocate", "Service Coordinator", "School District Rep", "Service Provider", "Person Conducting Eval", "Other");
                $arrValue = array("Parent", "Other Family Member", "Advocate", "Service Coordinator", "School District Representative", "Service Provider", "Person Conducting Evaluations", "Other");
                break;
        }


        return array_combine($arrValue, $arrLabel);
    }

}