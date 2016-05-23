<?php
	require_once "core/core.php";

	global $core;
	global $dbh;

	$core=new core;
	$dbh = $core->getDBH();
	$core->pageGiver();
	$core->find_plugin();
	$core->moduleController();
	$core->eventController();
?>