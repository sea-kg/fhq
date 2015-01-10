<?php
	
	if (!isset($config)) {
		echo "NO!!!!";
		exit;
	}
	
	$security = new fhq_security();
	if (!$security->isAdmin()) {
		echo 'You are not admin!!!';
		exit;
	}

	$curdir = dirname(__FILE__);
	
	$arrqueries = array();
	$arrqueries[] = 'UPDATE quest SET state = "open" WHERE ISNULL(state);';
	$arrqueries[] = 'UPDATE quest SET description_state = "" WHERE ISNULL(description_state);';
	$arrqueries[] = 'ALTER TABLE `quest` DROP COLUMN `status`;';
	$arrqueries[] = 'ALTER TABLE `quest` DROP COLUMN `description_status`;';
	$arrqueries[] = 'ALTER TABLE `user` DROP COLUMN `score`;';

	
	$queryid = -1;
	if (isset($_GET['queryid']))
		$queryid = $_GET['queryid'];
	
	foreach ($arrqueries as $n => $val)
	{
		echo '<pre>'.$val.' <a href="javascript:void(0);" onclick="load_content_page(\'query_db\', { queryid: '.$n.'});">execute</a><br><br>';
		if ($queryid == $n)
			echo 'Result: '.$db->query($val).'<br>';
		echo '</pre>';
	}
?>


