<?php
$worker= new GearmanWorker();
// $worker->addServer(); // defaults to localhost
$worker->addServers('192.168.1.1, 192.168.1.2');
$worker->addFunction('resize', 'resize_func');
while ($worker->work());
 
function resize_func($job)
{
	echo "Received task to resize image\n";

	$thumb = new Imagick();

	$workload = unserialize($job->workload());
	$fullpath = $workload['path'];
	$name     = basename($workload['name'], '.jpg');

	$path = 'images/';
	$names = array(
			'original' => $path . $name . '.jpg', 
			'half'     => $path . $name . '_half.jpg', 
			'thumb'    => $path . $name . '_thumb.jpg'
		);

	$thumb->readImage($fullpath);
	$height = $thumb->getImageHeight();
	$width  = $thumb->getImageWidth();

	// original
	$thumb->setImageFilename($names['original']);
	$thumb->writeImage();

	// half
	$thumb->scaleImage($height / 2, $width / 2);
	$thumb->setImageFilename($names['half']);
	$thumb->writeImage();

	// thumb
	$thumb->scaleImage(50,0);
	$thumb->setImageFilename($names['thumb']);
	$thumb->writeImage();


	return serialize($names);
}
