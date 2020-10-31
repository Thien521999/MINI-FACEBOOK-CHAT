<?php
    include('database_connection.php');
    
    require 'includes/init.php';
    if(isset($_SESSION['user_id']) && isset($_SESSION['email'])){
        $user_data = $user_obj->find_user_by_id($_SESSION['user_id']);//tra ve true/false
        if($user_data === false){
            header('Location: logout.php');
            exit;
        }
        // FETCH ALL USERS WHERE ID IS NOT EQUAL TO MY ID
        $all_users = $user_obj->all_users($_SESSION['user_id']);
    }
    else{
        header('Location: login.php');
        exit;
    }
    // TOTAL REQUESTS(số lời mời kết bạn)
    $get_req_num = $frnd_obj->request_notification($_SESSION['user_id'], false);
    // TOTAL FRIENDS(số friends)
    $get_frnd_num = $frnd_obj->get_all_friends($_SESSION['user_id'], false);
    // GET MY($_SESSION['user_id']) ALL FRIENDS
    $get_all_friends = $frnd_obj->get_all_friends($_SESSION['user_id'], true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Facebook</title>

    <link  href="./css/style2.css" rel="stylesheet">

    <script src="background/event.js"></script>
    <script src="background/options.js"></script>
    <!-- Thư viện Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>    
    
   <!--Thu vien Boostrap-->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"> 
   <!--end Boostrap-->

   <!-- thu vien icon -->
   <link rel="stylesheet" href="css/emojionearea.min.css">
   <script src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
   <script src="js/emojionearea.min.js"></script>

   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>
<body>

  <!--Head background-->
    <div style="position:fixed;left:0;top:0; height:6%; width:100%; z-index:1; background:#3B5998"></div>
  <!--end Head background-->

  <!--Head fb text-->
    <div style="position:fixed;left:5%;top:1.5%;font-size:20px;font-weight:900; z-index:2;"> 
        <a href="Home.php" style="color:#FFFFFF; text-decoration:none; " onMouseOver="on_head_fb_text()" onMouseOut="out_head_fb_text()"> 
            <font face="myFbFont"> Facebook </font>             
        </a> 
    </div>
  <!--end Head fb text-->

<!----------------------------------------Tim ban be-------------------------------------------------------->
    <form name="fb_search" action="Search_Display_submit.php" method="get" onSubmit="return bcheck()">
        <div style="position:fixed; left:11%; top:1.6%; z-index:1;"> 
            <input type="text" name="search1" style="height:25; width:300px;"  onKeyUp="searching();" id="search_text1" placeholder="Search for people" > 
        </div>
        
        <div id="searching_ID"></div> 

        <div style="position:fixed; left:31.2%;top:1.5%; z-index:1;">
            <input type="submit" value=" " style="background-image:url(img/search.png);">
        </div>
    </form>
<!--end Tim ban be-->
    
<!--Phan anh  va ten user-->
    <div style="position:fixed; left:71.8%; top:0.6%; z-index:1;">
        <table cellspacing="0">
            <tr id="headername2">
              <!--Anh dai dien-->
                <td style="padding-left:7;" id="head_img" onMouseOver="head_pro_pic_over()" onMouseOut="head_pro_pic_out()"> 
                    <img src="profile_images/<?php echo $user_data->user_image; ?>" style="height:25px; width:26px;" alt="Profile image"><!-----------anh dai dien------->
                </td>
              <!--Ten dai dien-->
                <td id="head_name_bg" onMouseOver="head_pro_pic_over()" onMouseOut="head_pro_pic_out()"> 
                    <?php echo  $user_data->username;?><!----------ten dai dien------->
                </td>

                <td style="color:#DEDEEF;"> | </td>
                <td id="head_home_bg" onMouseOver="head_home_over()" onMouseOut="head_home_out()"> 
                    <a href="Home.php" id="head_home_font" style="color:#DEDEEF; font-size:13; font-weight:900;
                    font-family:lucida Bright; text-decoration:none;"> &nbsp; Home &nbsp; </a> 
                </td>
                <td style="color:#DEDEEF;">|</td>
            </tr>
        </table>
    </div>
<!--end Phan anh  va ten user-->

<!--------------------------------------Phan setting-goc phải màn hình-------------------------------------------------------------->
    <div style="position:fixed; left:97%; top:0.4%; z-index:1;"> 
        <img src="img/nexusae0_home_settings_icon2.png" height="35" width="35" onClick="open_option()"> 
    </div>

    <div style="display:none" id="option">
    <!--Ảnh setting-->
        <div style="position:fixed; left:97%; top:0.4%; z-index:1;"> 
            <img src="img/nexusae0_home_settings_icon2.png" height="35" width="35" onClick="close_option()"> 
        </div>

    <!--timeline-->
        <div style="position:fixed; left:85%; top:6%; z-index:3; background:#FFF; height:32%; width:14.8%; 
        box-shadow:0px 2px 10px 1px rgb(0,0,0);"> </div>
        <div style="position:fixed; left:86%; top:8.5%; z-index:3;">
            <a href="../fb_profile/Profile.php"> 
                <img src="img/timeline.png" width="16" height="16" onMouseOver="head_timeline_over()" 
                onMouseOut="head_timeline_out()">
            </a>
        </div>
        <div style="position:fixed; left:88%; top:8%; z-index:3;">
            <a href="../fb_profile/Profile.php" style="text-decoration:none; color:#000;" id="head_timeline" 
            onMouseOver="head_timeline_over()" onMouseOut="head_timeline_out()" >
                <h7>Timeline</h7>
            </a> 
        </div>
    <!--about-->
        <div style="position:fixed; left:86%; top:13.5%; z-index:3;">
            <a href="../fb_profile/about.php"> <img src="img/about.png" onMouseOver="head_about_over()" 
            onMouseOut="head_about_out()"> </a>
        </div> 
        <div style="position:fixed; left:88%; top:13.5%; z-index:3;">
            <a href="../fb_profile/about.php" style="text-decoration:none; color:#000;" id="head_about" 
            onMouseOver="head_about_over()" onMouseOut="head_about_out()"><h7>About</h7></a> 
        </div>
    <!--photo-->       
        <div style="position:fixed; left:85.8%; top:18%; z-index:3;"> 
            <a href="../fb_profile/photos.php"><img src="img/photo&video.PNG" onMouseOver="head_photos_over()" 
            onMouseOut="head_photos_out()"> </a> 
        </div>
        <div style="position:fixed; left:88.2%; top:18.5%; z-index:3;">
            <a href="../fb_profile/photos.php" style="text-decoration:none; color:#000;" id="head_photos" 
            onMouseOver="head_photos_over()" onMouseOut="head_photos_out()"><h7>Photos</h7></a>
        </div>
    <!--Group chat-->
        <div style="position:fixed; left:85.8%; top:23%; z-index:3;"> 
            <a href="Settings.php"> <img src="img/settings2.png" height="25" width="23" 
            onMouseOver="head_settings_over()" onMouseOut="head_settings_out()"> </a> 
            </div>
        <div style="position:fixed; left:88.2%; top:23.5%; z-index:3;">
            <a href="Settings.php" style="text-decoration:none; color:#000;" id="head_settings" 
            onMouseOver="head_settings_over()" onMouseOut="head_settings_out()"><h7> Account Settings </h7></a>
        </div>
    <!--Feedback-->
        <div style="position:fixed; left:86.1%; top:27.5%; z-index:3;"> 
            <a href="feedback.php"> <img src="img/icon-feedback.png" height="20" width="20" 
            onMouseOver="head_feedback_over()" onMouseOut="head_feedback_out()"> </a> 
        </div>
        <div style="position:fixed; left:88.3%; top:28%; z-index:3;">
            <a href="feedback.php" style="text-decoration:none; color:#000;" id="head_feedback" 
            onMouseOver="head_feedback_over()" onMouseOut="head_feedback_out()"><h7> Feedback </h7></a>
        </div>
    <!--Logout-->
        <div style="position:fixed; left:86%; top:32.5%; z-index:3;">
            <a href="logout.php"> <img src="img/logout.png" height="20" width="20"  
            onMouseOver="head_logout_over()" onMouseOut="head_logout_out()"> </a> 
        </div>
        <div style="position:fixed; left:88.3%; top:32.1%; z-index:3;">
            <a href="logout.php" style="text-decoration:none; color:#000;" id="head_logout" 
            onMouseOver="head_logout_over()" onMouseOut="head_logout_out()"><h7> Logout </h7></a>
        </div>
    </div>
<!--end fb option-->

<!-----------------------------------------------------------Right part-Danh sach ban be------------------------------------------------------------------------->
    <div class="profile_container" style="position:fixed; left:81%; top:8%; z-index:1;" >
      <!--anh va ten user-->
        <div class="inner_profile">
            <div class="img">
                <img src="profile_images/<?php echo $user_data->user_image; ?>" alt="Profile image">
            </div>
            <h1 align="center"><?php echo  $user_data->username;?></h1>
        </div>
      <!--Hom/Request/Users-->
        <nav>
            <ul>
                <li><a href="Home.php" rel="noopener noreferrer" class="active">Home</a></li>
                <li><a href="notifications.php" rel="noopener noreferrer">Requests<span class="badge <?php
                if($get_req_num > 0){//neu so loi moi ket ban > 0
                    echo 'redBadge';
                }
                ?>"><?php echo $get_req_num;?></span></a></li>
                <li><a href="Users.php" rel="noopener noreferrer">Users</a></li>
            </ul>
        </nav>
      <!--Hien thi tat ca friends-->
        <div class="all_users">
            <div align="center" style="margin-bottom:5px;">
                <b style="font-size:20px;">All friends</b>
                <span>
                    <button type="button" name="group_chat" id="group_chat" class="btn btn-warning btn-xs">
                        Group Chat
                    </button>
                </span>
            </div>

            <div class="usersWrapper">
                <?php
                if($get_frnd_num > 0){//neu số friends lớn hơn 0
                    foreach($get_all_friends as $row){//hien thi tat ca friends
                        echo '<div class="user_box" >
                                <div class="user_img"><img src="profile_images/'.$row->user_image.'" alt="Profile image"></div>
                                <button type="button" class="start_chat"  name="user_chat" id="user_chat" >
                                    Chat
                                </button>
                                <div class="user_info"><span>'.$row->username.'</span>
                                <span><a href="user_profile.php?id='.$row->id.'" class="see_profileBtn">See profile</a>
                                
                                </div>
                            </div>';
                    }
                }
                else{
                    echo '<h4>You have no friends!</h4>';
                }
                ?>
            </div>          
        </div>
    </div>

  <!--Nơi chứa dialog chat user-->
    <div id="dialog-chat-user">
    </div>
<!-----------------------------------------------------End Right part-Danh sach ban be------------------------------------------------------------------>

<!--Form dialog Group chat-->
    <div id="group_chat_dialog" title="Group Chat Window">
        <div id="group_chat_history" >
        </div>
        <div class="form-group">
            <div class="chat_message_area">
                <div id="group_chat_message" contenteditable class="form-control">

                </div>
                <div class="image_upload">
                    <form id="uploadImage" method="post" action="upload.php" >
                        <label for="uploadFile"><img id="img" src="img/upload.png" /></label>
                        <input type="file" name="uploadFile" id="uploadFile" accept=".jpg, .png, .gif" />
                    </form>
                </div>
            </div>
        </div>
        <div class="form-group" align="right">
            <button type="button" name="send_group_chat" id="send_group_chat" class="btn btn-info">Send</button>
        </div>
    </div>
<!--END form dialog Group chat-->

<script>  
$(document).ready(function(){

	setInterval(function(){//thiết lập các hàm trong khoảng 5000ms,cách khoảng 5000ms thì thực hiện (mai mai)
		update_last_activity();//hàm này sẽ yêu cầu đến trang update_last_activity.php để cập nhật chi tiết hoạt động gần đây nhất của người dùng trong bảng login_details.
		//fetch_user();
		update_chat_history_data();//tạo ứng dụng trò chuyện trực tiếp và thời gian thực
		fetch_group_chat_history();//Tính năng trò chuyện nhóm theo thời gian thực.
    }, 5000);

	function update_last_activity()
	{
		$.ajax({
			url:"update_last_activity.php",
			success:function()//thanh cong se thuc hien
			{

			}
		})
	}

	function make_chat_dialog_box(to_user_id)
	{
		var modal_content = '<div id="user_dialog_'+to_user_id+ '" class="user_dialog" >';
		    modal_content += '<div  class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
		    modal_content +=        fetch_user_chat_history(to_user_id);
		    modal_content += '</div>';
		    modal_content += '<div class="form-group">';
		    modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control chat_message"></textarea>';
		    modal_content += '</div><div class="form-group" align="right">';
		    modal_content+= '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info send_chat">Send</button></div></div>';
		$('#dialog-chat-user').html(modal_content);//Lay doan chat them vap trong #dialog-chat-user
	}          

//Hien thi chat hop thoai user
	$(document).on('click', '.start_chat', function(){
		var to_user_id = $(this).data('touserid');
		//var to_user_name = $(this).data('tousername');
        make_chat_dialog_box(to_user_id);

        //Dùng toggle để ẩn và hiện hộp thoại
        $("#dialog-chat-user").toggle();//mo hop thoai
        
        //hien thi cac icon  dùng emojioneArea
		$('#chat_message_'+to_user_id).emojioneArea({
            pickerPosition:"top",
			toneStyle: "bullet"
		});
    });

//Khi nhấn nút send
	$(document).on('click', '.send_chat', function(){
		var to_user_id = $(this).attr('id');//tra ve id cua nguoi gui
		var chat_message = $.trim($('#chat_message_'+to_user_id).val());//lấy nd và xóa k/c đầu và cuối,
                                                                    //Sau đó, chúng tôi có giá trị tìm nạp của textarea mà chúng tôi đã viết tin nhắn trò chuyện. 
        // Sau đó, chúng tôi đã gửi yêu cầu ajax đến trang insert_chat.php để chèn tin nhắn trò chuyện vào cơ sở dữ liệu mysql.                                                            
		if(chat_message != '')//neu chat message khac trong
		{
			$.ajax({
				url:"insert_chat.php", //trang insert_chat.php để chèn tin nhắn trò chuyện vào cơ sở dữ liệu mysql
				method:"POST",//phuong thuc post
				data:{to_user_id:to_user_id, chat_message:chat_message},
				success:function(data)
				{
					var element = $('#chat_message_'+to_user_id).emojioneArea();
					element[0].emojioneArea.setText('');// xóa văn bản trường textarea bằng biểu tượng cảm xúc,
					$('#chat_history_'+to_user_id).html(data);//lay noi dung data gan vao ...
				}
			})
		}
		else
		{
			alert('Không có nội dung!');
		}
	});
    
    //Tìm nạp dữ liệu trò chuyện từ bảng mysql và hiển thị dưới thẻ div lịch sử 
    //trò chuyện của hộp thoại người dùng cụ thể=>Tra ve nd cua hop thoai
	function fetch_user_chat_history(to_user_id)
	{
		$.ajax({
			url:"fetch_user_chat_history.php",//in ra 1 dong chat cua user
			method:"POST",
			data:{to_user_id:to_user_id},
			success:function(data){
				$('#chat_history_'+to_user_id).html(data);//lấy noi dung data thay vào
			}
		})
	}

//Để tạo ứng dụng trò chuyện trực tiếp và thời gian thực
	function update_chat_history_data()
	{
		$('.chat_history').each(function(){
			var to_user_id = $(this).data('touserid');//phương thức này sẽ lấy data('touserid') của mỗi thẻ div của lớp này và lưu trữ dưới biến to_user_id này.
			fetch_user_chat_history(to_user_id);//sau đó sau nó sẽ gọi hàm fetch_user_chat_history () dựa trên giá trị của biến to_user_id
		});
	}

//nhan vao dau x mau do de tat hop thoai chatbox cua user or hop thoai chatbox cua group chat
	$(document).on('click', '.ui-button-icon', function(){
		$('.user_dialog').dialog('destroy').remove();
		//$('#is_active_group_chat_window').val('no');
	});

//Kèm một hành động khi sử dụng blur, sự kiện blur là khi người dùng thoát khỏi focus trong trường nhập (input).
//hiển thị thông báo nhập cho người nhận khi người gửi bắt đầu nhập vào hộp thoại trò chuyện của mình.
	/*$(document).on('focus', '.chat_message', function(){
		var is_type = 'yes';
		$.ajax({
			url:"update_is_type_status.php",
			method:"POST",
			data:{is_type:is_type},
			success:function()
			{

			}
		})
	});*/

//sự kiện blur là khi người dùng thoát khỏi focus trong trường nhập (input).
//hiển thị thông báo nhập cho người nhận khi người gửi bắt đầu nhập vào hộp thoại trò chuyện của mình.
	/*$(document).on('blur', '.chat_message', function(){
		var is_type = 'no';
		$.ajax({
			url:"update_is_type_status.php",
			method:"POST",
			data:{is_type:is_type},
			success:function()
			{
				
			}
		})
	});*/

//---------------------------------------------Group dialog chat--------------------------
    //Hien thi dialog group chat
    $("#group_chat").click(function(){
        $("#group_chat_dialog").toggle();//mo hop thoai
        //$('#is_active_group_chat_window').val('yes');//moi them
	    fetch_group_chat_history();
    });

    //Khi nhan vao nut send
	$('#send_group_chat').click(function(){
		var chat_message = $.trim($('#group_chat_message').html());
		var action = 'insert_data';
		if(chat_message != '')
		{
			$.ajax({
				url:"group_chat.php",
				method:"POST",
				data:{chat_message:chat_message, action:action},
				success:function(data){
					$('#group_chat_message').html('');
					$('#group_chat_history').html(data);
				}
			})
		}
		else
		{
			alert('Khong có noi dung!');//Gõ cai gi do
		}
	});

	function fetch_group_chat_history()
	{
		//var group_chat_dialog_active = $('#is_active_group_chat_window').val();
		var action = "fetch_data";
		if(group_chat_dialog_active == 'yes')
		{
			$.ajax({
				url:"group_chat.php",
				method:"POST",
				data:{action:action},
				success:function(data)
				{
					$('#group_chat_history').html(data);
				}
			})
		}
	}

	$('#uploadFile').on('change', function(){
		$('#uploadImage').ajaxSubmit({ 
			target: "#group_chat_message",//hiển thị kết quả vào trong #group_chat_message
			resetForm: true
		});
	});

	$(document).on('click', '.remove_chat', function(){
		var chat_message_id = $(this).attr('id');
		if(confirm("Are you sure you want to remove this chat?"))
		{
			$.ajax({
				url:"remove_chat.php",
				method:"POST",
				data:{chat_message_id:chat_message_id},
				success:function(data)
				{
					update_chat_history_data();
				}
			})
		}
	});
	
});  
</script>

<!--left part-->
    <div style="position:fixed; left:1.5%; top:16.5%; z-index:1;">
	    <table border="0">
            <tr>
                <td> 
                    <img src="profile_images/<?php echo $user_data->user_image; ?>" alt="Profile image" style="height:70px; width:70px;"><!--anh dai dien-->
                </td>
                <td> 
                    &nbsp; <?php echo  $user_data->username;?>     <!--ten dai dien-->              
                </td>
            </tr>
        </table>
    </div>

    <script>
        $(document).ready(function(){
            $("#color").click(function(){
                $("#list-color").toggle();
            });
        });
    </script>

    <!--Setting color-->
    <script>
      $(document).ready(function(){
        $(".red").on("click",function(){
            $('body').css('background-color','red')
        });
        $(".blue").on("click",function(){
            $('body').css('background-color','blue')
        });
        $(".yellow").on("click",function(){
            $('body').css('background-color','yellow')
        });
        $(".green").on("click",function(){
            $('body').css('background-color','green')
        });
        $(".7FFFD4").on("click",function(){
            $('body').css('background-color','#7FFFD4')
        });
        $(".FFE4C4").on("click",function(){
            $('body').css('background-color','#FFE4C4')
        });
        $(".7FFF00").on("click",function(){
            $('body').css('background-color','#7FFF00')
        });
        $(".D2691E").on("click",function(){
            $('body').css('background-color','#D2691E')
        });
        $(".orange").on("click",function(){
            $('body').css('background-color','orange')
        });
        $(".brown").on("click",function(){
            $('body').css('background-color','brown')
        });
        $(".BDB76B").on("click",function(){
            $('body').css('background-color','#BDB76B')
        });
        $(".8B008B").on("click",function(){
            $('body').css('background-color','#8B008B')
        });
        $(".20B2AA").on("click",function(){
            $('body').css('background-color','#20B2AA')
        });
        $(".FFFFE0").on("click",function(){
            $('body').css('background-color','#FFFFE0')
        });
        $(".FF00FF").on("click",function(){
            $('body').css('background-color','#FF00FF')
        });
        $(".3CB371").on("click",function(){
            $('body').css('background-color','#3CB371')
        });
        $(".FFE4E1").on("click",function(){
            $('body').css('background-color','#FFE4E1')
        });
        $(".9ACD32").on("click",function(){
            $('body').css('background-color','#9ACD32')
        });
        $(".C8C8C8").on("click",function(){
            $('body').css('background-color','#C8C8C8')
        });
        $(".4d0000").on("click",function(){
            $('body').css('background-color','#4d0000')
        });
      });
    </script>

    <!--color-->
    <div style="position:fixed; left:1.5%; top:29%;width:200px;">
        <button id="color" >Setting color</button><br>
        <div id="list-color" style="position:fixed; left:1.5%; top:33.2%;width:200px;display:none;">
            <div>
                <div class="red" style="float:left;background-color:red;width:40px;height:40px;" ></div>
                <div class="blue" style="float:left;background-color:blue;width:40px;height:40px; "></div>
                <div class="yellow" style="float:left;background-color:yellow;width:40px;height:40px; "></div>
                <div class="green" style="float:left;background-color:green;width:40px;height:40px; "></div>
                <div class="FFE4C4" style="float:left;background-color:#FFE4C4;width:40px;height:40px; "></div>
                <div class="7FFFD4" style="float:left;background-color:#7FFFD4;width:40px;height:40px; "></div>
                <div class="7FFF00" style="float:left;background-color:#7FFF00;width:40px;height:40px; "></div>
                <div class="D2691E" style="float:left;background-color:#D2691E;width:40px;height:40px; "></div>
                <div class="orange" style="float:left;background-color:orange;width:40px;height:40px; "></div>
                <div class="brown" style="float:left;background-color:brown;width:40px;height:40px; "></div>
                <div class="BDB76B" style="float:left;background-color:#BDB76B;width:40px;height:40px; "></div>
                <div class="8B008B" style="float:left;background-color:#8B008B;width:40px;height:40px; "></div>
                <div class="20B2AA" style="float:left;background-color:#20B2AA;width:40px;height:40px; "></div>
                <div class="FFFFE0" style="float:left;background-color:#FFFFE0;width:40px;height:40px; "></div>
                <div class="FF00FF" style="float:left;background-color:#FF00FF;width:40px;height:40px; "></div>
                <div class="3CB371" style="float:left;background-color:#3CB371;width:40px;height:40px; "></div>
                <div class="FFE4E1" style="float:left;background-color:#FFE4E1;width:40px;height:40px; "></div>
                <div class="9ACD32" style="float:left;background-color:#9ACD32;width:40px;height:40px; "></div>
                <div class="C8C8C8" style="float:left;background-color:#C8C8C8;width:40px;height:40px; "></div>
                <div class="4d0000" style="float:left;background-color:#4d0000;width:40px;height:40px; "></div>
            </div>   
        </div>
    </div>
    
<!--end left part-->

    <!--left hr-->
    <hr style="position:fixed; left:18%; top:4.8%; height:100%; width:0; border-color:#CCCCCC; box-shadow:-5px 0px 5px 0px rgb(0,0,0);z-index:4;">
    <!--end left hr -->

    <!--right hr-->
    <hr style="position:fixed; left:80%;top:4.8%; height:100%; width:0; border-color:#CCCCCC; box-shadow:5px 0px 5px 0px rgb(0,0,0); z-index:4;">
    <!--end right hr-->
</body>
</html>