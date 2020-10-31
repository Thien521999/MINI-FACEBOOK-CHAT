<form id="editUploadImage" action="" method="post" enctype="multipart/form-data" >
	<textarea rows="5" cols="70"  name="txtedit">{currnetMessage}</textarea>
	<span class="hidden">
    	<input type="text" name="message_id_edit" id="id_edit" value="{id}"/>
		<input type="text" name="action" value="edit" />
    </span>
	<div id="image_preview"><img id="image_edit" src="noimage.png" height="100px" width="auto"/></div><br>
    <label class="check_image">If you do not want to edit image, please check it</label>
    <input type="checkbox" name="check" >
	<hr>
	<label class="select_image">Select Your Image</label><br/>
	<input type="file" name="file" id="file_edit" />
	<button type="submit" name="submit">Save</button>
	<button name="cancel" onClick="cancelEdit({currnetMessage},{id})">Cancel</button>
</form>
<script>
	$(document).ready(function(e) {
	$("#editUploadImage").on('submit',(function(e) {
			e.preventDefault();
			var id = $("#id_edit").val();//lay gia tri thanh phan cua
			jQuery.ajax({
				url: "ajax.php", 		  // File mà data được gửi đến
				type: "POST",             // Phương thức gửi
				data: new FormData(this), // Dữ liệu gửi lên máy chủ(dạng foem)
				contentType: false,       
				cache: false,             
				processData:false,        
				success: function(data)   // Hàm sẽ được thực hiện nếu request thành công, giá trị trả về gán vào biên result
				{
					$("#message_" + id).html(data);
					$('#frmAdd').show();	
					$("#txtmessage").val('');
					$("#loaderIcon").hide();
				}
			});	
		}));
		
		$(function() {
			$("#file_edit").change(function() {
				var file = this.files[0];
				var imagefile = file.type;
				var match= ["image/jpeg", "image/png", "image/jpg", "image/gif"];
				if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]) || (imagefile==match[3])))
				{
					$('#image_edit').attr('src','noimage.png');//gan noi dung cho thuoc tinh 
					
					return false;
				}
				else
				{
					$("#message").html('');
					var reader = new FileReader();
					reader.onload = imageIsLoadedEdit;
					reader.readAsDataURL(this.files[0]);
				}
			});
		});
		function imageIsLoadedEdit(e) {
			$('#image_edit').attr('src', e.target.result);
			$('#image_edit').attr('width', 'auto');
			$('#image_edit').attr('height', '100px');
		};
	});
	// Hàm thoát edit box
	function cancelEdit(message,id) {
		$(".btnEditAction").css('disabled','false');
		$("#message_" + id + " .message-text").html(message);
		$('#frmAdd').show();
	}
</script>