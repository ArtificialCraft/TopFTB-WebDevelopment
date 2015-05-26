<?php
	$server = getServer('id', $_GET['data']);
	if(!isset($server['id'])){
		$_SESSION['error'] = "This is an invalid server id, please check the url!";
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
			if($solvemedia_response->error == "incorrect-solution" || $solvemedia_response->error == "wrong answer"){
				$error = 'Your captcha was invalid!';
			}else if($solvemedia_response->error == "already checked"){
				$error = 'You have already submitted this invalid form! Please reload the page!';
			}else{
				$error = $solvemedia_response->error;
			}
		}else{
			$lastvote = getDB()->select(array('timestamp'))->from('#__Votes')->where(array(array('serverid', $server['id']), array('group' => array(array('username',$_POST['un']), array('type' => 'OR', 'data' => array('ip',$_SERVER["REMOTE_ADDR"]))))))->orderby(array('timestamp'))->limit(1)->exec()->getResult();
			if(isset($lastvote[1]['timestamp']) && $lastvote[1]['timestamp'] + 86400 > time()){
				$now = new DateTime();
				$then = new DateTime('@'.($lastvote[1]['timestamp'] + 86400));
				$timeleft = $then->diff($now)->format('%h hours %i minutes %S seconds');
				$error = "Your username or IP address has already voted within the past 24 hours! Please try again in " . $timeleft;
			}else{
                $server['votifier'] = explode("!!",$server['votifier']);
				$vote = new Vote($server['votifier'][1], $server['votifier'][2], $_POST['un'], $server['votifier'][3]);
				if(!$vote->sendPacket()){
					$error = "Your vote has been recorded, but could not be sent to the server because it was unreachable!";
				}
				getDB()->insert('#__Votes',array('fields' => array('id','serverid','username', 'ip', 'timestamp'), 'values' => array(array('',$server['id'],$_POST['un'], $_SERVER['REMOTE_ADDR'],time()))))->exec();
				getDB()->from('#__ServerList')->update('#__ServerList')->set(array(array('statvotes', $server['statvotes ']+ 1)))->where(array(array('id', $server['id'])))->exec();
			}
		}
	}
	if(isset($error)){
		echo '<div class="alert-error">Oops! Seems there are some errors..<br/>'.$error.'</div>';	
		unset($error);
	}
	if(isset($thankyou)){
		echo '<div class="alert-error">Thank you for voting for '.$server['name'].'!</div>';	
		unset($thankyou);
	}
?>
<div id="votepage">
    <form class="topform" name="voteform" action="" method="post">
        <div class="formshow">
        	<div class="big">
            	<p>You are voting for <?php echo $server['name'];?></p>
                <div>
                    <img src="<?php echo $server['banner'];?>"/>
                </div>
            </div>
            <div>
            	<p>Username</p>
                <input type="text" name="un" size="21" placeholder="Notch" />
            </div>
            <div class="big captcha">
            	<p>Complete the Captcha</p>
                <div>
                	<script type="text/javascript">var ACPuzzleOptions = { theme:	    'white',lang:	    'en',size:	'300x150'};</script>
					<?php 
                        echo solvemedia_get_html("VIr9UxeRO1Q1RhxycJtiz7NzQGlJIWHf");	//solvemdia widget
                    ?>
                </div>
            </div>
            <div class="submit">
            	<input type="submit" value="SUBMIT"/>
            </div>
        </div>
    <form>
</div>