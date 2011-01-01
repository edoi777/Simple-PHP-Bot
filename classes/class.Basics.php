<?php

/**
 * @author Patrick Rennings
 * @copyright 2010
 * @company 

 */

class Basics {
    public function CheckHandling($Parent) {
        if ($Parent->Parameter['hostmask'] == 'PING') {
            $this->PlayPingPong($Parent);
        }
        if ($Parent->Parameter['servercmd'] == '376') {
            $this->ExecuteStartUp($Parent);
        }
    }
    
    protected function PlayPingPong($Parent) {
        $Parent->RawWrite('PONG '. $Parent->Parameter['servercmd']);  
        return true;
    }
    
    protected function ExecuteStartUp($Parent) {
        
        //join default channel
        $ConfQuery = mysql_query('SELECT config_value, config_name FROM configurations WHERE config_name = "mainchannel"');
        $ConfFetch = mysql_fetch_assoc($ConfQuery);
        
        $Parent->RawWrite('JOIN ' . $ConfFetch['config_value']);
    }
}

?> 