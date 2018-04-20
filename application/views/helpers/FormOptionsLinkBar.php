<?php
/**
 * Helper for creating a list of form option links
 *
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <mdanahy@esucc.org>
 * @version   $Id: $
 */
class Zend_View_Helper_FormOptionsLinkBar extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_linkBar;

    /**
     * build bar of links to form options
     *
     * @return string
     */
    function writevar1($var1,$var2) {

        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }


    public function formOptionsLinkBar($formID, $controller, $actionArr, $page = 1)
    {
    	$firstRun = true;
    	// foreach action, create a link
		foreach($actionArr as $a)
    	{
    	  //  $this->writevar1($a,'this is the actionArr from FormOptionsLlinkBar line 40 ');
			$link = $this->view->url(array(
			'controller' => $controller,
			'action' => strtolower($a),
			'document' => $formID,
			'page' => $page,
			), null, true); // 3rd param removes default values

			if(!$firstRun) $this->_linkBar .= " | ";
			$this->_linkBar .= "<a href=\"javascript:checkEditedStatus('" . $this->view->baseUrl() .  $link . "');\">$a</a>";

			$firstRun = false;
    	}
        return $this->_linkBar;
    }
}
