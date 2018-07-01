<?php
/**
 * Action Helper for building the nav bar
 * 
 * @uses Zend_Controller_Action_Helper_Abstract
 */
class My_View_Helper_NavBar extends Zend_View_Helper_Abstract
{

    /**
     * Create a navigation bar with two rows with highlighted elements and links
     * 
     * author  Jesse LaVere
     * date    20090219
     * @param  array $mainBarItems 
     * @param  array $mainBarItems 
     * @param  string $mainBarActivated - activated main bar element
     * @param  string $subBarActivated - activated sub bar element
     * @return html
     */
    public function navBar($mainBarActivated = '', $subBarActivated = '')
    {
            
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/navbar.ini', $this->view->controller);
        $html  = '<div id="navcontainer">';
        $html .= '<ul id="navlist">';
        if(isset($config->main->tab)) {
            foreach($config->main->tab as $tab => $value)
            {
                $label = isset($value->label)?$value->label:'';
                if(isset($value->link)) {
                    $link_controller = isset($value->link->controller)?$value->link->controller:'#';
                    $link_action = isset($value->link->action)?$value->link->action:'#';
                } else {
                    $link_controller = "";
                    $link_action = "";
                }
                if($tab == $mainBarActivated) {
                    $html .= "<li id=\"activated\"><a href=\"/$link_controller/$link_action\">$label</a></li>";
                    $html .= "<ul id=\"subnavlist\">";
                    if(isset($config->sub->tab) && count($config->sub->tab) > 0) {
                        foreach($config->sub->tab as $subtab => $subvalue)
                        {
                            $sublabel = isset($subvalue->label)?$subvalue->label:'';
                            if(isset($subvalue->link)) {
                                $sublink_controller = isset($subvalue->link->controller)?$subvalue->link->controller:'#';
                                $sublink_action = isset($subvalue->link->action)?$subvalue->link->action:'#';
                            } else {
                                $sublink_controller = "";
                                $sublink_action = "";
                            }
                            if($subtab == $subBarActivated) {
                                $html .= "<li id=\"subactivated\"><a href=\"#\" id=\"subcurrent\">$sublabel</a></li>";
                            } else {
                                $html .= "<li><a href=\"/$sublink_controller/$sublink_action\">$sublabel</a></li>";
                            }
            
                        }
                    }
                    $html .= "</ul>";
                } else {
                    $html .= "<li><a href=\"/$link_controller/$link_action\">$label</a></li>";
                }
            }
        }
        $html .= '</ul>';
        $html .= '</div>';
        
        return $html;
    }

}
