<?php
//ip, username, password, confirm password, email
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']){
	redirect('');
}
require_once("modules/solvemedialib.php"); 
if(isset($_POST['un'])){
	$privkey="v3KnmR1C.k7o4rTV0-op0HQgM2gh8cRR";
	$hashkey="GFUVziMbQpgsjymSwCv0dQxDOdRPBQ7N";
	$solvemedia_response = solvemedia_check_answer($privkey,
						$_SERVER["REMOTE_ADDR"],
						$_POST["adcopy_challenge"],
						$_POST["adcopy_response"],
						$hashkey);
	if (!$solvemedia_response->is_valid) {
		//handle incorrect answer
		if($solvemedia_response->error == "wrong answer" || $solvemedia_response->error == "incorrect-solution"){
			$error = 'Your captcha was invalid!';
		}else{
			$error = $solvemedia_response->error;
		}
	}else{
		$db = getDB()->select(array('username'))->from('#__UserDatabase')->where(array(array('username', $_POST['un'])))->exec();
		if($db->count() >= 1){
			$error = 'This username has already been taken!';
		}else{
			$db->insert('#__UserDatabase',array('fields' => array('id','username', 'password', 'email'), 'values' => array(array('',$_POST['un'], myencrypt($_POST['pw'], $_POST['un']), $_POST['email']))))->exec();
	redirect('./manage');
		}
	}
}
if(isset($error)){
	echo '<div class="alert-error">Oops! Seems there are some errors..<br/>'.$error.'</div>';	
	unset($error);
}
?>
<div id="register">
    <form class="topform" name="registerform" action="" method="post">
        <div class="formshow">
        	<div>
            	<p>Username</p>
                <input type="text" name="un" size="21" maxlength="20" required="required"/>
            </div>
            <div>
            	<p>Password</p>
                <input type="password" name="pw" size="21" required="required"/>
            </div>
            <div>
            	<p>Email</p>
                <input type="email" name="email" size="25" maxlength="32" placeholder="email@example.com" required="required"/>
            </div>
            <div class="big captcha">
            	<p>Complete the Captcha</p>
                <div>
                	<script type="text/javascript">var ACPuzzleOptions = { theme:'white',lang:	    'en',size:	'300x150'};</script>
					<?php 
                        echo solvemedia_get_html("VIr9UxeRO1Q1RhxycJtiz7NzQGlJIWHf");	//solvemdia widget
                    ?>
                </div>
            </div>
            <div class="checkboxes big">
            	<p>To register you must agree to these conditions!</p>
            	<div>
                <input name="agree" class="terms" type="checkbox" required="required"> I agree to TopFTB <a href="tos" target="_blank">Terms of Use</a>
                </div>
        	</div>
            <div class="submit">
            	<input type="submit" value="SUBMIT"/>
            </div>
        </div>
    <form>
</div>