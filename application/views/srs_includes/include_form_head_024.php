<?php ?>
<? //var_dump($this->sessPrivCheckObj); ?>
	<table border="0">	
		<tr>
			<td class="bts" nowrap="nowrap"><span class="btsb">Student</span>:&nbsp;<? echo $this->student_data['name_student_full'] . " (".$this->student_data['studentDisplay'].")"; ?></td>
			<? // added page status 4/22/02 JL ?>
			<td class="bts" nowrap="nowrap" align="center" style="width:100%"><? //echo $formObj->pageStatus($this->formData['status'], $this->formData['page_status']) . $formObj->suppDisplay($document, $this->sub, $this->formData['status']);?></td>
			
			<td class="bts" align="right" style="width:100%" nowrap="nowrap"><span class="btsb">Form Options</span>:&nbsp;</td>
			<td class="bts" style="white-space:nowrap"><? 
					//if($this->option == 'new') $databaseForm['status'] = 'Draft';
					//echo My_Classes_iepFunctionGeneral::buildOptionListAccess($this->formData, 'student', $this->sub, $this->page, array('view','edit','finalize','log','print'), $this->sessPrivCheckObj);
				?>
			</td>

		</tr>
		<tr>
			<td class="bts" align="right" colspan="3"><span class="btsb">Student Options</span>:&nbsp;</td>
			<td class="bts">
			<?	
			//echo $this->objStudent->buildOptionListAccess($this->id_student, $this->sessPrivCheckObj);
			
			?>
			</td>
		</tr>
	</table>
	
	<div id="serverInteractionProgressMsg" style="opacity: 0;"></div>
	<div id="page_navigation_controlbar">
	
	<table>
		<tr>
		<? if ($this->pageCount > 1) { ?>
    		<td class="bts"><span class="btsb">Page</span>:</td>
    		<td><? 
    			//echo My_Classes_iepFunctionGeneral::valueListNumbers("navPage", $this->pageCount, 1, $this->page, $this->page, "none", "onChange=\"javascript:recordAction(this.form, this.value);\"");
    			echo My_Classes_iepFunctionGeneral::valueListNumbers("navPage", $this->pageCount, 1, $this->page, $this->page, "none"); 
    			?>
    		</td>
		<? } ?>
		<? if ($this->page > 1) { ?>
			<td align="right" style="padding-left:4px;">
			<?php if(0) { ?>
				<a accesskey="b" href="javascript:recordAction(document.form0, '<? echo ($this->page - 1); ?>');" <? echo My_Classes_iepFunctionGeneral::windowStatus('Click here to go to the previous page.'); ?>>
					<img src="/images/button_prev.gif" alt="Prev" title="Previous Page (shortcut key = B)">
				</a>
			<?php } ?>
			<button dojoType="dijit.form.Button" id="prevPage" >Prev Page</button>
			</td>
		
		<? } ?>
		<? if ($this->page < $this->pageCount) { ?>
			<td align="right" style="padding-left:4px;">
			<?php if(0) { ?>
				<a accesskey="f" href="javascript:nextPage(document.form0, '<? echo ($this->page + 1); ?>');" <? echo My_Classes_iepFunctionGeneral::windowStatus('Click here to go to the next page.'); ?>>
					<img src="/images/button_next.gif" alt="Next" title="Next Page (shortcut key = F)">
				</a>
			<?php } ?>
			<button dojoType="dijit.form.Button" id="nextPage" >Next Page</button>
			</td>
		<? } ?>
		<?	if(( $this->sub != 'form_021_supp') && ($this->sub != "form_004dupe") ){ ?>
				<? if (empty($document)) { ?>
					<td align="right" style="width:100%; padding-right:4px;"><a accesskey="c" href="javascript:recordAction(document.forms[0], 'cancel');" <? echo My_Classes_iepFunctionGeneral::windowStatus('Click here to cancel this record &amp; return to the previous area.'); ?>><img src="/images/button_cancel.gif" alt="Cancel" title="Cancel (shortcut key = C)"></a></td>
				<? } else { ?>
					<td align="right" style="width:100%; padding-right:4px;"><a accesskey="d" href="javascript:recordAction(document.forms[0], 'done');" <? echo My_Classes_iepFunctionGeneral::windowStatus('Click here to exit this record &amp; return to the previous area.'); ?>><img src="/images/button_done.gif" alt="Done" title="Done (shortcut key = D)"></a></td>
				<? } ?>
				
				<? if ($this->mode == "edit") { ?>
					<? if ($this->spChkArr != "") { ?>
						<!-- Spell Check Button -->
						<td align="right" style="padding-right:4px;">
							<a href="javascript:spell()" <? echo My_Classes_iepFunctionGeneral::windowStatus('Click here to spell check this form.'); ?>><img src="/images/button_spelling.gif" alt="Check Spelling" title="Check Spelling"></a>
							<!-- <input type="button" value="Check Spelling" onClick="spell()"> -->
						</td>
					<? } ?>

					<td align="right" style="padding-right:4px;"><span id="revert"><a <? echo My_Classes_iepFunctionGeneral::windowStatus('Record can&rsquo;t be reverted because no changes have been made.'); ?>><img src="/images/button_revert_off.gif" alt="Revert" title="No changes have been made."></a></span></td>
					<td align="right" style="padding-right:4px;">
<!-- 
					    <span id="save">
					        <a <? echo My_Classes_iepFunctionGeneral::windowStatus('Record can&rsquo;t be saved because no changes have been made.'); ?>>
					            <img src="/images/button_save_off.gif" alt="Save" title="No changes have been made.">
                            </a>
                        </span>
 -->
                        <button dojoType="dijit.form.Button" id="submitButton" disabled='true'>Save</button>
                    </td>
				<? } ?>
				
				<? if (empty($document)) { ?>
					<td align="right" class="bt"><span id="print"><a <? echo My_Classes_iepFunctionGeneral::windowStatus('Record must be saved before it can be printed.'); ?>><img src="/images/button_print_off.gif" alt="Print" title="Record must be saved before it can be printed."></a></span></td>
				<? } else { 
						if ( $this->sub == "form_001" || $this->sub == "form_002" || $this->sub == "form_003" || $this->sub == "form_003" || $this->sub == "form_005" || $this->sub == "form_006" || $this->sub == "form_007" || $this->sub == "form_008" || $this->sub == "form_009" ) { ?>
							<td align="right" class="bt"><span id="print"><a accesskey="p" href="javascript:print('<? echo "$DOC_ROOT/form_print.php?form=$this->sub&document=$document"; ?>', '<? echo $document; ?>', 'scrollbars=1,status=1,toolbar=1,resizable=1,location=0,width=800,top=10,left=10');" <? echo My_Classes_iepFunctionGeneral::windowStatus('Click here to view a printable PDF version of this document.'); ?>><img src="/images/button_print.gif" alt="Print" title="Print (shortcut key = P)"></a></span></td>
						<? } else { ?>
							<td align="right" class="bt"><span id="print"><a accesskey="p" href="javascript:print('<? echo "$DOC_ROOT/srs.php?area=$area&sub=$this->sub&document=$document&page=$this->page&option=print"; ?>', '<? echo $document; ?>', 'scrollbars=1,status=1,toolbar=1,resizable=1,location=0,width=800,top=10,left=10');" <? echo My_Classes_iepFunctionGeneral::windowStatus('Click here to view a printer friendly version of this page.'); ?>><img src="/images/button_print.gif" alt="Print" title="Print (shortcut key = P)"></a></span></td>
					<? 	}
				   } ?>
		<?  } else { ?>
				<?	if ($this->option=="edit" && $suppID!='') { ?>
						<td align="right" style="width:90%; padding-right:4px;"><a accesskey="d" href="javascript:recordAction(document.forms[0], 'done');" <? echo My_Classes_iepFunctionGeneral::windowStatus('Click here to exit this record &amp; return to the previous area.'); ?>><img src="/images/button_done.gif" alt="Done" title="Done (shortcut key = D)"></a></td>
						<? if ($spChkArr != "") { ?>
							<!-- Spell Check Button -->
							<td align="right" style="padding-right:4px;"><input type="button" value="Check Spelling" onClick="spell()"></td>
						<? } ?>
						<td align="right" style="width:10%; padding-right:4px;"><a accesskey="s" href="javascript:recordAction(document.forms[0], 'save');" <? echo My_Classes_iepFunctionGeneral::windowStatus('Click here to save this record &amp; return to the previous area.'); ?>><img src="/images/button_save.gif" alt="Save" title="Save (shortcut key = S)"></a></td>
				<?	} elseif($this->option=="view" && $suppID!='') { ?>
						<td align="right" style="width:100%; padding-right:4px;"><a accesskey="d" href="javascript:recordAction(document.forms[0], 'done');" <? echo My_Classes_iepFunctionGeneral::windowStatus('Click here to exit this record &amp; return to the previous area.'); ?>><img src="/images/button_done.gif" alt="Done" title="Done (shortcut key = D)"></a></td>
				<?	} else { ?>
					<td align="right" class="bt" width="100%"></td>
				<?	} ?>
		<? 	}?>
		
		</tr>
	</table>
	</div>
    <span id="validationListSpan">
	<table class="formTop" id="validationList" style="display:<? echo $this->fatal ? "inline":"none"; ?>">
		<body>
		<?php if(count($this->validationArr) <= 0 && !$this->valid) echo "DEVELOPER ISSUE: FORM IS NOT VALID BUT VALIDATIONARR IS EMPTY.<BR/>"; ?>
        <? if (!$this->valid) { ?>
        
            <? if ($this->fatal) { //$arr = $formObj->validater->arrIssuesFatal; ?>
            <tr>
                <td align="left" class="bts" style="width:100%;" colspan="2"><span class="btsb">Error</span>:&nbsp;This page <span class="btsbRed">can&rsquo;t be saved</span> because of the following validation issues:</td>
            </tr>
            <? } else { //$arr = $formObj->validater->arrIssues; ?>
            <tr>
                <td align="left" class="bts" style="width:100%;" colspan="2">
                <span class="btsb">Checklist</span>:&nbsp;This page is valid for draft status but can&rsquo;t be finalized until the following issues are resolved:
                </td>
            </tr>
            <? }
    
    
            //
            // build validation output
            //
            
            if(count($this->validationArr) > 0)
            {
            	foreach($this->validationArr as $valRow) 
            	{ 
            		if(is_array($valRow['message'])) 
            		{
            			$message = implode(', ', $valRow['message']);
            		} else {
            			$message = $valRow['message'];
            		}
	                ?>
	                <tr>
	                    <td valign="top"><? echo "&#8226;"; ?></td>
	                    <td style="width:100%;"><? echo "<B>".$valRow['label']."</B>" . ' ' . $message; ?></td>
	                </tr>
	    			<?
            	}
            
            }
			
            ?>
            
        <? } ?>
		</body>
	</table>
	</span>
	
	<div id="hiddenValidationMessages">
		<?php 
			if(count($this->validationArr) > 0) {
            	echo '<input type="hidden" id="validationCount" value="'.count($this->validationArr).'" />';
            	$i = 0;
				foreach($this->validationArr as $valRow) { 
					
            		if(is_array($valRow['message'])) {
            			$message = implode(', ', $valRow['message']);
            		} else {
            			$message = $valRow['message'];
            		}
            		echo '<input type="hidden" id="validationMessage'.$i.'" name="'.$valRow['label'].'" value="'. $message .'" />'; 
            		$i++;
				}
            }
		?>
	</div>
	
    <span id="validationStatusSpan">
	<table class="formTop" id="validationStatus">
		<body>
		<tr>
        <? switch ($this->status) {
            case "Final": ?>
                <td align="left" style="width:100%;" class="bts"><? if ($this->option != "print") { ?><? echo "<span class=\"btsb\">Status</span>: Final";} ?></td>
                <?	break;
                default:
                if (!$this->valid && !$this->fatal) { ?>
                        <td align="left" class="bts" nowrap="nowrap">
                            <?  if ($this->option != "print" || $this->sub == "form_002" || $this->sub == "form_004" || $this->sub == "form_004dupe") { ?>
                                    <? echo "<span class=\"btsb\">Status</span>: <span class=\"btsbRed\">Draft</span>";
                                } ?>
                                <? if ($this->option != "print") { ?>
                                    (<a accesskey="v" href="javascript:displayValidationList();" <? echo My_Classes_iepFunctionGeneral::windowStatus('Toggle Validation Issues'); ?>>
                                    <span class="btsLBlue" style="text-decoration:underline;">click here</span></a> to view checklist)
                                <? } ?>
                        </td>
                <? } else { ?>
                        <td align="left" class="bts" nowrap="nowrap">
                            <?  if ($this->option != "print" || $this->sub == "form_002" || $this->sub == "form_004" || $this->sub == "form_004dupe") { ?>
                                    <? echo "<span class=\"btsb\">Status</span>: <span class=\"btsbRed\">Draft</span>";
                                } ?>
                                <? if ($this->valid && $this->option != "print") { ?>
                                    (page is OK)
                                <? } ?>
                        </td>
                <? } 
                break; 
            } ?>
		</tr>
		</body>
	</table>
	</span>

	<table class="formSectionHead" cellpadding="0" cellspacing="0">
		<tr>
			<td>Student Info</td>
		</tr>
	</table>

    <? if($this->db_form_data['status'] == "Draft" || $this->option == "new") { ?>
	    <table class="formInput" style="margin-top:1px;" border="0">
            <tr>
                <td nowrap="nowrap" style="width:33%">
                	<span class="btsb">Student</span>:&nbsp;
                	<?php 
                		if (array_key_exists('name_student_full', $this->student_data)) {
                			echo $this->student_data['name_student_full']; 
                		}
                	?>
                </td>
                <td nowrap="nowrap" style="width:25%">
                	<span class="btsb">Date of Birth</span>:&nbsp;
                	<?php 
                		if (array_key_exists('dob', $this->student_data)) {
                			echo My_Classes_iepFunctionGeneral::date_massage($this->student_data['dob']); 
                		}
                	?>
                </td>
                <td nowrap="nowrap" style="width:*; text-align:right">
                	<span class="btsb">Grade</span>:&nbsp;
                	<?php 
                		if (array_key_exists('grade', $this->student_data)) {
                			echo $this->student_data['grade']; 
                		}
                	?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" style="width:33%"><span class="btsb">Age</span>:&nbsp;
                	<?php 
                		if (array_key_exists('age', $this->student_data)) {
                			echo $this->student_data['age']; 
                		}
                	?>
                </td>
                <td nowrap="nowrap" style="width:25%"><span class="btsb">Gender</span>:&nbsp;
                	<?php
                		if (array_key_exists('gender', $this->student_data)) {
                			echo $this->student_data['gender']; 
                		}
                	?>
                </td>
                <td <? if('all' != $this->printpages) echo 'nowrap="nowrap"'; ?> style="width:*; text-align:right">
                	<span class="btsb">Address</span>:&nbsp;
                	<?php
	                	if (array_key_exists('address', $this->student_data)) {
	                		echo $this->student_data['address']; 
	                	}
                	?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" style="width:33%">
                	<span class="btsb">School District</span>:&nbsp;
                	<?php 
                		if (array_key_exists('name_district', $this->student_data)) {
                			echo $this->student_data['name_district']; 
                		}
                	?>
                </td>
                <td nowrap="nowrap" style="width:25%"><span class="btsb">School</span>:&nbsp;
                	<?php 
	                	if (array_key_exists('name_school', $this->student_data)) {
	                		echo $this->student_data['name_school'];
	                	} 
                	?>
                </td>
                <td nowrap="nowrap" style="width:*; text-align:right"><span class="btsb">
                	<? if(0) { ?> Date of Notice</span>:<? if ($this->mode == "edit") { ?>
                	<input type="text" name="date_notice" value="
                	<?php
                		if (array_key_exists('date_notice', $this->student_data)) {
                	 		echo My_Classes_iepFunctionGeneral::date_massage($this->formData['date_notice']); 
                		}
                	 ?>" 
                	 style="width:75px" class="bts" onFocus="javascript:modified('<? echo $DOC_ROOT; ?>', '<? echo $area; ?>', '<? echo $this->sub; ?>', '<? echo $keyName; ?>', '<? echo $pkey; ?>', '<? echo $this->page; ?>');"/><? } else { echo "<span style=\"width:75px;\" class=\"textAreaInset\">" . My_Classes_iepFunctionGeneral::htmlEncode($this->formData['date_notice'], true) . "</span>"; } ?> <? } ?>
                &nbsp;
                </td>
            </tr>
        </table>
    <? } else { ?>
	    <table class="formInput" style="margin-top:1px;" border="0">
            <tr>
                <td nowrap="nowrap" style="width:33%"><span class="btsb">Student</span>:&nbsp;
                	<?php 
                		if (array_key_exists('name_student_full', $this->student_data)) {
                			echo $this->student_data['name_student_full']; 
                		}
                	?>
                </td>
                <td nowrap="nowrap" style="width:25%"><span class="btsb">Date of Birth</span>:&nbsp;
                	<?php
                		if (array_key_exists('dob', $this->student_data)) {
                			echo My_Classes_iepFunctionGeneral::date_massage($this->student_data['dob']); 
                		}
                	?>
                </td>
                <td nowrap="nowrap" style="width:*; text-align:right"><span class="btsb">Grade</span>:&nbsp;
                	<?php
	                	if (array_key_exists('grade', $this->student_data)) {
	                		echo $this->student_data['grade']; 
	                	}
                	?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" style="width:33%">
	                <span class="btsb">Age</span>:&nbsp;
	                <?php 
	                	if (array_key_exists('age_at_finalize', $this->formData)) {
	                		echo $this->formData['age_at_finalize']; 
	                	}
	                ?>
                </td>
                <td nowrap="nowrap" style="width:25%"><span class="btsb">Gender</span>:&nbsp;
                	<?php
                	if (array_key_exists('gender', $this->student_data)) {
                		echo $this->student_data['gender']; 
                	}
                	?>
                </td>
                <td <? if('all' != $this->printpages) echo 'nowrap="nowrap"'; ?> style="width:*; text-align:right"><span class="btsb">Address</span>:&nbsp;
                	<?php
                	if (array_key_exists('address_at_finalize', $this->formData)) {
                		echo $this->formData['address_at_finalize'];
                	} 
                	?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" style="width:33%"><span class="btsb">School District</span>:&nbsp;
                	<?php 
                	if (array_key_exists('name_district_at_finalize', $this->formData)) {
                		echo $this->formData['name_district_at_finalize']; 
                	}
                	?>
                </td>
                <td nowrap="nowrap" style="width:25%"><span class="btsb">School</span>:&nbsp;
                <?php
	                if (array_key_exists('name_school_at_finalize', $this->formData)) {
	                	echo $this->formData['name_school_at_finalize']; 
	    			}
                ?>
                </td>
                <td nowrap="nowrap" style="width:*; text-align:right"><span class="btsb">Date of Notice</span>:
                <? if ($this->mode == "edit") { ?><input type="text" name="date_notice" value="
                <? echo My_Classes_iepFunctionGeneral::date_massage($this->formData['date_notice']); 
                ?>" style="width:75px" class="bts" onFocus="javascript:modified('<? echo $DOC_ROOT; ?>', '<? echo $area; ?>', 
                '<? echo $this->sub; ?>', '<? echo $keyName; ?>', '<? echo $pkey; ?>', '<? echo $this->page; ?>');"/><? } 
                else { echo "<span style=\"width:75px;\" class=\"textAreaInset\">" . My_Classes_iepFunctionGeneral::htmlEncode($this->formData['date_notice'], 
                true) . "</span>"; } ?></td>
            </tr>
        </table>
    <? }
    