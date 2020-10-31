<?php
	require_once("DBController.php");
	$db_handle = new DBController();
?>
<?php
// Lấy giá trị của action
$action = $_POST["action"];

// Kiểm tra xem có action hay ko
if(!empty($action)) {
	switch($action) {
		case "add":	//Nhan upload
			if(isset($_FILES["file"]["type"])) {//kieu file
				// Nếu có
				// Tạo mảng để kiểm tra loại của file
				$validextensions = array("jpeg", "jpg", "png", "gif");
				// Cắt chuỗi tên file bằng dáu .
				$temporary = explode(".", $_FILES["file"]["name"]);//explode:cat chuoi thanh mang
				// Lấy giá trị cuối của mảng temporary ( type của file )
				$file_extension = end($temporary);
				// Kiểm tra type của file có phải dạng png, jpg hay jpeg không, giới hạn đọ lớn file gửi lên, kiểm tra lần nữa xem type của file có nằm trong mảng validextensions không
				if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg"))
				&& in_array($file_extension, $validextensions)) {
					// Nếu file có nhiều hơn 0 lỗi
					if ($_FILES["file"]["error"] > 0) {//thong bao loi khi upload
						// In ra lỗi đó
						echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
					}
					// Ngược lại
					else {
						// Kiểm tra trong thư mục upload có file vừa được gửi lên không
						if (file_exists("upload/" . $_FILES["file"]["name"])) {//$_FILES["file"]["name"]):ten file upload len server
							// Nếu có, in tên file cùng thông báo file đã tồn tại
							echo $_FILES["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
						}
						//Ngược lại
						else {
							// Lấy đường dãn file tạm
							$sourcePath = $_FILES['file']['tmp_name']; 
							// Tao duong dẫn file đến thư mục upload
							$targetPath = "upload/".$_FILES['file']['name']; 
							// Chuyển file từ thư mục tạm sang thư mục upload
							move_uploaded_file($sourcePath,$targetPath);//upload tap tin sourcePath vào vị trí targetPath
							// Tạo câu lệnh truy vấn thêm dữ liệu
							$query = "INSERT INTO comment(message,imagepath) VALUES('".$_POST["txtmessage"]."','".$targetPath."')";
		    				$insert_id = $db_handle->insert($query);
							// Nếu Truy vấn thành công, Tạo một message post
							if($insert_id){
								echo "<div class='message-box'  id='message_" . $insert_id . "'>";
								echo 	"<div>";
								echo 		"<button class='btnEditAction' name='edit' onClick='showEditBox(this," . $insert_id . ")'>Edit</button>";
								echo 		"<button class='btnDeleteAction' name='delete' onClick='deleteMessage(" . $insert_id . ")'>Delete</button>";
								echo 	"</div>";
								echo 	"<div class='message-content'>";
								echo 		"<div class='message-image'>";
								echo 			"<img src='" . $targetPath . "' height='100px' width='auto'/>";
								echo 		"</div>";
								echo 		"<br />";
								echo 		"<div class='message-text'>";
												$string = $_POST['txtmessage'];
								echo 			$string;
								echo 		"</div>";
								echo 	"</div>";
								echo "</div>";
							}
						}
					}
				}
				// Ngược lại, nếu file không phải là ảnh
				else {
					//Thực hiện truy vấn với giá trị cột imagepath là chuỗi rỗng( không phải null )
					$query = "INSERT INTO comment(message,imagepath) VALUES('".$_POST["txtmessage"]."', '')";
					$insert_id = $db_handle->insert($query);
					if($insert_id){
						echo "<div class='message-box'  id='message_" . $insert_id . "'>";
						echo 		"<div>";
						echo 			"<button class='btnEditAction' name='edit' onClick='showEditBox(this," . $insert_id . ")'>Edit</button>";
						echo 			"<button class='btnDeleteAction' name='delete' onClick='deleteMessage(" . $insert_id . ")'>Delete</button>";
						echo 		"</div>";
						echo 		"<div class='message-content'>";
						echo 			"<div class='message-text'>";
											$string = $_POST['txtmessage'];
						echo 				$string;
						echo 			"</div>";
						echo 		"</div>";
						echo "</div>";
					}
				}
			}
			break;
				
		case "edit": // Nếu action là edit
			if(!empty($_POST["message_id_edit"])) {
				if(isset($_FILES["file"]["type"])) {
					$validextensions = array("jpeg", "jpg", "png", "gif");
					$temporary = explode(".", $_FILES["file"]["name"]);
					$file_extension = end($temporary);
					if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")) && in_array($file_extension,$validextensions)) {
						if ($_FILES["file"]["error"] > 0) {
							echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
						}
						else {
							if (file_exists("upload/" . $_FILES["file"]["name"])) {
								echo $_FILES["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
							}
							else {
								$query = "SELECT * FROM comment WHERE id=".$_POST["message_id_edit"];
								$result = $db_handle->runQuery($query);
								if(!empty($result)) {
									foreach($result as $k=>$v) {
										if(!empty($result[$k]["imagepath"]))
											unlink($result[$k]["imagepath"]);//unlink() sẽ xóa file dựa vào đường dẫn đã truyền vào.
									}
								}
								$sourcePath = $_FILES['file']['tmp_name']; 
								$targetPath = "upload/".$_FILES['file']['name']; 
								move_uploaded_file($sourcePath,$targetPath);
								$query = "UPDATE comment set message = '".$_POST["txtedit"]."', imagepath = '".$targetPath."' WHERE  id=".$_POST["message_id_edit"];
								$result = $db_handle->execute($query);
								if($result) {
									echo "<div>";
									echo 	"<button class='btnEditAction' name='edit' onClick='showEditBox(this," . $_POST["message_id_edit"] . ")'>Edit</button>";
									echo 	"<button class='btnDeleteAction' name='delete' onClick='deleteMessage(" . $_POST["message_id_edit"] . ")'>Delete</button>";
									echo "</div>";
									echo "<div class='message-content'>";
									echo 	"<div class='message-image'>";
									echo 		"<img src='" . $targetPath . "' height='100px' width='auto'/>";
									echo 	"</div>";
									echo 	"<br />";
									echo 	"<div class='message-text'>";
												$string = $_POST["txtedit"];
									echo 		$string;
									echo 	"</div>";
									echo "</div>";
								}
							}
						}
					}
					else {
						if(isset($_POST["check"])) {
							$query = "SELECT * FROM comment WHERE id=".$_POST["message_id_edit"];
							$result = $db_handle->runQuery($query);
							if($result) {
								foreach($result as $k=>$v) {
									if(empty($result[$k]["imagepath"])) {
										$query = "UPDATE comment set message = '".$_POST["txtedit"]."' WHERE  id=".$_POST["message_id_edit"];
										$result = $db_handle->execute($query);
										if($result) {	
											$query = "SELECT * FROM comment WHERE id=".$_POST["message_id_edit"];
											$result = $db_handle->runQuery($query);
											if($result) {
												foreach($result as $k=>$v) {
													echo "<div>";
													echo "<button class='btnEditAction' name='edit' onClick='showEditBox(this," . $_POST["message_id_edit"] . ")'>Edit</button>";
													echo "<button class='btnDeleteAction' name='delete' onClick='deleteMessage(" . $_POST["message_id_edit"] . ")'>Delete</button>";
													echo "</div>";
													echo "<div class='message-content'>";
													echo "<div class='message-text'>";
													echo $result[$k]["message"];
													echo "</div>";
													echo "</div>";
												}	
											}	
										}
									}
									else {
										$query = "UPDATE comment set message = '".$_POST["txtedit"]."' WHERE  id=".$_POST["message_id_edit"];
										$result = $db_handle->execute($query);
										if($result) {	
											$query = "SELECT * FROM comment WHERE id=".$_POST["message_id_edit"];
											$result = $db_handle->runQuery($query);
											if($result) {
												foreach($result as $k=>$v) {
													echo "<div>";
													echo "<button class='btnEditAction' name='edit' onClick='showEditBox(this," . $_POST["message_id_edit"] . ")'>Edit</button>";
													echo "<button class='btnDeleteAction' name='delete' onClick='deleteMessage(" . $_POST["message_id_edit"] . ")'>Delete</button>";
													echo "</div>";
													echo "<div class='message-content'>";
													echo "<div class='message-image'>";
													echo "<img src='" . $result[$k]["imagepath"] . "' height='100px' width='auto'/>";
													echo "</div>";
													echo "<br />";
													echo "<div class='message-text'>";
													echo $result[$k]["message"];
													echo "</div>";
													echo "</div>";
												}	
											}	
										}
									}
								}
							}
						}
						else {
							$query = "SELECT * FROM comment WHERE id=".$_POST["message_id_edit"];
							$result = $db_handle->runQuery($query);
							if(!empty($result)) {
								foreach($result as $k=>$v) {
									if(!empty($result[$k]["imagepath"]))
										unlink($result[$k]["imagepath"]);
								}
							}
							$query = "UPDATE comment set message = '".$_POST["txtedit"]."', imagepath = '' WHERE  id=".$_POST["message_id_edit"];
							$result = $db_handle->execute($query);
							if($result) {
								echo "<div>";
								echo "<button class='btnEditAction' name='edit' onClick='showEditBox(this," . $_POST["message_id_edit"] . ")'>Edit</button>";
								echo "<button class='btnDeleteAction' name='delete' onClick='deleteMessage(" . $_POST["message_id_edit"] . ")'>Delete</button>";
								echo "</div>";
								echo "<div class='message-content'>";
								echo "<div class='message-text'>";
								$string = $_POST["txtedit"];
								echo $string;
								echo "</div>";
								echo "</div>";
							}	
						}
					}
				}
			}
			break;
		
		
		
		case "delete": // Nếu action là delete
			// Kiểm tra giá trị của id được gửi đến
			if(!empty($_POST["message_id"])) {
				// Thực hiện truy vấn để lấy giá trị 'cọt imagepath', sau đó xóa file ảnh ở thưc mục upload
				$query = "SELECT * FROM comment WHERE id=".$_POST["message_id"];
				$result = $db_handle->runQuery($query);
				if(!empty($result)) {
					foreach($result as $k=>$v) {
						unlink($result[$k]["imagepath"]);
					}
				}
				// Thực hiện truy vấn để xóa 'dòng có id' tương ứng với id được gửi đến
				$query = "DELETE FROM comment WHERE id=".$_POST["message_id"];
				$result = $db_handle->execute($query);
			}
			break;	
	}
}
?>