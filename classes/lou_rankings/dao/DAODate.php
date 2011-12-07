<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'DAO.php';

/**
 * Description of DAODate
 *
 * @author Vladas
 */
class DAODate extends DAO {
    
    public static function getLastDateSid()
    {
        $query = "SELECT `date_sid` FROM `dates` ORDER BY `dates`.`date_sid` DESC LIMIT 1";
        $result = Database::query($query);
        $res = mysql_fetch_array($result);
        return $res['date_sid'];
    }
    
}
?>
