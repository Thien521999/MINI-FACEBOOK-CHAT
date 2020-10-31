<?php
	//insert_chat.php:để chèn tin nhắn trò chuyện vào cơ sở dữ liệu mysql
	session_start();
	include('database_connection.php');

	$data = array(
					':to_user_id'		=>	$_POST['to_user_id'],
					':from_user_id'		=>	$_SESSION['user_id'],
					':chat_message'		=>	$_POST['chat_message'],
					':status'			=>	'1'
				 ); 

	$query = "
				INSERT INTO chat_message 
				(to_user_id, from_user_id, chat_message, status) 
				VALUES (:to_user_id, :from_user_id, :chat_message, :status)
			 ";

	$statement = $connect->prepare($query);

	if($statement->execute($data))//nếu đúng
	{
		//chat group message=>tra ve doan chat cua 1 user
		echo fetch_user_chat_history($_SESSION['user_id'], $_POST['to_user_id'], $connect);
	}
?>