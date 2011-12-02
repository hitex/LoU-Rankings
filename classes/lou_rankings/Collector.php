<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Collector
 *
 * @author Vladas
 */
class Collector {

    private $_startTime;

    private $_date_sid;

    private $_passed_collectPlayerRankings = false;



    public function collect(){
        $this->_startTime = time();
        $this->_log("Collector started...");
        
        ini_set('max_execution_time', 3600);

        $this->_collectPlayerRankings();
        $this->_calculateWorldStats();
        $this->_generateAllianceData();

        $this->_log("Collector finished in " . (time() - $this->_startTime));
    }



    private function _collectPlayerRankings()
    {
        $this->_log("Collecting player rankings...");

        $louPlayer = new LOUPlayer();
        
        $totalPlayers = $louPlayer->getCount();
        
        $playerRankings = $louPlayer->getRange(0, $totalPlayers);
        $playerRankings = $this->_makeIndexed("i", $playerRankings);
        $offensiveData = $louPlayer->getRange(0, $totalPlayers, -1, LOUPlayer::$SORT_BY_OFFENSIVE_FAME, true, LOUPlayer::$TYPE_OFFENSIVE_FAME);
        $offensiveData = $this->_makeIndexed("i", $offensiveData);
        $defensiveData = $louPlayer->getRange(0, $totalPlayers, -1, LOUPlayer::$SORT_BY_DEFENSIVE_FAME, true, LOUPlayer::$TYPE_DEFENSIVE_FAME);
        $defensiveData = $this->_makeIndexed("i", $defensiveData);
        
        $playerRankings = $this->_mergeData($playerRankings, $offensiveData, "p", "offensive_fame");
        $playerRankings = $this->_mergeData($playerRankings, $offensiveData, "r", "offensive_rank");
        
        $playerRankings = $this->_mergeData($playerRankings, $defensiveData, "p", "defensive_fame");
        $playerRankings = $this->_mergeData($playerRankings, $defensiveData, "r", "defensive_rank");
        
        

        $date = date("Y-m-d H:i:s");
        Database::query("INSERT INTO `dates` SET `date_datetime`='$date'");
        $this->_date_sid = mysql_insert_id();

        foreach ($playerRankings as $player) {
            $pl_name = substr($player['n'], 1);
            $pl_status = substr($player['n'], 0, 1);
            Database::query("INSERT
                INTO `players`
                SET
                `date_sid`='" . $this->_date_sid . "',
                `player_id`='{$player['i']}',
                `player_name`='$pl_name',
                `player_status`='$pl_status',
                `alliance_id`='{$player['j']}',
                `alliance_name`='{$player['a']}',
                `player_points`='{$player['p']}',
                `player_ranking`='{$player['r']}',
                `player_cities`='{$player['c']}',
                `player_offensive_fame`='{$player['offensive_fame']}',
                `player_defensive_fame`='{$player['defensive_fame']}',
                `player_offensive_rank`='{$player['offensive_rank']}',
                `player_defensive_rank`='{$player['defensive_rank']}'"
           );
        }

        $this->_passed_collectPlayerRankings = true;

        $this->_log("Player rankings collected!");
    }
    
    
    
    private function _makeIndexed($index, $array)
    {
        $new = array();

        foreach ($array as $value) {
            $new[$value[$index]] = $value;
        }
        return $new;
    }
    
    
    
    private function _mergeData($a, $b, $what, $where)
    {
        foreach ($b as $k => $v) {
            $a[$k][$where] = $v[$what];
        }
        return $a;
    }



    private function _calculateWorldStats()
    {
        if (!$this->_passed_collectPlayerRankings) {
            return;
        }

        $this->_log("Calculating world stats...");

        Database::query(
            "INSERT
            INTO `world_stats`
            SET
            `date_sid`='" . $this->_date_sid . "',
            `world_average_player_points`=(SELECT AVG(`players`.`player_points`) FROM `players` WHERE `date_sid`='" . $this->_date_sid . "'),
            `world_total_players`=(SELECT COUNT(*) FROM `players` WHERE `date_sid`='" . $this->_date_sid . "')"
        );

        $this->_log("World stats calculated.");
    }
    
    
    
    private function _generateAllianceData()
    {
        $result = mysql_query("SELECT 
            DISTINCT(`alliance_id`) as alliance_id, 
            `alliance_name` 
            FROM `players` 
            WHERE `date_sid`='{$this->_date_sid}' AND `alliance_id` > 0");
        $allAlliances = array();
        while($row = mysql_fetch_assoc($result)){
            $allAlliances[] = $row;
        }

        foreach ($allAlliances as $key => $alliance) {
            $result = mysql_query("SELECT 
                COUNT(`player_id`) as alliance_members_count, 
                SUM(`player_points`) as alliance_score, 
                SUM(`player_cities`) as alliance_cities_count
                FROM `players` 
                WHERE `date_sid`='{$this->_date_sid}' 
                AND `alliance_id`='" . $alliance['alliance_id'] . "'"
            );
            $allAlliances[$key] += mysql_fetch_assoc($result);
            $allAlliances[$key]['alliance_average_score'] = round($allAlliances[$key]['alliance_score'] / $allAlliances[$key]['alliance_members_count']);
        }

        $allianceForSort = array();
        foreach ($allAlliances as $alliance) {
            $allianceForSort[$alliance['alliance_score']] = $alliance;
        }

        ksort($allianceForSort);
        $allianceForSort = array_reverse($allianceForSort);

        $alliancePlaceCount = 1;
        $lastScore = -1;
        $place = -1;
        foreach ($allianceForSort as $alliance) {
            if($alliance['alliance_score'] != $lastScore){
                $place = $alliancePlaceCount;
                $lastScore = $alliance['alliance_score'];
            }
            $sql = "INSERT 
                INTO `alliances`
                SET
                `date_sid`='{$this->_date_sid}',
                `alliance_id`='{$alliance['alliance_id']}',
                `alliance_name`='{$alliance['alliance_name']}',
                `alliance_average_score`='{$alliance['alliance_average_score']}',
                `alliance_score`='{$alliance['alliance_score']}',
                `alliance_ranking`='$place',
                `alliance_cities_count`='{$alliance['alliance_cities_count']}',
                `alliance_members_count`='{$alliance['alliance_members_count']}'";
            //echo $sql;

            mysql_query($sql);
            $alliancePlaceCount++;
        }
    }


    
    private function _log($message)
    {
        file_put_contents('collector.log', date("Y-m-d H:i:s") . ' | ' . $message . "\n", FILE_APPEND);
    }
}
?>
