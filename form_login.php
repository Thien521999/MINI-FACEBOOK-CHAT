	<?php
		require 'includes/init.php';		

		if( isset($_POST['login']) ){
			
			// IF USER MAKING LOGIN REQUEST
			if(isset($_POST['email']) && isset($_POST['password'])){
				$email=$_POST['email'];
				$pass_word=$_POST['password'];
				$result = $user_obj->loginUser($email,$pass_word);			
			}		
		}
	?>
	
	<form  method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
			<div id="form1" class="fb-header">
				Email or Phone	<br>
				<input placeholder="Email" type="mail" name="email" spellcheck="false" id='email' required autofocus /><br>
				<input type="checkbox" />keep me logged in
			</div>
			
			<div id="form2" class="fb-header">
				Password<br>
				<input placeholder="Password" type="password" name="password" required /><br>
				<a href="Forgot_Password.php" style="color:#CCCCCC; text-decoration:none;">Forgotten your password?</a>
			</div>
					
			<input type="submit" name="login" class="submit1" value="Login" />
			<?php
				if(isset($result['errorMessage'])){
					echo "<script>alert('Đăng nhập không thành công')</script>";
				}
				if(isset($result['successMessage'])){
					echo "<script>alert('Đăng nhập thành công')</script>";
				}
            ?> 
	</form>
