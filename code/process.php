<?php
if ($_FILES['file']['error'] > 0)
{
	echo 'Error: ' . $_FILES['file']['error'] . '<br />';
}
else
{
	$name      = $_FILES['file']['name'];
	$type      = $_FILES['file']['type'];
	$fullpath  = $_FILES['file']['tmp_name'];

	if(file_exists($fullpath)) {
		$client= new GearmanClient();
		// $client->addServer(); // defaults to localhost
        $client->addServers('192.168.1.1, 192.168.1.2');
		
		$workload = serialize(array('path' => $fullpath, 'name' => $name));

		$ret = $client->do('resize', $workload);

		$resized = unserialize($ret);

		if(is_array($resized)) {
			foreach($resized as $image) {
				echo '<img src="' . $image . '"> ';
			}
		}
		else {
			echo 'uh oh something broke';
		}
	}
}
