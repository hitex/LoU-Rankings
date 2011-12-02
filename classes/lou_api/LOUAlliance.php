<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LOUAlliance
 *
 * @author Vladas
 */
class LOUAlliance extends LOU {
    
    public static $ALLIANCE_ID = "i";
    public static $ALLIANCE_NAME = "n";
    public static $ALLIANCE_AVERAGE_SCORE = "a";
    public static $ALLIANCE_SCORE = "p";
    public static $ALLIANCE_RANKING = "r";
    public static $ALLIANCE_CITIES_COUNT = "c";
    public static $ALLIANCE_MEMBERS_COUNT = "m";



    /**
     * Returns total alliances in the world and logged user's alliance ranking.
     *
     * @return Array [0] - total alliances, [1] - logged user's alliance ranking. (ex. "[143,11]")
     */
    public function getCountAndIndex($continent = -1, $sort = 0, $ascending = true, $type = 0)
    {
        $data = array();

        $data["session"] = self::$session;
        $data["continent"] = $continent;
        $data["sort"] = $sort;
        $data["ascending"] = $ascending;
        $data["type"] = $type;

        return $this->query("AllianceGetCountAndIndex", $data);
    }



    /**
     * Returns total alliances in the world.
     *
     * @return Integer Total alliances in the world.
     */
    public function getCount($continent = -1, $type = 0)
    {
        $data = array();

        $data["session"] = self::$session;
        $data["continent"] = $continent;
        $data["type"] = $type;

        return $this->query("AllianceGetCount", $data);
    }



    /**
     * Returns alliance rankings information from specified range.
     * 
     * @param Integer $start Alliance start.
     * @param Integer $end   Alliance end.
     * @param Integer $continent
     * @param Integer $sort
     * @param Boolean $ascending
     * @param Integer $type
     *
     * @return Array Alliance rankings information from specified range.
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

        return $this->query("AllianceGetRange", $data);
    }
}
?>
