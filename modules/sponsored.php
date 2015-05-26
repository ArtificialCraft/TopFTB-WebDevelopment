<?php //status of bid: 0: made, 1:emailed, 2:paid, 3:sponsored and put into previous purchases
$mode = 0;//0 = bidding, 1 = paying
$end = new DateTime(date('Y-m-21 00:00:00'));
$now = new DateTime(date('Y-m-d h:i:s'));
$diff = $end->diff($now);
//$new = new DateTime(date('Y-'.(date('m')+1).'-1 00:00:00'));
if(date('d') == 1){
	$mode = 2;
}else if($end < $now){
	$mode = 1;
}
$data = isset($_GET['data']);
$all = $data && $_GET['data'] == "all";
?>
<div id="sponsored">
    <div class="container-header">
    	<h2><img src="i/Core/icon/List.png" alt="E"/>&nbsp;&nbsp;&nbsp;SPONSORED SERVERS</h2>
        <div class="condesc">Every month TopFTB sponsors 5 servers for a full month. These servers will recieve commonly recieve more attention, traffic and players than other servers and are the first servers that <strong>all</strong> potential players will see. The top server is displayed in the header of <strong>every</strong> page on our website. The other five servers will be displayed on the top of the TopFTB server list. For more information please visit our <a style="color:#09F;" href="help/">help page</a>.</div>
    </div>
    <div class="container-body" id="bidlist">
    	<h2><ul>
        	<a href="<?php $var = $all ? "sponsored" : "#"; echo $var;?>"><li <?php if(!$data) echo 'class="active"'?>>Top Bids</li></a>
        	<a href="<?php $var = $all ? "#" : "sponsored/all"; echo $var;?>"><li <?php if($data) echo 'class="active"'?>>All Bids</li></a>
        	<a href="bid/"><li>Place a Bid</li></a>
        </ul></h2>
        <div>
        	<table id="bidtable">
            	<thead>
                	<th>#</th>
                	<th>Server</th>
                	<th>Bid</th>
                </thead>
                <tbody>
					<?php 
                        $db = getDB()->select(array('id', 'serverid', 'bid'))->from('#__Bids')->orderBy(array('bid'));
						if(!$all){
							$db->limit(6);
						}
						$db->exec();
						$bids = $db->getResult();
						foreach($bids as $id => $bid){
							$server = getServer('id', $bid['serverid']);
							echo "<tr>
									<td>$id</td>
									<td><a href=".BASE."server/".$bid['serverid']."/>".$server['name']."</a></td>
									<td>&pound; ".$bid['bid']."</td>
								</tr>";
						}
					?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="container-body" id="aucinfo">
    	<h2>&nbsp;<img src="i/Core/icon/Auction.png" alt="E"/>&nbsp;Auction Information</h2>
        <div>
        	<h3>How do I sponsor my server?</h3>
            <p>Simply visit <a href="<?php echo BASE;?>bid/">this page</a> and type in the highest amount you are willing to pay. If you are one of the top 5 bidders, and pay your bid, you will be sponsored for a month.</p>
        	<h3>When does the servers show on top?</h3>
            <p>On the first of every month the sponsored servers are updated. If there are no problems with your bid you will see your ad up.</p>
        	<h3>Can I change or retract my bid?</h3>
            <p>Yes, at any time before the auction closes. No questions asked.</p>
        	<h3>How can I pay?</h3>
            <p>You make your payment through <a href="http://www.paypal.com">Paypal</a>. Please note that paypal <strong>does</strong> accept credit cards.</p>
        	<h3>If chosen, am I obligated to pay? <i>What if I dont wanna?</i></h3>
            <p>You are obligated to pay. Failure to pay will result in a disqualification from any further competitions and a one-time reduction of your votes as determined by TopFTB. This is to prevent misuse of our auction program.</p>
        	<h3>Can I get a refund after the auction closes?</h3>
            <p>Refunds will be handled on a per-case basis and are not guaranteed.</p>
        </div>
    </div>
</div>
