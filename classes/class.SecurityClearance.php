<?php

/**
 * SecurityClearance
 * 
 * @package IRCBot
 * @author Patrick Rennings
 * @copyright Patrick Rennings
 * @version 2010
 * @access public
 */
 
class SecurityClearance {
    
    /**
     * SecurityClearance::CheckClearance()
     * 
     * @param object $Parent
     * @param string $Command
     * @param integer $Level
     * @param string $Channel
     * @return boolean
     */
     
    protected function CheckClearance ($Parent, $Command, $Level, $Channel = null) {
        if (!is_string($Command) OR !is_string($Level)) {
            return false;
        }
        else {
            
            $Command = mysql_real_escape_string($Command);
            
            if ($Level == 'oper') {
                return $this->CheckClearanceOper($Parent, $Command);
            }
            elseif ($Level == 'chan') {
                return $this->CheckClearanceChannel($Parent, $Command, $Channel);
            }
            else {
                return $this->CheckClearanceChannel($Parent, $Command);
            }
        }
    }
    
    // Todo,
    // Make commands configurable for every channel
    
    
    /**
     * SecurityClearance::GetClearanceOfUser()
     * 
     * @param object $Parent
     * @param string $NickName
     * @return integer
     */
     
    public function GetClearanceOfUser ($Parent, $NickName) {
        $GetAuthId = mysql_query('SELECT nick_nickname, nick_auth_id FROM nicknames
                                  WHERE nick_nickname = "' . $NickName . '"');
                                  
        if (mysql_num_rows($GetAuthId) > 0) {
                                  
            $GetAuthFetch = mysql_fetch_assoc($GetAuthId);
            
            $GetClearance = mysql_query('SELECT account_id, account_security_clearance FROM accounts
                                         WHERE account_id = "' . $GetAuthFetch['nick_auth_id'] . '"');
            
            if (mysql_num_rows($GetClearance) > 0) {  
                $FetchClearance = mysql_fetch_assoc($GetClearance);
                return (int) $FetchClearance['account_security_clearance'];
            }
            else {
                return 0;
            }
        }
        else {
            return 0;
        }
    }
    
    /**
     * SecurityClearance::GetChannelClearanceOfUser()
     * 
     * @param object $Parent
     * @param string $NickName
     * @param string $Channel
     * @return boolean
     */
    public function GetChannelClearanceOfUser ($Parent, $NickName, $Channel) {
        $GetAuthId = mysql_query('SELECT nick_nickname, nick_auth_id FROM nicknames
                                  WHERE nick_nickname = "' . $NickName . '"');
                                  
        if (mysql_num_rows($GetAuthId) > 0) {

            $GetAuthFetch = mysql_fetch_assoc($GetAuthId);
            $GetClearance = mysql_query('SELECT 
                                             channels.channel_id, 
                                             channels.channel_name, 
                                             userlists.userlist_channel_id,
                                             userlists.userlist_auth_id,
                                             userlists.userlist_clearance
                                         FROM 
                                            channels
                                         INNER JOIN 
                                            userlists
                                         ON 
                                            channels.channel_id = userlists.userlist_channel_id
                                         WHERE 
                                            userlists.userlist_auth_id = ' . $GetAuthFetch['nick_auth_id'] . '
                                         AND
                                            channels.channel_name = "' . mysql_real_escape_string($Channel) . '"');
                                            
            if (mysql_num_rows($GetClearance) > 0) { 
                $FetchClearance = mysql_fetch_assoc($GetClearance);
                return (int) $FetchClearance['userlist_clearance'];
            }
            else {
                return 0;
            }
        }
        else {
            return 0;
        }
    }
    
    /**
     * SecurityClearance::HasChannelClearance()
     * 
     * @param object $Parent
     * @param string $Command
     * @param integer $Level
     * @param string $NickName
     * @param string $Channel
     * @return boolean
     */
     
    public function HasChannelClearance ($Parent, $Command, $Level, $NickName, $Channel) {
        if ($this->CheckClearance($Parent, $Command, $Level) > $this->GetChannelClearanceOfUser($Parent, $NickName, $Channel)) {
            return false;
        }
        else {
            return true;
        }
    }
    
    /**
     * SecurityClearance::CheckClearanceOper()
     * 
     * @param object $Parent
     * @param string $Command
     * @return integer
     */
     
    protected function CheckClearanceOper ($Parent, $Command) {
        $CmdCheck = mysql_query('SELECT * FROM commands
                                 WHERE cmd_class_function = "' . $Command . '" AND
                                 cmd_security_clearance <> 0');
        
        if (mysql_num_rows($CmdCheck) > 0) {
            $CmdFetch = mysql_fetch_assoc($CmdCheck);
            return (int) $CmdFetch['cmd_security_clearance'];
        }
        else {
            return 0;
        }
    }
    
    /**
     * SecurityClearance::CheckClearanceChannel()
     * 
     * @param object $Parent
     * @param string $Command
     * @param string $Channel
     * @return integer
     */
     
    protected function CheckClearanceChannel ($Parent, $Command, $Channel = null) {
        // if ($Channel == null) {
        //    return false;
        // }
        // else {
            $CmdCheck = mysql_query('SELECT * FROM commands
                                     WHERE cmd_class_function = "' . $Command . '" AND
                                     cmd_channel_clearance <> 0');
            
            if (mysql_num_rows($CmdCheck) > 0) {
                $CmdFetch = mysql_fetch_assoc($CmdCheck);
                
                return (int) $CmdFetch['cmd_channel_clearance'];
            }
            else {
                return 0;
            }
        // }
    }
    
    /**
     * SecurityClearance::HasSecurityClearance()
     * 
     * @param object $Parent
     * @param string $Command
     * @param integer $Level
     * @param string $NickName
     * @return boolean
     */
     
    public function HasSecurityClearance ($Parent, $Command, $Level, $NickName) {
        if ($this->CheckClearance($Parent, $Command, $Level) > $this->GetClearanceOfUser($Parent, $NickName)) {
            return false;
        }
        else {
            return true;
        }
    }
}

?> 