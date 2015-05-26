<?php
if(isset($_POST['slot'])){
	// Prepare GET data
	$slot = array('0.5' => "2", '1' =>"1");
	$cost = array(10 => "7 days",20 => "14 days",36 => "30 days", 60 => "60 days");
	$price = $_POST['slot'] * $_POST['duration'];
	$days = str_replace(' days','', $cost[$_POST['duration']]);
    $query = array();
    $query['notify_url'] = 'http://www.topftb.com/paypal/paid.php';
    $query['cmd'] = '_xclick';
    $query['business'] = 'donate-facilitator@artificialcraft.net';//TODO remove facilitator
    $query['address_override'] = '1';
    $query['item_name'] = 'TopFTB Side Banner';
    $query['amount'] = $price;
    $query['no_note'] = '1';
    $query['no_shipping'] = '1';
    $query['return'] = BASE.'manage';
    $query['cancel_return'] = BASE.'slot';
    $query['lc'] = 'ca';
    $query['currency_code'] = 'GBP';
	$owner = 00000;
	if(isset($_SESSION['id']))
		$owner = $_SESSION['id'];
	
	$data = 'purchase!!TopFTB Side Banner|price!!'.$price.'|type!!'.$slot[$_POST['slot']].'|duration!!'.$cost[$_POST['duration']].'|owner!!'.$owner.'|title!!'.str_replace('|', '-',$_POST['title']).'|link!!'.$_POST['link'].'|image!!'.$_POST['image'];
	
	$time = time();
	//insert tobepurchased
	getDB()->insert('#__ToBePurchased',array('fields' => array('id','time','data'), 'values' => array(array('',$time,$data))))->exec();
	
	$id = getDB()->select(array('id'))->from('#__ToBePurchased')->where(array(array('time', $time)))->exec()->getResult();
    $query['item_number'] = $id[1]['id'];

    // Prepare query string
    $query_string = http_build_query($query);

    //TODO header('Location: https://www.paypal.com/cgi-bin/webscr?' . $query_string);
    header('Location: https://www.sandbox.paypal.com/cgi-bin/webscr?' . $query_string);
}

?>
<div id="purchaseslot">
	<script>
	var slot=[0.5, 1.0];
	var cost=[10,20,36,60];
    function updateCost(){
		$slot = slot[$('#slot').find(':selected').index()];
		$price = cost[$('#duration').find(':selected').index()];
		$days = $('#duration').find(':selected').text().split(" ")[0];
		$cost = $slot * $price;
		$('#cost').val('£ ' + $cost);
		$('#cpd').text('£ ' + Math.round(($cost / $days) * 100) / 100 + "\/day");
	}
    </script>
    <div class="alert-info">Thank you for your interest in advertising with TopFTB! <br/> To purchase a slot simply select which slot you want and the duration you want. The cost will be updated for you.<br/>You are also required to input a link, where your users will be redirected and a link for the image you wish to display. Please ensure that the image is 160px * 600px for the best quality. If it is not this size it will be scaled.<?php if(!isset($_SESSION['id']))echo '<br/><strong>To track how many views and clicks this ad recieves please register or login.</strong>'?></div>
    <form class="topform" action="" method="post">
        <div class="formshow">
        	<div>
                <p>Slot</p>
               	<select id="slot" name="slot" onchange="updateCost()">
					<option value="0.5">Bottom Slot</option>
					<option value="1">Top Slot</option>
				</select>
            </div>
            <div>
            	<p>Duration</p>
               	<select id="duration" name="duration" onchange="updateCost()">
					<option value="10">7 days</option>
					<option value="20">14 days</option>
					<option value="36">30 days</option>
					<option value="60">60 days</option>
				</select>
            </div>
            <div>
            	<p>Cost</p>
                <input id="cost" name="cost" type="text" readonly="readonly" size="25" value="£ 5" />
            </div>
            <small id="cpd" class="help">£ 1.42/day</small>
            <div>
            	<p>Ad Title</p>
                <input id="title" name="title" type="text" size="23" placeholder="" />
            </div>
            <small class="help">A title to identify your ad.</small>
            <div>
            	<p>Link</p>
                <input id="link" name="link" type="text" size="26" placeholder="http://www.mywebsite.com"/>
            </div>
            <small class="help">The website the users will visit.</small>
            <div>
            	<p>Image</p>
                <input id="image" name="image" type="text" size="25" placeholder="http://mypic.com/img.png"/>
            </div>
            <small class="help">The link to your image!</small>
            <div class="submit">
            	<input type="submit" value="SUBMIT"/>
            </div>
        </div>
    </form>
</div>