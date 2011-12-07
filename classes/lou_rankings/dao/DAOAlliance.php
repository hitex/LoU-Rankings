<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'DAO.php';

/**
 * Description of DAOAlliance
 *
 * @author Vladas
 */
class DAOAlliance extends DAO {

    public static RANK_BY_RANK = "alliance_ranking";
    public static RANK_BY_CITIES = "alliance_cities_count";
    public static RANK_BY_MEMBERS = "alliance_members_count";
    public static RANK_BY_SCORE = "alliance_score";
    public static RANK_BY_AVERAGE_SCORE = "alliance_average_score";

    public static function getByName($string)
    {
        $query = "SELECT * FROM `alliances`, `dates` WHERE `alliance_name`='$string' AND `dates`.`date_sid`=`alliances`.`date_sid` ORDER BY `dates`.`date_sid` ASC";
        $result = Database::query($query);
        return $result;
    }
    
    public static function getTop($dateSid, $rankBy, $number = 10)
    {
        $query = "SELECT * FROM `alliances`, `dates` WHERE `dates`.`date_sid`=`$dateSid` ORDER BY `alliances`.`$rankBy` ASC LIMIT $number";
        $result = Database::query($query);
        return $result;
    }
    
}
?>
