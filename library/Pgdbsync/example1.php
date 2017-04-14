<?php
include("lib/Pgdbsync/includes.php");
use \Pgdbsync;
$conf  = parse_ini_file("conf.ini", true);

$dbVc = new Pgdbsync\Db();
$dbVc->setMasrer(new Pgdbsync\DbConn($conf['devel']));
$dbVc->setSlave(new Pgdbsync\DbConn($conf['prod1']));

$result = $dbVc->run('public');
echo "<pre>";
var_dump($result);
//print_r($dbVc->diff('public')); // name of schema
echo "</pre>";

