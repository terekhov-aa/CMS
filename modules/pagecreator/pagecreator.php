<?php
/**
* 
*/

global $core;

class pagecreator //implements iPlugin
{	

	private $action;
	private $events;
	private $plugin_button;

	function __construct()
	{
		// echo "firstplugin __construct <br>";
		$this->action = array();
		$this->AddAction('event','eventController');
		$this->AddAction('run_send_content','send_content');
		$this->AddAction('run_page_control','page_control');
		$this->AddAction('run_change_iclusion','change_iclusion');
		$this->events = array();
		$this->AddEvent('OnAdmMenuLoad','OnAdmMenuLoad');
		$this->create_table();
	}

	function create_table(){
		$sql = "CREATE TABLE pages (
    	id int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    	pagename text NOT NULL,
    	pagecontent text NOT NULL,
    	inclusion INT( 2 ) NULL DEFAULT  '0'
    	)";
    	global $dbh;
    	$dbh->exec($sql);
	}

	function change_iclusion(){
		global $dbh;
		$inclusion = $_POST['inclusion']=='true'?1:0;
		$id = intval($_POST['id']);
		$sql = "UPDATE `pages` SET `inclusion`='$inclusion' WHERE `id`='$id'";
		// echo $sql."\n";
		$sth = $dbh->prepare($sql);
		$sth->execute();
	}

	function page_control(){
		global $dbh;

		$sql = "SELECT * FROM `pages` ";
		$sth = $dbh->prepare($sql);
		$sth->execute();
		while ($result = $sth->fetch(PDO::FETCH_ASSOC)) 
		{
			$result['inclusion'] = ($result['inclusion']==1)?true:false;
			$pluginarray[$result['pagename']]=$result;
		}
		 echo json_encode($pluginarray);
	}

	function send_content(){
		global $dbh;
		$sql = "INSERT INTO `pages`
		(`pagename`, `pagecontent`) 
		VALUES ('".$_POST['page_name']."', '".$_POST['page_content']."')";
		$sth = $dbh->prepare($sql);
		$sth->execute();
	}

	function moduleController(){
		if(isset($this->action[$_POST['action']]))
		{
			$funcName = $this->action[$_POST['action']];
			$this->$funcName();
		}
	}

	function eventController(){
		// echo "eventController\n";
		// print_r($this->events);
		if(isset($this->events[$_POST['eventName']]))
		{
			$funcName = $this->events[$_POST['eventName']];
			// echo $funcName."\n";
			$this->$funcName();
		}
	}

	function OnAdmMenuLoad(){
		$this->plugin_button = array('name' => 'Страницы', 'action' => 'load_pagecreator');
		echo json_encode($this->plugin_button);

	}

	function AddAction($name, $funcName)
	{
		$this->action[$name] = $funcName;
	}

	function AddEvent($name, $funcName)
	{
		$this->events[$name] = $funcName;
	}
}

$core->AddModule('pagecreator',new pagecreator);
$core->AddEvent('OnAdmMenuLoad','pagecreator');
// $this->modules['firstplugin'] = new firstplugin;
?>