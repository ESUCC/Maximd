<?php

class App_JQueryDatatable {
	var $sWhere;
	var $debug = false;
		
	function __construct() {
		$this->setSwhere('1=0');
	}
	function debug($debugMe, $label='', $die=false) {
		if($this->debug) {
			Zend_Debug::dump($debugMe, $label);
			if($die) {
				echo "die.....aaarrrgg";
				die();
			}
		}
	}
	function datatable($aColumns, $ilikColumns, $sTable, $sIndexColumn) {
		
		$config = Zend_Registry::get ( 'config' );
		
		/* Database connection information */
		$gaSql ['user'] = $config->db2->params->username;
		$gaSql ['password'] = $config->db2->params->password;
		$gaSql ['db'] = $config->db2->params->dbname;
		$gaSql ['server'] = $config->db2->params->host;
		
		$db = Zend_Registry::get ( 'db' );
		
		/*
		 * Paging - build the LIMIT expression
		*/
		$sLimit = "";
		if (isset ( $_GET ['iDisplayStart'] ) && $_GET ['iDisplayLength'] != '-1') {
			$sLimit = "LIMIT " . pg_escape_string ( $_GET ['iDisplayLength'] ) . " OFFSET " . pg_escape_string ( $_GET ['iDisplayStart'] );
		}
		$this->debug($sLimit, 'slimit');
		/*
		 * Ordering - build the ORDER BY expressions
		 * loop through GET for sort columns and 
		*/
// 		$this->debug($_GET ['iSortCol_0'], 'iSortCol_0');
		if (isset ( $_GET ['iSortCol_0'] )) {
			$sOrder = "ORDER BY  ";
			for($i = 0; $i < intval ( $_GET ['iSortingCols'] ); $i ++) {
				if ($_GET ['bSortable_' . intval ( $_GET ['iSortCol_' . $i] )] == "true") {
					$sOrder .= $aColumns [intval ( $_GET ['iSortCol_' . $i] )] . "
					" . pg_escape_string ( $_GET ['sSortDir_' . $i] ) . ", ";
				}
			}
			
			$sOrder = substr_replace ( $sOrder, "", - 2 );
			if ($sOrder == "ORDER BY") {
				$sOrder = "";
			}
		}
		$this->debug($sOrder, 'sOrder');
		/*
		 * Filtering - WHERE clause
		* NOTE This assumes that the field that is being searched on is a string typed field (ie. one
				* on which ILIKE can be used). Boolean fields etc will need a modification here.
		*/
		$sWhere = ' WHERE '  .$this->getSwhere() . ' ' ;
		if ($_GET ['sSearch'] != "") {
			if($sWhere!="") $sWhere .= " and";
			$sWhere .= " (";
			for($i = 0; $i < count ( $aColumns ); $i ++) {
				if ($_GET ['bSearchable_' . $i] == "true") {
					if (in_array ( $aColumns [$i], $ilikColumns )) {
						$sWhere .= $aColumns [$i] . " ILIKE '%" . pg_escape_string ( $_GET ['sSearch'] ) . "%' OR ";
					}
				
				}
			}
			$sWhere = substr_replace ( $sWhere, "", - 3 );
			$sWhere .= ")";
		}
		$this->debug($sWhere, 'sWhere');
		/* Individual column filtering */
// 		for($i = 0; $i < count ( $aColumns ); $i ++) {
// 			if ($_GET ['bSearchable_' . $i] == "true" && $_GET ['sSearch_' . $i] != '') {
// 				if ($sWhere == "") {
// 					$sWhere = "WHERE ";
// 				} else {
// 					$sWhere .= " AND ";
// 				}
// 				$sWhere .= $aColumns [$i] . " ILIKE '%" . pg_escape_string ( $_GET ['sSearch_' . $i] ) . "%' ";
// 			}
// 		}
		
		$sQuery = "
		SELECT " . str_replace ( " , ", " ", implode ( ", ", $aColumns ) ) . "
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
		";
//  		echo $sQuery;; die();
		$this->debug($sQuery, 'sQuery');
		$rResult = $db->fetchAll ( $sQuery );


		
		
		
		
		
		
		// new
		$select = $db->select()
		->from( $sTable);
		
		// offset
		if (isset ( $_GET ['iDisplayStart'] ) && $_GET ['iDisplayLength'] != '-1') {
			$select->limit( $_GET ['iDisplayLength'], $_GET ['iDisplayStart'] );
		}

		/*
		 * Ordering
		*/
		if (isset ( $_GET ['iSortCol_0'] )) {
			$jsOrder = "";
			for($i = 0; $i < intval ( $_GET ['iSortingCols'] ); $i ++) {
				if ($_GET ['bSortable_' . intval ( $_GET ['iSortCol_' . $i] )] == "true") {
					$jsOrder .= $aColumns [intval ( $_GET ['iSortCol_' . $i] )] . "
					" . pg_escape_string ( $_GET ['sSortDir_' . $i] ) . ", ";
				}
			}
		
			$jsOrder = substr_replace ( $jsOrder, "", - 2 );
			if ($jsOrder != "") {
				$select->order( $jsOrder );
			}
		}		
		
		$jsWhere = ' '  .$this->getSwhere() . ' ' ;
		if ($_GET ['sSearch'] != "") {
			if($jsWhere!="") $jsWhere .= " and ";
			$jsWhere .= "";
			for($i = 0; $i < count ( $aColumns ); $i ++) {
				if ($_GET ['bSearchable_' . $i] == "true") {
					if (in_array ( $aColumns [$i], $ilikColumns )) {
						$jsWhere .= $aColumns [$i] . " ILIKE '%" . pg_escape_string ( $_GET ['sSearch'] ) . "%' OR ";
					}
		
				}
			}
			$jsWhere = substr_replace ( $jsWhere, "", - 3 );
			$jsWhere .= "";
		}
		
		/* Individual column filtering */
		for($i = 0; $i < count ( $aColumns ); $i ++) {
			if ($_GET ['bSearchable_' . $i] == "true" && $_GET ['sSearch_' . $i] != '') {
				if ($jsWhere == "") {
					$jsWhere = "";
				} else {
					$jsWhere .= " AND ";
				}
				$jsWhere .= $aColumns [$i] . " ILIKE '%" . pg_escape_string ( $_GET ['sSearch_' . $i] ) . "%' ";
			}
		}
		if ($jsWhere != "") {
			$select->where( $jsWhere );
		}
		
// 		echo $select; die();
		$rResult = $db->fetchAll ( $select );
		
		// get estimated number of rows
		
		
		
// 		$sQuery = "SELECT reltuples as count FROM pg_class WHERE relname = '$sTable'";

		$sQuery = "SELECT count(1) as count FROM $sTable WHERE " . $this->getSwhere();
// 		Zend_Debug::dump($this->getSwhere());
// 		die();
		
		$rResultTotal = $db->fetchAll ( $sQuery );
		if(false != $rResultTotal) {
			$iTotal = (int) $rResultTotal[0]['count'];
		} else {
			$iTotal = 0;
		}
		
		if ($sWhere != "") {
			$sQuery = "SELECT count(1) FROM   $sTable $sWhere";
			$rResultFilterTotal = $db->fetchAll ( $sQuery );
			if(false!=$rResultTotal) {
				$iFilteredTotal = (int) $rResultTotal[0]['count'];
			} else {
				$iFilteredTotal = 0;
			}
		} else {
			$iFilteredTotal = $iTotal;
		}
		
		/*
			* Output
			*/
		// "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal,
		$output = array (
				"sEcho" => intval ( $_GET ['sEcho'] ),
				"iTotalRecords" => $iTotal, 
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array ()
		);
		
		while ( $aRow = array_shift ( $rResult ) ) {
			$row = array ();
			for($i = 0; $i < count ( $aColumns ); $i ++) {
				if ($aColumns [$i] == "version") {
					/* Special output formatting for 'version' column */
					$row [] = ($aRow [$aColumns [$i]] == "0") ? '-' : $aRow [$aColumns [$i]];
				} else if ($aColumns [$i] != ' ') {
					/* General output */
					$row [] = $aRow [$aColumns [$i]];
				}
			}
			$output ['aaData'] [] = $row;
		}
		
		return json_encode ( $output );
	
	}
	function getSwhere() {
		return $this->sWhere;
	}
	function setSwhere($sWhere) {
		$this->sWhere = $sWhere;
	}
}