<?php
//require_once(APPLICATION_PATH . '/models/DbTable/user.php');
//require_once(APPLICATION_PATH . '/models/DbTable/survey_distribute.php');

/**
 * SurveyController 
 * 
 * @uses      Zend_Controller_Action
 * @package   Paste
 * @license   New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version   $Id: $
 */
class TestController extends Zend_Controller_Action
{
//	  public function gorillaAction() {
//	  	
//	  	echo md5('jkshcs83vr85gnev529br21kc1' + strtotime('2011-10-20 09:11:59.560984-05')) . "<BR>";
//	  	echo md5('srja1f8qgvfbo6g0csf2f9ta03' + strtotime('2011-10-20 09:11:59.560984-05')) . "<BR>";
//	  	echo uniqid('', true) . "<BR>";
//	  	
//	  	die();
//	  }

	  public function testGhostscriptAction() {
	  	
	  	App_Classes_Ghostscript::convertToPdf14();
	  	echo "END...\n";	  	
	  	die();
	  }

	
	  public function editorFilterAction() {
	  	
	  	$this->view->hideLeftBar=true;

	  }
	  public function spellAction() {
	  	$this->view->headScript()->appendFile("/sproxy/sproxy.php?cmd=script&doc=wsc");
	  	$this->view->headScript()->appendFile('/js/startSpellCheck.js');
	  	
// 	  	$this->_helper->layout()->disableLayout();
	  }
	  public function spellTabAction() {
	  	$this->view->headScript()->appendFile('/js/srs_forms/common_form_functions_new.js');
	  	$this->view->headScript()->appendFile("/sproxy/sproxy.php?cmd=script&doc=wsc");
	  	$this->view->headScript()->appendFile('/js/startSpellCheck.js');
	  	
// 	  	$this->_helper->layout()->disableLayout();
	  }
	  public function jspellAction() {
	  	$this->view->headScript()->appendFile('/js/srs_forms/common_form_functions_new.js');
	  	$this->view->headScript()->appendFile('/js/JavaScriptSpellCheck/include.js');
	  	// fancybox config
	  	$this->view->headScript()->appendFile('/js/JavaScriptSpellCheck/extensions/modalbox/modalbox.combined.js');
	  	$this->view->headLink()->appendStylesheet('/js/JavaScriptSpellCheck/extensions/modalbox/modalbox.css', 'screen');
	  	
	  }
	  public function uploaderAction() {
	  	$this->_helper->layout()->disableLayout();
	  }
	  public function uploaderzendAction() {
// 				$this->view->headLink()->appendStylesheet('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css', 'screen', array('id'=>'theme'));
// 				$this->view->headLink()->appendStylesheet('/js/temp/jquery.fileupload-ui.css', 'screen');
// 				$this->view->headScript()->appendFile('http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js');
				
				
// 				$this->view->headScript()->appendFile('//ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
// 				$this->view->headScript()->appendFile('/js/jquery_addons/jquery-ui.min.js');
				
				
// 				$this->view->headScript()->appendFile('http://ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js');
// 				$this->view->headScript()->appendFile('/js/temp/jquery.iframe-transport.js');
// 				$this->view->headScript()->appendFile('/js/temp/jquery.fileupload.js');
// 				$this->view->headScript()->appendFile('/js/temp/jquery.fileupload-ui.js');
				
// 			} else {
// 				$this->headLink()->appendStylesheet('/js/jquery-ui/css/custom-theme/jquery-ui-1.8.16.custom.css', 'screen', array('id'=>'theme'));
// 				$this->headLink()->appendStylesheet('/js/jquery_addons/jquery.fileupload-ui.css', 'screen');
				
// 				$this->headScript()->appendFile('/js/jquery-ui-1.8.16.custom.min.js');
// 				$this->headScript()->prependFile('/js/jquery/jquery-1.7.min.js'.$refreshCode);
				
// 				$this->headScript()->appendFile('/js/jquery_addons/jquery-ui.min.js');
// 				$this->headScript()->appendFile('/js/jquery_addons/jquery.tmpl.min.js');
// 				$this->headScript()->appendFile('/js/jquery_addons/jquery.iframe-transport.js');
// 				$this->headScript()->appendFile('/js/jquery_addons/jquery.fileupload.js');
// 				$this->headScript()->appendFile('/js/jquery_addons/jquery.fileupload-ui.js');
// 			}
			
// 			// init the app on load
// 			$this->headScript()->appendFile('/js/jquery_addons/jquery-init-fileupload.js');
				  
	  }
	  public function testAction() {
	  	
	  	$this->view->headScript()->appendFile('/js/srs_forms/test.js');
		$this->view->dojo()->requireModule('soliant.form.Button');
		$this->view->dojo()->requireModule('soliant.layout.TabContainer');

		// Custom Dojo Widget DataList
		// add the custom widget do the dojo scope
		$this->view->dojo()->requireModule('soliant.complex.DataList');
		// include the custom widget css
	  	$this->view->headLink()->appendStylesheet('/js/dojo_development/soliant/complex/DataList.css');
		// add onload functionality
		$this->view->headScript()->appendFile('/js/dojo_development/soliant/complex/DataListOnLoad.js');
		// additional code is used in the phtml file
		// also requires the data delivery functions
	  }
	  public function page1Action() {
		$str = <<<EOD
{
	"pages" : 2,
	"items" : [
		{
			"caption" : "Syndicate content with Zend Framework Zend_Feed classes",
			"description" : "This article explains basics of content syndications and demonstrates how to use Zend Framework Zend_Feed classes for consuming a news feed of your site.",
			"info" : "Issued at 2007-Jan-04 by Alexander",
			"id" : 1,
			"permalink" : "http:\/\/www.alexatnet.com\/node\/11"
		},
		{
			"caption" : "Creating new Dojo Widget",
			"description" : "This tutorial demonstrates how to create a Dojo widget that displays a list of items. The widget implements paging and fetches data from the server using ajax request.",
			"info" : "Issued at 2006-Aug-23 by Alexander",
			"id" : 1,
			"permalink" : "http:\/\/www.alexatnet.com\/node\/14"
		},
		{
			"caption" : "Model-View-Controller (MVC) with JavaScript",
			"description" : "The article describes an implementation of Model-View-Controller software design pattern in JavaScript. Created classes conform to Dojo toolkit class building concepts: dojo.lang.declare creates classes and dojo.event.connect supports low coupling of MVC.",
			"info" : "Issued at 2006-Aug-04 by Alexander",
			"id" : 1,
			"permalink" : "http://www.alexatnet.com/node/8"
		}
	]
}
EOD;
		echo $str;
		die(); 	
	  }
	  public function page2Action() {
		$str = <<<EOD
{
	"pages" : 2,
	"items" :
	[
		{
			"caption" : "Create Ajax Login page",
			"description" : "Article shows how to create Ajax forms with server-side actions using the Dojo toolkit and Zend Framework. It guides you through creation of a sample user login form that uses dojo.io client-side packages and Zend_Controller and Zend_Filter_Input server-side packages.",
			"info" : "Issued at 2006-Jul-26 by Alexander",
			"id" : 1,
			"permalink" : "http://www.alexatnet.com/node/13"
		}
	]
}
EOD;
		echo $str;
		die(); 	
	  }
	  
	  function test2Action() {
	  	
	  	$data = <<<EOT

<style>@font-face {
  font-family: &amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;Courier New&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;;
}@font-face {
  font-family: &amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;Wingdings&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;;
}@font-face {
  font-family: &amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;Wingdings&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;;
}p.MsoNormal, li.MsoNormal, div.MsoNormal { margin: 0in 0in 0.0001pt; font-size: 11pt; font-family: &amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;Courier New&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;; }h1 { margin: 0in 0in 0.0001pt; page-break-after: avoid; font-size: 11pt; font-family: &amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;Courier New&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;; }p.MsoHeader, li.MsoHeader, div.MsoHeader { margin: 0in 0in 0.0001pt; font-size: 11pt; font-family: &amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;Courier New&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;; }span.Heading1Char { font-family: &amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;Courier New&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;; font-weight: bold; }span.HeaderChar { font-family: &amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;Courier New&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;quot;; }.MsoChpDefault { font-size: 10pt; }div.WordSection1 { page: WordSection1; }div.WordSection2 { page: WordSection2; }ol { margin-bottom: 0in; }ul { margin-bottom: 0in; }</style>




<div class="WordSection1">

<h1><span>REASON FOR
ASSESSMENT <br /></span></h1>

<p class="MsoNormal"><span>Matrje was
referred to the Multidisciplinary Team because of academic and behavioral
concerns.<span style=""> </span>A variety of Student
Assistance Team (SAT) plans to address these concerns have been attempted but
were unsuccessful. <br /></span></p>

<p class="MsoNormal"><b &quot;times="" 10pt;="" font-family:="" font-size:="" new="" roman&quot;;=""></b></p>

<p class="MsoNormal"><b &quot;times="" 10pt;="" font-family:="" font-size:="" new="" roman&quot;;=""></b></p>

<p class="MsoNormal"><b &quot;times="" font-family:="" new="" roman&quot;;="">VERIFICATION SUMMARY</b></p>

<p class="MsoNormal"><span>Matrje meets the
criteria for verification as a student with a Developmental Delay. Based upon
the assessment information, Matrje scored greater than 1.3 Standard Deviations
below average (i.e., less than 80 Standard Score points) in the areas of cognition
(SS = 75), reading (SS = 70), math (SS = 72), writing (SS = 78), communication
(SS=62). Matrje also scored in the clinically significant range on behavior
rating scales in the areas of hyperactivity, conduct problems and attention
problems.</span></p>

<br />

<p class="MsoNormal"><span>The
Multidisciplinary Team determined that Matrje meets the criteria for
verification as a student with a speech-language impairment in the area of
language. Formal and informal assessments and consideration of all
determining factors listed on the language verification rubric indicate that Matrje
displays a moderate language deficit that adversely effects her educational
performance. Matrje's challenges with language impact her ability
to: comprehend oral and written information and directions; participate
in classroom discussions; communicate orally; process oral language; read and
write; maintain interpersonal relationships with peers and adults; and
recognize, interpret and use nonverbal cues.</span></p>

<br />

<br />

<h1><span>The
Multidisciplinary Team determined Matrje does not meet Nebraska state criteria
for identification and verification as a student with Autism. Since
characteristics of autism are not prevalent in both the home and school
environment at this time the team may want to consider re-evaluating these
characteristics during her next evaluation.</span></h1>

<br />

</div>

<span><br clear="all" />
</span>

<p class="MsoNormal"><b &quot;times="" 10pt;="" font-family:="" font-size:="" new="" roman&quot;;="">Strengths:</b></p>

<ul type="disc"><li &quot;times="" 10pt;="" class="MsoNormal" font-family:="" font-size:="" new="" roman&quot;;="">Friendly and helpful to others</li><li &quot;times="" 10pt;="" class="MsoNormal" font-family:="" font-size:="" new="" roman&quot;;="">Processing speed</li><li &quot;times="" 10pt;="" class="MsoNormal" font-family:="" font-size:="" new="" roman&quot;;="">Perceptual reasoning, or hands-on
     activities</li><li &quot;times="" 10pt;="" class="MsoNormal" font-family:="" font-size:="" new="" roman&quot;;="">Recognizes beginning and ending
     sounds with visuals and adult assistance</li><li &quot;times="" 10pt;="" class="MsoNormal" font-family:="" font-size:="" new="" roman&quot;;="">Understanding of single word
     vocabulary items</li></ul>

<br />

<p class="MsoNormal"><u><span>Background
Information and Health Review</span></u></p>

<p class="MsoNormal"><span>Matrje’s
mother reported that Matrje started talking when she was 4 to 5 years old.
She also stated that Matrje had concerns with her hearing, but recent hearing
screenings at the Hartley health office reported she passed the screening on
02/23/11. Matrje also passed her vision screening on 02/23/11(20/30 right and
20/30 left).</span></p>

<br />


EOT;
	  	
		$editor = new App_Form_Element_TestEditor('tempEditor');
		$editor->setValue($data);
		
		$tempForm = new Zend_Form();
		$tempForm->addElement($editor);
	  	
	  	echo $editor->getValue();die();
	  }
}  
