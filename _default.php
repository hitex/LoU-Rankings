<?php
require_once 'classes/lou_rankings/component/ComponentGoogleTimeGraph.php';
$result = Database::query("SELECT * FROM `dates`,`world_stats` WHERE `dates`.`date_sid`=`world_stats`.`date_sid` ORDER BY `dates`.`date_sid` DESC");
?>
<h2>Server stats</h2>

<?php
$cgtg = new ComponentGoogleTimeGraph();
$cgtg->addSeries("players", "Total players");
$cgtg->addSeries("points", "Avg. player points");

while($row = mysql_fetch_array($result)){
    $cgtg->addDate("players", $row['date_datetime'], $row['world_total_players'], "", "");
	$cgtg->addDate("points", $row['date_datetime'], $row['world_average_player_points'], "", "");
}

echo $cgtg->draw();
?>