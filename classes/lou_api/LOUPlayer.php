<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LOUPlayer
 *
 * @author Vladas
 */
class LOUPlayer extends LOU {

    public static $PLAYER_ID = "i";
    public static $PLAYER_NAME = "n";
    public static $ALLIANCE = "a";
    public static $ALLIANCE_ID = "j";
    public static $PLAYER_POINTS = "p";
    public static $PLAYER_RANKING = "r";
    public static $PLAYER_CITIES_COUNT = "c";

    public static $TYPE_SCORE = 0;
    public static $TYPE_UNITS_DEFEATED = 5;
    public static $TYPE_PLUNDERED_RESOURCES = 6;
    public static $TYPE_OFFENSIVE_FAME = 8;
    public static $TYPE_DEFENSIVE_FAME = 9;
    public static $TYPE_FAME = 10;
    
    public static $SORT_BY_SCORE = 0;
    public static $SORT_BY_NAME = 1;
    public static $SORT_BY_ALLIANCE = 3;
    public static $SORT_BY_CITIES = 4;
    public static $SORT_BY_PLUNDERED_RESOURCES = 16;
    public static $SORT_BY_UNITS_DEFEATED = 17;
    public static $SORT_BY_OFFENSIVE_FAME = 27;
    public static $SORT_BY_DEFENSIVE_FAME = 28;
    public static $SORT_BY_FAME = 29;
    


    /**
     * Returns total players in the world and logged user's ranking.
     *
     * @return Array [0] - total players, [1] - logged user's ranking. (ex. "[14399,591]")
     */
    public function getCountAndIndex($continent = -1, $sort = 0, $ascending = true, $type = 0)
    {
        $data = array();

        $data["session"] = self::$session;
        $data["continent"] = $continent;
        $data["sort"] = $sort;
        $data["ascending"] = $ascending;
        $data["type"] = $type;

        return $this->query("PlayerGetCountAndIndex", $data);
    }



    /**
     * Returns total players in the world.
     *
     * @return Integer Total players in the world.
     */
    public function getCount($continent = -1, $type = 0)
    {
        $data = array();

        $data["session"] = self::$session;
        $data["continent"] = $continent;
        $data["type"] = $type;

        return $this->query("PlayerGetCount", $data);
    }



    /**
     * Returns player rankings information from specified range.
     *
     * @param Integer $start Player start.
     * @param Integer $end   Player end.
     *
     * @return Array Player informations from specified range.
     */
    public function getRange($start, $end, $continent = -1, $sort = 0, $ascending = true, $type = 0)
    {
        $data = array();

        $data["session"] = self::$session;
        $data["start"] = $start;
        $data["end"] = $end;
        $data["continent"] = $continent;
        $data["sort"] = $sort;
        $data["ascending"] = $ascending;
        $data["type"] = $type;

        return $this->query("PlayerGetRange", $data);
    }
}
?>
