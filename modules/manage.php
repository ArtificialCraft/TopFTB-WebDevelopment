<?php
require('checklogin.php');
?>
<div id="manage">
    <div class="container">
        <h2>My Servers <span class="right"><a href="add/" class="btn-page">+</a><span></h2>
        <table class="adv-table" id="MyServers">
            <?php 
                $db = getDB()->select(array('id', 'name', 'ip', 'website', 'votes', 'views', 'ownerid', 'registered', 'uptime'))->from('#__ServerList')->where(array(array('ownerid', $_SESSION['id'])))->orderBy(array('votes'));
                $db->exec();
                $servers = $db->getResult();
                    echo '<tr>
                        <th>rank</th>
                        <th>title</th>
                        <th>ip</th>
                        <th>website</th>
                        <th>votes</th>
                        <th>registered</th>
                        <th>actions</th>
                    </tr>';
				if(count($servers) == 0){
					echo '<tr><td colspan=7>You do not have any servers!</td><tr>';
				}
                foreach($servers as $id => $server){
					$server['rank'] = getDB()->select(array('COUNT(*)'))->from('#__ServerList')->buildQuery('WHERE votes>=\''.$server['votes'].'\'', true, false, false)->exec()->getResult();
                    echo '<tr>
                        <td>'.$server['rank'][1]['COUNT(*)'].'</td>
                        <td><a href="server/'.$server['id'].'">'.$server['name'].'</a></td>
                        <td>'.$server['ip'].'</td>
                        <td><a href="http://'.$server['website'].'">'.$server['website'].'</a></td>
                        <td>'.$server['votes'].'</td>
                        <td>'.date('M dS Y', $server['registered']).'</td>
                        <td>
                            <div class="dropdown-group">
                                <a class="btn-dropdown" data-toggle="dropdown">Action</a>
                                <ul class="dropdown-menu">
                                    <li><a href="edit/'. $server['id'] .'/">Edit</a></li>
                                    <li><a href="delete/'. $server['id'] .'">Delete Server</a></li>
                                    <li><a href="premium/'. $server['id'] .'">Upgrade</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>';
                }
            ?>
        </table>
    </div>
    <div class="container">
        <h2>My Bids <span class="right"><a href="bid/" class="btn-page">+</a><span></h2>
        <table class="adv-table" id="MyBids" cellpadding="0" cellspacing="0">
            <?php 
                $db = getDB()->select(array('id', 'serverid', 'link', 'img','bid', 'status', 'time'))->from('#__Bids')->where(array(array('ownerid', $_SESSION['id'])))->exec();
                $bids = $db->getResult();
                    echo '<tr>
                        <th>#</th>
                        <th>server</th>
                        <th>image</th>
                        <th>bid</th>
                        <th>status</th>
                        <th>time</th>
                        <th>actions</th>
                    </tr>';
				if(count($bids) == 0){
					echo '<tr><td colspan=7>You do not have any current bids!</td><tr>';
				}
                foreach($bids as $id => $bid){
                    $server = getServer('id',$bid['serverid']);
					if($bid['status'] == 0){
						$bid['status'] = "NOT PAID!";
					}
                    echo '<tr>
                        <td>'.$id.'</td>
                        <td><a href="server/'.$server['id'].'">'.$server['name'].'</a></td>
                        <td width="500px"><a href="'.$bid['link'].'"><img src="'.$bid['img'].'" width="468px" height="60px"/></a></td>
                        <td>&pound;'.$bid['bid'].'</td>
                        <td>'.$bid['status'].'</td>
                        <td>'.date('M dS h:i:s', $bid['time']).'</td>
                        <td>
                            <div class="dropdown-group">
                                <a class="btn-dropdown" data-toggle="dropdown">Action</a>
                                <ul class="dropdown-menu">
                                    <li><a href="bid/'. $bid['id'] .'/">Edit</a></li>
                                    <li><a href="bid/remove-'. $bid['id'] .'">Retract Bid</a></li>
                                    <li><a href="pay/'. $bid['id'] .'">Pay</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>';
                }
            ?>
        </table>
    </div>
    <div class="container">
        <h2>My Advertisements <span class="right"><a href="slot/" class="btn-page">+</a><span></h2>
        <table class="adv-table" id="MyAds" cellpadding="0" cellspacing="0">
            <?php 
                $ads = getDB()->select(array('ownerid', 'title', 'type', 'expiration', 'displays', 'clicks'))->from('#__Ads')->where(array(array('ownerid', $_SESSION['id'])))->exec()->getResult();
                    echo '<tr>
                        <th>#</th>
                        <th>title</th>
                        <th>expiration</th>
                        <th>slot</th>
                        <th>displays</th>
                        <th>clicks</th>
                    </tr>';
				if(count($ads) == 0){
					echo '<tr><td colspan=7>You do not have any current advertisements!</td><tr>';
				}
                foreach($ads as $id => $ad){
                    $expire = date('M dS Y', $ad['expiration']);
                    if($ad['expiration'] < time())
                        $expire = '<span style="color:red;">expired!</span>';
					if($ad['type'] == 0){
						$ad['type'] = "bottom";
					}else{
						$ad['type'] = "top";
					}
                    echo '<tr>
                        <td>'.$id.'</td>
                        <td>'.$ad['title'].'</td>
                        <td>'.$expire.'</td>
                        <td>'.$ad['type'].'</td>
                        <td>'.round($ad['displays']*1.8) .'</td>
                        <td>'.$ad['clicks'].'</td>
                    </tr>';
                }
            ?>
        </table>
    </div>
</div>