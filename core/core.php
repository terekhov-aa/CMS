<?php
include "config.php";
class Core 
{
	private $modules;
	private $events;
	private $dbh;

	function __construct()
	{	
		$this->modules = array();
		$this->events = array();
		global $config;

		$this->dbh = new PDO('mysql:host=localhost;dbname='.$config['db_name'], $config['db_user'], $config['db_pass']);
		$this->dbh->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
		$sql = "CREATE TABLE plugincontent (
    	id int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    	pluginame text NOT NULL,
    	pluginpath text NOT NULL,
    	inclusion INT( 2 ) NULL DEFAULT  '0'
    	)";
    	$this->dbh->exec($sql);
    	global $core;
    	global $dbh;
    	$core = &$this;
    	$dbh = &$this->dbh;
	}

	function pageGiver()
	{
		if(!isset($_POST['module']) && !isset($_POST['event']))
		{
			if(isset($_GET['admin']))
			{
				include "layoutit/admin/index.html";
			}
			else
			{
				echo "string";
			}
		}
	}

	function getDBH()
	{
		return $this->dbh;
	}

	function AddModule($module_name, $exemp)
	{
		$this->modules[$module_name] = $exemp;
	}


	function AddEvent($evnet, $name)
	{
		$this->events[$evnet][] = $name;		
	}

	function moduleController()
	{
		if(isset($this->modules[$_POST['module']]))
		{
			$this->modules[$_POST['module']]->moduleController();
		}
	}

	function eventController()
	{
		if(isset($this->events[$_POST['event']]))
		{
			// echo $_POST['event']."\n";
			//$this->events[$_POST['event']]->eventController();
			echo json_encode($this->events[$_POST['event']]);
		}
	}


	function find_plugin()
	{	
		$dir    = 'modules/';
		$files = scandir($dir);
		unset($files[array_search('.',$files)]);
		unset($files[array_search('..',$files)]);
		//print_r($files);
		$viborka="SELECT * FROM `plugincontent`";
		$sth = $this->dbh->prepare($viborka);
		$sth->execute();
		$pluginarray = array();
		$pluginarray1 = array();

		while ($result = $sth->fetch(PDO::FETCH_ASSOC)) 
		{
			$pluginarray[$result['pluginame']]=$result;
		}
		// print_r($pluginarray);
		foreach ($files as $key => $value) 
		{
			$pluginpath='modules/'.$value.'/'.$value.'.php';
			// echo $value;
			// echo "<br>";
			if (isset($pluginarray[$value])) {
				if ($pluginarray[$value]["inclusion"]==true) {
					// echo $value." подключен<br>";
					include $pluginpath;
					//$core->modules[$value]->register();
				}
				else{
			    	// echo $value."  найден"."<br>";
				}
			}
			else
			{
				$plugininsert="INSERT INTO `plugincontent`(`pluginame`, `pluginpath`) VALUES ('".$value."','".$pluginpath."')";
				$this->dbh->exec($plugininsert);
				// echo $value."  был добавлен"."<br>";
			}
		}
		$pluginarray = array_diff_key($pluginarray, array_flip($files));
		foreach ($pluginarray as $key => $value) {
			// echo $key." ";
			// echo  $value."<br>";
			// echo "DELETE FROM `plugincontent` WHERE `pluginame`='".$value."'";
			// echo $value."  был удален"."<br>";
			$plugindelete="DELETE FROM `plugincontent` WHERE `pluginame`='".$key."'";
			$this->dbh->exec($plugindelete);

		}
	}
}
?>