<?php
	$id = $_POST["id"];
	$currentMessage = $_POST["currentMessage"];
	$aParams = array(
			'{id}' => $id,
			'{currnetMessage}' => $currentMessage,
    	);
	echo strtr(file_get_contents('form_edit.php'), $aParams);
?>