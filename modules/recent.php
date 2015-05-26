<?php
	$ad1 = getRandomAd(1);
	$ad2 = getRandomAd(2);
?>
<div class="serverlist">
    <h1>Top FTB Servers</h1>
    <div class="left" id="servers">
    	<?php 
			$db = getDB()->select(array('id','name', 'ip', 'players', 'maxplayers', 'website', 'summary', 'banner', 'votes', 'views', 'registered', 'uptime', 'updated'))->from('#__ServerList')->orderBy(array('votes'))->exec();
			$servers = $db->getResult();
			foreach($servers as $id => $server){
				if($server['updated'] < time()){
					aSyncUpdateServer(array('key' => 'id', 'value' => $server['id']));
				}
				echo '<div class="server-gold">
					<div class="rank">'.$id.'</div>
					<div class="info">
						<h2>'.htmlentities($server['name']).'</h2>
						<div class="banner"><a href="server/'.$server['id'].'"><img width="468px" height="60px" src="'.$server['banner'].'" /></a></div>
						<p class="description">'.htmlentities($server['summary']).'</p>
					</div>
					<div class="details">
						<input class="ip" readonly="readonly" value="'.$server['ip'].'">
						<div class="stats left">
							<div class="players">'.$server['players'].'/'.$server['maxplayers'].'</div>
							<div class="votes">'.$server['votes'].'</div>
						</div>
						<div class="social right">
							<ul>
								<a href=""><li class="icn-fbook"></li></a>
								<a href=""><li class="icn-twitter"></li></a>
								<a href=""><li class="icn-youtube"></li></a>
							</ul>
						</div>
						<div class="rating">
							<div class="base full"></div>
							<div class="average full" style="width:95%;"></div>
							<span>98.25% - 24515 ratings</span>
						</div>
					</div>
				</div>';
			}
		?>
    </div>
</div>
    <div id="banners">
    	<a href="adout/<?php echo $ad1['id'];?>/"><img height="600" width="160" class="sidebanner" src="<?php echo $ad1['link'];?>"></a>
    	<a href="adout/<?php echo $ad2['id'];?>/"><img height="600" width="160" class="sidebanner" src="<?php echo $ad2['link'];?>"></a>
    </div>