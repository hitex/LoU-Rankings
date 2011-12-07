<html>
    <head>
        <title>LOU Server 43 collector</title>
    </head>
    <body>
<?php

error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors", "1");

require_once 'config/server.php';
        

switch ($_POST['id']){
    case 'collect':
        error_reporting(E_ALL ^ E_NOTICE);
        require_once 'classes/lou_rankings/Database.php';
        require_once 'classes/lou_rankings/Collector.php';
        require_once 'classes/lou_api/LOU.php';
        require_once 'config/db.php';
        require_once 'config/collector.php';
        
        if ($_POST['collector_password'] != $_CONFIG['collector_password']) {
            echo '<h2>Incorrect password!</h2>';
            break;
        }

        Database::connect($_CONFIG['db_host'], $_CONFIG['db_user'], $_CONFIG['db_password'], $_CONFIG['db_database']);

        $lou = new LOU($_POST['key'], $_CONFIG['server_hostname']);

        $collector = new Collector();
        $collector->collect();
        
        echo '<h2>Data collected successfully!</h2>';
        break;
    
    default:
        echo '<form action="collector.php" method="POST">
                    <h2>LOU ' . $_CONFIG['server_world'] . ' collector</h2>
                    Enter key:<br/>
                    <input type="text" name="key"/><br/>
                    <input type="text" name="collector_password"/><br/>
                    <input type="hidden" value="collect" name="id"/>
                    <input type="submit" value="Submit" name="get"/>
                </form>';
        break;
}
?>
    </body>
</html>