<?php
/**
 * Description of LOUPoll
 *
 * @author Vladas
 */
class LOUPoll extends LOU {

    

    public function poll($continent = -1, $sort = 0, $ascending = true, $type = 0)
    {
        
        // {"session":"c6317cf5-1d18-4dba-bb68-96a748257d82","requestid":"-1","requests":"TM:409,0,\fCAT:3\fTIME:1321913700726\fSERVER:\fALLIANCE:\fQUEST:\fTE:\fFW:\fPLAYER:\fCITY:7668044\fWC:\fWORLD:\fVIS:c:7668044:0:-714:-428:1516:984\fUFP:\fREPORT:\fMAIL:\fFRIENDINV:\fCHAT:\fSUBSTITUTION:\fEC:\fINV:\fAI:\fMAT:7668044\f"}
        $data = array();

        $data["session"] = self::$session;
        $data["requestid"] = -1;
        $data["sort"] = $sort;
        $data["ascending"] = $ascending;
        $data["type"] = $type;

        return $this->query("Poll", $data);
    }
    
    
}
?>
