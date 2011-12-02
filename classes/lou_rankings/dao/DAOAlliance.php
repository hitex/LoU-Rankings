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

    public static function getByName($string)
    {
        $query = "SELECT * FROM `alliances`, `dates` WHERE `alliance_name`='$string' AND `dates`.`date_sid`=`alliances`.`date_sid` ORDER BY `dates`.`date_sid` ASC";
        $result = Database::query($query);
        return $result;
    }
    
}
?>
