<?php
	require_once('htmlpurifier/HTMLPurifier.standalone.php');
	$server = getServer('id', $_GET['data']);
	if(!isset($server['id'])){
		$_SESSION['error'] = "This is an invalid server id, please check the url!";
		redirect('');
	}
	if(false){//TODO CHECK TO SEE IF PLAYER IS ACTUALLY OWNER OF THE SERVER, DONT WANT TO FUCK THAT UP NOW DO WE?
		$_SESSION['error'] = "You are not the owner of this server or have not logged in.";
		redirect('');
	}
	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	if(isset($_POST['serverdesc'])){
		$clean_desc = $purifier->purify($_POST['desc']);
		$clean_summary = $purifier->purify($_POST['summary']);
		if(strpos($_POST['ip'],':') !== false){
			$info = explode(':',$_POST['ip']);
			$_POST['port'] = ':'.$info[1];
			$_POST['rawport'] = $info[1];
			$_POST['ip'] = $info[0];
		}else{
			$_POST['port'] = "";
			$_POST['rawport'] = "25565";
		}
		getDB()->from('#__ServerList')->update('#__ServerList')->set(array(
			array('name', $_POST['name']), 
			array('ip', $_POST['ip'] . $_POST['port']), 
			array('modpack', $_POST['mp']), 
			array('rawip', gethostbyname($_POST['ip']) . $_POST['port']), 
			array('description', $clean_desc), 
			array('summary', $clean_summary),
			))->where(array(array('id', $server['id'])))->exec();
		$server = getServer('id', $_GET['data']);
	}else if(isset($_POST['votingdata'])){
		$string = $_POST['enabled']."!!".$_POST['voteip']."!!".$_POST['voteport']."!!".$_POST['votekey'];
		getDB()->from('#__ServerList')->update('#__ServerList')->set(array(
			array('votifier', $string)
			))->where(array(array('id', $server['id'])))->exec();
		$server = getServer('id', $_GET['data']);
	}else if(isset($_POST['socialdata'])){
		$string = $_POST['socialname1'].";".$_POST['sociallink1']."!!".$_POST['socialname2'].";".$_POST['sociallink2']."!!".$_POST['socialname3'].";".$_POST['sociallink3'];
		getDB()->from('#__ServerList')->update('#__ServerList')->set(array(
			array('social', $string)
			))->where(array(array('id', $server['id'])))->exec();
		$server = getServer('id', $_GET['data']);
	}else if(isset($_POST['gallery'])){
		getDB()->from('#__ServerList')->update('#__ServerList')->set(array(
			array('gallery', $server['gallery'] .(strlen($server['gallery']) > 0 ? ';' : '').$_POST['gallerylink'])
			))->where(array(array('id', $server['id'])))->exec();
		$server = getServer('id', $_GET['data']);
	}//TODO MAKE SURE PEOPLE CAN REMOVE PICTURES SOMEHOW STRREPLACE(LINK . ';', $DATA)
	echo '<script type="text/javascript" src="'.BASE.'js/ckeditor/ckeditor.js"></script>';
?>
<div id="edit">
	<div id="pages">
    	<div class="page" id="fserverdesc">1</div>
    	<div class="page" id="fvotingdata">2</div>
    	<div class="page" id="fsocialdata">3</div>
    	<div class="page" id="fgallery">4</div>
    </div>
	<div id="forms">
        <form class="topform" id="serverdesc" name="serverdesc" action="" method="post">
            <div class="formshow">
        	<h1><?php echo $server['name'];?></h1>
                <input name="serverdesc" type="hidden" />
                <div class="editname"><p>Name</p><input type="text" size="24" name="name" value="<?php echo $server['name']?>" maxlength="20"/></div>
                <div class="editip"><p>IP</p><input type="text" size="28" name="ip" value="<?php echo $server['ip']?>" maxlength="20"/></div>
                <div class="editmodpack">
                    <p>Modpack</p>
                    <select id="modpack" name="mp">
                        <option value="Unleashed" <?php if ($server['modpack'] == "Unleashed") echo 'selected="selected"';?>>Unleashed</option>
                        <option value="Unhinged" <?php if ($server['modpack'] == "Unhinged") echo 'selected="selected"';?>>Unhinged</option>
                        <option value="DireWolf20_1.5.2" <?php if ($server['modpack'] == "DireWolf20_1.5.2") echo 'selected="selected"';?>>DireWolf20 1.5.2</option>
                        <option value="Ultimate" <?php if ($server['modpack'] == "Ultimate") echo 'selected="selected"';?>>Ultimate</option>
                        <option value="Mindcrack" <?php if ($server['modpack'] == "Mindcrack") echo 'selected="selected"';?>>Mindcrack</option>
                        <option value="DireWolf20_1.4.7" <?php if ($server['modpack'] == "DireWolf20_1.4.7") echo 'selected="selected"';?>>DireWolf20 1.4.7</option>
                        <option value="Yogscraft" <?php if ($server['modpack'] == "Yogscraft") echo 'selected="selected"';?>>Yogscraft</option>
                        <option value="Other" <?php if ($server['modpack'] == "Other") echo 'selected="selected"';?>>Other</option>
                        <option value="Custom" <?php if ($server['modpack'] == "Custom") echo 'selected="selected"';?>>Custom</option>
                    </select>
                </div>
                <div class="big" id="editsummary">
                    <p>Summary</p>
                    <div><textarea id="summary" name="summary" maxlength="250"><?php echo $server['summary']?></textarea></div>
                </div>
                <div class="big" id="editdesc">
                    <p>Description</p>
                    <div><textarea class="ckeditor" id="desc" name="desc"><?php echo $server['description']?></textarea></div>
                </div>
                <div class="submit">
                    <input type="submit" value="SUBMIT"/>
                </div>
            </div>
        </form>
        <form class="topform" id="votingdata" name="votingdata" action="" method="post">
            <div class="formshow">
        	<h1>Votifier</h1>
                <input name="votingdata" type="hidden" />
                <div>
                    <p>Enabled</p>
                    <?php 
					$server['votifier'] = explode("!!",$server['votifier']);
					if(!isset($server['votifier'][2])){
						$server['votifier'][1] = "";
						$server['votifier'][2] = "";
						$server['votifier'][3] = "";
					}
					?>
                    <select id="voting" name="enabled">
                        <option value="false">Disabled</option>
                        <option value="true" <?php if ($server['votifier'][0] == "true") echo 'selected="selected"';?>>Enabled</option>
                    </select>
                </div>
                <div>
                    <p>Address</p>
                    <div><input type="text" id="voteip" name="voteip" size="22" value="<?php echo $server['votifier'][1]?>" placeholder="<?php echo $server['ip']?>"/></div>
                </div>
                <div>
                    <p>Port</p>
                    <div><input type="text" id="voteport" name="voteport" size="25" placeholder="8192" value="<?php echo $server['votifier'][2]?>"/></div>
                </div>
                <div class="big">
                    <p>Votifier Public Key</p>
                    <div><textarea id="votekey" name="votekey"><?php echo htmlentities($server['votifier'][3])?></textarea></div>     
                </div>
                <div class="submit">
                    <input type="submit" value="SUBMIT"/>
                </div>
            </div>
        </form>
        <form class="topform" id="socialdata" name="socialdata" action="" method="post">
            <div class="formshow">
        	<h1>Social Networks</h1>
                <input name="socialdata" type="hidden" />
                    <?php 
						if(empty($server['social'])){
							for($i=0;$i<=2;$i++){
								$server['social'][$i][0] = "none";
								$server['social'][$i][1] = "";
							}
						}else{
							$server['social'] = explode("!!",$server['social']);
							foreach($server['social'] as $network => $link){
								$server['social'][$network] = explode(";",$link);
							}
						}
                    ?>
                <small>Please only put the id or username of the social networking website. So for "<a href="http://facebook.com/finestservers">http://facebook.com/finestservers</a>" you would put "finestservers".</small>
                <div class="social">
                    <p>Network 1</p>
                    <select id="socialname1" name="socialname1">
                    	<option value="disabled">none</option>
                        <option value="facebook" <?php if ($server['social'][0][0] == "facebook") echo 'selected="selected"';?>>Facebook</option>
                        <option value="twitter" <?php if ($server['social'][0][0] == "twitter") echo 'selected="selected"';?>>Twitter</option>
                        <option value="youtube" <?php if ($server['social'][0][0] == "youtube") echo 'selected="selected"';?>>Youtube</option>
                    	<option value="google" <?php if ($server['social'][0][0] == "google") echo 'selected="selected"';?>>Google Plus</option>
                    	<option value="email" <?php if ($server['social'][0][0] == "email") echo 'selected="selected"';?>>Email</option>
                    </select>
                    <input type="text" id="sociallink1" name="sociallink1" value="<?php echo $server['social'][0][1];?>"/>
                </div>
                <div class="social">
                    <p>Network 2</p>
                    <select id="socialname2" name="socialname2">
                    	<option value="disabled">none</option>
                        <option value="facebook" <?php if ($server['social'][1][0] == "facebook") echo 'selected="selected"';?>>Facebook</option>
                        <option value="twitter" <?php if ($server['social'][1][0] == "twitter") echo 'selected="selected"';?>>Twitter</option>
                        <option value="youtube" <?php if ($server['social'][1][0] == "youtube") echo 'selected="selected"';?>>Youtube</option>
                    	<option value="google" <?php if ($server['social'][1][0] == "google") echo 'selected="selected"';?>>Google Plus</option>
                    	<option value="email" <?php if ($server['social'][1][0] == "email") echo 'selected="selected"';?>>Email</option>
                    </select>
                    <input type="text" id="sociallink2" name="sociallink2" value="<?php echo $server['social'][1][1];?>"/>
                </div>
                <div class="social">
                    <p>Network 3</p>
                    <select id="socialname3" name="socialname3">
                    	<option value="disabled">none</option>
                        <option value="facebook" <?php if ($server['social'][2][0] == "facebook") echo 'selected="selected"';?>>Facebook</option>
                        <option value="twitter" <?php if ($server['social'][2][0] == "twitter") echo 'selected="selected"';?>>Twitter</option>
                        <option value="youtube" <?php if ($server['social'][2][0] == "youtube") echo 'selected="selected"';?>>Youtube</option>
                    	<option value="google" <?php if ($server['social'][2][0] == "google") echo 'selected="selected"';?>>Google Plus</option>
                    	<option value="email" <?php if ($server['social'][2][0] == "email") echo 'selected="selected"';?>>Email</option>
                    </select>
                    <input type="text" id="sociallink3" name="sociallink3" value="<?php echo $server['social'][2][1];?>"/>
                </div>
                <div class="submit">
                    <input type="submit" value="SUBMIT"/>
                </div>
            </div>
        </form>
        <form class="topform" id="gallery" name="gallery" action="" method="post">
            <div class="formshow">
        		<h1>Gallery</h1>
                <input name="gallery" type="hidden" />
                <div class="big" id="publickey">
                    <p>Gallery</p>
                    <div class="gallery"><?php 
					if(strlen($server['gallery']) > 1){
						$server['gallery'] = explode('!!', $server['gallery']);
						foreach($server['gallery'] as $data){
							$data = explode(";",$data);
							echo '<img height="100" src="'.$data[1].'" alt="'.$data[0].'">';
						}
					}
                    ?></div>
                </div>
                <div id="gallery">
                    <p>Add picture</p>
                    <input type="text" id="gallerylink" name="gallerylink" placeholder="http://www.mypichost.com/lol.png" size="40"/>
                </div>
                <div class="submit">
                    <input type="submit" value="SUBMIT"/>
                </div>
            </div>
        </form>
    </div>
</div>