<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors", "1");

require_once 'config/server.php';
require_once 'classes/lou_rankings/Database.php';
require_once 'classes/lou_rankings/Collector.php';
require_once 'classes/lou_api/LOU.php';
require_once 'config/db.php';
require_once 'config/collector.php';

Database::connect($_CONFIG['db_host'], $_CONFIG['db_user'], $_CONFIG['db_password'], $_CONFIG['db_database']);
        
$result = Database::query("SELECT UNIX_TIMESTAMP(`date_datetime`) FROM `dates` ORDER BY `date_sid` DESC LIMIT 1");

if(mysql_num_rows($result) == 0) {
    $lastUpdateTime = 0;
} else {
    $lastUpdateTime = array_pop(mysql_fetch_assoc($result));
}

if($lastUpdateTime + $_CONFIG['update_interval'] > time()) {
    echo '<h2>This server is configured to allow updates once in ' . round($_CONFIG['update_interval']/60/60, 1) . ' hours.<br/>Another data update can be run after ' . ceil(($lastUpdateTime + $_CONFIG['update_interval'] - time())/60/60) . ' hours</h2>';

} else {

    switch ($_POST['action']){
        case 'collect':
            
            if (strval($_POST['collector_password']) != strval($_CONFIG['collector_password'])) {
                echo '<h2>Incorrect password!</h2>';
                break;
            }
            
            $result = Database::query("SELECT * FROM `vars` WHERE `var_name`='collector_running'");
            $row = mysql_fetch_assoc($result);
            
            if($row['var_value'] != 0){
                echo '<h2>Error! Data is already being collected.</h2>';
                break;
            }
                
            $lou = new LOU($_POST['key'], $_CONFIG['server_hostname']);
            $collector = new Collector();
            $collector->collect();
            
            echo '<h2>Data collected successfully!</h2>';
            
            break;
        
        default:
            if(!empty($_CONFIG['collector_password'])) {
                $passField = '<input type="password" name="collector_password"/><br/>';
            } else {
                $passField = '<small>Authorisation is not required on this server.</small><br/>';
            }
            
            echo '<form action="index.php?id=collector" method="POST">
                        <h2>LOU ' . $_CONFIG['server_world'] . ' collector</h2>
                        Enter your LoU session id:<br/>
                        <input type="text" name="key"/><br/>
                        ' . $passField . '
                        <input type="hidden" value="collect" name="action"/>
                        <input type="submit" value="Submit" name="get"/>
                    </form>';
            break;
    }
    
}
?>