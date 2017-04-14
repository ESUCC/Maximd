<?php
class App_Classes_StudentChart {
    
    var $plotObjs = array();
     
     public static function insert($id_student)
     {
        $studentChartObj = new Model_Table_StudentChart();
        $data = array(
            'id_student' => $id_student,
        );
        $newId = $studentChartObj->insert($data);

//        $studentChart = $studentChartObj->fetchRow("id_student_chart = '$newId'");
        return $newId;
     }
     
     public static function getAll($id_student)
     {
        $studentChartObj = new Model_Table_StudentChart();
        $studentCharts = $studentChartObj->fetchAll("id_student = '$id_student'  and status = 'Active' ", 'id_student_chart ASC');

        // execute stmt
        if($studentCharts->count() > 0)
        {
            return $studentCharts->toArray();
        }
        return false;
     }
     
     public static function getAllForSelectMenu($id_student)
     {
        $retArr = array(''=>'Choose');
        $rows = App_Classes_StudentChart::getAll($id_student);
        if(false !== $rows) 
        {
	        foreach($rows as $row)
	        {
	        	if('' == $row['goal_desc']) {
	        		$retArr[$row['id_student_chart']] = $row['chart_type'];
	        	} else {
	        		$retArr[$row['id_student_chart']] = substr($row['goal_desc'], 0,30);
	        	}
	        }
        }
        return $retArr;
     }
     
     public static function get($id_student, $id_student_chart)
     {
     
        $studentChartObj = new Model_Table_StudentChart();
        $studentChart = $studentChartObj->fetchRow(
            "id_student = '$id_student' and id_student_chart = '$id_student_chart'", 
            'id_student_chart ASC'
        );

        return $studentChart;
     	
     }

     public static function getChartById($id_student_chart)
     {
     
        $studentChartObj = new Model_Table_StudentChart();
        $studentChart = $studentChartObj->fetchRow(
            "id_student_chart = '$id_student_chart'", 
            'id_student_chart ASC'
        );

        return $studentChart;
     }
          
     public static function save($id_student_chart, $data)
     {
     
        $studentChartObj = new Model_Table_StudentChart();
        $studentChart = $studentChartObj->fetchRow(
            "id_student_chart = '$id_student_chart'", 
            'id_student_chart ASC'
        );
        if(isset($data['goal_desc'])) $studentChart->goal_desc = $data['goal_desc'];
        if(isset($data['chart_color'])) $studentChart->chart_color = $data['chart_color'];
        if(isset($data['label_x'])) $studentChart->label_x = $data['label_x'];
        if(isset($data['label_y'])) $studentChart->label_y = $data['label_y'];
        if(isset($data['data_x'])) $studentChart->data_x = $data['data_x'];
        if(isset($data['data_y'])) $studentChart->data_y = $data['data_y'];
        if(isset($data['data_type_x'])) $studentChart->data_type_x = $data['data_type_x'];
        if(isset($data['data_type_y'])) $studentChart->data_type_y = $data['data_type_y'];
        if(isset($data['secondary_plot_charts'])) $studentChart->secondary_plot_charts = $data['secondary_plot_charts'];
        if(isset($data['goal_line'])) $studentChart->goal_line = $data['goal_line'];        
        if(isset($data['line_style'])) $studentChart->goal_line = $data['line_style'];        
        if($studentChart->save())
        {
        	return true;
        } else {
        	return false;
        }
        
     }
     
    public static function jsPlot($yValues)
    {
        $jsText = "";
        $addComma = false;
        foreach($yValues as $k => $v)
        {
            if($addComma) $jsText .= ", ";

            if(is_numeric(trim($v))) 
            {
                $val = trim($v);
            } else {
                $val = '"' . trim($v) . '"';
            }

            $jsText .= "{x: ".$k.", y: ".$val."}";
            $addComma = true;
        }
        return $jsText;
        
    }

    public static function jsLabels($theArr)
    {
        //pre_print_r($theArr);
        //return;
        $jsText = "";
        $addComma = false;
        foreach($theArr as $k => $v)
        {
            if($addComma) $jsText .= ", ";

            if(is_numeric(trim($v))) 
            {
                $val = trim($v);
            } else {
                $val = '"' . trim($v) . '"';
            }

            $jsText .= "{value: ".$k.", text: ".$val."}";
            $addComma = true;
        }
        return $jsText;
        
    }
    public static function canvasLabels($theArr)
    {
        //pre_print_r($theArr);
        //return;
        $jsText = "";
        $addComma = false;
        foreach($theArr as $k => $v)
        {
            if($addComma) $jsText .= ", ";

            if(is_numeric(trim($v))) 
            {
                $val = trim($v);
            } else {
                $val = '"' . trim($v) . '"';
            }

            $jsText .= "'$val'";
            $addComma = true;
        }
        return $jsText;
        
    }
    
    public static function jsArrayFromKeys($theArr, $keys)
    {
        //pre_print_r($theArr);
        //return;
        $jsText = "";
        $arrayKeys = array_keys($theArr);
        $addComma = false;
        for($i = 0; $i < count($theArr); $i++)
        {
            if($addComma) $jsText .= ", ";
            
            if(is_numeric(trim($theArr[$i][$keys[1]]))) 
            {
                $val = trim($theArr[$i][$keys[1]]);
            } else {
                $val = '"' . trim($theArr[$i][$keys[1]]) . '"';
            }
            
            $jsText .= "{".$keys[0].": ".$theArr[$i][$keys[0]].", ".$keys[1].": ".$val."}";
            $addComma = true;
        }
        return $jsText;
        
    }

    public static function labelTwelveMonths($startingMonth)
    {
        $monthsArr = array(
                        "1" => "Jan", 
                        "2" => "Feb", 
                        "3" => "Mar", 
                        "4" => "Apr", 
                        "5" => "May", 
                        "6" => "Jun", 
                        "7" => "Jul", 
                        "8" => "Aug", 
                        "9" => "Sep", 
                        "10" => "Oct", 
                        "11" => "Nov", 
                        "12" => "Dec");
        
        
        $retArr = array();
        $firstDate = "";
        for($i=$startingMonth-1; $i< $startingMonth+11; $i++)
        {
            $pos = ($i % 12) + 1; 
//            if("" == $firstDate) $firstDate = date_massage();
            
            $retArr[] = $monthsArr[$pos];
        }
        return $retArr;
    }

    public static function monthsDaysArray($startDate)
    {
        $monthsArr = array(
                        "1" => "Jan", 
                        "2" => "Feb", 
                        "3" => "Mar", 
                        "4" => "Apr", 
                        "5" => "May", 
                        "6" => "Jun", 
                        "7" => "Jul", 
                        "8" => "Aug", 
                        "9" => "Sep", 
                        "10" => "Oct", 
                        "11" => "Nov", 
                        "12" => "Dec");
        
        $startingMonth = date_massage($startDate, 'n');
        
        $tmpPlots = array();
        $monthNum = date_massage($startDate, 'n');
        $tmpPlots[] = new date_plot($startDate, $startDate, $monthsArr[$monthNum]);

        for($i=1; $i<12; $i++)
        {
            $monthNum = date_massage($startDate."+$i months", 'n');
            $tmpPlots[] = new date_plot($startDate, date_massage($startDate."+$i months"), $monthsArr[$monthNum]);
        }
        
        //pre_print_r($tmpPlots);

        $retArr = array();
        foreach($tmpPlots as $date_plot)
        {
            $retArr[] = array("value" => $date_plot->days_interval, "text" => $date_plot->value);
        }
        //pre_print_r($retArr);
        return $retArr;

    }

    public function buildDatePlots($dateArr, $valuesArr, $styleArr = array())
    {
        $startDate = $dateArr[0];
        for($i = 0; $i < count($dateArr); $i++)
        {
            $this->plotObjs[] = new date_plot($startDate, $dateArr[$i], $valuesArr[$i], $styleArr);
        }
    }
    
    public function printDatePlots()
    {
        pre_print_r($this->plotObjs);
    }

    public function getDatePlotMaxDays()
    {
        $maxDays = 0;
        foreach($this->plotObjs as $date_plot)
        {
            if($date_plot->days_interval > $maxDays) $maxDays = $date_plot->days_interval;
        }
        return $maxDays;
    }
    
    public function getDayValueArray()
    {
        $retArr = array();
        foreach($this->plotObjs as $date_plot)
        {
            $retArr[] = array("x" => $date_plot->days_interval, "y" => $date_plot->value);
        }
        return $retArr;
    }

    public function renderCanvas($id_student, $id_student_chart, $chartID, $styleArr=array("height"=>"200px", "width"=>"650px", "border"=>"2")) {

    	
    	if(!$primaryChart = App_Classes_StudentChart::get($id_student, $id_student_chart)) return false;
		
		$xRows = explode ( "\n", trim ( $primaryChart ['data_x'], "\n" ) );
		$yRows = explode ( "\n", trim ( $primaryChart ['data_y'], "\n" ) );
    	
        $retText = '';
		$retText .= '    <script src="/js/RGraph/libraries/RGraph.common.core.js" ></script>';
		$retText .= '    <script src="/js/RGraph/libraries/RGraph.common.context.js" ></script>';
		$retText .= '    <script src="/js/RGraph/libraries/RGraph.common.annotate.js" ></script>';
		$retText .= '    <script src="/js/RGraph/libraries/RGraph.common.tooltips.js" ></script>';
		$retText .= '    <script src="/js/RGraph/libraries/RGraph.common.zoom.js" ></script>';
		
		$retText .= '    <script src="/js/RGraph/libraries/RGraph.common.resizing.js" ></script>';
		$retText .= '    <script src="/js/RGraph/libraries/RGraph.line.js" ></script>';
		$retText .= '    <!--[if IE 8]><script src="/js/RGraph/excanvas/excanvas.compressed.js"></script><![endif]-->';

		$retText .= "    <script>\n";
		$retText .= "        window.onload = function ()\n";
		$retText .= "        {\n";
		$retText .= "            console.debug('rgraph loading');\n";

        $retText .= "            var line4 = new RGraph.Line('line4', [". App_Classes_StudentChart::canvasLabels($yRows)."]);\n";
        
//        $retText .= "            line4.Set('chart.key', ['2008', '2007', '2006']);\n";
//        $retText .= "            line4.Set('chart.key.background', '#fff');\n";
//        $retText .= "            line4.Set('chart.key.shadow', true);\n";
        $retText .= "            line4.Set('chart.gutter', 45);\n";
        $retText .= "            line4.Set('chart.background.grid.autofit', true);\n";
        
        $retText .= "            if (!RGraph.isIE8()) {\n";
        $retText .= "                line4.Set('chart.zoom.mode', 'thumbnail');\n";
        $retText .= "            }\n";
        
//        $retText .= "            line4.Set('chart.filled', true);\n";
//        $retText .= "            line4.Set('chart.tickmarks', null);\n";
//        $retText .= "            line4.Set('chart.background.barcolor1', 'white');\n";
//        $retText .= "            line4.Set('chart.background.barcolor2', 'white');\n";

        // title
        if('' != $primaryChart['goal_desc']) {
			$retText .= "            line4.Set('chart.title', '{$primaryChart['goal_desc']}');\n";
        }
        
//        $retText .= "            line4.Set('chart.colors', ['rgba(169, 222, 244, 0.7)', 'red', '#ff0']);\n";
//        $retText .= "            line4.Set('chart.fillstyle', ['#daf1fa', '#faa', '#ffa']);\n";
        
        $retText .= "            line4.Set('chart.labels', [". App_Classes_StudentChart::canvasLabels($xRows)."]);\n";
//        $retText .= "            line4.Set('chart.text.size', '7');\n";

//        $retText .= "            line4.Set('chart.yaxispos', 'right');\n";
//        $retText .= "            line4.Set('chart.linewidth', 5);\n";
        $retText .= "            line4.Draw();\n";
        
//        $retText .= "            var canvas = document.getElementById(\"line4\");\n";
//        $retText .= "            var context = canvas.getContext(\"2d\");\n";
//        $retText .= "            var img     = canvas.toDataURL(\"image/png\");\n";
//        $retText .= "            document.write('<img src=\"'+img+'\"/>');\n";
        



        
		$retText .= "        }\n";
		$retText .= "    </script>\n";
		
		$retText .= '    <script>';
		$retText .= '        if (RGraph.isIE8()) {';
		$retText .= '            document.write(\'<div style="background-color: #fee; border: 2px dashed red; padding: 5px"><b>Important</b><br /><br /> Internet Explorer does not natively support the HTML5 canvas tag yet, so if you want to see the graphs, you can either:<ul><li>Install <a href="http://code.google.com/chrome/chromeframe/">Google Chrome Frame</a></li><li>Use ExCanvas. This is provided in the RGraph Archive.</li><li>Use another browser entirely. Your choices are Firefox 3.5+, Chrome 2+, Safari 4+ or Opera 10.5+. </li></ul></div>\');';
		$retText .= '        }';
		$retText .= '    </script>';
		
		    
		$retText .= '    <div>';
		$retText .= '        <div>';
		$retText .= '            <canvas id="line4" width="650" height="250">[Please wait...]</canvas>';
		$retText .= '        </div>';
		$retText .= '    </div>';
				
		return $retText;
    }
    
    public static function render($id_student, $id_student_chart, $chartID, $styleArr=array("height"=>"200px", "width"=>"650px", "border"=>"2"))
    {
//    Zend_Debug::dump($chartID);die();
        if(!$primaryChart = App_Classes_StudentChart::get($id_student, $id_student_chart)) return false;

        if('' != $primaryChart['label_x']) {
            $addedRow = "<tr>";
            $addedRow .= "<td align=\"center\">";
            $addedRow .= $primaryChart['label_x'];
            $addedRow .= "</td>";
            $addedRow .= "</tr>";

            $rowspan = " rowspan=\"2\"";
        } else {
            $addedRow = "";

            $rowspan = " rowspan=\"1\"";
        }

        $retText = "<table style=\"font-family: sans-serif;font-size: 18px;\"width=\"".$styleArr['width']."\" border=\"".$styleArr['border']."\" align=\"center\">";
        $retText .= "<tr>";
        $retText .= "<td colspan=\"2\" align=\"center\">";
        $retText .= $primaryChart['goal_desc'];
        $retText .= "</td>";

        $retText .= "<tr>";

            $retText .= "<td $rowspan >";
            $retText .= "<div id=\"chart_".$chartID."_h_label\" style=\"height: 200px;width: 30px;\"></div>";
            $retText .= "</td>";
    
            $retText .= "<td style=\"width:95%;text-align:left;\">";
            $retText .= "<div id=\"chart_".$chartID."\" style=\"width: ".($styleArr['width'] - 50)."; height: ".$styleArr['height'].";\"></div>";
            $retText .= "</td>";

        $retText .= "</tr>";
        
        $retText .= $addedRow;
        
        $retText .= "<tr>";
            $retText .= "<td colspan=\"2\" nowrap=\"nowrap\">";

            $retText .= "<font size=\"-2\" color=\"".$primaryChart['chart_color']."\">" . $primaryChart['chart_color'] . ": " . $primaryChart['goal_desc'] . "</font><br/>";
                
                $idArr = explode("\n", trim($primaryChart['secondary_plot_charts'], "\n"));
                if('' != $primaryChart['secondary_plot_charts'] && count($idArr) > 0 )
                {
                    foreach($idArr as $secID)
                    {
                        $secChart = App_Classes_StudentChart::get($id_student, $secID);                    
                        $retText .= "<font size=\"-2\" color=\"".$secChart['chart_color']."\">" . $secChart['chart_color'] . ": " . $secChart['goal_desc'] . "</font><br/>";
                    }
                }
				if($primaryChart['goal_line'] > 0) {
                	$retText .= "<font size=\"-2\" color=\"blue\">Blue: Goal Line</font><br/>";
                }
                
            $retText .= "</td>";
        $retText .= "</tr>";

        $retText .= "</table>";
        
        return $retText;
    }
    
    
    
    function buildChartJavascript($id_student, $chart_id, $container_id)
    {
        if($primaryChart = App_Classes_StudentChart::get($id_student, $chart_id))
        {                    
            $xRows = explode("\n", trim($primaryChart['data_x'], "\n"));
            $yRows = explode("\n", trim($primaryChart['data_y'], "\n"));
                        
            // plot the dates from first to last
            if(!isset($primaryChart['chart_type']) || '' == $primaryChart['chart_type']) $primaryChart['chart_type'] = 'simplechart';
            if(!isset($primaryChart['line_style']) || '' == $primaryChart['line_style']) $primaryChart['line_style'] = 'solid';

            $returnString = "\n";
            $returnString .= "try{\n";
                
                $returnString .= "var makeChart_".$container_id." = function(){\n";
                
                $returnString .= "var chart = new dojox.charting.Chart2D('chart_".$container_id."');\n";
                
                $returnString .= "// plot the current chart\n";
                $returnString .= "chart.addPlot(\"default\", {type: \"Lines\",markers: true, tension:3});\n";
                $returnString .= "chart.addAxis(\"x\", {labels: [". App_Classes_StudentChart::jsLabels($xRows)."]});\n";
                $returnString .= "chart.addAxis(\"y\", {vertical: true});\n";
                $returnString .= "chart.addSeries(\"Series A\", [". App_Classes_StudentChart::jsPlot($yRows)."], {plot: \"default\", stroke: {color:\"".strtolower($primaryChart['chart_color'])."\", style:\"".$primaryChart['line_style']."\"} });\n";
				
                if(0 && $primaryChart['goal_line'] > 0) {
	                $returnString .= "// plot the Goal Line\n";
	                $returnString .= "chart.addSeries(\"Series Goal_Line\", [". App_Classes_StudentChart::jsPlot(array_fill(0, count($xRows), $primaryChart['goal_line']))."], {plot: \"default\", stroke: {color:\"blue\"} });\n";
                }
                
                
                if('' != $primaryChart['secondary_plot_charts'])
                {
                	$returnString .= "// plot secondary charts\n";
                    $idArr = explode("\n", trim($primaryChart['secondary_plot_charts'], "\n"));
                    foreach($idArr as $secID)
                    {
                        $secChart = App_Classes_StudentChart::get($id_student, $secID);
                    	if(!isset($secChart['line_style']) || '' == $secChart['line_style']) $secChart['line_style'] = 'solid';
                        $xRowsSec = explode("\n", trim($secChart['data_x'], "\n"));
                        $yRowsSec = explode("\n", trim($secChart['data_y'], "\n"));
                        
                        $returnString .= "chart.addSeries(\"Series $secID\", [". App_Classes_StudentChart::jsPlot($yRowsSec)."], {plot: \"default\", stroke: {color:\"".strtolower($secChart['chart_color'])."\", style:\"".$secChart['line_style']."\"} });\n";
                    }
                }
                
                $returnString .= "chart.render();\n";
                
                $returnString .= "surface = dojox.gfx.createSurface('chart_".$container_id."_h_label', 30, 200);\n";
//                $returnString .= "surface.bgNode.style = \"color:red;\";\n";
                
                $returnString .= "var m = dojox.gfx.matrix;\n";
                
                $returnString .= "t1 = makeText(surface, {x: 28, y: 100, text: \"".$primaryChart['label_y']."\", align: \"middle\"}, \n";
                $returnString .= "  {family: \"Helvetica\", size: \"14pt\", weight: \"normal\"}, \"black\")\n";
                $returnString .= "  .setTransform(m.rotategAt(ROTATION, 28, 100))\n";
                $returnString .= "  ;\n";
                $returnString .= "};\n";
				
                $returnString .= "dojo.addOnLoad(makeChart_".$container_id.");\n";

            $returnString .= "} catch(err) {\n";

            $returnString .= "}\n";
            
            // end javascript
            return $returnString;
        }
        return false;
    }
    
    protected function buildLinePlot(&$graph, $xdata, $ydata, $chartColor, $chartType, $legendText, $points = 50) {

        if(null == $chartType || '' == $chartType) {
            $chartType = 'solid';
        }
                
        // trim each element of the array
        array_walk($xdata, create_function('&$val', '$val = trim($val);')); 
        array_walk($ydata, create_function('&$val', '$val = trim($val);')); 
        
#        Zend_Debug::dump($ydata);
#        Zend_Debug::dump($xdata);
#        die();

        $lineplot=new LinePlot($ydata, $xdata);
         $lineplot->SetColor(strtolower($chartColor));
         $lineplot->SetWeight(3);
         $lineplot->SetLegend($legendText);
         $lineplot->SetStyle(strtolower($chartType));
         $graph->Add($lineplot);

        // We use a scatterplot to illustrate the original
        // contro points.
         $splot = new ScatterPlot($ydata,  $xdata);
         $splot->mark->SetFillColor(strtolower($chartColor).'@0.3');
         $splot->mark->SetColor(strtolower($chartColor).'@0.5');
         $graph->Add($splot);
    
    }
    
    protected function buildGoalLine(&$graph, $xdata, $ydata, $goalLineValue, $chartColor, $points = 50) {

//        // Get the interpolated values by creating a new Spline object.
//        $spline = new Spline($xdata,$ydata);
//
//        // For the new data set we want 50 points to get a smooth curve.
//        list($newx,$newy) = $spline->Get($points);

        // forget the curve
        $newx = $xdata;
        $newy = $ydata;        
        
        $goalLine = new LinePlot(array_pad(array(), count($newy), $goalLineValue), $newx);
//        $goalLine->SetColor($chartColor);
        $goalLine->SetWeight(2);

        $graph->Add($goalLine);
    
    }

    public static function renderJGraph($id_student_chart)
    {
        
        if(!$primaryChart = App_Classes_StudentChart::getChartById($id_student_chart)) {
            return false;
        }
        // render based on chart type
        if('simplechart' == $primaryChart['chart_type']) {
            return App_Classes_StudentChart::buildSimpleChart($id_student_chart, $primaryChart);
            
        } elseif('LinearRegression' == $primaryChart['chart_type']) {
            return App_Classes_StudentChart::buildLinearRegression($id_student_chart, $primaryChart);
            
        } elseif('PieGraph' == $primaryChart['chart_type']) {
            return App_Classes_StudentChart::buildPieGraph($id_student_chart, $primaryChart);
            
        } elseif('BarGraph' == $primaryChart['chart_type']) {
            return App_Classes_StudentChart::buildBarGraph($id_student_chart, $primaryChart);
        }
        
    }

    public static function buildBarGraph($id_student_chart, $primaryChart)
    {
        require_once ('library/jpgraph/src/jpgraph.php');
        require_once ('library/jpgraph/src/jpgraph_bar.php');

		$xRows = explode ( "\n", trim ( $primaryChart ['data_x'], "\n" ) );
		$yRows = explode ( "\n", trim ( $primaryChart ['data_y'], "\n" ) );
        
        // trim each element of the array
        array_walk($xRows, create_function('&$val', '$val = trim($val);')); 
        array_walk($yRows, create_function('&$val', '$val = trim($val);')); 
        
        if('' == $xRows || '' == $yRows || count($xRows) == 0) {
            return false;
        }
        
        // pad any unentered values to be 0
        if(count($yRows) < count($xRows)) {
            $yRows = array_pad($yRows, count($xRows), 0);
        }

        
        
        // Create the graph. These two calls are always required
        $graph = new Graph(700,400,'auto');
        $graph->SetScale("textlin");
                
//        $graph->yaxis->SetTickPositions(array(0,30,60,90,120,150), array(15,45,75,105,135));
//        $graph->SetBox(false);
        
//         $graph->ygrid->SetFill(false);
//         $graph->xaxis->SetTickLabels(array('A','B','C','D'));
//         $graph->yaxis->HideLine(false);
//         $graph->yaxis->HideTicks(false,false);
        
        // Create the bar plots
        $b1plot = new BarPlot($yRows);
        
        // Create the grouped bar plot
        $gbplot = new GroupBarPlot(array($b1plot));
        // ...and add it to the graPH
        $graph->Add($gbplot);
        
        
        $b1plot->SetColor("white");
        $b1plot->SetFillColor("#cc1111");
                
//        $graph->title->Set("Bar Plots");
        
        // Display the graph
//        $graph->img->SetImgFormat('jpeg');
        $graph->Stroke();

        
    }
    
    public static function buildPieGraph($id_student_chart, $primaryChart)
    {
        // content="text/plain; charset=utf-8"
        require_once ('library/jpgraph/src/jpgraph.php');
        require_once ('library/jpgraph/src/jpgraph_pie.php');

        global $sessIdUser;

		$xRows = explode ( "\n", trim ( $primaryChart ['data_x'], "\n" ) );
		$yRows = explode ( "\n", trim ( $primaryChart ['data_y'], "\n" ) );
        
        // trim each element of the array
        array_walk($xRows, create_function('&$val', '$val = trim($val);')); 
        array_walk($yRows, create_function('&$val', '$val = trim($val);')); 
        
        if('' == $xRows || '' == $yRows || count($xRows) == 0) {
            return false;
        }
        
        // pad any unentered values to be 0
        if(count($yRows) < count($xRows)) {
            $yRows = array_pad($yRows, count($xRows), 0);
        }


        
        // Create the Pie Graph. 
        $graph = new PieGraph(700,400);        
        $graph->SetScale('linlin');
        $graph->SetMarginColor('white');
        $graph->SetMargin(55,30,10,50);

        // ===========================================================================
        // titles/labels
        // ===========================================================================
        // Setup a title for the graph
        $graph->title->Set($primaryChart['goal_desc']);
        $graph->title->SetFont(FF_ARIAL,FS_BOLD,24);
        
        // ===========================================================================
        // end titles/labels
        // ===========================================================================
        
        // Create
        $p1 = new PiePlot($yRows);
        $p1->SetLegends($xRows); 
        $graph->Add($p1);
        
        $p1->ShowBorder();
        $p1->SetColor('black');
        $graph->Stroke();    
    }
    
    protected function buildLinearRegressionPlot(&$graph, $chart) {
        global $sessIdUser;

		$xRows = explode ( "\n", trim ( $chart ['data_x'], "\n" ) );
		$yRows = explode ( "\n", trim ( $chart ['data_y'], "\n" ) );
        
        // trim each element of the array
        array_walk($xRows, create_function('&$val', '$val = trim($val);')); 
        array_walk($yRows, create_function('&$val', '$val = trim($val);')); 
        
        if('' == $xRows || '' == $yRows || count($xRows) == 0) {
            return false;
        }
        
        // pad any unentered values to be 0
        if(count($yRows) < count($xRows)) {
            $yRows = array_pad($yRows, count($xRows), 0);
        }
        
        
        
        if('date' == $chart['data_type_x']) {
            $lastDateTime = false;
            // xdataDayValues will contain 1 and then the interval of days between dates
            $xdataDayValues = array(); 
            $lastDate = false;
            foreach($xRows as $date) {
                if(false != $lastDateTime) {
                    $days = App_Classes_StudentChart::dateDiffNew("/", $date, $lastDateTime);
                    $dayCounter += $days;
                    if(false != $lastDateTime) $xdataDayValues[] = $dayCounter;
                } else {
                    $xdataDayValues[] = 1;
                    $dayCounter = 1;
                }
                $lastDateTime = $date;
            }
            $xRows = $xdataDayValues;
            $rightLimit = $dayCounter;
        } else {
            $rightLimit = max($xRows);
        }


        $lr = new LinearRegression($xRows, $yRows);
        list( $stderr, $corr ) = $lr->GetStat();
        list( $xd, $yd ) = $lr->GetY(0, $rightLimit);
        $lr->rightLimit = $rightLimit;
        
        // Create the scatter plot with some nice colors
        $sp1 = new ScatterPlot($yRows,$xRows);
        $sp1->mark->SetType(MARK_FILLEDCIRCLE);
        $sp1->mark->SetFillColor($chart['dot_color']);
         
        // Create the regression line
        $lplot = new LinePlot($yd);
        $lplot->SetWeight(3);
        $lplot->SetColor(strtolower($chart['chart_color']));
         
        // Add the plots to the graph
        $graph->Add($sp1);
        $graph->Add($lplot);
        
        return $lr;
    
    }
    
    public static function buildLinearRegression($id_student_chart, $primaryChart)
    {
        require_once('library/jpgraph/src/jpgraph_utils.inc.php');

//        global $sessIdUser;        

        // Create the graph
        $graph = new Graph(700,400);        
        $graph->SetScale('linlin');
        $graph->SetMarginColor('white');
        $graph->SetMargin(55,30,10,50);
        
        
        
        $lr = App_Classes_StudentChart::buildLinearRegressionPlot($graph, $primaryChart);
        list( $stderr, $corr ) = $lr->GetStat();
        list( $xd, $yd ) = $lr->GetY(0, $lr->rightLimit);
        
        // ===========================================================================
        // titles/labels
        // ===========================================================================
        // Setup a title for the graph
        $graph->title->Set($primaryChart['goal_desc']);
        $graph->title->SetFont(FF_ARIAL,FS_BOLD,24);

        // subtitle
        $graph->subtitle->Set('(stderr='.sprintf('%.2f',$stderr).', corr='.sprintf('%.2f',$corr).')');
        $graph->subtitle->SetFont(FF_ARIAL,FS_NORMAL,12);
                
        // format X axis
        // Setup titles and X-axis labels
        $graph->xaxis->title->Set($primaryChart['label_x']);
        $graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,12);
        $graph->xaxis->SetTitleMargin(10);
        $graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,9);
         
        // Setup Y-axis title
        $graph->yaxis->title->Set($primaryChart['label_y']);
        $graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,12);
        $graph->yaxis->SetTitleMargin(35);
        // ===========================================================================
        // end titles/labels
        // ===========================================================================
        
        // Display the graph
        $graph->Stroke();        
    
    }

    public static function buildSimpleChart($id_student_chart, $primaryChart)
    {
		$xRows = App_Classes_StudentChart::buildXArrayFromData($primaryChart);
		$yRows = App_Classes_StudentChart::buildYArrayFromData($primaryChart);


        if('' == $xRows || '' == $yRows || count($xRows) == 0) {
            return false;
        }
        
        // pad any unentered values to be 0
        if(count($yRows) < count($xRows)) {
            $yRows = array_pad($yRows, count($xRows), 0);
        }

        // Create the graph. These two calls are always required
        $graph = new Graph(700,400);        
        $graph->SetScale('datint');
        $graph->SetMarginColor('white');
        
        
        
        
        // build array of date objects and get max and min date for building graph x-range
        if('date' == $primaryChart['data_type_x']) {
            $xdata = array();
            foreach($xRows as $date) {
                $xdata[] = strtotime($date);
            }
        } else {
            // build numerically indexed array
            $xdata = array_keys(array_pad(array(), count($xRows), 'x'));
        }
        
        // convert to jgraph formatted array
        //$ydata = App_Classes_StudentChart::canvasLabels($yRows);


        // Create the main plot
        App_Classes_StudentChart::buildLinePlot($graph, $xdata, $yRows, $primaryChart['chart_color'], $primaryChart['line_style'], $primaryChart['goal_desc']);//

        // build the secondary plots
        $idArr = App_Classes_StudentChart::buildSecondaryPlots($primaryChart, $graph);

        // Setup a legend for the graph
        $graph->legend->Pos(0.025,0.98,"left","bottom");

        // Setup a title for the graph
        $graph->title->Set($primaryChart['goal_desc']);
        $graph->title->SetFont(FF_ARIAL,FS_BOLD,24);


        // format X axis
        // Setup titles and X-axis labels
        $graph->xaxis->title->Set($primaryChart['label_x']);
        $graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,12);
        $graph->xaxis->SetLabelFormatString('m/d/y',true);
        $graph->xaxis->SetTickPositions($xdata);
        $graph->xaxis->SetTitleMargin(30);
        $graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,9);
        $graph->xaxis->SetLabelAngle(20);
         
        // set y-axis min and max
        if(isset($primaryChart['y_axis_min'])) $graph->yaxis->scale->SetAutoMin($primaryChart['y_axis_min']);
        if(isset($primaryChart['y_axis_max'])) $graph->yaxis->scale->SetAutoMax($primaryChart['y_axis_max']);
        
        // Setup Y-axis title
        $graph->yaxis->title->Set($primaryChart['label_y']);
        $graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,12);
        $graph->yaxis->SetTitleMargin(25);
        
        // Slightly larger than normal margins at the bottom to have room for
        // the x-axis labels
//        Zend_Debug::dump($idArr);die();
        $graph->SetMargin(55,20,30,75 + (count($idArr) * 18));
        // Create the goal line
        if($primaryChart['goal_line'] > 0) {
            //App_Classes_StudentChart::buildGoalLine($graph, $xdata, $yRows, $primaryChart['goal_line'], 'lightblue');
        }
        
        $lineplot=new LinePlot(array(1,2,3),array(1,2,3));
//        $lineplot->SetColor(strtolower($chartColor));
        $lineplot->SetWeight(3);
//        $lineplot->SetLegend($legendText);
//        $lineplot->SetStyle(strtolower($chartType));
        //$graph->Add($lineplot);

        // Display the graph
        $graph->Stroke();        
    
    }
    
    public static function buildSecondaryPlots($primaryChart, $graph) {
    
        // build the secondary plots
        if(null == $primaryChart['secondary_plot_charts']) {
            $idArr = array();
        } else {
            $idArr = explode("\n", trim($primaryChart['secondary_plot_charts'], "\n"));
            if('' != $primaryChart['secondary_plot_charts'] && count($idArr) > 0 )
            {
                foreach($idArr as $secID)
                {
                    $secChart = App_Classes_StudentChart::getChartById($secID);
                    if($secChart) {                        
        		        $xRowsSec = App_Classes_StudentChart::buildXArrayFromData($secChart);
		                $yRowsSec = App_Classes_StudentChart::buildYArrayFromData($secChart);
                        $xdatasec = array();
                        foreach($xRowsSec as $date) {
                            $xdatasec[] = strtotime($date);
                        }
                
                        // format data
                        //$ydatasec = App_Classes_StudentChart::canvasLabels($yRowsSec);
                        
                        if('simplechart' == $secChart['chart_type']) {
                            // Create the secondary linear plot
                            App_Classes_StudentChart::buildLinePlot($graph, $xdatasec, $yRowsSec, $secChart['chart_color'], $secChart['line_style'], $secChart['goal_desc']);//
                        } elseif('LinearRegression' == $secChart['chart_type']) {
                            // Create the secondary linear regression
                            App_Classes_StudentChart::buildLinePlot($graph, $xdatasec, $yRowsSec, $secChart['chart_color'], $secChart['line_style'], $secChart['goal_desc']);//
                            //App_Classes_StudentChart::buildLinearRegressionPlot($graph, $secChart);
                        }
                    }
                }
            }
        }
        return $idArr;
    }

    function trim_value(&$value) 
    { 
        $value = trim($value); 
    }    

	function dateDiffNew($dformat, $endDate, $beginDate)
	{
	    $date_parts1=explode($dformat, $beginDate);
	    $date_parts2=explode($dformat, $endDate);
	    $start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
	    $end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
	    return $end_date - $start_date;
	}
    public static function buildXArrayFromData($chart) {
		$xRows = explode ( "\n", trim ( $chart ['data_x'], "\n" ) );

        // trim each element of the array
        array_walk($xRows, create_function('&$val', '$val = trim($val);')); 
        
        if('' == $xRows || count($xRows) == 0) {
            return false;
        }
        
        return $xRows;        
    }
    public static function buildYArrayFromData($chart) {
		$xRows = explode ( "\n", trim ( $chart ['data_x'], "\n" ) );
		$yRows = explode ( "\n", trim ( $chart ['data_y'], "\n" ) );
        
        // trim each element of the array
        array_walk($xRows, create_function('&$val', '$val = trim($val);')); 
        array_walk($yRows, create_function('&$val', '$val = trim($val);')); 
        
        if('' == $xRows || '' == $yRows || count($xRows) == 0) {
            return false;
        }
        
        // pad any unentered values to be 0
        if(count($yRows) < count($xRows)) {
            $yRows = array_pad($yRows, count($xRows), 0);
        }
        return $yRows;
        
    }
	

}


class date_plot {

    var $start_date;
    var $current_date;
    var $value;
    var $days_interval;
    
    function __construct($start_date, $current_date='', $value='', $styleArr = array())
    {
        $this->start_date = $start_date;
        $this->current_date = $current_date;
        $this->value = $value;
        $this->styleArr = $styleArr;
        $this->daysInterval();
    }

    function daysInterval()
    {
        $dateDiff = strtotime($this->current_date) - strtotime($this->start_date);
        $this->days_interval = floor($dateDiff/(60*60*24));
    }
}


