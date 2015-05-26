<?php
	/*getDB()->delete('#__ServerList')->buildQuery('WHERE `id`!= 696969', true, false, false)->exec();
	$mp = 0;
	for ($i = 1; $i <= 205; $i++) {
			$serverid = getRandId("ServerList");
			$modpack = "Unleashed";
			$premium = "gold";
			if($mp > 3)
				$mp = 0;
			if($mp == 1){
				$modpack = "Unhinged";
				$premium = "blue";
			}else if($mp == 2){
				$modpack = "Ultimate";
				$premium = "normal";
			}else if($mp == 3){
				$modpack = "Mindcrack";
				$premium = "gold";
			}
			$mp++;
			$rand = rand(strtotime("-1week"),time());
			getDB()->insert('#__ServerList',array('fields' => array('id','ownerid', 'name', 'ip','rawip','modpack', 'premium', 'premiumduration', 'summary', 'players', 'maxplayers', 'votes', 'views', 'registered', 'updated', 'uptime'), 'values' => array(array($serverid, "696969", "Server " . $i, "playac.ca", "192.99.33.177:25565", $modpack, $premium, '1401483746', '<b>This server is so cool like omg.</b> This server is so cool like omg. This server is so cool like omg. This server is so cool like omg. This server is so cool like omg. This server is so cool like omg. This server is so cool like omg. This server', (2*$i), (1000 - (2*$i)), (600 - (2*$i)), rand(0, 1500), floor(time()*1.95) - $rand,$rand, 8*$i))))->exec();
	}*/
	$ad1 = getRandomAd(1);
	$ad2 = getRandomAd(2);
	$servers = getDB()->select(array('id','name', 'modpack', 'ip', 'players', 'maxplayers', 'website', 'summary', 'social', 'votes', 'uptime','updated','premium', 'premiumduration', 'views','registered', 'uptime', 'updated'))->from('#__ServerList');
	if(!isset($_GET['data']) || $_GET['data'] == "rank" || $_GET['data'] == "votes"){
		$servers = $servers->orderBy(array('votes'));
	}else if(in_array($_GET['data'], array('uptime', 'players', 'views'))){
		$servers = $servers->orderBy(array(strtolower($_GET['data'])));
	}else if($_GET['data'] == "oldest"){
		$servers = $servers->orderBy(array(array('registered', 'ASC')));
	}else if($_GET['data'] == "new"){
		$servers = $servers->orderBy(array('registered'));
	}else if($_GET['data'] == "recent"){
		$servers = $servers->orderBy(array('updated'));
	}else if(startswith($_GET['data'], "modpack")){
		$servers = $servers->where(array(array("modpack", strtolower(str_replace("modpack-", "", $_GET['data'])))))->orderBy(array('votes'));
	}else if($_GET['data'] == ""){
	}else if($_GET['data'] == ""){
	}else if($_GET['data'] == ""){
	}else if($_GET['data'] == ""){
	}else if($_GET['data'] == ""){
	}else{
		$servers = $servers->orderBy(array('votes'));
	}
	$start = 0;
	if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 1){
		$start = (25 * ($_GET['page'] -1));
	}
	$servers = $servers->limit(25, $start)->exec()->getResult();
	foreach($servers as $id => $server){
		copy("/Users/Anmol/Desktop/meh.gif", "/Users/Anmol/Sites/topftb/uploaded/images/server/server_".$server['id'].".gif");
	}
?>
<div id="sponsoredservers">
	<div id="buffer">
        <ul>
            <?php
                $sponsoredservers = getDB()->select(array('id','serverid', 'link', 'bid'))->from('#__SponsoredServers')->orderBy(array('bid'))->exec()->getResult();
                foreach($sponsoredservers as $id => $sponsored){
                    $server = getServer('id', $sponsored['serverid']);
					$socialdata = explode('!!', $server['social']);
					foreach($socialdata as $data){
						$data = explode(';', $data);
						$id = trim($id);
						if(!isset($id))
							continue;
						if(!empty($data[0]))	
							$social[$data[0]] = getSocialLink($data[0], $data[1]);
					}
                    echo '<li class="server-sponsored">
                        <div class="rank">!</div>
                        <div class="info">
                            <h3>'.htmlentities($server['name']).'</h3>
                            <div class="banner">
								<img src="'.$server['banner'].'" alt="'.$server['name'].'" />
							</div>
                        </div>
                        <div class="details">
                            <input class="ip left" readonly="readonly" value="'.$server['ip'].'">
                            <div class="social">
                                <ul>';
								foreach($socialdata as $network => $link){
									echo '<a href="'.$link.'"><li class="icn-'.$network.'"></li></a>';
								}
                                echo '</ul>
                            </div>
                            <div class="stats left">
                                <div class="players">'.$server['players'].'/'.$server['maxplayers'].'</div>
                                <div class="votes">'.$server['votes'].'</div>
                            </div>
                            <div class="modpack left">
                                Modpack: Unleashed'.$server['modpack'].'
                            </div>
                        </div>
                    </li>';
                }
				unset($social);
				unset($socialdata);
				unset($id);
            ?>
        </ul>
    </div>
</div>
<div class="serverlist">
    <div class="title">
    	<h1>Top FTB Servers</h1>
    </div>
    <div class="left" id="servers">
    	<?php 
			foreach($servers as $id => $server){
				$banner = 'uploaded/images/server/server_'.$server['id'].'.gif';
				if($server['updated'] < time()){
					aSyncUpdateServer(array('key' => 'id', 'value' => $server['id']));
				}
				if($server['premium'] != "normal" && $server['premiumduration'] < time()){
					$server['premium'] = 'normal';
					setPremium($server['id'], 'normal');
					echo 'hey';
				}
				$server['sociallist'] = array();
				$server['social'] = htmlentities($server['social']);
				$socialdata = explode('!!', $server['social']);//facebook;blahblahblah!!twitter;blah...
				foreach($socialdata as $data){//facebook;blahblahblah
					$data = explode(';', $data);//$network and $id
					if(!isset($data[1]))
						continue;
					$data[1] = getSocialLink($data[0], $data[1]);
					if(isset($data[0]))
						array_push($server['sociallist'], $data);
				}
				$nosocial = "";
				if(!isset($server['sociallist'][0])){
					$nosocial = "nosocial";
				}
				echo '<div class="server-'.$server['premium'].'">
					<div class="rank">'.($start + $id).'</div>
					<div class="info">
						<h2>'.htmlentities($server['name']).'</h2>
						<div class="banner"><a href="server/'.$server['id'].'"><img width="468px" height="60px" src="'.$banner.'" /></a></div>
						<p class="description">'.htmlentities($server['summary']).'</p>
					</div>
					<div class="details">
						<input class="ip" readonly="readonly" value="'.$server['ip'].'">
						<div class="stats left '.$nosocial.'">
							<div class="players">'.$server['players'].'/'.$server['maxplayers'].'</div>
							<div class="votes">'.$server['votes'].'</div>
						</div>
						<div class="social">
                                <ul>';
								foreach($server['sociallist'] as $id => $data){
									if(!empty($data[0]))
										echo '<a href="'.$data[1].'"><li class="icn-'.$data[0].'"></li></a>';
								}
                                echo '</ul>
						</div>
						<div class="rating '.$nosocial.'">
							<div class="base full"></div>
							<div class="average full" style="width:'.'60%;"></div>
						</div>
                		<div class="modpack '.$nosocial.'">Modpack: '.$server['modpack'].'</div>
					</div>
				</div>';
			}?>
    </div>
</div>
<div id="banners">
	<a target="_blank" href="adout/<?php echo $ad1['id'];?>/"><img height="600" width="160" class="sidebanner" src="<?php echo $ad1['image'];?>"></a>
	<a target="_blank" href="adout/<?php echo $ad2['id'];?>/"><img height="600" width="160" class="sidebanner" src="<?php echo $ad2['image'];?>"></a>
</div>
<div id="pagebar">
	<div id="selector"></div>
	<ul>
        <?php 
			if(!isset($_GET['page']))
				$_GET['page'] = 1;
			$pagestart = $start + 1;
			$page = $start / 25 + 1;
			if($pagestart < 51){
				$pagestart = 1;
				$page = 1;
				$prepage = "#";
				if($_GET['page'] > 1)
					$prepage = "page/1";
				echo '<a href="'.$prepage.'" class="arrow"><li class="arrow"><img src="i/Core/Servers/pagebar/left.png"></li></a>';
				for($i = 0; $i < 5; $i++){
					$class="";
					if($start == $pagestart - 1)
						$class='class="active"';
					echo '<a href="page/'.$page.' "'.$class.'><li>'.$pagestart.'-'.($pagestart + 24).'</li></a>';
					$pagestart += 25;
					$page++;
				}
				echo '<a href="page/'.($_GET['page']+1).'" class="arrow"><li class="arrow"><img src="i/Core/Servers/pagebar/right.png"></li></a>';
			}else{
				$rows = getDB()->buildQuery("SELECT COUNT( id ) AS ROWS FROM `TopFTB_ServerList`", false, false, false)->exec()->getResult();
				$rows = floor(($rows[1]['ROWS']-1)/25-1)*25;//the rows that 
				if($pagestart > $rows){
					$pagestart = ($rows-74);
					$page = floor($pagestart / 25 + 1);
					echo '<a href="'.($_GET['page']-1).'" class="arrow"><li class="arrow"><img src="i/Core/Servers/pagebar/left.png"></li></a>';
					for($i = 0; $i < 5; $i++){
						$class="";
						if($start == $pagestart - 1)
							$class='class="active"';
						echo '<a href="page/'.$page.' "'.$class.'><li>'.$pagestart.'-'.($pagestart + 24).'</li></a>';
						$pagestart += 25;
						$page++;
					}
					echo '<a href="page/'.($_GET['page']+1).'" class="arrow"><li class="arrow"><img src="i/Core/Servers/pagebar/right.png"></li></a>';
				}else{
					$pagestart -= 50;
					$page = floor($pagestart / 25 + 1);
					echo '<a href="'.($_GET['page']-1).'" class="arrow"><li class="arrow"><img src="i/Core/Servers/pagebar/left.png"></li></a>';
					for($i = 0; $i < 5; $i++){
						$class="";
						if($start == $pagestart - 1)
							$class='class="active"';
						echo '<a href="page/'.$page.' "'.$class.'><li>'.$pagestart.'-'.($pagestart + 24).'</li></a>';
						$pagestart += 25;
						$page++;
					}
					echo '<a href="page/'.($_GET['page']+1).'" class="arrow"><li class="arrow"><img src="i/Core/Servers/pagebar/right.png"></li></a>';
				}
			}
		?>
    </ul>
</div>