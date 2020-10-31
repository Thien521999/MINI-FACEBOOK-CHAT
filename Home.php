<?php
	//background
	session_start();
	error_reporting(1);
	if(isset($_SESSION['email']))
	{
		 include("background.php");
		 include("connection.php");
		 
		 $mail=$_SESSION['email'];
		 //echo "<script>alert('$mail')</script>";
		 $check_email="select * from users where user_email='$mail'";
		 $query=mysqli_query($con,$check_email);
		 $row=mysqli_fetch_assoc($query);//tra ve 1 mang
		 $user_id=$row[0];
 
		 $check_user_id = "select * from comment where id='$user_id' ";
		 $comments=mysqli_fetch_assoc($con,$check_user_id);
		 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home</title>
	<link  href="./css/style2.css" rel="stylesheet">
	<!-- <link href="Home_css/Home.css" rel="stylesheet" type="text/css"> -->
	<script src="home.js" ></script>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>

	<link rel="stylesheet" href="css/emojionearea.min.css">
   <script src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="js/emojionearea.min.js"></script>


    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<style>
		body{margin:0;padding:0;}
		#post_message {
			width:40%;
			position:absolute;
			left:30%;
			top:8%;
		}
		.message-box{margin-bottom:20px;border-top:#F0F0F0 5px solid;background:#FAF8F8;padding:10px;}
		.btnEditAction{background-color:#2FC332;border:0;padding:2px 10px;color:#FFF;}
		.btnDeleteAction{background-color:#D60202;border:0;padding:2px 10px;color:#FFF;margin-bottom:15px;}
		.hidden{visibility:hidden;}
		#frmAdd{margin-bottom:20px;border-top:#F0F0F0 5px solid;background:#CCC3;padding:10px;}
		.select_image{color: rgba(0, 0, 0, 0.71);margin-left: 190px;}
		#image_preview{margin-top:10px;left: 100px;text-align: center;overflow: auto;}
		#file {color: red;padding: 5px;border: 5px solid #8BF1B0;background-color: #8BF1B0;margin-top: 10px;border-radius: 5px;box-shadow: 0 0 15px #626F7E;margin-left: 15%;width: 70%;}
		#btnUploadAction{font-size: 16px;background: linear-gradient(#ffbc00 5%, #ffdd7f 100%);border: 1px solid #e5a900;color: #4E4D4B;font-weight: bold;cursor: pointer;width: 70%;border-radius: 5px;padding: 10px 0;outline: none;margin-top: 20px;margin-left: 15%;}
		#btnUploadAction:hover{background: linear-gradient(#ffdd7f 5%, #ffbc00 100%);}

		#txtmessage{
			margin-left:34px;
		}
	</style>

	<script>
		/*function time_get()
		{
			d = new Date();
			mon = d.getMonth()+1;
			time = d.getDate()+"-"+mon+"-"+d.getFullYear()+" "+d.getHours()+":"+d.getMinutes();
			posting_txt.txt_post_time.value=time;
		}
		function time_get1()
		{
			d = new Date();
			mon = d.getMonth()+1;
			time = d.getDate()+"-"+mon+"-"+d.getFullYear()+" "+d.getHours()+":"+d.getMinutes();
			posting_pic.pic_post_time.value=time;
		}*/
	</script>

</head>
<body>	
<!-- Post message -->
<div id="post_message">
	<div id="comment-list-box" >
		<?php
			if(!empty($comments)) {
				foreach($comments as $k=>$v) {
		?>
		<div class="message-box"  id="message_<?php echo $comments[$k]["id"];?>" >  
				<div>
					<button class="btnEditAction" name="edit" onClick="showEditBox(this,<?php echo $comments[$k]["id"]; ?>)">Edit</button>
					<button class="btnDeleteAction" name="delete" onClick="deleteMessage(<?php echo $comments[$k]["id"]; ?>)">Delete</button>
				</div>
				<div class="message-content">
					<?php 
						if($comments[$k]["imagepath"] != '') { ?>
							<div class="message-image">
								<?php echo "<img src='" . $comments[$k]["imagepath"] . "' height='100px' width='auto'><br />"; ?>
							</div>
						<br />
					<?php	} ?>
					<div class="message-text">
						<?php
							echo $comments[$k]["message"];
						?>
					</div>
				</div>
		</div>
		<?php
			}
		} ?>
	</div>

	<div id="frmAdd" >
		<form id="uploadimage" action="" method="post" enctype="multipart/form-data" >
			<textarea name="txtmessage"  id="txtmessage" cols="70" rows="5"></textarea>
			<span class="hidden"><input type="text" name="action" value="add"></span>
			<div id="image_preview">
				<img id="previewing" src="img/noimage.png" height="100px" width="auto"/>
			</div>	
			<hr id="line">
			<label class="select_image">Select Your Image</label><br/>
			<input type="file" name="file" id="file" /><br />
			<button id="btnUploadAction" name="submit">Upload</button>
		</form>   
	</div>
	<div id="message"></div>
	<img src="img/LoaderIcon.gif" id="loaderIcon" style="display:none" />
</div>
<script>
	$(document).ready(function (e) {
		//Xử lý khi nhấn nút(Upload) submit form “uploadimage”
		$("#uploadimage").on('submit',(function(e) {
			e.preventDefault();//Ngăn chặn một liên kết theo URL
			$("#message").empty();//Xóa thông tin message để cập nhật tin mới
			$('#loaderIcon').show();//Hiển thị ảnh chữa loading
			jQuery.ajax({
				url: "ajax.php", 		  // File mà data được gửi đến
				type: "POST",             // Phương thức gửi
				data: new FormData(this), // Dữ liệu gửi lên máy chủ(dạng foem)
				contentType: false,       
				cache: false,             
				processData:false,        
				success: function(result)   // Hàm sẽ được thực hiện nếu request thành công, giá trị trả về gán vào biên result
				{
					$('#loaderIcon').hide();//ẩn ảnh load
					$('#comment-list-box').append(result);//them du lieu vao the div
					$("#txtmessage").val('');
					$("#file").val('');
					$('#previewing').attr('src', 'noimage.png');
				}
			});	
		}));

		//Xử lý khi nút file nhấn và đã chọn được file từ máy tính(Hàm thay đỏi ảnh khi chọn file).
		$(function() {
			$("#file").change(function() {
				var file = this.files[0];
				var imagefile = file.type;
				var match= ["image/jpeg","image/png","image/jpg","image/gif"];
				if(!( (imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]) || (imagefile==match[3]) ))
				{
					$('#previewing').attr('src','noimage.png');//.attr("attribute-name","new-value"):gan noi dung  cho thuoc tinh
					$("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg, gif and png Images type allowed</span>");
					//<div id="message"></div>(dong 63)
					return false;
				}
				else
				{
					$("#message").html('');
					var reader = new FileReader();
					reader.onload = imageIsLoaded;
					reader.readAsDataURL(this.files[0]);
				}
			});
		});
		//Hàm load ảnh
		function imageIsLoaded(e) {
			$("#file").css("color","green");//#file :dong 59
			$('#previewing').attr('src', e.target.result);//#previewing:dong 56
			$('#previewing').attr('width', 'auto');
			$('#previewing').attr('height', '100px');
		};
	});

	// Hàm hiển thị nút 'Edit' box
	function showEditBox(editobj,id) {
		$('#frmAdd').hide();
		$(editobj).css('disabled','true');//vô hiệu hoá một button
		var currentMessage = $("#message_" + id + " .message-text").html().trim();
		var queryString = 'id=' + id + '&currentMessage=' + currentMessage;
		jQuery.ajax({
			url: "ajax_edit.php",
			type: "POST",
			data: queryString,
			success:function(data) {
				$("#message_" + id + " .message-text").html(data);
			},
			error:function (){}
		});
	}

	// Hàm xóa post(nút 'delete')
	function deleteMessage(id) {
		$('#loaderIcon').show();
		var action = "delete";
		var queryString = 'action='+action+'&message_id='+ id;
		jQuery.ajax({
		url: "ajax.php",
		type: "POST",
		data:queryString,
		success:function(){
			$('#message_'+id).fadeOut();//Ẩn các thành phần phù hợp với hiệu ứng mờ dần (fade).
			$("#txtmessage").val('');//gan giá trị của thành phần.vd:$('input').val();thành phần thẻ là input.gán rong cho #txtmessage
			$("#loaderIcon").hide();
			$('#frmAdd').show();
		},
		error:function (){}
		});
	}
	// Hàm lưu post mới sau khi chỉnh sửa.Nút 'Save'
	function saveEditMessage(obj) {
		$('#loaderIcon').show();
		jQuery.ajax({
			url: "ajax.php", 
			type: "POST",            
			data: new FormData(),
			success:function(data){
				$("#message_" + id + " .message-text").html(data);
				$('#frmAdd').show();	
				$("#txtmessage").val('');//#txtmessage:dong 54
				$("#loaderIcon").hide();
			},
			error:function (){}
		});
	}

	//nút Update Status
	function upload_close()
	{
		document.getElementById("post_txt").style.display='block';
		document.getElementById("post_pic").style.display='none';
	}

	function upload_open()
	{
		document.getElementById("post_pic").style.display='block';
		document.getElementById("post_txt").style.display='none';
	}

	function blank_post_check()
	{
		var post=document.posting_txt.post_txt.value;
		if(post=="")
		{
			return false;
		}
		return true;
	}
					
	function Img_check()
	{
		var file = document.getElementById('img');
		var fileName = file.value;
		var ext = fileName.slice(fileName.length-4,fileName.length);//hàm slice sẽ tạo ra một array mới chứ không hề chỉnh sửa trên đối tượng array gọi đến nó.
		if(fileName=="")
		{
			return false;
		}
		else
		{
			if(ext!=".jpg" && ext!=".JPG" && ext!=".png" && ext!=".PNG" && ext!=".gif" && ext!=".GIF" && ext!=".jpeg" && ext!=".JPEG")
			{
				document.getElementById("photo_erorr").style.display='block';
				document.getElementById("body").style.overflow="hidden";
				return false;
			}
		}
		document.getElementById("photo_erorr").style.display='none';
		document.getElementById("body").style.overflow="visible";
		return true;
	}
</script>

	<!--left hr-->
    <hr style="position:fixed; left:18%; top:4.8%; height:100%; width:0; border-color:#CCCCCC; box-shadow:-5px 0px 5px 0px rgb(0,0,0);z-index:1;">
    <!--end left hr -->

    <!--right hr-->
    <hr style="position:fixed; left:18%; top:4.8%; height:100%; width:0; border-color:#CCCCCC; box-shadow:-5px 0px 5px 0px rgb(0,0,0);z-index:1;">
    <!--end right hr-->

</body>
</html>

<?php
	}
?>


