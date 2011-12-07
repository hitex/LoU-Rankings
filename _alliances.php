<?php
require_once 'classes/lou_rankings/dao/DAODate.php';
require_once 'classes/lou_rankings/dao/DAOAlliance.php';
require_once 'classes/lou_rankings/component/ComponentGoogleTimeGraph.php';

$cgtg = new ComponentGoogleTimeGraph();

$alliances_str = $_REQUEST['alliances'];

if (empty($alliances_str)){
    $alliances_str = $_COOKIE['alliances'];
} else {
    setcookie("alliances", $alliances_str);
}

$alliances = explode(",", $alliances_str);

$alliancesData = array();

$i=0;
foreach ($alliances as $alliance) {
    $cgtg->addSeries(strtoupper($alliance), strtoupper($alliance));

    $result = DAOAlliance::getByName($alliance);
    while($row = mysql_fetch_array($result)){
        $alliancesData[$i][] = $row;
    }
    $i++;
}

$totalTimePoints = count($alliancesData[0]);
$totalalliances = count($alliancesData);
$dataRowsString = '';

switch($_GET['type']){
    default:
    case "score":
        $dataFieldName = 'alliance_score';
        break;
    
    case "rank":
        $dataFieldName = 'alliance_ranking';
        break;
		
	case "avg_score":
        $dataFieldName = 'alliance_average_score';
        break;
		
	case "cities":
        $dataFieldName = 'alliance_cities_count';
        break;
		
	case "members":
        $dataFieldName = 'alliance_members_count';
        break;
}

$i = 0;
for ($i = 0; $i < $totalTimePoints; $i++) {

    $time = strtotime($alliancesData[0][$i]['date_datetime']);

    for($j=0; $j < $totalalliances; $j++){
        $title = '';
        $text = '';

        // Change in alliance
        if($i > 0 && $alliancesData[$j][$i]['alliance_name'] != $alliancesData[$j][$i-1]['alliance_name']){
            if(empty($alliancesData[$j][$i-1]['alliance_name'])){
                $title .= 'Created. ';
                $text .= "Alliance " . $alliancesData[$j][$i]['alliance_name'] . " created.";
            } elseif (empty($alliancesData[$j][$i]['alliance_name'])) {
                $title .= 'Disbanded. ';
                $text .= "Alliance " . $alliancesData[$j][$i-1]['alliance_name'] . " disbanded.";
            }
        }
        
        // Settled new city
        if(false && $i > 0 && $alliancesData[$j][$i]['alliance_members_count'] > $alliancesData[$j][$i-1]['alliance_members_count']){
            $newCityCount = $alliancesData[$j][$i]['alliance_members_count'] - $alliancesData[$j][$i-1]['alliance_members_count'];
            $title .= 'Joined. ';
            $text .= $newCityCount . " new players joined " . $alliancesData[$j][$i]['alliance_name'] . ".";
        } else if ($i > 0 && $alliancesData[$j][$i]['alliance_members_count'] < $alliancesData[$j][$i-1]['alliance_members_count']) {
            $newCityCount = $alliancesData[$j][$i-1]['alliance_members_count'] - $alliancesData[$j][$i]['alliance_members_count'];
            $title .= 'Left. ';
            $text .= $newCityCount . " players left " . $alliancesData[$j][$i]['alliance_name'] . ".";
        }
        
        $cgtg->addDate(strtoupper($alliancesData[$j][$i]['alliance_name']), $alliancesData[$j][$i]['date_datetime'], $alliancesData[$j][$i][$dataFieldName], $title, $text);
        
    }
}

?>
<form action="index.php?id=alliances" method="POST">
    Input alliance names, separated by comma:<br/>
    <textarea type="text" name="alliances" cols="100"><?php echo $alliances_str ?></textarea><br/>
    <input type="submit" value="Submit" name="get"/><br/>
    <a href="index.php?id=alliances&alliances=<?php echo $alliances_str ?>&type=score">Score</a>
    | <a href="index.php?id=alliances&alliances=<?php echo $alliances_str ?>&type=avg_score">Avg. Score</a>
    | <a href="index.php?id=alliances&alliances=<?php echo $alliances_str ?>&type=rank">Rank</a>
    | <a href="index.php?id=alliances&alliances=<?php echo $alliances_str ?>&type=cities">Cities</a>
	| <a href="index.php?id=alliances&alliances=<?php echo $alliances_str ?>&type=members">Members</a>
</form>

<?php
echo $cgtg->draw();
?>

<br/>
Link: <input type="text" value="<?php echo $_CONFIG['server_url'] ?>/index.php?id=alliances&alliances=<?php echo $alliances_str ?>" />