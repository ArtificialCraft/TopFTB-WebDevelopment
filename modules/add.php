<?php
require('checklogin.php');
require_once("modules/solvemedialib.php");
if(isset($_POST['title'])){
	$connection = getDB()->getConnection();
	foreach($_POST as $input => $value){
		$_POST[$input] = mysqli_real_escape_string($connection, $value);
	}
	if(strpos($_POST['ip'],':') !== false){
		$info = explode(':',$_POST['ip']);
		$_POST['port'] = ':'.$info[1];
		$_POST['rawport'] = $info[1];
		$_POST['ip'] = $info[0];
	}else{
		$_POST['port'] = "";
		$_POST['rawport'] = "25565";
	}
	$privkey="v3KnmR1C.k7o4rTV0-op0HQgM2gh8cRR";
	$hashkey="GFUVziMbQpgsjymSwCv0dQxDOdRPBQ7N";
	$solvemedia_response = solvemedia_check_answer($privkey, $_SERVER["REMOTE_ADDR"], $_POST["adcopy_challenge"], $_POST["adcopy_response"], $hashkey);
	if (!$solvemedia_response->is_valid) {
		//handle incorrect answer
		if($solvemedia_response->error == "incorrect-solution"){
			$error = 'Your captcha was invalid!';
		}else if($solvemedia_response->error == "already checked"){
			$error = 'Your captcha has already been processed before! Please submit form again!';
		}else{
			$error = 'Captcha failed: ' . $solvemedia_response->error;
		}
	}else{
		$confirm = confirm();
		if($confirm != null){
			$error = $confirm;
		}else{
			$serverid = getRandId("ServerList");
			getDB()->insert('#__ServerList',array('fields' => array('id','ownerid', 'name', 'ip','rawip','modpack', 'banner', 'players', 'maxplayers', 'views', 'registered', 'updated'), 'values' => array(array($serverid, $_SESSION['id'], $_POST['title'], $_POST['ip'] . $_POST['port'], gethostbyname($_POST['ip']) . $_POST['port'], $_POST['mp'], $_POST['banner'], $players, $maxplayers, '0', time(), time()))))->exec();
			redirect('manage');
		}
	}
}

function confirm(){
	require 'MinecraftServerPing.php';
	try{
		$Query = new MinecraftPing($_POST['ip'], $_POST['rawport']);
		$Info = $Query->Query();
		global $players, $maxplayers;
		if($Info === false){
			$Query->Close();
			$Query->Connect();
			$Info = $Query->QueryOldPre17();
			$players = $Info['Players'];
			$maxplayers = $Info['MaxPlayers'];
		}else{
			$players = $Info['players']['online'];
			$maxplayers = $Info['players']['max'];
		}
	}catch(Exception $ex){
		return 'The ip you have entered does not lead to a minecraft server. If you are not using the default port please enter it after a colon like this: "mc.example.net:12345".';
	}
	$db = getDB()->select(array('id'))->from('#__ServerList')->where(array(array('rawip', gethostbyname($_POST['ip']).$_POST['port'])))->exec();	
	if($db->count() > 0){
		return "This ip address is already being used!";
	}
	return null;
}

?>

<?php 
if(isset($error)){
	echo '<div class="alert-error">Oops! Seems there are some errors..<br/>'.$error.'</div>';	
	unset($error);
}
?>
<div id="register">
    <form class="topform" name="registerform" action="" method="post">
        <div class="formshow">
        	<div>
            	<p>Server Title</p>
                <input type="text" name="title" size="19" maxlength="20" placeholder="ExampleCraft 1.7.4" required="required"/>
            </div>
            <div>
            	<p>IP</p>
                <input type="text" name="ip" size="28" maxlength="32" placeholder="mc.examplecraft.com" required="required"/>
            </div>
            <div>
                <p>Modpack</p>
               	<select id="modpack" name="mp">
					<option value="Unleashed">Unleashed</option>
					<option value="Unhinged">Unhinged</option>
					<option value="DireWolf20_1.5.2">DireWolf20 1.5.2</option>
					<option value="Ultimate">Ultimate</option>
					<option value="Mindcrack">Mindcrack</option>
					<option value="DireWolf20_1.4.7">DireWolf20 1.4.7</option>
					<option value="Yogscraft">Yogscraft</option>
					<option value="Other">Other</option>
					<option value="Custom">Custom</option>
				</select>
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
            <div>
            	<p>Banner</p>
                <input type="text" name="banner" size="23" placeholder="http://mypic.net/banner.gif" required="required"/>
            </div>
            <div class="checkboxes big">
            	<p>To bid you must agree to these conditions!</p>
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