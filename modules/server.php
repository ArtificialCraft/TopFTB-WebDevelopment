<?php
	$server = getServer('id', $_GET['data']);
	if(!isset($server['id'])){
		$_SESSION['error'] = "This is an invalid server id, please check the url!";
		redirect('');
	}
	if($server['updated'] + 300 < time()){
		updateServer('id',$_GET['data']);
		$server = getServer('id', $_GET['data']);
	}
?>
<div id="server">
    <div id="heading">
        <ul>
            <li id="rank"><?php echo $server['rank'];?></li>
            <li><img width="468" height="60" src="<?php echo $server['banner']?>"/></li>
            <li id="info">
            	ModPack: <?php echo $server['modpack'];?><br />
                Players: <?php echo $server['players'];?><br />
                Votes: <?php echo $server['votes'];?><br />
            </li>
            <li id="status">
              	<div id="inner"<?php 
				if($server['online'] == 'true'){
					echo 'Online';
				}else{
					echo 'Offline';
				}
				?>></div>
                <?php 
				$now = new DateTime();
				$last = new DateTime();
				$last->setTimestamp($server['updated']);
				$diff = $now->diff($last);
				echo $diff->format('%i minutes ago');
				?>
            </li>
        </ul>
    </div>
    <div class="title">
    	<a href="vote/<?php echo $server['id'];?>"><div class="votefor left">Vote</div></a>
        <h1 class="left"><?php echo htmlentities($server['name']);?></h1>
        <a href="review/<?php echo $server['id'];?>"><div class="review right">Review</div></a>
    </div>
    <div id="display">
    	<div>
        	<ul>
            	<li>Information</li>
            	<li>Statistics</li>
            	<li>Gallery</li>
            	<a href="<?php echo $server['website']?>"><li>Website</li></a>
            </ul>
            <input id="serverip" type="text" value="<?php echo $server['ip'];?>" readonly="readonly" />
        </div>
        <div id="serverinfo">
            <div id="information" class="on"><?php echo $server['description'];?></div>
            <div id="statistics">2
            	<div id="chartdiv"></div>d
				<script src="js/amcharts.js" type="text/javascript"></script>
           		<script src="js/serial.js" type="text/javascript"></script>
                <script type="text/javascript">
                    var chartData = [
						<?php 
							$stats = explode('!!', $server['statistics']);
							$echo = "";
							foreach($stats as $log){
								$data = explode(';', $log);
								$echo .= "{\"date\":\"$data[0]\", \"players\":$data[1], \"votes\":$data[2]},";
							}
							echo substr($echo, 0, strlen($echo) - 1);
						?>
                    ];
					var chart;
					AmCharts.ready(function () {
						// SERIAL CHART
						chart = new AmCharts.AmSerialChart();
						chart.dataProvider = chartData;
                		chart.pathToImages = "i/Core/Servers/amchart/";
						chart.marginTop = 0;
		
						// AXES
						// category axis
						var categoryAxis = chart.categoryAxis;
						categoryAxis.autoGridCount = false;
						categoryAxis.gridCount = 50;
						categoryAxis.gridAlpha = 0.1;
						categoryAxis.gridColor = "#000000";
						categoryAxis.axisColor = "#555555";
		
						// Distance value axis 
						var distanceAxis = new AmCharts.ValueAxis();
						distanceAxis.title = "votes";
						distanceAxis.gridAlpha = 0;
						distanceAxis.inside = true;
						distanceAxis.axisAlpha = 0;
						chart.addValueAxis(distanceAxis);
		
						// as we have data of different units, we create two different value axes
						// Duration value axis            
						var durationAxis = new AmCharts.ValueAxis();
						durationAxis.title = "players";
						durationAxis.gridAlpha = 0.05;
						durationAxis.axisAlpha = 0;
						durationAxis.position = "right";
						durationAxis.inside = true;
						chart.addValueAxis(durationAxis);
		
						// GRAPHS
						// duration graph
						var durationGraph = new AmCharts.AmGraph();
						durationGraph.title = "votes";
						durationGraph.valueField = "votes";
						durationGraph.type = "line";
						durationGraph.valueAxis = durationAxis; // indicate which axis should be used
						durationGraph.lineColor = "#CC0000";
						durationGraph.balloonText = "[[value]] votes";
						durationGraph.lineThickness = 1;
						durationGraph.legendValueText = "[[value]]";
						chart.addGraph(durationGraph);
		
						// distance graph
						var distanceGraph = new AmCharts.AmGraph();
						distanceGraph.valueField = "players";
						distanceGraph.title = "players";
						distanceGraph.type = "line";
						distanceGraph.fillAlphas = 0.1;
						distanceGraph.valueAxis = distanceAxis; // indicate which axis should be used
						distanceGraph.balloonText = "[[value]] players";
						distanceGraph.legendValueText = "[[value]] p";
						distanceGraph.lineColor = "#000000";
						chart.addGraph(distanceGraph);
		
						// CURSOR                
						var chartCursor = new AmCharts.ChartCursor();
						chartCursor.zoomable = false;
						chartCursor.categoryBalloonDateFormat = "DD";
						chartCursor.cursorAlpha = 0;
						chart.addChartCursor(chartCursor);
		
						// LEGEND
						var legend = new AmCharts.AmLegend();
						legend.bulletType = "round";
						legend.equalWidths = false;
						legend.valueWidth = 120;
						legend.color = "#000000";
						legend.useGraphSettings = true;
						chart.addLegend(legend);
				
               			var chartScrollbar = new AmCharts.ChartScrollbar();
                		chart.addChartScrollbar(chartScrollbar);
		
						// WRITE                                
						chart.write("chartdiv");
					});
				</script>
            </div>
            <div id="gallery">3</div>
            <div id="ratings">4</div>
        </div>
    </div>
    <div id="stats">
    	<h2>Overall Rating: 98%</h2>
        <?php
        
		$categories = array('Gameplay,Support','Maintenance,Staff','Community,Care');
		
		echo '<table>';
		foreach($categories as $category){
			$category = explode(',',$category);
			echo '
			<tr>
            	<td>'.$category[0].'</td><td></div><div class="starsbg"></div><div class="stars" style="width:150px;"></td>
            	<td>'.$category[1].'</td><td><div class="starsbg"></div><div class="stars" style="width:100px;"></div></td>
            </tr>';
		}
		echo '</table>';
		?>
    </div>
</div>