<?php
/**
 * Script for building the output file
 */


$LOG_FILE = "/www/keyword.info.com/www/infodotcom_v2/scripts/output_file_log.txt";

if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application/'));
}

if (!class_exists('Zend_Registry', false) || !Zend_Registry::isRegistered('config')) {

    if (!class_exists('Zend_Registry')) {
        $paths = array(
            '.', 
            APPLICATION_PATH . '/../library',
        );
        ini_set('include_path', implode(PATH_SEPARATOR, $paths));
        require_once 'Zend/Loader.php';
        Zend_Loader::registerAutoload();
    }
    $env = defined('APPLICATION_ENV')?APPLICATION_ENV:'production';
    $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/paste.ini', $env);
    Zend_Registry::set('config', $config);
    unset($base, $path, $config);
}

$config = Zend_Registry::get('config');

$db = Zend_Db::factory($config->db2);
Zend_Db_Table_Abstract::setDefaultAdapter($db);

//
// get the related file_request record
//
$id_file_request = $argv[1];

//
// get file request record
//
$fileRequest = getFileRequest($id_file_request, $db);

//
// get user record
//
$user = getUser($fileRequest['webuser_id']);

$subcatArr = unserialize($fileRequest['subcat_array']);
$filename = '../public/OutputFiles/keywords_' . $fileRequest['id_file_request'] . '.csv';
$kwCount = 0;

//
// process file request record
//
if (count($subcatArr) > 0) {
    //
    // BUILD THE OUTPUT FILE
    //
    if(buildOutputFileFromSubcatArr($filename, $subcatArr))
    {
        $data['complete'] = true;
        $data['keword_count'] = $kwCount;
        //
        // UPDATE THE FILE REQUEST RECORD WITH COMPLETION DATA
        //
        updateFileRequest($data, $fileRequest['id_file_request']);
        $body = "Your keywords file has finished being generated. You can download it from your profile page. File " . $fileRequest['id_file_request'] . ".";
    } else {
        $body = "There was an error building your file.";
    }
} else {
    //var_dump($fileRequest);
    echo "subcat array empty\n";
    $body = "There was an error building your file.";
}

//
// email the user the results of the build
//
writeToLog(print_r($user));
emailResults($user['email'], $body);



// end




//
// ======================================== FUNCTIONS ========================================
//
    function buildOutputFileFromSubcatArr ($filename, $subcatArr)
    {
        global $kwCount;
        
        if (count($subcatArr) > 0) {
            foreach ($subcatArr as $node) {
                set_time_limit(1800);
                if('subcat' == $node['rowtype']) {
                    getKeywords($node['id_subcategory'], $node['tablename'], $node['volume_level'], $node['search_type'], $filename);
                } elseif('uncat' == $node['rowtype']) {
                    uncategorizedSearch(urldecode($node['urlencoded_phrase']));
                }
            }
        }
        return true;
    }
    
    function getFileRequest ($id_file_request, $db)
    {
        $sqlStmt = "select ";
        $sqlStmt .= "    * ";
        $sqlStmt .= "from ";
        $sqlStmt .= "    file_request ";        
        $sqlStmt .= "where  ";        
        $sqlStmt .= "id_file_request = '$id_file_request';";

        $result = $db->fetchAll($sqlStmt);
        if(!$result) return false;
        return $result[0];
    }
    
    function getKeywords ($id_subcategory, $tableName, $volume_level, $search_type, $filename)
    {
        global $db, $kwCount;

        $sql = "SELECT t.keyword, cl.full_category  FROM $tableName t left join cat_list cl on t.id_subcategory = cl.id_subcategory ";
        $sql .= "WHERE ";
        $sql .= "t.id_subcategory = '".pg_escape_string($id_subcategory)."' ";
        
        if('spend' == $search_type) {
            if('' != $volume_level) {
                $sql .= "and adspend_search_set is not null and adspend_search_set <= '$volume_level' ";
            }
        } else {
            if('' != $volume_level) {
                $sql .= "and volume_search_set is not null and volume_search_set <= '$volume_level' ";
            }
        }
        
        if('spend' == $search_type)
        {
            $sql .= "order by ad_spend_rank ";
        } else {
            $sql .= "order by volume_rank ";
        }
        $sql .= ";";
        
        //writeToLog(  $sql . "\n");
        
        $newline = "\n";
        $firstRow = true;

        $result = $db->query($sql);
        while ($row = $result->fetch(Zend_Db::FETCH_ASSOC)) {
          // do some with the data returned in $row
            //foreach($result as $row){
            
            //writeToLog(print_r($row, true));
            //writeToLog("categorized: " . $row['keyword']);
            
            $outputData = array(); // init an empty array before each insert
            if($firstRow)
            {
                $outputData[] = array($row['keyword'], "Category: " . $row['full_category']);
                writeFile($filename, $outputData);
                echo "cat: " . $row['keyword'] . "\n";
                $firstRow = false;
                $kwCount++;
            } else {
                $outputData[] = array($row['keyword']);
                writeFile($filename, $outputData);
                echo "cat: " . $row['keyword'] . "\n";
                $kwCount++;
            }
            
            //}
        }


        return true;
//        return $outputData;
    }
    
    function uncategorizedSearch ($phrase)
    {
        global $db, $filename, $kwCount;
        
        $modPhrase = $phrase;

        //
        // preserve phrases in quotes
        //
        $quotedPhrases = array();
        $modPhrase = replaceSpacesInQuotes($modPhrase, $quotedPhrases);

        //
        // '!' replace not if first char
        //
        if(substr($modPhrase, 0, 1) == '!') $modPhrase = '!_' . substr($modPhrase, 1);

        //
        // '!' replace not in phrase (not first char)
        //
        $pattern = '/ !(\w+)/i';
        $replacement = '_&_!_$1';
        $modPhrase = preg_replace($pattern, $replacement, $modPhrase);

        //
        // 'and' replace pass one (replace two words connected with ands)
        //
        $pattern = '/(\w+) and (\w+)/i';
        $replacement = '($1_&_$2)';
        $modPhrase = preg_replace($pattern, $replacement, $modPhrase);
        
        //
        // 'and' replace pass two (replacing for 3+ ands)
        //
        $pattern = '/\((\w+)_&_(\w+)\) and \((\w+)_&_(\w+)\)/i';
        $replacement = '($1_&_$2_&_$3_&_$4)';
        $modPhrase = preg_replace($pattern, $replacement, $modPhrase);
                
        //
        // 'and' replace pass three (replacing for 3+ ands)
        //
        $pattern = '/\((\w+)_&_(\w+)\) and (\w+)/i';
        $replacement = '($1_&_$2_&_$3)';
        $modPhrase = preg_replace($pattern, $replacement, $modPhrase);
                
        //
        // ' ' replace spaces with OR (|)
        //
        $pattern = '/ /i';
        $replacement = '_|_';
        $modPhrase = preg_replace($pattern, $replacement, $modPhrase);
        
        //
        // '_' replace underscores with spaces ' '
        //
        $pattern = '/_/i';
        $replacement = ' ';
        $modPhrase = preg_replace($pattern, $replacement, $modPhrase);


//        $phrase = $modPhrase;
        
        $sqlStmt = "select ";
        $sqlStmt .= "    keywords ";
        $sqlStmt .= "from uncategorized_keywords, ";
        $sqlStmt .= "    to_tsquery('english', '" . pg_escape_string($modPhrase) . "') as fsQuery ";
        $sqlStmt .= "where ";
        $sqlStmt .= "    kw_index @@ fsQuery  ";
        
        foreach($quotedPhrases as $ph) {
            $sqlStmt .= "    and keywords ilike '%".pg_replace_string($ph)."%' ";
        }
            
        $sqlStmt .= "order by ";
        $sqlStmt .= "    keywords asc;";
//        echo $sqlStmt;
        
#        $searchParams = new Zend_Session_Namespace();
#        $searchParams->uncat_sqlStmt = $sqlStmt;
//        $searchParams->jesse = $quotedPhrases;
//        $searchParams->phrase = $phrase;
        //$db = Zend_Registry::get('db');
//        $result = $db->fetchAll($sqlStmt);

        echo "sql: $sqlStmt\n\n";
        $i = 1;
        $result = $db->query($sqlStmt);
        while ($wordRow = $result->fetch(Zend_Db::FETCH_ASSOC)) {
        //foreach($uncategorizedResults as $wordRow){
            $outputData = array(); // init an empty array before each insert
            if($i == 1)
            {
                $outputData[] = array($wordRow['keywords'], "Uncategorized: $phrase");
                writeFile($filename, $outputData);
                echo "uncat: " . $wordRow['keywords'] . "\n";
                $kwCount++;
            } else {
                $outputData[] = array($wordRow['keywords'], '');
                writeFile($filename, $outputData);
                echo "uncat: " . $wordRow['keywords'] . "\n";
                $kwCount++;
            }
            //writeToLog("uncat: " . $wordRow['keywords']);
            $i++;
        }

        
//        $searchParams->nocat_resultRows = count($result); 
        return $result;
    }
    
    function writeFile($filename, $data)
    {
//        if(file_exists($filename)) unlink($filename);
        
        $fp = fopen($filename, 'a');
        foreach($data as $rowArr)
        {
            $writeResult = fputcsv($fp, $rowArr);
        }
        fclose($fp);
        //chmod($filename, 0766);  // octal; correct value of mode
        if($writeResult && file_exists($filename)) return true;
        
        return false;
    }

    function updateFileRequest($data, $id_file_request)
    {
        global $db;

        $where[] = "id_file_request = '". $id_file_request ."'";
        $result = $db->update('file_request', $data, $where);
        
        return $result;
    }


    function writeToLog($outputData)
    {
        global $LOG_FILE;
        file_put_contents($LOG_FILE, $outputData . "\n", FILE_APPEND);
    }
    
    function replaceSpacesInQuotes($str, &$quotedPhrases, $replacement='_')
    {
        $quoteOn = false;
        $quoteChar = '"';
        $spaceChar = ' ';
        $retString = '';
                
        for($i=0;$i<strlen($str);$i++) {
            if($str[$i] == $quoteChar) {
                $quoteOn = !$quoteOn;
                if($quoteOn){
                    // init phrase
                    $currentPhrase = '';
                } else {
                    // quote being turned off
                    // add word to phraseArr
                    $quotedPhrases[] = $currentPhrase;
                }
//                $retString .= $str[$i];
                $retString .= "''";
            } elseif($str[$i] == $spaceChar && $quoteOn) {
                $str[$i] = $replacement;
                if($quoteOn) $currentPhrase .= $str[$i];
                $retString .= $replacement;
            } else {
                if($quoteOn) $currentPhrase .= $str[$i];
                $retString .= $str[$i];
            }
        }
        return $retString;
    }
    
    function getUser($webUserId)
    {
        global $db;
        
        $sqlStmt = "select ";
        $sqlStmt .= "    * ";
        $sqlStmt .= "from ";
        $sqlStmt .= "    webuser ";
        $sqlStmt .= "where ";
        $sqlStmt .= "    id = '$webUserId';";
        echo "sql: $sqlStmt<BR>";

        $result = $db->fetchAll($sqlStmt);
        
        return $result[0];
    }
    
    function emailResults($to, $body)
    {        
        $subject = "Infodotcom - Keywords Download";
        if (mail($to, $subject, $body)) {
//          echo("<p>Message successfully sent!</p>");
        } else {
//          echo("<p>Message delivery failed...</p>");
        }
    }
    
