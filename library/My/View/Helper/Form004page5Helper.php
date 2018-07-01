<?php

	class My_View_Helper_form004page5Helper {
		
		public function getTransitionPlanSubform($transition_secgoals, $transition_16_course_study, 
							$transition_16_instruction, $transition_16_rel_services, $transition_16_emp_options, 
							$transition_16_comm_exp, $transition_16_dly_liv_skills, $transition_16_func_voc_eval,
							$transition_16_inter_agency_link) {
			
			$text = '<div id="subformToChange">				
						<table class="formInput" style="margin-top:1px;">        
						     <tr style="background:#EEE8CD; margin:0px;padding:0px;">
						    	<td>
						    		<span><strong>This student will not turn 16 during this IEP, so a statement of the 
						    		child\'s transition services is not required.</strong></span>
						    	</td>
						    </tr>
						     <tr>
						        <td style="">
						           Complete all that apply, otherwise explain why this item does not apply.
						        </td>
						    </tr>
						    <tr style="background:#EEE8CD; margin:0px;padding:0px;">
						    	<td>
						    		<strong>Measurable post-secondary goals based on age appropriate transition 
						    		assessments: (This section MUST include education/training, employment, 
						    		and when appropriate independent living goals.)</strong>
						    	</td>
						    </tr>
						    <tr style="background:#EEE8CD; margin:0px;padding:0px;">
						    	<td style="text-align: center;">
						    		<strong>Post Secondary Goal(s)</strong>
						    	</td>
						    </tr>
						    <tr>
						        <td style="text-align: center;">
						        	'.$transition_secgoals.'
						            The above goal(s) include education/training, employment, and when appropriate independent living goals.
						        </td>
						    </tr>
						    <tr style="background:#EEE8CD; margin:0px;padding:0px;">
						    	<td style="color: red;">
						    		<strong>Course of Study</strong>
						    	</td>
						    </tr>
						    <tr>
						    	<td>
						    		'.$transition_16_course_study.'
						    	</td>
						    </tr>
						    <tr>
						        <td>
									<strong><span style="">Beginning no later than the first IEP to be in effect when the 
									child turns 16, or younger if determined appropriate by the IEP team, 
									a statement of needed transition services is required</span></strong> (indicate the 
									strengths and/or needs for each area and when appropriate, statement of 
									interagency responsibilities or any needed linkages):
						        </td>
						    </tr>
						    <tr style="background:#EEE8CD; margin:0px;padding:0px;">
						    	<td>
						    		Instruction:
						    	</td>
						    </tr>
						    <tr>
						    	<td>
						    		'.$transition_16_instruction.'
						    	</td>
						    </tr>
						    <tr style="background:#EEE8CD; margin:0px;padding:0px;">
						    	<td>
						    		Related Services:
						    	</td>
						    </tr>
						    <tr>
						    	<td>
						    		'.$transition_16_rel_services.' 
						    	</td>
						    </tr>
						    <tr style="background:#EEE8CD; margin:0px;padding:0px;">
						    	<td>
						    		Community Experiences:
						    	</td>
						    </tr>
						    <tr>
						    	<td>
						    		'.$transition_16_comm_exp.'  
						    	</td>
						    </tr>
						    <tr style="background:#EEE8CD; margin:0px;padding:0px;">
						    	<td>
									Development of employment and other appropriate <span style="">
									<strong>post school adult living objectives: </strong></span>(i.e. career 
									fair, vocational evaluation, job shadowing, work experience, job seeking 
									and keeping skills, visit college/training programs):
						    	</td>
						    </tr>
						    <tr>
						    	<td>
						    		'.$transition_16_emp_options.'
						    	</td>
						    </tr>
						    <tr style="background:#EEE8CD; margin:0px;padding:0px;">
						    	<td>
						    		Daily Living Skills:
						    	</td>
						    </tr>
						    <tr>
						    	<td>
						    		'.$transition_16_dly_liv_skills.' 
						    	</td>
						    </tr>
						    <tr style="background:#EEE8CD; margin:0px;padding:0px;">
						    	<td>
						    		Functional Vocational Evaluation:
						    	</td>
						    </tr>
						    <tr>
						    	<td>
						    		'.$transition_16_func_voc_eval.'  
						    	</td>
						    </tr>
						    <tr style="background:#EEE8CD; margin:0px;padding:0px;">
						    	<td>
						    		Interagency Linkages and Responsibilities:
						    	</td>
						    </tr>
						    <tr>
						    	<td>
						    		'.$transition_16_inter_agency_link.'  
						    	</td>
						    </tr>
						    <tr style="background:#EEE8CD; margin:0px;padding:0px;">
						    	<td>
						    		<table style="width: 100%; background:#EEE8CD; margin:0px;
						    		padding:0px;">
						    			<tr>
						    				<td>Transition Activities</td>
						    				<td>Agency Responsible</td>
						    				<td>Date Due</td>
						    			</tr>
						    		</table>
						    	</td>
						    </tr>
						</table>
					</div>';
			
			return $text;
		}
		
		public function getHiddenFiles($transition_secgoals, $transition_16_course_study, $transition_16_instruction, 
							$transition_16_rel_services, $transition_16_emp_options, $transition_16_comm_exp, 
							$transition_16_dly_liv_skills, $transition_16_func_voc_eval, $transition_16_inter_agency_link) {
			
			$text = '<input type="hidden" id="transition_secgoals_value" value="'.htmlentities($transition_secgoals).'" />
					<input type="hidden" id="transition_16_course_study_value" value="'.htmlentities($transition_16_course_study).'" />
					<input type="hidden" id="transition_16_instruction_value" value="'.htmlentities($transition_16_instruction).'" />
					<input type="hidden" id="transition_16_rel_services_value" value="'.htmlentities($transition_16_rel_services).'" />
					<input type="hidden" id="transition_16_emp_options_value" value="'.htmlentities($transition_16_emp_options).'" />
					<input type="hidden" id="transition_16_comm_exp_value" value="'.htmlentities($transition_16_comm_exp).'" />
					<input type="hidden" id="transition_16_dly_liv_skills_value" value="'.htmlentities($transition_16_dly_liv_skills).'" /> 
					<input type="hidden" id="transition_16_func_voc_eval_value" value="'.htmlentities($transition_16_func_voc_eval).'" />
					<input type="hidden" id="transition_16_inter_agency_link_value" value="'.htmlentities($transition_16_inter_agency_link).'" />';
			
			return $text;
		}
	}