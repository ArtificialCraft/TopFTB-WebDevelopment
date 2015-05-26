<?php 
require('checklogin.php');
$bid = getLastValidBid() + 5;
if(isset($_POST['bid'])){
	if($bid > $_POST['bid']){
		$error = "Your bid must be greater than the current minimum of $bid!";
	}else if($_POST['bid'] % 5 != 0){
		$error = "Your bid must be a multiple of 5!";
	}else{
		$db = getDB()->select(array('*'))->from('#__Bids');
		if($db->where(array(array('serverid', $_POST['server'])))->exec()->count() >= 1){
			$db->update('#__Bids')->set(array(array('bid', $_POST['bid']), array('link', $_POST['link']), array('img', $_POST['img']), array('time', time())))->exec();
		}else{
			$db->insert('#__Bids',array('fields' => array('id','ownerid', 'serverid', 'link', 'img', 'bid','paid', 'time'), 'values' => array(array('',$_SESSION['id'], $_POST['server'], $_POST['link'], $_POST['img'], $_POST['bid'], 'false', time()))))->exec();
		}
	}
}
$servers = null;
if(!isset($_SESSION['id'])){
	$servers = getDB()->select(array('id', 'title'))->from('#__ServerList')->where(array(array('ownerid', $_SESSION['id'])))->exec()->getResult();
	if(count($servers) == 0){
		$error = 'You do not have a server to bid for! If you wish to register a server please sign up <a href="add">here</a>!';
		$exit = true;
	}
}
if(isset($error)){
	echo '<div class="alert-error">Oops! Seems there are some errors..<br/>'. $error .'</div>';
	if($exit)
		exit();
	unset($error);
	unset($exit);
}
?>
<div id="bid">
    <form class="topform" action="" method="post" name="bid_form">
        <div class="formshow">
        	<div>
                <p>Server</p>
                <select id="selectserver" name="server">
                	<?php 
						$myservers = getDB()->select(array('name','id'))->from('#__ServerList')->where(array(array('ownerid',$_SESSION['id'])))->exec()->getResult();
						foreach($myservers as $server){
							echo '<option value="'.$server['id'].'"'.(isset($_GET['data']) && ($server['id'] == $_GET['data']) ? 'selected="selected"' : '').'>'.$server['name'].'</option>';
						}
					?>
                </select>
            </div>
            <small class="help">Which server are you bidding for?</small>
    		<div>
        		<p>Bid</p>
            	<input type="text" name="bid" size="27" placeholder="Multiple of 5" data-required="true"  onchange='if($(this).val()%5!=0){$(this).val(""); alert("The value must be a multiple of 5!");}else if($(this).val() < <?php echo $bid;?>){$(this).val(""); alert("The value must be greater than the current minimum of <?php echo $bid;?>!");}' required/>
        	</div>
            <small class="help">This is your bid in <a href="https://www.google.com/search?q=1gbp+converted">GBP</a>. <br>Current minimum of &pound;<?php echo $bid;?></small>
    		<div>
        		<p>Link</p>
            	<input type="text" name="link" size="26" placeholder="http://mywebsite.com" required/>
        	</div>
            <small class="help">Where your ad will direct users.</small>
    		<div>
        		<p>Image</p>
            	<input type="text" name="img" size="24" placeholder="http://mypic.com/img.jpg" required/>
        	</div>
            <small class="help">Link to image. Must be 468px * 60px.</small>
            <div class="checkboxes big">
            	<p>To bid you must agree to these conditions!</p>
            	<div>
                <input class="terms" name="agree" type="checkbox"> I agree to pay the specified amount if I am chosen to be sponsored!
        <br/><input class="terms" name="agree" type="checkbox"> I agree to the TopFTB <a href="tos">tos</a><br/>
                </div>
        	</div>
            <div class="submit">
            	<input type="submit" value="SUBMIT"/>
            </div>
       	</div>
    </form>
</div>