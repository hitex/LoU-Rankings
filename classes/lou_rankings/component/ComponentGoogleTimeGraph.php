<?php
require_once 'Component.php';
/**
 * Description of ComponentGoogleTimeGraph
 *
 * @author Vladas
 */
class ComponentGoogleTimeGraph extends Component {
    
    private $datesArray = array();
    
    private $series = array();
    
    private $_width = 800;
    private $_height = 600;


    
    function __construct()
    {
        
    }
    
    
    
    public function setSize($w, $h)
    {
        $this->_width = $w;
        $this->_height = $h;
    }
    
    
    
    public function addSeries($id, $name)
    {
        $this->series[$id] = $name;
    }
    
    
    
    public function addDate($id, $date, $value, $title, $text)
    {
        $this->datesArray[$date][$id] = array($value, $title, $text);
    }


    
    public function draw()
    {
        $series = "";
        $data = "";
        
        $keys = array_keys($this->series);
        
        foreach ($this->series as $key => $value) {
            $series .= "data.addColumn('number', '$value');
                        data.addColumn('string', 'title_$key');
                        data.addColumn('string', 'message_$key');";
        }
        
        foreach ($this->datesArray as $date => $array) {
            $time = strtotime($date);
            $data .= "[new Date(" . date("Y", $time) . ", " . (date("m", $time)-1) . " ," . date("d", $time) . "," . date("H", $time) . ", " . date("i", $time) . "," . date("s", $time) . ")";
    
            foreach ($keys as $k) {
                if (!empty($array[$k])) {
                    $data .= "," . $array[$k][0] . ",'" . $array[$k][1] . "','" . $array[$k][2] . "'";
                } else {
                    $data .= ",undefined,undefined,undefined";
                }
            }
            
            $data .= "],\n";
        }
        
        return "<script type=\"text/javascript\" src=\"http://www.google.com/jsapi\"></script>
                <script type=\"text/javascript\">
                    google.load('visualization', '1', {packages: ['annotatedtimeline']});
                    function drawVisualization() {
                        var data = new google.visualization.DataTable();
                        data.addColumn('date', 'Date');
      
                        " . $series . "
                        
                        data.addRows([$data]);

                        var annotatedtimeline = new google.visualization.AnnotatedTimeLine(
                        document.getElementById('visualization'));
                        annotatedtimeline.draw(data, {
                            'displayAnnotations': true,
                            'displayExactValues': true, // Do not truncate values (i.e. using K suffix)
                            'displayRangeSelector' : false, // Do not sow the range selector
                            'displayZoomButtons': true,
                            'legendPosition': 'newRow', // Can be sameRow
                            'thickness': 2, // Make the lines thicker
                            'dateFormat': 'yyyy-MM-dd HH:mm:ss'
                        });
                    }
                    google.setOnLoadCallback(drawVisualization);
                </script>
                <div id=\"visualization\" style=\"width: {$this->_width}px; height: {$this->_height}px;\"></div>";
    }

}

?>