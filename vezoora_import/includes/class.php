<?php
function updateLog($message,$logFile)
{
	$fileOpen = fopen($logFile, 'w');
	fwrite($fileOpen, $message);
	fclose($fileOpen);
}
?>
