	<?php
		// IF USER MAKING SIGNUP REQUEST
		if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['re_enter_email']) ){
			$result = $user_obj->singUpUser($_POST['username'], $_POST['email'], $_POST['re_enter_email'], $_POST['password']);			
		}
	?>
	<div id="intro1" class="fb-body">
		Facebook helps you connect and share with the <br>people in your life.
	</div>
	<div id="intro2" class="fb-body">Create an account</div>
	<div id="img2" class="fb-body"><img src="./img/world.png" /></div>
	<div id="intro3" class="fb-body">It's free and always will be.</div>
	<div id="form3" class="fb-body" >	
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<input placeholder="Full Name" type="text" id="namebox" name="username" spellcheck="false"  required autofocus  />
			<!-- <input placeholder="Last Name"  type="text" id="namebox" name="lastName" required/> -->
			<input placeholder="Email" type="text" id="mailbox" name="email" required  />
			<input placeholder="Re-enter email" type="text" id="mailbox" name="re_enter_email" required  />
			<input placeholder="Password" type="password" id="mailbox" name="password" required  />
			<input type="date" min="1900-01-01" max="3019-01-01"  id="namebox" name="date" required/><br><br>
			<input type="radio" id="r-b" name="sex" value="male" checked="checked" />Male
			<input type="radio" id="r-b" name="sex" value="female" />Female<br><br>
			<p id="intro4">By clicking Create an account, you agree to our Terms and that you have read our Data Policy, including our Cookie Use.</p>
			<input type="submit" name="account" class="button2" value="Create an account" />
			<br><hr>
			<p id="intro5">Create a Page for a celebrity, band or business.</p>
		</form>
		<div>  
			<?php 
				if(isset($result['errorMessage'])){
					echo '<p class="errorMsg">'.$result['errorMessage']. '</p>';
				}
				if(isset($result['successMessage'])){
					echo '<p class="successMsg">'.$result['successMessage']. '</p>';
				}
			?>    
		</div>
	</div>