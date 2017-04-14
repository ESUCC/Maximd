<?php
require_once ("iep_function_general.php");
class result_tree
{
    // stores an object in each array element
    //
    // array (
    //      categoryName1 => categoryObject,
    //      categoryName2 => categoryObject,
    //      ...
    // )
    
    /*
     * For each node the following must be considered
     * 
     * Top nodes should never be hidden
     * 
     */
    
    var $checkboxChecked = false; // checkbox that shows on display rows
    var $showNodeKeywords = true; // checkbox that shows on display rows
    
    var $nodeCount;
    var $categoryName;
    var $id_subcategory;
    var $full_category_data;
    var $tableName;
    var $searchType;
    var $volumeLevel;
    var $hideNode = false;
    var $hideKeywords = false; // if there are keywords in the root of this tree, should they be displayed
    var $nodeArr;
    
    function __construct ($categoryName = '', $catArr = array(), $full_category_data = '', $depth = 0, $parentObj = '')
    {
        //
        // when category is null, we're on the top of the tree
        //
        if ('' != $categoryName)
            $this->categoryName = $categoryName;
        if ('' != $parentObj) {
            $this->parentObj = $parentObj;
        } else {
            $this->checkboxChecked = true; // top category should always be checked
        }
        if('' != $parentObj) {
            $parTable = $parentObj->getTableName();
            if('' != $parTable)
            {
                $this->setTableName($parTable);
            }

            $parSearchType = $parentObj->getSearchType();
            if('' != $parSearchType)
            {
                $this->setSearchType($parSearchType);
            }

            $parVolumeLevel = $parentObj->getVolumeLevel();
            if('' != $parVolumeLevel)
            {
                $this->setVolumeLevel($parVolumeLevel);
            }
        }
        
        $this->parent_category_display = $this->parent_category_display();
        if (count($catArr) > 0) {
            $this->addNode($catArr, $full_category_data, $depth);
        } else {
            $this->nodeCount = 0;
            if ('' != $full_category_data) {
                $this->full_category_data = $full_category_data;
                $this->kw_count = $full_category_data['kw_count'];
//                echo "end of the line" . $full_category_data['full_category'] . "<BR>";
                //
                // build data for just this subcat
                $this->mykw_count = $full_category_data['kw_count'];
                $this->myfreq_rank = $full_category_data['freq_rank'];
            }
        }
        if (isset($this->full_category_data['id_subcategory'])) {
            if (! isset($this->id_subcategory)) {
                $this->id_subcategory = $this->full_category_data['id_subcategory'];
            }
        } else {
            $subcatCounter = new Zend_Session_Namespace();
            if (! isset($subcatCounter->id)) {
                $subcatCounter->id = 999000;
            }
            $this->id_subcategory = $subcatCounter->id ++;
        }
        //$this->hideNode = false;
        $this->depth = $depth;
    }

    function setTableName ($tableName)
    {
        $this->tableName = $tableName;
    }
    
    function getTableName ()
    {
        return $this->tableName;
    }
    

    function setSearchType ($searchType)
    {
        $this->searchType = $searchType;
    }

    function getSearchType ()
    {
        return $this->searchType;
    }
    
    function setVolumeLevel ($volumeLevel)
    {
        $this->volumeLevel = $volumeLevel;
    }

    function getVolumeLevel ()
    {
        return $this->volumeLevel;
    }
    
    // displayMode is used to determine which 
    // viewscript to use to display this object
    // possible values: '', category, badic, advanced 
//    function setDisplayMode ($displayMode = 'basic')
//    {
//        $this->displayMode = $displayMode;
//    }
//    function getDisplayMode ()
//    {
//        return $this->displayMode;
//    }
    
    
    function parent_category_display ()
    {
        if ('' == $this->categoryName)
            return '';
        if (isset($this->parentObj))
            return $this->parentObj->parent_category_display() . $this->categoryName . '::';
    }
    function nodeSum ($eleName)
    {
        $summValue = 0;
        if (count($this->nodeArr) > 0) {
            foreach ($this->nodeArr as $nodeObj) {
                $summValue += $nodeObj->nodeSum($eleName);
            }
            if (isset($this->full_category_data)) {
                $summValue += $this->full_category_data[$eleName];
            }
        } else {
            #echo "get from array {$this->categoryName}: " . $this->full_category_data[$eleName] . "<BR>";
            $summValue += $this->full_category_data[$eleName];
        }
        #echo "summValue {$this->categoryName}: " . $summValue . "<BR>";
        $this->$eleName = $summValue;
        return $summValue;
    }
    function kwSumChecked (result_tree $rObj, $showCatLev)
    {
        $summValue = 0;
        if (count($rObj->nodeArr) > 0) {
            foreach ($rObj->nodeArr as $nodeObj) {
                if ($nodeObj->depth == $showCatLev) {
                    //
                    // node has keywords not part of subcats, count them
                    //
                    if (isset($rObj->mykw_count) && '' != $rObj->mykw_count) {
                        if ($rObj->hideNode != true) {
                            $summValue += $rObj->mykw_count;
                            echo "adding node has keywords not part of subcats: " . $rObj->mykw_count . " " . $rObj->categoryName . "<BR>";
                        } else {
                            // node is hidden
                        }
                    }
                    //
                    // these nodes will be displayed, add their counts
                    //
                    if ($nodeObj->hideNode != true) {
                        $summValue += $nodeObj->kw_count;
                        echo "adding displayed node: " . $nodeObj->kw_count . " " . $nodeObj->categoryName . "<BR>";
                    } else {
                        // node is hidden
                    }
                }
                    if (0 == count($nodeObj->nodeArr)) {
                        if ($nodeObj->hideNode != true) {
                            $summValue += $nodeObj->mykw_count;
                            echo "adding 3: " . $nodeObj->mykw_count . " " . $nodeObj->categoryName . "<BR>";
                        } else {
                            // node is hidden
                        }
                    }
                
                $summValue += $this->kwSumChecked($nodeObj, $showCatLev);
            }
        }
        return $summValue;
    }
    function nodeFreqRank ($eleName)
    {
        $summValue = 0;
        if (count($this->nodeArr) > 0) {
            foreach ($this->nodeArr as $nodeObj) {
                // run on sub nodes
                $nodeObj->nodeFreqRank($eleName);
            }
        }
        if (isset($this->kw_count) && isset($this->freq_rank)) {
            $this->$eleName = $this->displayFreqRank($this->kw_count, $this->freq_rank);
        }
        //return;
    }
    function buildKeywordSamples ()
    {
        if (count($this->nodeArr) > 0) {
            foreach ($this->nodeArr as $nodeObj) {
                // run on sub nodes
                $nodeObj->buildKeywordSamples();
            }
        }
        
        if (isset($this->full_category_data['full_category'])) {
            $this->full_category_data['kw_samples'] = $this->getKeywordSamples($this->full_category_data['full_category']);
            
            // add freq_rank -- too slow
//            $this->freq_rank = $this->getFreqRank($this->full_category_data['full_category'], 'sausages');
        }
        //return;
    }
    function setEmptyKWSamplesToFirstChildSample ()
    {
        if (count($this->nodeArr) > 0) {
            foreach ($this->nodeArr as $nodeObj) {
                // run on sub nodes
                $nodeObj->setEmptyKWSamplesToFirstChildSample();
            }
        }
        
        if(!isset($this->full_category_data['kw_samples'])) {
            if (count($this->nodeArr) > 0) {
                foreach ($this->nodeArr as $nodeObj) {
                    // run on sub nodes
                    if(isset($nodeObj->full_category_data['kw_samples'])) {
                        $this->full_category_data['kw_samples'] = $nodeObj->full_category_data['kw_samples'];
                        break;
                    }
                }
            }
        }
        //return;
    }
    
    function getKeywordSamples($fullCat) {
        /*
         * get sample keywords for this category
         */
        $sqlStmt = "select ";
        $sqlStmt .= "    keyword, num ";
        $sqlStmt .= "from ";
        
        $sqlStmt .= "    kw_samples ";  

        $sqlStmt .= "where full_category = '$fullCat' ";  

        
        $sqlStmt .= "    order by num; ";        
        
        $db = Zend_Registry::get('db');
        $result = $db->fetchAll($sqlStmt);
        
        $flatArr = $this->arrayOfArrays2array ($result, 'keyword');
        
        return $flatArr;
        
    }
    function getFreqRank($fullCat, $phrase) {
        /*
         * get sample keywords for this category
         */

        $sqlStmt = "select ";
        $sqlStmt .= "    count(1) ";
        $sqlStmt .= "from ";
        
        $sqlStmt .= "    cat_key ";  

        $sqlStmt .= "where full_category = '$fullCat' ";  
        
        $sqlStmt .= "    and keyword = '$phrase'; ";


        echo "sqlStmt: $sqlStmt<BR>";
        $searchParams->timeArr = array();
        $mtime = microtime();         
        $mtime = explode(' ', $mtime); 
        $mtime = $mtime[1] + $mtime[0]; 
        $starttime = $mtime; 
        echo "time: $starttime<BR>";
        $searchParams->timeArr[] = $starttime;


#        $db = Zend_Registry::get('db');
#        $result = $db->fetchAll($sqlStmt);

        $mtime = microtime(); 
        $mtime = explode(" ", $mtime); 
        $mtime = $mtime[1] + $mtime[0]; 
        $endtime = $mtime; 
        $totaltime = ($endtime - $starttime); 
        echo "time: $endtime ($totaltime)<BR>";

        
        $flatArr = $this->arrayOfArrays2array ($result, 'count');
        
        return $flatArr;
        
    }
    function hideNodes ($catArr = array(), $showCatLev)
    {
        
        $searchParams = new Zend_Session_Namespace();
        if (count($this->nodeArr) > 0) {
            foreach ($this->nodeArr as $nodeObj) {
                if ($nodeObj->depth == $showCatLev) {
                    if (false === array_search($nodeObj->categoryName, $catArr)) {
                        $searchParams->hidden[] = $nodeObj->categoryName;
                        //Zend_Debug::dump($nodeObj->categoryName, 'label');
                        $nodeObj->hideNode = true;
                    } else {
                        // category name is in the cat array
                    // show this category
                    }
                }
                $nodeObj->hideNodes($catArr, $showCatLev);
            }
        }
    }
    function arrayOfArrays2array ($arrayOfArrays, $key = null)
    {
        $retArr = array();
        foreach ($arrayOfArrays as $subArr) {
            if(isset($key)) {
                $retArr[] = $subArr[$key];
            } else {
                $retArr[] = $subArr[0];
            }
        }
        return $retArr;
    }

    function hideSubNodes_array ($idSubcatArr, $depth)
    {
        $debug = false;
        if (count($this->nodeArr) > 0) {
            foreach ($this->nodeArr as $nodeObj) {
                if($debug) echo "process hideSubNodes_array($depth)(".$nodeObj->depth."): " . $nodeObj->categoryName . "(" . $nodeObj->id_subcategory .")<BR>";
                if ($nodeObj->depth != $depth) {
                    //if($debug) echo "<B>exiting ".$nodeObj->depth.' ' . $nodeObj->categoryName . ' ' . $nodeObj->id_subcategory ."</B><BR>";
                    /*
                     * 
                     */
                    if (false === array_search($nodeObj->id_subcategory, $idSubcatArr)) {
                        if($debug) echo "<B>hide parent keywords - search positive HIDING(".$nodeObj->depth."): " . $nodeObj->categoryName . "(" . $nodeObj->id_subcategory .")</B><BR>";
                        $nodeObj->hideKeywords = true;
                    } else {
                        if($debug) echo "DIFFERENT DEPTH NOT hiding(".$nodeObj->depth."): " . $nodeObj->categoryName . "(" . $nodeObj->id_subcategory .")<BR>";
                        $nodeObj->hideKeywords = false;
                    }
                    $nodeObj->hideSubNodes_array($idSubcatArr, $depth);
                } else {
                    if (false === array_search($nodeObj->id_subcategory, $idSubcatArr)) {
                        if($debug) echo "<B>search positive HIDING(".$nodeObj->depth."): " . $nodeObj->categoryName . "(" . $nodeObj->id_subcategory .")</B><BR>";
                        //pre_print_r ( $idSubcatArr );
                        //$nodeObj->hideNode = false;
                        $nodeObj->hideKeywords = true;
                        $nodeObj->hideSubNodes($nodeObj);
                    } else {
                        if($debug) echo "NOT hiding(".$nodeObj->depth."): " . $nodeObj->categoryName . "(" . $nodeObj->id_subcategory .")<BR>";
                        $nodeObj->hideKeywords = false;
                        $nodeObj->showSubNodes($nodeObj);
                        //$nodeObj->hideNode = false;
                    }
                }
            }
        }
    }
    function hideSubNodes ($nodeObj)
    {
        if (isset($nodeObj->nodeArr)) {
            if (count($nodeObj->nodeArr) > 0) {
                foreach ($nodeObj->nodeArr as $subNode) {
                    $subNode->hideNode = true;
                    $subNode->hideKeywords = true;
                    $subNode->hideSubNodes($subNode);
                }
            }
        }
    }
    function showSubNodes ($nodeObj)
    {
        if (isset($nodeObj->nodeArr)) {
            if (count($nodeObj->nodeArr) > 0) {
                foreach ($nodeObj->nodeArr as $subNode) {
                    $subNode->hideNode = false;
                    $subNode->hideKeywords = false;
                    $subNode->showSubNodes($subNode);
                }
            }
        }
    }
    function nodeFreq ()
    {
        $kw_count = 0;
        $summValue = 0;
        foreach ($this->nodeArr as $nodeObj) {
            $kw_count += $nodeObj->kw_count;
            $summValue += $nodeObj->kw_count * $nodeObj->freq_rank;
        }
        return $summValue / $kw_count;
    }
    function setParent ($parentOb)
    {
        $this->parentOb = $parentOb;
    }
    function display_tree_rows ($displayDepth = 0)
    {
        $html = "";
        if (isset($this->nodeArr) && count($this->nodeArr) > 0) {
            $displayHeader = 0;
            foreach ($this->nodeArr as $nodeObj) {
                if (isset($nodeObj->hideNode) && $nodeObj->hideNode)
                    continue;
                if ($displayDepth == $nodeObj->depth) {
                    // show only once
                    if ($displayHeader ++ == 0) {
                        $html .= "<TR class=\"treeRow\" style=\"background-color:LightGrey;\">";
                        $html .= "<TD colspan=\"8\" class=\"treeCell\" style=\"\">";
                        $html .= "<B>" . $this->parent_category_display . "</B>";
                        //                            $html .= "<B>" . pre_print_r($nodeObj, true) . "</B>";
                        $html .= "</TD>";
                        $html .= "</TR>";
                    }
                    $html .= "<TR class=\"treeRow\" style=\"\">";
                    $html .= "<TD class=\"treeCell\" style=\"width:300px;\">";
                    $html .= $nodeObj->categoryName;
                    $html .= "</TD>";
                    $html .= "<td style=\"\">";
                    $html .= $this->displayFreqRank($nodeObj->kw_count, $nodeObj->freq_rank);
                    $html .= "</td>";
                    $html .= "<td style=\"\">";
                    if ($displayDepth < (substr_count($nodeObj->full_category_data['full_category'], '::') + 1))
                        $html .= "<input type=\"checkbox\" checked name=\"catArr[]\" value=\"" . $nodeObj->categoryName . "\">" . $nodeObj->categoryName;
                    $html .= "</td>";
                    $html .= "<td style=\"\">";
                    $html .= $nodeObj->freq_rank;
                    $html .= " <a href=\"\">view samples</a>";
                    $html .= "</td>";
                    $html .= "<td style=\"\">";
                    $html .= "+";
                    $html .= "</td>";
                    $html .= "<td style=\"\">";
                    $html .= ($nodeObj->kw_count - $nodeObj->freq_rank);
                    $html .= "</td>";
                    $html .= "<td style=\"\">";
                    $html .= "=";
                    $html .= "</td>";
                    $html .= "<td style=\"\">";
                    $html .= $nodeObj->kw_count;
                    $html .= "</td>";
                    $html .= "</TR>";
                }
                //
                // sub rows
                //
                $html .= $nodeObj->display_tree_rows($displayDepth);
            }
        }
        return $html;
    }
    function processResultSet ($resultSet)
    {
        // for each non-null top level category, build a category object
        if (isset($resultSet) && count($resultSet) > 0) {
            //
            // for each record in the subcategory table
            //
            foreach ($resultSet as $resultRow) {
                if (substr_count($resultRow['full_category'], '::') > 0) {
                    $catArr = explode('::', $resultRow['full_category']);
                } else {
                    $catArr = array();
                    $catArr[0] = $resultRow['full_category'];
                }
                $this->addNode($catArr, $resultRow, 0);
            }
        }
    }
    function displayFreqRank ($totalKeyWords, $exactMatches)
    {
        if ($totalKeyWords == 0)
            return '';
        return $exactMatches / $totalKeyWords;
    }
    function mergeNode ($categoryName, $catArr, $full_category_data, $depth)
    {
        //         echo "<B>merge categoryName</B>: $categoryName<BR>";
        //         pre_print_r($catArr);
        //         pre_print_r($full_category_data);
        //         echo "<B>depth</B>: $depth<BR>";
        array_shift($catArr);
        if (count($catArr) > 0) {
            $this->addNode($catArr, $full_category_data, $depth);
            #echo "merge setting count from array for <B>$categoryName</B>: " . $this->kw_count . "|" . $full_category_data['kw_count'] ."<BR>";
        #$this->kw_count += $this->nodeSum('kw_count');
        #echo "<B>$categoryName</B> set kw_count: " . $catArr[0] . "|".$this->kw_count."<BR>";
        } else {
            $this->nodeCount = 0;
            if ('' != $full_category_data) {
                $this->full_category_data = $full_category_data;
                $this->kw_count = $full_category_data['kw_count'];
                //
                // build data for just this subcat
                $this->mykw_count = $full_category_data['kw_count'];
                $this->myfreq_rank = $full_category_data['freq_rank'];
                //$this->kw_count += $full_category_data['kw_count'];
            //parent::addToVar('kw_count', $this->kw_count);
            }
        }
        $this->depth = $depth;
    }
    function addNode ($catArr, $full_category_data, $depth)
    {
        $categoryName = $catArr[0];
        $mergeNodeObj = $this->nodeExists($categoryName);
        if (false == $mergeNodeObj) {
            //
            // there is no existing node with that category name at this level
            //
//            echo "<B>adding node</B>: " . $categoryName . "<BR>";
            array_shift($catArr);
            $this->nodeArr[$categoryName] = new result_tree($categoryName, $catArr, $full_category_data, $depth + 1, $this);
//            echo "adding id_subcategory: " . $this->nodeArr[$categoryName]->id_subcategory . "<BR>";
        } else {
            // merge nodes
            #echo "<B>merge categoryName</B>: $categoryName<BR>";
            $mergeNodeObj->mergeNode($categoryName, $catArr, $full_category_data, $depth + 1);
//            echo "merge id_subcategory: " . $mergeNodeObj->id_subcategory . "<BR>";
        }
        $this->nodeCount = count($this->nodeArr);
    }
    function nodeExists ($categoryName)
    {
        if (count($this->nodeArr) > 0) {
            foreach ($this->nodeArr as $nodeObj) {
                #echo "nodeArr categoryName: " . $nodeObj->categoryName  . "<BR>";
                if ($nodeObj->categoryName == $categoryName)
                    return $nodeObj;
            }
        }
        return false;
    }
    function getNodeArr ($depth)
    {
        if (count($this->nodeArr) > 0) {
            $retArr = array();
            foreach ($this->nodeArr as $nodeObj) {
                #echo "nodeArr categoryName: " . $nodeObj->categoryName  . "<BR>";
                if ($nodeObj->depth < $depth) {
                    $retArr[] = $nodeObj->getNodeArr($depth);
                } elseif ($nodeObj->depth == $depth) {
                    $retArr[] = $this->nodeArr;
                }
            }
            return $retArr;
        }
    }
    function getCategoryFromFullCategory ($full_category, $level = 1, $prePost = '', $style = true)
    {
        $level = $level - 1;
        $retData = '';
        if (substr_count($full_category, '::') > 0) {
            $catArr = explode('::', $full_category);
            #pre_print_r($catArr);
            #echo "cat[$level]: " . $catArr[$level] . "<BR>";
            if ('post' == $prePost && count($catArr) > $level) {
                for ($i = $level; $i < count($catArr); $i ++) {
                    $retData .= $catArr[$i] . "::";
                    if ($i != count($catArr))
                        $retData .= "::";
                }
                return $retData;
            } elseif ('pre' == $prePost && $level > 0) {
                for ($i = 0; $i <= $level; $i ++) {
                    $retData .= $catArr[$i];
                    if ($i != $level)
                        $retData .= "::";
                }
            } else {
                $retData .= $catArr[$level];
            }
            return $retData;
        } else {
            return $full_category;
        }
    }
    
    function displayCheckedStatusTree ($indentCounter = 0)
    {
        echo str_repeat('&nbsp;', 2*$indentCounter++) .  $this->categoryName . "(" . $this->id_subcategory . "): ".$this->checkboxChecked."<BR>";
        
        if (count($this->nodeArr) > 0) {
            
            foreach ($this->nodeArr as $nodeObj) {
                $nodeObj->displayCheckedStatusTree($indentCounter);
            }
        }
    }
    function searchNodeArray($uncheckArr, $keyName, $findValue, $debug = true) {
        // return key (position) in array of array
        reset($uncheckArr);
        foreach($uncheckArr as $key => $ucArr) {
            if($debug) Zend_Debug::dump($ucArr[$keyName] . " " . $findValue, 'check if this node is in the check/uncheck array');
            if($debug) Zend_Debug::dump($findValue, 'findvalue');
            if($debug) Zend_Debug::dump($ucArr[$keyName], 'ucArr[keyName]');
            if($ucArr[$keyName] == $findValue) {
                if($debug) Zend_Debug::dump($findValue, 'match');
                return $key;
            }
        }
        return false;
    }
    
    function uncheckNodes ($uncheckArr, $forceUncheck = false)
    {
        $debug = false;
        if (count($this->nodeArr) > 0) {
            foreach ($this->nodeArr as $nodeObj) {
                if($forceUncheck) {
//                    if($debug) echo "uncheckNodes <B>FORCE uncheck(".$nodeObj->depth."): " . $nodeObj->categoryName . "(" . $nodeObj->id_subcategory .")</B><BR>";
//                    $nodeObj->checkboxChecked = false;
//                    $nodeObj->showNodeKeywords = false;
//                    $nodeObj->uncheckNodes ($uncheckArr, true);
                } else {
                    $key = $this->searchNodeArray($uncheckArr, 'id_subcategory', $nodeObj->id_subcategory, $debug);
                    if (false === $key) {
                        //if($debug) echo "uncheckNodes NOT hiding(".$nodeObj->depth."): " . $nodeObj->categoryName . "(" . $nodeObj->id_subcategory .")<BR>";
                        $nodeObj->uncheckNodes ($uncheckArr);
                    } else {
                        // node was unchecked
                        //
                        $ucArr = $uncheckArr[$key]; 
                        if('node' == $ucArr['nodeType']) {
                            // if this is type node, uncheck the node itself
                            if($debug) echo "uncheckNodes <B>uncheck(".$nodeObj->depth."): " . $nodeObj->categoryName . "(" . $nodeObj->id_subcategory .")</B><BR>";
                            $nodeObj->checkboxChecked = false;
                        } elseif('keywords' == $ucArr['nodeType']) {
                            // if the type is keywords, unchcek the keywords display
                            if($debug) echo "uncheckNodes <B>uncheck KEYWORDS(".$nodeObj->depth."): " . $nodeObj->categoryName . "(" . $nodeObj->id_subcategory .")</B><BR>";
                            $nodeObj->showNodeKeywords = false;
                        }
//                        $nodeObj->uncheckNodes ($uncheckArr, true);
                        $nodeObj->uncheckNodes ($uncheckArr);
                    }
                }
            }
        }
    }

    function checkNodes ($checkArr, $forceTrue = false)
    {
        $debug = false;
        if (count($this->nodeArr) > 0) {
            $i = 1;
            foreach ($this->nodeArr as $nodeObj) {
                if($debug) echo "check id_subcategory: $nodeObj->id_subcategory<BR>";
                
                if($forceTrue) {
//                    if($debug) echo "checkNodes <B>FORCE check(".$nodeObj->depth."): " . $nodeObj->categoryName . "(" . $nodeObj->id_subcategory .")</B><BR>";
                    
//                    $nodeObj->checkboxChecked = true;
//                    $nodeObj->showNodeKeywords = true;
//                    $nodeObj->checkNodes ($checkArr, true);
                } else {
                    
                    $key = $this->searchNodeArray($checkArr, 'id_subcategory', $nodeObj->id_subcategory, $debug);
                    //if($debug) echo "process check(".$nodeObj->depth."): " . $nodeObj->categoryName . "(" . $nodeObj->id_subcategory .")<BR>";
                    
                    if (false === $key) {
                        //if($debug) echo "uncheckNodes NOT hiding(".$nodeObj->depth."): " . $nodeObj->categoryName . "(" . $nodeObj->id_subcategory .")<BR>";
                        if($debug) Zend_Debug::dump($key, 'key NOT found');
                        $nodeObj->checkNodes ($checkArr);
                    } else {
                        // node was unchecked
                        //
                        if($debug) Zend_Debug::dump($key, 'keyfound');
                        $ucArr = $checkArr[$key]; 
                        if($debug) Zend_Debug::dump($ucArr, 'ucArr');
                        if('node' == $ucArr['nodeType']) {
                            // if this is type node, uncheck the node itself
                            if($debug) echo "checkNodes <B>check(".$nodeObj->depth."): " . $nodeObj->categoryName . "(" . $nodeObj->id_subcategory .")</B><BR>";
                            $nodeObj->checkboxChecked = true;
                        } elseif('keywords' == $ucArr['nodeType']) {
                            // if the type is keywords, unchcek the keywords display
                            if($debug) echo "checkNodes <B>check KEYWORDS(".$nodeObj->depth."): " . $nodeObj->categoryName . "(" . $nodeObj->id_subcategory .")</B><BR>";
                            $nodeObj->showNodeKeywords = true;
                        }
//                        $nodeObj->checkNodes ($checkArr, true);
                        $nodeObj->checkNodes ($checkArr);
                    }
                }
            }
        }
    }

    function parentChecked() {
        
//        Zend_Debug::dump($this->categoryName . "(" . $this->id_subcategory . ")" . $this->parentObj->checkboxChecked, 'parentChecked categoryName');
        if(isset($this->parentObj) && '' != $this->parentObj) {
            if(false === $this->parentObj->checkboxChecked) {
                return false;
            } else {
                return $this->parentObj->parentChecked();
            }
        }
        return true;
    }
    
}
