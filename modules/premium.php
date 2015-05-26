<?php 
require('checklogin.php');
if(isset($_POST['lvl'])){
	$level = array('0.5' => "blue", '1' =>"gold");
	$cost = array(10 => "30 days",30 => "90 days",50 => "180 days", 90 => "360 days");
	$price = $_POST['lvl'] * $_POST['duration'];
	$days = str_replace(' days','', $cost[$_POST['duration']]);
    $query = array();
    $query['notify_url'] = 'http://www.topftb.com/paypal/paid.php';
    $query['cmd'] = '_xclick';
    $query['business'] = 'donate-facilitator@artificialcraft.net';//TODO remove facilitator
    $query['address_override'] = '1';
    $query['item_name'] = 'TopFTB Premium';
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
	
	$data = 'purchase!!TopFTB Premium|price!!'.$price.'|server!!'.$_POST['server'].'|level!!'.$level[$_POST['lvl']].'|duration!!'.$cost[$_POST['duration']];
	
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
<div id="purchasepremium">
	<script>
	var level=[0.5, 1.0];
	var cost=[10,30,50,90];
    function updateCost(){
		$level = level[$('#lvl').find(':selected').index()];
		$price = cost[$('#duration').find(':selected').index()];
		$days = $('#duration').find(':selected').text().split(" ")[0];
		$cost = $level * $price;
		$('#cost').val('£ ' + $cost);
		$('#cpd').text('£ ' + Math.round(($cost / $days) * 100) / 100 + "/per day");
	}
    </script>
    <div class="alert-info">Thank you for your interest in advertising with TopFTB! <br/>Premium slots allow you to change the color of your listing to better attract potential players. You can choose from the gold or blue levels and may select the duration. The cost and the cost per day will be updated for you.</div>
    <form class="topform" action="" method="post">
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
        	<div>
                <p>Type</p>
               	<select id="lvl" name="lvl" onchange="updateCost()">
					<option value="0.5">Level 1 - Blue</option>
					<option value="1">Level 2 - Gold</option>
				</select>
            </div>
            <div>
            	<p>Duration</p>
               	<select id="duration" name="duration" onchange="updateCost()">
					<option value="10">30 days</option>
					<option value="30">90 days</option>
					<option value="50">180 days</option>
					<option value="90">360 days</option>
				</select>
            </div>
            <div>
            	<p>Cost</p>
                <input id="cost" name="cost" type="text" readonly="readonly" size="25" value="£ 5" />
            </div>
            <small id="cpd" class="help">£ 0.17/per day</small>
            <div class="submit">
            	<input type="submit" value="SUBMIT"/>
            </div>
        </div>
    </form>
</div>