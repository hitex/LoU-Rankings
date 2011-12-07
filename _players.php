<?php
//error_reporting(E_ALL);
//ini_set("display_errors", "1");

require_once 'classes/lou_rankings/dao/DAODate.php';
require_once 'classes/lou_rankings/dao/DAOPlayer.php';
require_once 'classes/lou_rankings/component/ComponentGoogleTimeGraph.php';

$cgtg = new ComponentGoogleTimeGraph();

$players_str = $_REQUEST['players'];

if (empty($players_str)){
    $players_str = $_COOKIE['players'];
} else {
    setcookie("players", $players_str);
}

$players = explode(",", $players_str);

$playersData = array();

$i=0;
foreach ($players as $player) {
    $cgtg->addSeries(strtoupper($player), strtoupper($player));

    $result = DAOPlayer::getByName($player);
    while($row = mysql_fetch_array($result)){
        $playersData[$i][] = $row;
    }
    $i++;
}

$totalTimePoints = count($playersData[0]);
$totalPlayers = count($playersData);
$dataRowsString = '';

switch($_GET['type']){
    default:
    case "score":
        $dataFieldName = 'player_points';
        $topFor = 'player_points';
        break;
		
	case "cities":
        $dataFieldName = 'player_cities';
        $topFor = 'player_cities';
        break;
    
    case "rank":
        $dataFieldName = 'player_ranking';
        $topFor = 'player_points';
        break;
    
    case "offence":
        $dataFieldName = 'player_offensive_fame';
        $topFor = 'player_offensive_fame';
        break;
    
    case "defence":
        $dataFieldName = 'player_defensive_fame';
        $topFor = 'player_defensive_fame';
        break;
    
    case "offence_r":
        $dataFieldName = 'player_offensive_rank';
        $topFor = 'player_offensive_fame';
        break;
    
    case "defence_r":
        $dataFieldName = 'player_defensive_rank';
        $topFor = 'player_defensive_fame';
        break;
}

$i = 0;
for ($i = 0; $i < $totalTimePoints; $i++) {

    $time = strtotime($playersData[0][$i]['date_datetime']);

    for($j=0; $j < $totalPlayers; $j++){
        $title = '';
        $text = '';
        
        // Change in alliance
        if($i > 0 && $playersData[$j][$i]['alliance_name'] != $playersData[$j][$i-1]['alliance_name']){
            $allianceMsg = '';
            if(!empty($playersData[$j][$i-1]['alliance_name'])){
                $allianceMsg .= $playersData[$j][$i]['player_name'] . " left alliance {$playersData[$j][$i-1]['alliance_name']}";
                if(!empty($playersData[$j][$i]['alliance_name'])){
                    $allianceMsg .= " and joined {$playersData[$j][$i]['alliance_name']}";
                }
            } else {
                $allianceMsg .= $playersData[$j][$i]['player_name'] . " joined {$playersData[$j][$i]['alliance_name']}";
            }

            $title .= 'Alliance. ';
            $text .= $allianceMsg . '. ';
        }
        
        // Hit new 5k points
        /*if($i > 0 && floor($playersData[$j][$i]['player_points'] / 5000) > floor($playersData[$j][$i-1]['player_points'] / 5000)){
            $pointsMsg = $playersData[$j][$i]['player_name'] . " passed " . floor($playersData[$j][$i]['player_points'] / 1000) . "k points";
            $title .= 'Points. ';
            $text .= $pointsMsg . '. ';
        }*/

        // Got crown
        /*if($i > 0 && $playersData[$j][$i]['player_status'] != $playersData[$j][$i-1]['player_status']){
            $title .= 'Crowned. ';
            $text .= $playersData[$j][$i]['player_name'] . " got crowned. ";
        }*/

        // Settled new city
        if($i > 0 && $playersData[$j][$i]['player_cities'] > $playersData[$j][$i-1]['player_cities']){
            $newCityCount = $playersData[$j][$i]['player_cities'] - $playersData[$j][$i-1]['player_cities'];
            $title .= 'City settled. ';
            $text .= $playersData[$j][$i]['player_name'] . " settled $newCityCount new cities. ";
        } else if ($i > 0 && $playersData[$j][$i]['player_cities'] < $playersData[$j][$i-1]['player_cities']) {
            $title .= 'City lost. ';
            $text .= $playersData[$j][$i]['player_name'] . " lost his city. ";
        }
        
        $cgtg->addDate(strtoupper($playersData[$j][$i]['player_name']), $playersData[$j][$i]['date_datetime'], $playersData[$j][$i][$dataFieldName], $title, $text);
        
    }
}
?>

<form action="index.php?id=players" method="POST">
    Input player names, separated by comma:<br/>
    <textarea type="text" name="players" cols="100"><?php echo $players_str ?></textarea><br/>
    <input type="submit" value="Submit" name="get"/><br/>
    <a href="index.php?id=players&players=<?php echo $players_str ?>&type=score">Score</a>
    | <a href="index.php?id=players&players=<?php echo $players_str ?>&type=rank">Rank</a>
    | <a href="index.php?id=players&players=<?php echo $players_str ?>&type=cities">Cities</a>
    | <a href="index.php?id=players&players=<?php echo $players_str ?>&type=offence">Offensive Fame</a>
    | <a href="index.php?id=players&players=<?php echo $players_str ?>&type=offence_r">Offensive Rank</a>
    | <a href="index.php?id=players&players=<?php echo $players_str ?>&type=defence">Defensive Fame</a>
    | <a href="index.php?id=players&players=<?php echo $players_str ?>&type=defence_r">Defensive Rank</a>
</form>

<?php
echo $cgtg->draw();

echo "Top players: ";
$allTop = array();
$topPlayers = DAOPlayer::getTop(DAODate::getLastDateSid(), $topFor);
while ($topPlayer = mysql_fetch_array($topPlayers)) {
    $allTop[] = $topPlayer['player_name'];
    echo '<a href="index.php?id=players&players=' . $topPlayer['player_name'] . '&type=' . $_GET['type'] . '">' . $topPlayer['player_name'] . '</a> [' . $topPlayer[$topFor] . '] | ';
}
echo '<a href="index.php?id=players&players=' . implode(",", $allTop) . '&type=' . $_GET['type'] . '">Compare All</a>';
?>

<br/>
Link: <input type="text" value="<?php echo $_CONFIG['server_url'] ?>/index.php?id=players&players=<?php echo $players_str ?>&type=<?php echo $_GET['type'] ?>" />