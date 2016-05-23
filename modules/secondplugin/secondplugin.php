<?php
/**
* 
*/

global $core;

class secondplugin //implements iPlugin
{	

	private $action;
	private $events;
	private $plugin_button;

	function __construct()
	{
		// echo "firstplugin __construct <br>";
		$this->action = array();
		$this->AddAction('event','eventController');
		$this->AddAction('lol','secondplugin');
		$this->events = array();
		$this->AddEvent('OnAdmMenuLoad','OnAdmMenuLoad');
		

	}

	function secondplugin(){
		//echo "first plugin mother fucker!!!";
		$arrayName = array('qwer' => 'second plugin mother fucker!!!');
		echo json_encode($arrayName);
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
		$this->plugin_button = array('name' => 'Второй', 'action' => 'lol');
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

$core->AddModule('secondplugin',new secondplugin);
$core->AddEvent('OnAdmMenuLoad','secondplugin');
// $this->modules['firstplugin'] = new firstplugin;
?>