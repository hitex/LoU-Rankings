<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'LOUPlayer.php';
require_once 'LOUAlliance.php';

/**
 * Description of LOU
 *
 * @author Vladas
 */
class LOU {

    /**
     *
     * @var Array of headers (ex. "Content-Type: application/json");
     */
    protected static $headers = array();

    /**
     *
     * @var String LOU session number (ex. "529f869b-e371-4de0-80de-de21c354ab47")
     */
    protected static $session;

    /**
     *
     * @var String LOU server hostname (ex. "prodgame14.lordofultima.com").
     */
    protected static $host;
    
    /**
     *
     * @var String LOU server endpoint (ex. "prodgame11.lordofultima.com/33/Presentation/Service.svc/ajaxEndpoint/").
     */
    protected static $hostname;

    

    /**
     * Constructor.
     * 
     * @param String $session LOU session number (ex. "529f869b-e371-4de0-80de-de21c354ab47")
     */
    public function __construct($session = null, $hostname = null)
    {
        if (empty(self::$session)) {
            self::$session = $session;
            self::$hostname = $hostname;
            $tmp = explode("/", $hostname);
            self::$host = $tmp[0];

            // Setting up headers.
            self::$headers[] = "Content-Type: application/json";
            self::$headers[] = "X-Qooxdoo-Response-Type: application/json";
            self::$headers[] = "Host: " . self::$host;
        }
    }



    /**
     * Sends query to LOU server and returns an answer.
     *
     * @param String $method LOU server method name (ex. )
     * @param Array $data Array of data to send to server.
     * 
     * @return Array Decoded JSON data.
     */
    protected function query($method, $data)
    {
        $process = curl_init(self::$hostname . $method);
        curl_setopt($process, CURLOPT_HTTPHEADER, self::$headers);
        //curl_setopt($process, CURLOPT_HEADER, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($process, CURLOPT_POST, 1);
        $return = curl_exec($process);
        curl_close($process);
        $result = json_decode($return, true);

        if ($result == -1) {
            throw new Exception("Error! Server returned -1.");
        }
        
        if (is_null($result)) {
            throw new Exception("Error! Server returned NULL.");
        }

        return $result;
    }



    /**
     * Returns LOU session id.
     *
     * @return String LOU session number (ex. "529f869b-e371-4de0-80de-de21c354ab47")
     */
    public function getSession() {
        return self::$session;
    }


}

?>
