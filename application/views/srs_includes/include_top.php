<?php
/**************** CHANGES ***********/
// 2003-03-22 sl removed the New Personnel option per Ben's emailed request
// 030403-JL REPORTS TAB ADDE
// 030411-JL Added code to block New Student when user doesn't have create privs

// user classes
$UC_SA  = 1;												// system admin
$UC_DM  = 2;												// district manager
$UC_ADM = 3;												// associate district manager
$UC_SM  = 4;												// school manager
$UC_ASM = 5;												// associate school manager
$UC_CM  = 6;												// case manager
$UC_SS  = 7;												// school staff
$UC_SP  = 8;												// specialist
$UC_PG  = 9;												// parent/guardian
$UC_SC  = 10;                       			            // service coordinator

if(!isset($option)) $option = "";
?>

<!-- BEGIN INCLUDE TOP -->

<div id="body">
<? //if('all' == $printpages) echo "<div id=\"page1\">"; ?>
<table cellspacing="0" cellpadding="0">
	<tr> 
		<td style="height:33px; padding-left:25px;" width="100%"><img src='/images/logo.gif'></td>
		<? if($sessIdUser) { ?>
            <!-- <td><a href="help.php" <? echo My_Classes_iepFunctionGeneral::windowStatus('Click here for help'); ?> target="_blank" title="Help" class="btsBlue">Help</a><span class="btvsGrey">&nbsp;&nbsp;|&nbsp;&nbsp;</span></td> -->
    		<td style="padding-right:10px;"><a href="<?= $this->DOC_ROOT; ?>srs.php?area=logoff" <? echo My_Classes_iepFunctionGeneral::windowStatus('Log Off'); ?> title="Log Off" class="btsBlue">Log&nbsp;Off</a></td>
		<? } elseif ($area == "site_policy" || $area == "rule51") { ?>
            <!-- <td><a href="help.php" <? echo My_Classes_iepFunctionGeneral::windowStatus('Click here for help'); ?> target="_blank" title="Help" class="btsBlue">Help</a><span class="btvsGrey">&nbsp;&nbsp;|&nbsp;&nbsp;</span></td> -->
            <td><a href="javascript:window.close();" onMouseOver="javascript:window.status='Click here to close this window'; return true;" onMouseOut="javascript:window.status=''; return true;" title="Close" class="btsBlue">Close</a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <? } elseif ($area == "help"  ) { ?>
                <td><a href="javascript:window.close();" onMouseOver="javascript:window.status='Click here to close this window'; return true;" onMouseOut="javascript:window.status=''; return true;" title="Close" class="btsBlue">Close</a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <? } else { ?>
                <!-- <td><a href="help.php" <? echo My_Classes_iepFunctionGeneral::windowStatus('Click here for help'); ?> target="_blank" title="Help" class="btsBlue">Help</a><span class="btvsGrey">&nbsp;&nbsp;&nbsp;&nbsp;</span></td> -->
		<? } ?>
	</tr>
	<tr>
		<td colspan="10">
			<table cellspacing="0" cellpadding="0">
				<tr class="bt" style="height:20px;">
				<? if ($area == "index") { ?>
						<? if (empty($notice)) { ?>
						<td style="padding-left:25px; width:71px;"><img src="/images/tab_notice_cur.gif" alt="Notice" title=""></td>
						<? } else { ?>
						<td style="padding-left:25px; width:71px;"><img src="/images/tab_welcome_cur.gif" alt="Log On" title=""></td>
						<? } ?>
                <? } elseif ($area == "help" && false) { ?>
					<td style="padding-left:25px; width:71px;"><img src="/images/tab_help_cur.gif" alt="Help" title=""></td>
				<? } elseif ($area == "error") { ?>
					<td style="padding-left:25px; width:71px;"><img src="/images/tab_error_cur.gif" alt="Error" title=""></td>
				<? } elseif ($area == "rule51") { ?>
					<td style="padding-left:25px; width:71px;"><img src="/images/tab_rule51_cur.gif" alt="Rule 51" title=""></td>
				<? } elseif ($area == "password") { ?>
					<td style="padding-left:25px; width:71px;"><img src="/images/tab_password_cur.gif" alt="Password" title=""></td>
				<? } elseif ($area == "new_account") { ?>
					<td style="padding-left:25px; width:71px;"><img src="/images/tab_new_account_cur.gif" alt="New Account" title=""></td>
				<? } elseif ($area == "site_policy") { ?>
					<td style="padding-left:25px; width:71px;"><img src="/images/tab_site_policy_cur.gif" alt="Site Policy" title=""></td>
				<? } elseif ($area == "logon") { ?>
					<td style="padding-left:25px; width:71px;"><img src="/images/tab_logon_cur.gif" alt="Log On" title=""></td>
				<? } elseif ($area == "logoff") { ?>
					<td style="padding-left:25px; width:71px;"><img src="/images/tab_logoff_cur.gif" alt="Log Off" title=""></td>
				<? } elseif ($area == "expired") { ?>
					<td style="padding-left:25px; width:71px;"><img src="/images/tab_expired_cur.gif" alt="Expired" title=""></td>
				<? } elseif ($sessIdUser) {
					if ($area == "home") { ?>
						<td style="padding-left:25px; width:71px;"><img src="/images/tab_home_cur.gif" alt="Home" title=""></td>
					<? } else { ?>
						<td style="padding-left:25px; width:71px;"><a href="<?= $this->DOC_ROOT; ?>srs.php?area=home&sub=home" <? echo My_Classes_iepFunctionGeneral::windowStatus('Home'); ?>><img src="/images/tab_home_sel.gif" alt="Home" title=""></a></td>
					<? } ?>
				<? if ($area == "student") { ?>
					<td style="width:70px;"><img src="/images/tab_student_cur.gif" alt="Students" title=""></td>
				<? } else { ?>
					<td style="width:70px;"><a href="<?= $this->DOC_ROOT; ?>srs.php?area=student&sub=list" <? echo My_Classes_iepFunctionGeneral::windowStatus('Students'); ?>><img src="/images/tab_student_sel.gif" alt="Students" title=""></a></td>
				<? } ?>
				<? if ($sessUserMinPriv != $UC_PG) { if ($area == "personnel") { // decision was made to disallow parental access to personnel tab sl 10/1/2002 ?>
					<td style="width:70px;"><img src="/images/tab_personnel_cur.gif" alt="Personnel" title=""></td>
				<? } else { ?>
					<td style="width:70px;"><a href="<?= $this->DOC_ROOT; ?>srs.php?area=personnel&sub=list" <? echo My_Classes_iepFunctionGeneral::windowStatus('Personnel'); ?>><img src="/images/tab_personnel_sel.gif" alt="Personnel" title=""></a></td>
				<? } } ?>
				<? if ($sessUserMinPriv != $UC_PG) { if ($area == "school") { ?>
					<td style="width:70px;"><img src="/images/tab_school_cur.gif" alt="Schools" title=""></td>
				<? } else { ?>
					<td style="width:70px;"><a href="<?= $this->DOC_ROOT; ?>srs.php?area=school&sub=list" <? echo My_Classes_iepFunctionGeneral::windowStatus('Schools'); ?>><img src="/images/tab_school_sel.gif" alt="Schools" title=""></a></td>
				<? } } ?>
                <? if ($sessUserMinPriv != $UC_PG) { if ($area == "district") { ?>
					<td style="width:70px;"><img src="/images/tab_district_cur.gif" alt="Districts" title=""></td>
				<? } else { ?>
					<td style="width:70px;"><a href="<?= $this->DOC_ROOT; ?>srs.php?area=district&sub=list" <? echo My_Classes_iepFunctionGeneral::windowStatus('Districts'); ?>><img src="/images/tab_district_sel.gif" alt="Districts" title=""></a></td>
				<? } } ?>
                <? if ($sessUserMinPriv == $UC_SA && false) { if ($area == "sysadmin") { ?>
					<td style="width:70px;"><img src="/images/tab_sysadmin_cur.gif" alt="Sys Admin" title=""></td>
				<? } else { ?>
					<td style="width:70px;"><a href="<?= $this->DOC_ROOT; ?>srs.php?area=sysadmin&sub=settings" <? echo My_Classes_iepFunctionGeneral::windowStatus('Sys Admin'); ?>><img src="/images/tab_sysadmin_sel.gif" alt="Sys Admin" title=""></a></td>
                <? } } ?>
                                
				<? if ($sessUserMinPriv == $UC_SA) { 
						if ($area == "admin") { ?>
							<td style="width:70px;"><img src="/images/tab_admin_cur.gif" alt="Admin" title=""></td>
					<? } else { ?>
							<td style="width:70px;"><a href="<?= $this->DOC_ROOT; ?>srs.php?area=admin&sub=server" <? echo My_Classes_iepFunctionGeneral::windowStatus('Admin'); ?>><img src="/images/tab_admin_sel.gif" alt="Admin" title=""></a></td>
					<? } 
					}
					
					// 030403-JL REPORTS TAB ADDED
					include_once( "iep_reports_arrays_004.php");
					if ($sessUserMinPriv <= $UC_ASM) { 
						if ($area == "reports") { ?>
							<td style="width:70px;"><img src="/images/tab_reports_cur.gif" alt="Reports" title=""></td>
					<? } else { ?>
							<td style="width:70px;"><a href="<?= $this->DOC_ROOT; ?>srs.php?area=reports&sub=reports" <? echo My_Classes_iepFunctionGeneral::windowStatus('Admin'); ?>><img src="/images/tab_reports_sel.gif" alt="Reports" title=""></a></td>
					<? } 
					}?>

				<?  if ($area == "help") { ?>
						<td style="width:70px;"><img src="/images/tab_help_cur.gif" alt="Help" title=""></td>
				<? } else { ?>
						<td style="width:70px;"><a href="<?= $this->DOC_ROOT; ?>srs.php?area=help&sub=tutorials" <? echo My_Classes_iepFunctionGeneral::windowStatus('Help'); ?>><img src="/images/tab_help_sel.gif" alt="Help" title=""></a></td>
				<? }?>
                <? }?>
					<td style="width:100%;"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table cellspacing="0" cellpadding="0">
	<? switch($area) {
		case "home": ?>
			<tr class="bgDark">
				<td style="padding-left:25px; height:19; width:1px;" class="btvsWhite">|</td>
				<? if ($sub == "home") { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Welcome</td>
				<? } else { ?>
				    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" >
                        <a href="<?= $this->DOC_ROOT; ?>srs.php?area=<? echo $area ?>&sub=home" <? echo My_Classes_iepFunctionGeneral::windowStatus('Welcome'); ?> class="menuTextLink" title="Welcome">Welcome</a>
                    </td>
				<? } ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<? if ($sub == "msg_center" || $sub == "message") { ?>
				
				<? if ($option == "edit") { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Edit Message</td>
				<? } ?>
				<? if ($option == "view") { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">View Message</td>
				<? } ?>
				<? if ($option == "log") { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Message Log</td>
				<? } ?>
				<? if ($option == "delete") { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Delete Message</td>
				<? } ?>
				<? if ($sub == "msg_center") { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">My Messages</td>
				<? } ?>
				<? } else { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=msg_center" <? echo My_Classes_iepFunctionGeneral::windowStatus('Messages'); ?> class="menuTextLink" title="Messages">My Messages</td>
				<? } ?>
						<td style="height:19; width:1px;" class="btvsWhite">|</td>
						<? if ($sub == "announcements") { ?>
							<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Announcements</td>
						<? } else { ?>
							<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?area=<? echo $area ?>&sub=announcements" class="menuTextLink" title="Announcements">Announcements</td>
						<? } ?>
						<? if ($sessUserMinPriv != $UC_PG) { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
                <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?area=personnel&sub=personnel&personnel=<? echo $sessIdUser; ?>&option=edit" <? echo My_Classes_iepFunctionGeneral::windowStatus('Edit Profile'); ?> class="menuTextLink" title="Edit Profile">Edit Profile</a></td>
				<? } ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<? if ($sub == "password") { ?>
                <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Password</a></td>
                <? } else { ?>
                            <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?area=<? echo $area ?>&sub=password" <? echo My_Classes_iepFunctionGeneral::windowStatus('Password'); ?> class="menuTextLink" title="Password">Password</a></td>
                <? } ?>
                <td style="height:19; width:1px;" class="btvsWhite">|</td>
                <td align="right" style="width:100%; padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="btsWhite">Logged On: <? echo $sessNameFull; ?></td>
            </tr>
            <? break;
	case "student": ?>
			<tr class="bgDark">
				<td style="padding-left:25px; height:19; width:1px;" class="btvsWhite">|</td>
				<? if ($sub == "list") { ?>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Student List</td>
				<? } else { ?>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=list" <? echo My_Classes_iepFunctionGeneral::windowStatus('Student List'); ?> class="menuTextLink" title="Student List">Student List</a></td>
				<? } ?>
				<? //if ($sessUserMinPriv <= $UC_CM) { ?>
				<? if ($sessCanCreateStudent) { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<? if ($sub == "student" && $option == "new") { ?>
						<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">New Student</td>
					<? } else { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=<? echo $area ?>&option=new" <? echo My_Classes_iepFunctionGeneral::windowStatus('New Student'); ?> class="menuTextLink" title="New Student">New Student</a></td>
					<? }
				} ?>
				<? if ($sessUserMinPriv <= $UC_ASM) { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<? if ($sub == "transfer_center") { ?>
						<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Transfer Students</td>
					<? } else {
							if ( $sub == "transfer_request" ) {?>
								<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Transfer Request</td>
							<?  } else { ?>
                            <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=student&sub=transfer_center" <? echo My_Classes_iepFunctionGeneral::windowStatus('Transfer Students'); ?> class="menuTextLink" title="Transfer Students">Transfer Students</a></td>
							<? }
						}
				} ?>
				<? // ================================================== ?>
				<? // ======= admin sub-tab ============================ ?>
				<? if($sessPrivCheckObj->minPrivUC_ADMorBetter() && $sub != "admin") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
                <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a onClick="<? echo "student_admin=createWindow3('srs.php?&area=$area&sub=admin','student_admin','700','400','yes','yes', 'toolbar=no, status=yes');"; ?>" href="#" <? echo My_Classes_iepFunctionGeneral::windowStatus('Student Admin'); ?> class="menuTextLink" title="Student Admin">Student Admin</a></td>
				<? } elseif($sessPrivCheckObj->minPrivUC_ADMorBetter() && $sub == "admin") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Student Admin</td>
				<? } ?>
				<? // ================================================== ?>

            <? if ($sessUserMinPriv <= $UC_CM) { ?>
                <td style="height:19; width:1px;" class="btvsWhite">|</td>
                <? if ($sub == "search_helper") { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Search Helper</td>
                <? } else {
                        if ( $sub == "search_helper" ) {?>
                            <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Search Helper</td>
                        <?  } else { ?>
                            <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=student&sub=search_helper" <? echo My_Classes_iepFunctionGeneral::windowStatus('Search Helper'); ?> class="menuTextLink" title="Search Helper">Search Helper</a></td>
                        <? }
                    }
            } ?>



				<? // ================================================== ?>
				<? if ($sub == "student" && $option == "view") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">View Student</td>
				<? } ?>
				<? if ($sub == "guardian" && $option == "view") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">View Parent(s)</td>
				<? } ?>
				<? if ($sub == "guardian" && $option == "edit") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Edit Parent(s)</td>
				<? } ?>
				<? if ($sub == "guardian" && $option == "log") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Parent Log</td>
				<? } ?>
				<? if ($sub == "guardian" && $option == "delete") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Delete Parent</td>
				<? } ?>
				<? if ($sub == "student" && $option == "edit") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Edit Student</td>
				<? } ?>
				<? if ($option == "parents") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Parent(s)</td>
				<? } ?>
				<? if ($option == "forms") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Student Forms</td>
				<? } ?>
				<? if ($option == "team") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Student Team</td>
				<? } ?>
				<? if ($sub == "student" && $option == "notes") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Student Notes</td>
				<? } ?>
				<? if ($sub == "student" && $option == "log") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Student Log</td>
				<? } ?>
				<? if ($sub == "student" && $option == "delete") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Delete Student</td>
				<? } ?>
				<? if ($sub == "form_001") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Notice and Consent for Initial Evaluation</td>
				<? } ?>
				<? if ($sub == "form_002") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">MDT Report</td>
				<? } ?>
				<? if ($sub == "form_003") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Notification of IEP Meeting</td>
				<? } ?>
				<? if ($sub == "form_004") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Individual Education Program (IEP)</td>
				<? } ?>
				<? if ($sub == "form_005") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Notice and Consent For Initial Placement</td>
				<? } ?>
				<? if ($sub == "form_006") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Notice of School District&rsquo;s Decision</td>
				<? } ?>
				<? if ($sub == "form_007") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Notice and Consent for Reevaluation</td>
				<? } ?>
				<? if ($sub == "form_008") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Notice of Change of Placement</td>
				<? } ?>
				<? if ($sub == "form_009") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Notice of Discontinuation</td>
				<? } ?>
				<? if ($option == "progress") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Progress Report</td>
				<? } ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td align="right" style="width:100%; padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="btsWhite">Logged On: <? echo $sessNameFull; ?></td>
			</tr>
			<? break;
	case "personnel": ?>
			<tr class="bgDark">
				<td style="padding-left:25px; height:19; width:1px;" class="btvsWhite">|</td>
				<? if ($sub == "list") { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Personnel List</td>
				<? } else { ?>
                <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=list" <? echo My_Classes_iepFunctionGeneral::windowStatus('Personnel List'); ?> class="menuTextLink" title="Personnel List">Personnel List</a></td>
				<? } ?>
						<? if ($sessUserMinPriv != $UC_PG) { // 2003-03-22 sl removed New Personnel choice ?>
                        <td style="height:19; width:1px;" class="btvsWhite">|</td>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="new_privilege.php?personnel=<? echo $sessIdUser; ?>" target="_blank" <? echo My_Classes_iepFunctionGeneral::windowStatus('New Privilege'); ?> class="menuTextLink" title="New Privilege">New Privilege</a></td>
				<? } ?>
				<? if ($option == "edit") { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText"><? if ($sessIdUser == $personnel) { echo "Edit Profile"; } else { echo "Edit Personnel"; } ?></td>
				<? } ?>
				<? if ($option == "view") { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText"><? if ($sessIdUser == $personnel) { echo "View Profile"; } else { echo "View Personnel"; } ?></td>
				<? } ?>
				<? if ($option == "notes") { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Personnel Notes</td>
				<? } ?>
				<? if ($option == "log") { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Personnel Log</td>
				<? } ?>
				<? if($sessPrivCheckObj->isUC_SA() && $sub != "admin") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=admin" <? echo My_Classes_iepFunctionGeneral::windowStatus('Personnel Admin'); ?> class="menuTextLink" title="Personnel Admin">Personnel Admin</td>
				<? } elseif($sessPrivCheckObj->isUC_SA() && $sub == "admin") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Personnel Admin</td>
				<? } ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td align="right" style="width:100%; padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="btsWhite">Logged On: <? echo $sessNameFull; ?></td>
			</tr>
	<? break;
	case "school": ?>
			<tr class="bgDark">
				<td style="padding-left:25px; height:19; width:1px;" class="btvsWhite">|</td>
				<? if ($sub == "list") { ?>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">School List</td>
				<? } else { ?>
                <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=list" <? echo My_Classes_iepFunctionGeneral::windowStatus('School List'); ?> class="menuTextLink" title="School List">School List</a></td>
				<? } ?>
				<? if ($sessUserMinPriv <= $UC_ADM && false) { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<? if ($option == "new") { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">New School</td>
				<? } else { ?>
                        <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=<? echo $area ?>&option=new" <? echo My_Classes_iepFunctionGeneral::windowStatus('New School'); ?> class="menuTextLink" title="New School">New School</a></td>
				<? } } ?>
				<? if ($option == "edit") { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Edit School</td>
				<? } ?>
				<? if ($option == "view") { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">View School</td>
				<? } ?>
				<? if ($option == "log") { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">School Log</td>
				<? } ?>
				<? if ($option == "delete") { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Delete School</td>
				<? } ?>
				<? if($sessPrivCheckObj->isUC_SA() && $sub != "admin") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
                <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=admin" <? echo My_Classes_iepFunctionGeneral::windowStatus('School Admin'); ?> class="menuTextLink" title="School Admin">School Admin</a></td>
				<? } elseif($sessPrivCheckObj->isUC_SA() && $sub == "admin") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">School Admin</td>
				<? } ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td align="right" style="width:100%; padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="btsWhite">Logged On: <? echo $sessNameFull; ?></td>
			</tr>
	<? break;
	case "district": ?>
			<tr class="bgDark">
				<td style="padding-left:25px; height:19; width:1px;" class="btvsWhite">|</td>
				<? if ($sub == "list") { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">District List</td>
				<? } else { ?>
            <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=list" <? echo My_Classes_iepFunctionGeneral::windowStatus('District List'); ?> class="menuTextLink" title="District List">District List</a></td>
				<? } ?>
						<? if ($sessUserMinPriv == $UC_SA && false) { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<? if ($option == "new") { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">New District</td>
				<? } else { ?>
            <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=<? echo $area ?>&option=new" <? echo My_Classes_iepFunctionGeneral::windowStatus('New District'); ?> class="menuTextLink" title="New District">New District</a></td>
				<? } } ?>
				<? if ($option == "edit") { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Edit District</td>
				<? } ?>
				<? if ($option == "view") { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">View District</td>
				<? } ?>
				<? if ($option == "log") { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">District Log</td>
				<? } ?>
				<? if ($option == "delete") { ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Delete District</td>
				<? } ?>
				<? if($sessPrivCheckObj->isUC_SA() && $sub != "admin") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
                <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=admin" <? echo My_Classes_iepFunctionGeneral::windowStatus('District Admin'); ?> class="menuTextLink" title="District Admin">District Admin</a></td>
				<? } elseif($sessPrivCheckObj->isUC_SA() && $sub == "admin") { ?>
					<td style="height:19; width:1px;" class="btvsWhite">|</td>
					<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">District Admin</td>
				<? } ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td align="right" style="width:100%; padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="btsWhite">Logged On: <? echo $sessNameFull; ?></td>
			</tr>
	<? break;
	case "sysadmin": ?>
			<tr class="bgDark">
				<td style="padding-left:25px; height:19; width:1px;" class="btvsWhite">|</td>
				<? if ($sub == "settings") { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Settings</td>
				<? } else { ?>
            <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=settings" <? echo My_Classes_iepFunctionGeneral::windowStatus('Settings'); ?> class="menuTextLink" title="Settings">Settings</a></td>
				<? } ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<? if ($sub == "logs") { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Logs</td>
				<? } else { ?>
            <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=logs" <? echo My_Classes_iepFunctionGeneral::windowStatus('Logs'); ?> class="menuTextLink" title="Logs">Logs</a></td>
				<? } ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<td align="right" style="width:100%; padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="btsWhite">Logged On: <? echo $sessNameFull; ?></td>
			</tr>
	<? break;
	case "reports": ?>
			<tr class="bgDark">
				<td style="padding-left:25px; height:19; width:1px;" class="btvsWhite">|</td>
				<? if ($sub == "case_load") { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Case Load</td>
				<? } else { ?>
            <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=case_load" <? echo My_Classes_iepFunctionGeneral::windowStatus('Case Load'); ?> class="menuTextLink" title="Case Load">Case Load</a></td>
				<? } ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<? if ($sub == "sesis") { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">SESIS</td>
				<? } else { ?>
            <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=sesis" <? echo My_Classes_iepFunctionGeneral::windowStatus('SESIS'); ?> class="menuTextLink" title="SESIS">SESIS</a></td>
				<? } ?>
				<td style="height:19; width:1px;" class="btvsWhite">|</td>
				<? if ($sub == "evaluation_date_report") { ?>
				<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Evaluation Date Report</td>
				<? } else { ?>
            <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=evaluation_date_report" <? echo My_Classes_iepFunctionGeneral::windowStatus('Evaluation Date Report'); ?> class="menuTextLink" title="Evaluation Date Report">Evaluation Date Report</a></td>
				<? } ?>
				<? if(0) { ?>

                    <? if ($sub == "nssrss_file") { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Build NSSRSS File</td>
                    <? } else { ?>
                <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=nssrss_file" <? echo My_Classes_iepFunctionGeneral::windowStatus('Build NSSRSS File'); ?> class="menuTextLink" title="Build NSSRSS File">Build NSSRSS File</a></td>
                    <? } ?>
				<? } ?>

				<td align="right" style="width:100%; padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="btsWhite">Logged On: <? echo $sessNameFull; ?></td>
			</tr>
	<? break;
        case "admin": ?>
				<tr class="bgDark">
						<td style="padding-left:25px; height:19; width:1px;" class="btvsWhite">|</td>
						
						<? if ($sub == "server") { ?>
							<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Server</td>
						<? } else { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=server" <? echo My_Classes_iepFunctionGeneral::windowStatus('Server'); ?> class="menuTextLink" title="Settings">Server</a></td>
						<? } ?>
						
						<td style="height:19; width:1px;" class="btvsWhite">|</td>
		
						<? if ($sub == "sessions") { ?>
							<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Sessions</td>
						<? } else { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=sessions" <? echo My_Classes_iepFunctionGeneral::windowStatus('Sessions'); ?> class="menuTextLink" title="Logs">Sessions</a></td>
                <? } ?>

                <td style="height:19; width:1px;" class="btvsWhite">|</td>

                <? if ($sub == "announcements") { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Announcements</td>
                <? } else { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=announcements" <? echo My_Classes_iepFunctionGeneral::windowStatus('Announcements'); ?> class="menuTextLink" title="Logs">Announcements</a></td>
						<? } ?>
		
						<td style="height:19; width:1px;" class="btvsWhite">|</td>
		
						<? if ($sub == "dataadmin") { ?>
							<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Data Admin</td>
						<? } else { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=dataadmin" <? echo My_Classes_iepFunctionGeneral::windowStatus('Data Admin'); ?> class="menuTextLink" title="Logs">Data Admin</a></td>
						<? } ?>
						
						<td style="height:19; width:1px;" class="btvsWhite">|</td>
						<td align="right" style="width:100%; padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="btsWhite">Logged On: <? echo $sessNameFull; ?></td>
				</tr>
        <? break;
        case "help": ?>
				<tr class="bgDark">
						<td style="padding-left:25px; height:19; width:1px;" class="btvsWhite">|</td>
						
						<? if ($sub == "tutorials") { ?>
							<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Tutorials</td>
						<? } else { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=tutorials" <? echo My_Classes_iepFunctionGeneral::windowStatus('Tutorials'); ?> class="menuTextLink" title="Tutorials">Tutorials</a></td>
						<? } ?>
						
						<td style="height:19; width:1px;" class="btvsWhite">|</td>
		
		
						<? if ($sub == "faq") { ?>
							<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">FAQ</td>
						<? } else { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=faq" <? echo My_Classes_iepFunctionGeneral::windowStatus('FAQ'); ?> class="menuTextLink" title="FAQ">FAQ</a></td>
						<? } ?>
		
						<td style="height:19; width:1px;" class="btvsWhite">|</td>
		
						<? if ($sub == "techreq") { ?>
							<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Tech Requirements</td>
						<? } else { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=techreq" <? echo My_Classes_iepFunctionGeneral::windowStatus('Tech Requirements'); ?> class="menuTextLink" title="Tech Requirements">Tech Requirements</a></td>
						<? } ?>
		
						<td style="height:19; width:1px;" class="btvsWhite">|</td>
		
						<? if ($sub == "hdrequests") { ?>
							<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Help Desk Request</td>
						<? } else { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=hdrequests" <? echo My_Classes_iepFunctionGeneral::windowStatus('Help Desk Request'); ?> class="menuTextLink" title="Help Desk Request">Help Desk Request</a></td>
						<? } ?>
		
						<td style="height:19; width:1px;" class="btvsWhite">|</td>
		
						<? if ($sub == "feedback" || $sub == "survey") { ?>
							<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Feedback</td>
						<? } else { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=feedback" <? echo My_Classes_iepFunctionGeneral::windowStatus('Feedback'); ?> class="menuTextLink" title="Feedback">Feedback</a></td>
						<? } ?>
		
						<td style="height:19; width:1px;" class="btvsWhite">|</td>
		
						<? if ($sub == "security") { ?>
							<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Security</td>
						<? } else { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=security" <? echo My_Classes_iepFunctionGeneral::windowStatus('Security'); ?> class="menuTextLink" title="Security">Security</a></td>
						<? } ?>
		
						<td style="height:19; width:1px;" class="btvsWhite">|</td>
		
						<? if ($sub == "enhancements") { ?>
							<td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="menuText">Enhancements</td>
						<? } else { ?>
                    <td style="padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap" ><a href="<?= $this->DOC_ROOT; ?>srs.php?&area=<? echo $area ?>&sub=enhancements" <? echo My_Classes_iepFunctionGeneral::windowStatus('Enhancements'); ?> class="menuTextLink" title="Enhancements">Enhancements</a></td>
                <? } ?>
						
						<td style="height:19; width:1px;" class="btvsWhite">|</td>
						<td align="right" style="width:100%; padding-left:10px; padding-right:10px; white-space:nowrap;" nowrap="nowrap"  class="btsWhite">Logged On: <? echo $sessNameFull; ?></td>
				</tr>
        <? break;
	default: ?>
		<tr class="bgDark">
			<td>&nbsp;</td>
			<td style="width:100%; height:19px;">&nbsp;</td>
		</tr>
	<? } ?>
</table>
<!-- END INCLUDE TOP --><div id="content" align="center">
<table class="contentOuter">
<tr valign="top"><td class="contentOuter">