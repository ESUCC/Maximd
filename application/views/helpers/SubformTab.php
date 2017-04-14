<?php
/**
 * Helper for displaying a subform 
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <mdanahy@esucc.org> 
 * @version   $Id: $
 */
class Zend_View_Helper_SubformTab extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_retString;

    /**
     * build bar of links to form options
     * 
     * @return string
     */
    public function subformTab($view, $subform_index, $displayHeader = true, $title = "Tab", $pageBreak = false)
    {
        $this->_retString = "";
    	if(isset($view->db_form_data[$subform_index])) {
            if($pageBreak) $this->_retString .= '<div style="page-break-before: always;">'; 
    		$this->_retString .= '<table id="'.$subform_index.'_parent" style="width:100%;">';
    		
    		
    		
			if($displayHeader)
			{
				$this->_retString .= '<tr><td>';
				$this->_retString .= $view->form->getSubForm($subform_index); // header row
				$this->_retString .= '</td>';
				$this->_retString .= '</tr>';
			}
			
			
			
			
			
			$this->_retString .= '<tr><td>';
				if('print' != $view->mode)
				{
					// not print mode
					$this->_retString .= '<script type="text/javascript">';
					$this->_retString .= '
							dojo.declare("subform.TabContainer", dijit.layout.TabContainer, {
								doLayout: false,
							    style: "width:850px;",
								_setupChild: function(child){
									// summary: same as normal addChild, but this one adds a setTitle
									// method to the ContentPane if it doesnt have one
									this.inherited(arguments);
									if(!child["setTitle"] && child.title){
										dojo.mixin(child,{ 
											setTitle: function(title){
												// summary: set the title (25 char max)
												this.title = title.substring(0, 25);
												this.controlButton.containerNode.innerHTML = title.substring(0, 25) || ""; 
											}
										});
									}
								}
							});
					';
					$this->_retString .= '</script>';
					$this->_retString .= '<div id="'.$subform_index.'_tab_container_parent">';
					$this->_retString .= '<div id="'.$subform_index.'_tab_container" dojoType="subform.TabContainer">';
				}
				
				/*
				 * loop through subform rows
				 */
				for($x=1; $x <= $view->db_form_data[$subform_index]['count']; $x++)
				{
					if('print' != $view->mode)
					{
						// not print mode
	                    $display_title = $title . ' ' . $x;
	                    if (isset($view->db_form_data[$subform_index.'_'.$x]['title']) && strlen($view->db_form_data[$subform_index.'_'.$x]['title']) > 0 && 'form_002_suppform' === $subform_index)
	                        $display_title = $view->db_form_data[$subform_index.'_'.$x]['title'];
	                    elseif ('form_002_suppform' === $subform_index)
	                        $display_title = "Supplemental Form {$x}";
						$this->_retString .= $view->contentPane(
							$subform_index.'_'.$x, 
							$view->form->getSubForm($subform_index.'_'.$x),
							array(
								'title' => $display_title,
							), 
							array()
						);
					} else {
						// print mode
						if($x > 1 && $pageBreak) $this->_retString .= '<div style="page-break-before: always;">';
						$this->_retString .= $view->form->getSubForm($subform_index.'_'.$x) . "<BR />";
						if($x > 1 && $pageBreak) $this->_retString .= '</div>';
					}				
					
					
				}
				if('print' != $view->mode)
				{
					// not print mode
					$this->_retString .= '</div>';
					$this->_retString .= '</div><!-- end tab container parent -->';
				}
			$this->_retString .= '</td></tr>';
			
			
			$this->_retString .= '</table>';
            if($pageBreak) $this->_retString .= '</div>';
    	}
    	return $this->_retString;
    }


}
