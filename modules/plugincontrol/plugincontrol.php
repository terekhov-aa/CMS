<?php
/**
* 
*/

global $core;
global $dbh;


class plugincontrol //implements iPlugin
{	

	private $action;
	private $events;
	private $plugin_button;

	function __construct()
	{
		// echo "firstplugin __construct <br>";
		$this->action = array();
		$this->AddAction('event','eventController');
		$this->AddAction('run_control','plugincontrol');
		$this->AddAction('run_update','update_db');
		$this->AddAction('remove_folder','remove_folder');
		$this->events = array();
		$this->AddEvent('OnAdmMenuLoad','OnAdmMenuLoad');
	}

	function remove_folder(){
		$dir    = 'modules/';
		$files = scandir($dir);
		unset($files[array_search('.',$files)]);
		unset($files[array_search('..',$files)]);
		
	}

	function plugincontrol(){
		// $dbh = $core->dbh;
		//echo "first plugin mother fucker!!!";
		// $arrayName = array('qwer' => 'plugincontrol mozer fuka!!');
		// echo json_encode($arrayName);
		//CASE WHEN ValueColumn IS NULL THEN 'FALSE' ELSE 'TRUE' END BooleanOutput
		global $dbh;

		$sql = "SELECT * FROM `plugincontent` ";
		$sth = $dbh->prepare($sql);
		$sth->execute();
		while ($result = $sth->fetch(PDO::FETCH_ASSOC)) 
		{
			$result['inclusion'] = ($result['inclusion']==1)?true:false;
			$pluginarray[$result['pluginame']]=$result;
		}
		 echo json_encode($pluginarray);
	}

	function update_db(){
		global $dbh;
		$inclusion = $_POST['inclusion']=='true'?1:0;
		$id = intval($_POST['id']);
		$sql = "UPDATE `plugincontent` SET `inclusion`='$inclusion' WHERE `id`='$id' AND `pluginame` != 'plugincontrol'";
		echo $sql."\n";
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
		$this->plugin_button = array('name' => 'Плагины', 'action' => 'run_control');
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

$core->AddModule('plugincontrol',new plugincontrol);
$core->AddEvent('OnAdmMenuLoad','plugincontrol');
// $this->modules['firstplugin'] = new firstplugin;
?>