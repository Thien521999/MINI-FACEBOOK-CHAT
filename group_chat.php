<?php
	//group_chat.php
	include('database_connection.php');

	session_start();
	//$ _POST ["action"] == 'insert_data' thì khối mã đó sẽ chèn tin nhắn trò chuyện nhóm vào bảng Mysql 
	if($_POST["action"] == "insert_data")//chen data
	{
		$data = array(
			':from_user_id'		=>	$_SESSION["user_id"],
			':chat_message'		=>	$_POST['chat_message'],
			':status'			=>	'1'
			//':img'				=> $_POST['img'] //xem lai
		);

		$query = "
					INSERT INTO chat_message 
					(from_user_id, chat_message, status) 
					VALUES (:from_user_id, :chat_message, :status)
				";

		$statement = $connect->prepare($query);
		if($statement->execute($data))//neu dung
		{
			echo fetch_group_chat_history($connect);//in ra 1 dong chat cua group
		}
	}
	//$ _POST ["action"] == 'fetch_data' thì khối mã đó sẽ tìm nạp tất cả nhóm tin nhắn 
	//trò chuyện từ cơ sở dữ liệu Mysql.
	if($_POST["action"] == "fetch_data")//lay data
	{
		echo fetch_group_chat_history($connect);//in ra 1 dong chat cua group
	}

?>