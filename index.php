<?php 
	session_start();
	ob_start();
	if(isset($_GET['clear']) && $_GET['clear']){
		session_destroy();	
	}
	$local = $_SERVER["SERVER_NAME"] == 'localhost';
	if($local){
		define('BASE', "http://localhost/topftb/");
	}else{
		define('BASE', "http://174.95.151.188/topftb/");
	}
	//	define('BASE', "http://topftb.com/");
	include('util.php');
	ini_set('error_reporting', E_ALL);//change to 0 after 
	function alert($txt){
		echo $txt . '<br>';
	}
?>
<!DOCTYPE html>
<html>
<head>
<?php
if($local){
	echo '<base href="http://localhost/topftb/" target="_self">';
}else{
	echo '<base href="http://174.95.151.188/topftb/" target="_self">';
}
	//echo '<base href="http://topftb.com/" target="_self">';
	?>
    <!--DO YOUR META TAGSSSSSSSSSSSSSS-->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>TopFTB: Your top Feed the Beast server list - Find the best FTB servers here!</title>
    <link href="<?php echo BASE;?>css/style.css" rel="stylesheet"/>
    <script type="text/javascript" src="<?php echo BASE;?>js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo BASE;?>js/parsley.js"></script>
    <script type="text/javascript" src="<?php echo BASE;?>js/prefixfree.min.js"></script>
    <script type="text/javascript" src="<?php echo BASE;?>js/topftb.js"></script>
    </head>
<body>
<?php include('header.php');?>
<div id="core">
    <?php
       	if(isset($_SESSION['error'])){
			if(isset($_SESSION['type'])){
				$type = $_SESSION['type'];
				if($type == "login"){
					goto after;
				}
			}else{
				echo '<div class="alert-error">Oops! Seems there are some errors..<br/>'. $_SESSION['error'] .'</div>';
				unset($_SESSION['error']);
			}
		}
		after:
		if(isset($_SESSION['thankyou'])){
			echo '<div class="alert-thanks>Thank you for '.$_SESSION['thankyou'].'</div>"';
			unset($_SESSION['thankyou']);
		}
       	if(isset($_GET['action']) && strpos($_GET['action'],'.php') == false){
			if(!is_file('modules/' . $_GET['action'].'.php')){
				$_SESSION['error'] = "That was an invalid url!";
				redirect();
				return;
			}
			include('modules/' . $_GET['action'] . '.php');
		}else{
			include('modules/sort.php');
		}
	?>
</div>
<div id="login">
    <div id="module">
    	<h3>Login to <strong class="logo">TopFTB</strong></h3>
        <form name="loginform" action="login.php" method="post">
            <input id="username" name="un" type="text" placeholder="Username" required>
            <input id="password" name="pw" type="password" placeholder="Password" required>
            <p id="loginerror">
            	<?php 
					if(isset($_SESSION['type']) && $_SESSION['type'] == "login"){
						echo "<script>toggleLogin();</script>";
						echo $_SESSION['error'];
						unset($_SESSION['error']);
						unset($_SESSION['type']);
					}
				?>
            </p>
            <div id="options" class="left">
                <a href="dfs"><p>Forgot Username</p></a>
                <a href="dfs"><p>Forgot Password</p></a>
            </div>
            <input class="left" id="loginsubmit" type="submit" value="LOGIN"/ >
        </form>
    </div>
	<div id="overlay" onClick="toggleLogin();"></div>
</div>
</body>
</html>
