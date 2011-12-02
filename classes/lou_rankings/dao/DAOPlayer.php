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

    public static function getByName($string)
    {
        $query = "SELECT * FROM `players`, `dates` WHERE `player_name`='$string' AND `dates`.`date_sid`=`players`.`date_sid` ORDER BY `dates`.`date_sid` ASC";
        $result = Database::query($query);
        return $result;
    }

}
?>
