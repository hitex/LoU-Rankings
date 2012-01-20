<?php
error_reporting(0);
ini_set("display_errors", "0");
//error_reporting(E_ALL);
//ini_set("display_errors", "1");

require_once 'classes/lou_rankings/Database.php';
require_once("config/db.php");
require_once("config/server.php");
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LoU <?php echo $_CONFIG['server_world']; ?> timeline</title>
<?php
include "config/google_analytics.html";
?>
<style type="text/css">
body {
    font-family: Arial;
}

#container
{
	margin: 0 auto;
	width: 100%;
	background: #fff;
}

#header
{
	background: #ccc;
	padding: 10px;
}

#header h1 { margin: 0; }

#navigation
{
	float: left;
	width: 100%;
	background: #333;
}

#navigation ul
{
	margin: 0;
	padding: 0;
}

#navigation ul li
{
	list-style-type: none;
	display: inline;
}

#navigation li a
{
	display: block;
	float: left;
	padding: 5px 10px;
	color: #fff;
	text-decoration: none;
	border-right: 1px solid #fff;
}

#navigation li a:hover { background: #ffaa00; }

#content
{
	float: left;
	width: auto;
	padding: 10px;
}

#content h2 { margin: 0; }

#footer
{
	clear: both;
	background: #ccc;
	text-align: right;
	padding: 20px;
	height: 50px;
}
</style>
</head>
<body>
    <div id="container">
	<div id="header">
		<h1>
            <a href="http://www.lordofultima.com/" target="_blank"><img src="images/loulogo.png" alt="LoU" /></a> <?php echo $_CONFIG['server_world']; ?> timeline</h1>
	</div>
	<div id="navigation">
		<ul>
            <li><a href="index.php">Home</a></li>
			<li><a href="index.php?id=players">Players</a></li>
			<li><a href="index.php?id=alliances">Alliances</a></li>
			<li><a href="index.php?id=about">About</a></li>
		</ul>
	</div>
			<div id="content">
<?php

Database::connect($_CONFIG['db_host'], $_CONFIG['db_user'], $_CONFIG['db_password'], $_CONFIG['db_database']);

file_put_contents('searches_player.log', date("Y-m-d H:i:s") . '|' . $_SERVER['REMOTE_ADDR'] . '|' . $_GET['player'] . "\n", FILE_APPEND);

switch ($_GET['id']){
    case 'players':
        require_once '_players.php';
        break;
    
    case 'alliances':
        require_once '_alliances.php';
        break;
    
    case 'about':
        require_once '_about.php';
        break;
    
    default:
        require_once '_default.php';
        break;
}

if ($_SERVER['REMOTE_ADDR'] == "192.168.1.1") {
    //echo '<br/><a href="index.php?id=lab_alliance">_lab_alliance</a>';
}


// Inform users when data is refreshing.
$result = Database::query("SELECT * FROM `vars` WHERE `var_name`='collector_running'");
$row = mysql_fetch_assoc($result);
if($row['var_value'] == 1){
    echo '<br/>Note: Data is being updated now...';
}

?>
			</div>
			<div id="footer">
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
                    ">
                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
			</div>
</div>
</body>
</html>