<div id="header">
    <div id="topbar">
        <div class="buffer">
            <p id="date" class="topbarbtn left"><?php echo date('D M d Y');?></p>
            <ul id="auth">
                <?php 
                        if(!isset($_SESSION['loggedin'])){
                            echo '<li class="topbarbtn right">
                                <a href="register" >Register</a>
                            </li>
                            <li class="topbarbtn right">
                                <a href="javascript:toggleLogin();">Login</a>
                            </li>';
                        }else{
                            echo '
                            <li class="topbarbtn right">
                                <a href="logout">Logout</a>
                            </li>
							<li class="topbarbtn right">
                                <a href="manage" >'. $_SESSION['un'] .'</a>
                            </li>';	
                        }
                    ?>
            </ul>
        </div>
    </div>
    <div id="promotion"> 
    	<a href="./"><div id="banner"></div> </a> 
        <?php
		$sotw = getSOTW();
		echo '<a href="'.$sotw['website'].'"> <div id="sotw"><span id="caption"> Server of the Week </span><img src="'.$sotw['banner'].'"></div> </a>';
		?>
    </div>
    <div id="navbar">
        <div class="buffer">
            <ul>
                <?php 
                    $nav = array(
                        'Home' => array(),
                        'Top List' => array('Rank' => 'sort/rank', 'Uptime' => 'sort/uptime', 'Players' => 'sort/players', 'Views' => 'sort/views'),
                        'Time' => array('Longest Listed' => 'sort/oldest', 'New Servers' => 'sort/new', 'Recently Updated' => 'sort/recent'),
						'Modpacks' => array('Unleashed' => '#', 'Unhinged' => '#', 'DireWolf 1.5.2' => '#', 'Ultimate' => '#', 'Mindcrack' => '#', 'DireWolf 1.4.7' => '#', 'Yogscraft' => '#'),
                        'Advertise ' => array('Sponsorship' => 'sponsored', 'Premium' => 'premium', 'Side Banner' => 'slot'),
                        'Partners' => array('Become a Partner' => 'partner'),
                        'Support' => array("FAQ" => '#', "Bugs" => '#', "New Features" => '#', "Contact Us" => 'contactus')
                    );
					$i = 0;
                    foreach($nav as $link => $list) {
						echo '<li class="ddm-nav"> 
							<a href="#"><h5>'.$link.'</h5></a>
							<img class="arrow" src="i/Header/NavBar/Module/Arrow.png" alt="V" />
							<div class="details" style="margin-left:-'.$i.'px;"><ul>'.PHP_EOL;
								foreach($list as $mini => $minilink){
									$name = str_replace(' ', '-',$mini);
								echo '<a target="_self" href="'.$minilink.'">
								<li>
									<img width="40" height="40" src="i/Header/NavBar/Extention/icons/'.$link.'/'.$name.'.png" alt="'.$mini.'"/>
									<span>'.$mini.'</span>
								</li></a>'.PHP_EOL;
									}
							echo '</ul></div>
						</li>'.PHP_EOL;
						$i += 143;
                    }
                ?>
            </ul>
        </div>
    </div>
    <div id="navdetails"></div>
</div>
