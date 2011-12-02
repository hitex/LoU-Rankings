<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Database
 *
 * @author Vladas
 */
class Database {

    
    public static function connect($host, $user, $password, $database)
    {
        if (!mysql_connect($host,$user,$password)) exit;
        mysql_select_db($database);
        mysql_query("SET NAMES 'utf8'");
    }


    public static function query($query)
    {
        $result = mysql_query($query) or die(mysql_error());
        return $result;
    }
}
?>
