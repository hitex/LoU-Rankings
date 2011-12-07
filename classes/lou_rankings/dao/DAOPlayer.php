<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'DAO.php';

/**
 * Description of DAOPlayer
 *
 * @author Vladas
 */
class DAOPlayer extends DAO {
    
    public static $RANK_BY_RANK = "player_ranking";
    public static $RANK_BY_CITIES = "player_ranking";
    public static $RANK_BY_DEFENSIVE_FAME = "player_defensive_fame";
    public static $RANK_BY_DEFENSIVE_RANK = "player_defensive_rank";
    public static $RANK_BY_OFFENSIVE_FAME = "player_offensive_fame";
    public static $RANK_BY_OFFENSIVE_RANK = "player_offensive_rank";

    public static function getByName($string)
    {
        $query = "SELECT * FROM `players`, `dates` WHERE `player_name`='$string' AND `dates`.`date_sid`=`players`.`date_sid` ORDER BY `dates`.`date_sid` ASC";
        $result = Database::query($query);
        return $result;
    }
    
    public static function getTop($dateSid, $rankBy, $number = 5)
    {
        $query = "SELECT `players`.* FROM `players`, `dates` WHERE `dates`.`date_sid`='$dateSid' AND `players`.`date_sid`=`dates`.`date_sid` ORDER BY `players`.`$rankBy` DESC LIMIT $number";
        $result = Database::query($query);
        return $result;
    }

}
?>
