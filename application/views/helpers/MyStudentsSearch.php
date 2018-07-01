<?php

class Zend_View_Helper_MyStudentsSearch extends Zend_View_Helper_Abstract {
	
	public function myStudentsSearch() {
		
		$text='<form action="/student/search" id="studentSearchParams">
		  		<table>
		  		<tr>
		  			<td>
			            <input type="button" onClick="javascript:startStudentSearch();" value="Search">
		  			</td>
		  		</tr>
		  		<tr>
		  			<td>
			            <label style="color:black;">Limit to</label>
			            <select id="limitto" name="limitto">
				            <option value="all" selected="selected">All</option>
			            	<option value="caseload">Case Load</option>
						</select>			            
		  			</td>
		  		</tr>
		  		<tr>
		  			<td>
			            <label style="color:black;">Status</label>
			            <select id="status" name="status">
				            <option value="All">All</option>
			            	<option value="Active" selected="selected">Active</option>
				            <option value="Inactive">Inactive</option>
				            <option value="Never Qualified">Never Qualified</option>
				            <option value="No Longer Qualifies">No Longer Qualifies</option>
				            <option value="Transferred to Non-SRS District">Transferred to Non-SRS District</option>
						</select>			            
		  			</td>
		  		</tr>
		  		<tr>
		  			<td>
			            <label style="color:black;">Order</label>
			            <select id="orderby" name="orderby">
				            <option value="name" selected="selected">Name</option>
				            <option value="school">School</option>
						</select>			            
		  			</td>
		  		</tr>
		  		<tr>
		  			<td>
			            <label style="color:black;">Number of records</label>
			            <select id="recsPer" name="recsPer">
			            	<option>2</option>
			            	<option>5</option>
			            	<option selected>15</option>
			            </select>
		  			</td>
		  		</tr>
		  		<tr>
		  			<td>
			            <label style="color:black;">Student First Name</label><br />
			            <input type="text" style="width:135px;" id="name_first" name="name_first" value="">
		  			</td>
		  		</tr>
		  		<tr>
		  			<td>
			            <label style="color:black;">Student Last Name</label><br />
			            <input type="text" style="width:135px;" id="name_last" name="name_last" value="">
		  			</td>
		  		</tr>
		  		<tr>
		  			<td>
			            <label style="color:black;">Student ID (SRS)</label><br />
			            <input type="text" style="width:135px;" id="id_student" name="id_student" value="">
		  			</td>
		  		</tr>
		  		<tr>
		  			<td>
			            <label style="color:black;">Student ID (District)</label><br />
			            <input type="text" style="width:135px;" id="id_student_local" name="id_student_local" value="">
		  			</td>
		  		</tr>
		  		<tr>
		  			<td>
			            <label style="color:black;">NSSRS ID#</label><br />
			            <input type="text" style="width:135px;" id="unique_id_state" name="unique_id_state" value="">
		  			</td>
		  		</tr>
		  		<tr>
		  			<td>
			            <label style="color:black;">Case Manager First Name</label><br />
			            <input type="text" style="width:135px;" id="name_case_mgr_first" name="name_case_mgr_first" value="">
		  			</td>
		  		</tr>
		  		<tr>
		  			<td>
			            <label style="color:black;">Case Manager Last Name</label><br />
			            <input type="text" style="width:135px;" id=""name_case_mgr_last"" name="name_case_mgr_last" value="">
		  			</td>
		  		</tr>

		  		<tr>
		  			<td>
			            <label style="color:black;">School Name</label><br />
			            <input type="text" style="width:135px;" id="get_name_school(id_county,id_district,id_school)" name="get_name_school(id_county,id_district,id_school)" value="">
		  			</td>
		  		</tr>

		  		<tr>
		  			<td>
			            <label style="color:black;">District Name</label><br />
			            <input type="text" style="width:135px;" id="get_name_district(id_county,id_district)" name="get_name_district(id_county,id_district)" value="">
		  			</td>
		  		</tr>

		  		<tr>
		  			<td>
			            <label style="color:black;">Public School Student (T/F)</label><br />
			            <input type="text" style="width:135px;" id="pub_school_student" name="pub_school_student" value="">
		  			</td>
		  		</tr>

		  		<tr>
		  			<td>
			            <label style="color:black;">Student Team (ID personnel)</label><br />
			            <input type="text" style="width:135px;" id="onteam" name="onteam" value="">
		  			</td>
		  		</tr>

		  		<tr>
		  			<td>
			            <label style="color:black;">Student CM (ID personnel)</label><br />
			            <input type="text" style="width:135px;" id="isCM" name="isCM" value="">
		  			</td>
		  		</tr>

		  		<tr>
		  			<td>
			            <label style="color:black;">Student SC (ID personnel)</label><br />
			            <input type="text" style="width:135px;" id="isSC" name="isSC" value="">
		  			</td>
		  		</tr>

		  		<tr>
		  			<td>
			            <label style="color:black;">Student EI CM (ID personnel)</label><br />
			            <input type="text" style="width:135px;" id="isEICM" name="isEICM" value="">
		  			</td>
		  		</tr>

		  		<tr>
		  			<td>
			            <label style="color:black;">Grade</label><br />
			            <input type="text" style="width:135px;" id="s.grade" name="s.grade" value="">
		  			</td>
		  		</tr>

		  		<tr>
		  			<td>
			            <label style="color:black;">Grade Greater Than</label><br />
			            <input type="text" style="width:135px;" id="s.gradegreaterthan" name="s.gradegreaterthan" value="">
		  			</td>
		  		</tr>

		  		<tr>
		  			<td>
			            <label style="color:black;">Grade Less Than</label><br />
			            <input type="text" style="width:135px;" id="s.gradelessthan" name="s.gradelessthan" value="">
		  			</td>
		  		</tr>

		  		<tr>
		  			<td>
			            <label style="color:black;">Alternate Assessment (Y/N)</label><br />
			            <input type="text" style="width:135px;" id="s.alternate_assessment" name="s.alternate_assessment" value="">
		  			</td>
		  		</tr>
		  		
		  		</table>
	            <br />
			</form>
		';
		
		return $text;
	}
}