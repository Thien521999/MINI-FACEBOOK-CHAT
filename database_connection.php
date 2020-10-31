<?php

//database_connection.php

$connect = new PDO("mysql:host=localhost; dbname=facebookchatnew; charset=utf8mb4", "root", "");

date_default_timezone_set('Asia/Kolkata');

//Tra về 1 mang=>lấy phần tử $row['last_activity'] //thoi gian hoat dong
function fetch_user_last_activity($id, $connect)
{
	$query = "SELECT * FROM user_details WHERE id = '$id' ORDER BY last_activity DESC LIMIT 1";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row['last_activity'];
	}
}

//Chức năng này lấy tin nhắn trò chuyện mới nhất từ ​​cơ sở dữ liệu mysql và trả về dữ liệu ở định dạng html.
//(Nhớ nếu status=2 thì tin nhắn đã bị xóa và status=1 không xoa)
function fetch_user_chat_history($from_user_id, $to_user_id, $connect)
{
	$query = "
				SELECT * FROM chat_message 
				WHERE (from_user_id = '".$from_user_id."' 
				AND to_user_id = '".$to_user_id."') 
				OR (from_user_id = '".$to_user_id."' 
				AND to_user_id = '".$from_user_id."') 
				ORDER BY timestamp DESC
			 ";

	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();//tra ve 1 mang
	$output = '<ul class="list-unstyled">';
	foreach($result as $row)
	{
		$user_name = '';		 //ten cua user
		$dynamic_background = '';//mau cua daon chat
		$chat_message = '';		 //noi dung chat
		if($row["from_user_id"] == $from_user_id)
		{
			if($row["status"] == '2')//bi xoa
			{
				$chat_message = '<em>This message has been removed</em>';
				$user_name = '<b class="text-success">You</b>';
			}
			else
			{
				$chat_message = $row['chat_message'];
				$user_name = '<button type="button" class="btn btn-danger btn-xs remove_chat" id="'.$row['chat_message_id'].'">
								x
							  </button>&nbsp;
							  <b class="text-success">You</b>';
			}
			
			$dynamic_background = 'background-color:#ffe6e6;';//mau cua doan chat
		}
		else
		{
			if($row["status"] == '2')
			{
				$chat_message = '<em>This message has been removed</em>';
			}
			else
			{
				$chat_message = $row["chat_message"];
			}
			$user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $connect).'</b>';
			$dynamic_background = 'background-color:#ffffe6;';//mau cua doan chat
		}
		$output .= '
		<li style="border-bottom:1px dotted #ccc;padding-top:8px; padding-left:8px; padding-right:8px;'.$dynamic_background.'">
			<p>'.$user_name.' - '.$chat_message.'
				<div align="right">
					- <small><em>'.$row['timestamp'].'</em></small>
				</div>
			</p>
		</li>
		';
	}
	$output .= '</ul>';
	$query = "
				UPDATE chat_message SET status = '0' 
				WHERE from_user_id = '".$to_user_id."' 
				AND to_user_id = '".$from_user_id."' 
				AND status = '1'
			 ";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $output;
}

//lấy tên của user
function get_user_name($id, $connect)
{
	$query = "SELECT username FROM users WHERE id = '$id'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row['username'];
	}
}

//nhận tin nhắn trò chuyện, số tin nhan chưa được đọc trong bảng tin nhắn trò 
//chuyện cho người dùng 
//==>dem so tin nhan =>tra ve so tin nhan.
//Ở đây trạng thái(status) '1' có nghĩa là tin nhắn chưa đọc 
//và khi tin nhắn đã được đọc thì trạng thái sẽ chuyển thành '0'.
/*function count_unseen_message($from_user_id, $to_user_id, $connect)
{
	$query = "
	SELECT * FROM chat_message 
	WHERE from_user_id = '$from_user_id' 
	AND to_user_id = '$to_user_id' 
	AND status = '1'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$count = $statement->rowCount();
	$output = '';
	if($count > 0)
	{
		$output = '<span class="label label-success">'.$count.'</span>';
	}
	return $output;
}*/

//cho biết là is_type: đang nhập tin nhan va ko nhap tin nhan=>xuat ra html Typing...
/*function fetch_is_type_status($id, $connect)
{
	$query = "
				SELECT is_type FROM user_details WHERE id = '".$id."' 
				ORDER BY last_activity DESC 
				LIMIT 1
			 ";	
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		if($row["is_type"] == 'yes')
		{
			$output = ' - <small><em><span class="text-muted">Typing...</span></em></small>';
		}
	}
	return $output;
}*/

//Tra ve doan chat cua GROUP(Nhớ nếu status=2 thì tin nhắn đã bị xóa )
function fetch_group_chat_history($connect)
{
	$query = "
				SELECT * FROM chat_message 
				WHERE to_user_id = '0'  
				ORDER BY timestamp DESC
			 ";

	$statement = $connect->prepare($query);

	$statement->execute();

	$result = $statement->fetchAll();//tra ve 1 mang

	$output = '<ul class="list-unstyled">';
	foreach($result as $row)//duyet
	{
		$user_name = '';
		$dynamic_background = '';
		$chat_message = '';
		if($row["from_user_id"] == $_SESSION["user_id"])
		{
			if($row["status"] == '2')
			{
				$chat_message = '<em>This message has been removed</em>';
				$user_name = '<b class="text-success">You</b>';
			}
			else
			{
				$chat_message = $row["chat_message"];
				$user_name = '<button type="button" class="btn btn-danger btn-xs remove_chat" id="'.$row['chat_message_id'].'">
								x
							  </button>&nbsp;<b class="text-success">You</b>';
			}
			$dynamic_background = 'background-color:#ffe6e6;';
		}
		else
		{
			if($row["status"] == '2')
			{
				$chat_message = '<em>This message has been removed</em>';
			}
			else
			{
				$chat_message = $row["chat_message"];
			}
			$user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $connect).'</b>';
			$dynamic_background = 'background-color:#ffffe6;';
		}

		$output .= '
		<li style="border-bottom:1px dotted #ccc;padding-top:8px; padding-left:8px; padding-right:8px;'.$dynamic_background.'">
			<p>'.$user_name.' - '.$chat_message.' 
				<div align="right">
					- <small><em>'.$row['timestamp'].'</em></small>
				</div>
			</p>
		</li>
		';
	}
	$output .= '</ul>';
	return $output;
}
?>

<style>	
	ul > li{
		list-style:none;
	}
</style>