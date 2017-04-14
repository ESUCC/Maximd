<?php

class Zend_View_Helper_SortDirectionHelper extends Zend_View_Helper_Abstract
{

    /**
     * Returns the correct sorting direction indicator
     * 
     * @return string
     */
    public function SortDirectionHelper($field, $sortValue)
    {
        if (0 === strpos($sortValue, $field)) {
            if (strpos($sortValue, 'desc') > 0)
                return '&nbsp; ^';
            elseif (strpos($sortValue, 'asc') > 0)
                return '';
            else
                return '&nbsp; v';
        } 
    }
}