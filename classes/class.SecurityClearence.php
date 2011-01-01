<?php

/**
 * SecurityClearence
 * 
 * @package IRCBot
 * @author Patrick Rennings
 * @copyright Patrick Rennings
 * @version 2010
 * @access public
 */
 
class SecurityClearence {
    
    /**
     * SecurityClearence::CheckClearence()
     * 
     * @param object $Parent
     * @param string $Command
     * @param integer $Level
     * @param string $Channel
     * @return boolean
     */
     
    protected function CheckClearence ($Parent, $Command, $Level, $Channel = null) {
        if (!is_string($Command) OR !is_string($Level)) {
            return false;
        }
        else {
            
            $Command = mysql_real_escape_string($Command);
            
            if ($Level == 'oper') {
                return $this->CheckClearenceOper($Parent, $Command);
            }
            elseif ($Level == 'chan') {
                return $this->CheckClearenceChannel($Parent, $Command, $Channel);
            }
            else {
                return $this->CheckClearenceChannel($Parent, $Command);
            }
        }
    }
    
    // Todo,
    // Make commands configurable for every channel
    
    
    /**
     * SecurityClearence::GetClearenceOfUser()
     * 
     * @param object $Parent
     * @param string $NickName
     * @return integer
     */
     
    public function GetClearenceOfUser ($Parent, $NickName) {
        $GetAuthId = mysql_query('SELECT nick_nickname, nick_auth_id FROM nicknames
                                  WHERE nick_nickname = "' . $NickName . '"');
                                  
        if (mysql_num_rows($GetAuthId) > 0) {
                                  
            $GetAuthFetch = mysql_fetch_assoc($GetAuthId);
            
            $GetClearence = mysql_query('SELECT account_id, account_security_clearence FROM accounts
                                         WHERE account_id = "' . $GetAuthFetch['nick_auth_id'] . '"');
            
            if (mysql_num_rows($GetClearence) > 0) {  
                $FetchClearence = mysql_fetch_assoc($GetClearence);
                return (int) $FetchClearence['account_security_clearence'];
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
     * SecurityClearence::GetChannelClearenceOfUser()
     * 
     * @param object $Parent
     * @param string $NickName
     * @param string $Channel
     * @return boolean
     */
    public function GetChannelClearenceOfUser ($Parent, $NickName, $Channel) {
        $GetAuthId = mysql_query('SELECT nick_nickname, nick_auth_id FROM nicknames
                                  WHERE nick_nickname = "' . $NickName . '"');
                                  
        if (mysql_num_rows($GetAuthId) > 0) {

            $GetAuthFetch = mysql_fetch_assoc($GetAuthId);
            $GetClearence = mysql_query('SELECT 
                                             channels.channel_id, 
                                             channels.channel_name, 
                                             userlists.userlist_channel_id,
                                             userlists.userlist_auth_id,
                                             userlists.userlist_clearence
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
                                            
            if (mysql_num_rows($GetClearence) > 0) { 
                $FetchClearence = mysql_fetch_assoc($GetClearence);
                return (int) $FetchClearence['userlist_clearence'];
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
     * SecurityClearence::HasChannelClearence()
     * 
     * @param object $Parent
     * @param string $Command
     * @param integer $Level
     * @param string $NickName
     * @param string $Channel
     * @return boolean
     */
     
    public function HasChannelClearence ($Parent, $Command, $Level, $NickName, $Channel) {
        if ($this->CheckClearence($Parent, $Command, $Level) > $this->GetChannelClearenceOfUser($Parent, $NickName, $Channel)) {
            return false;
        }
        else {
            return true;
        }
    }
    
    /**
     * SecurityClearence::CheckClearenceOper()
     * 
     * @param object $Parent
     * @param string $Command
     * @return integer
     */
     
    protected function CheckClearenceOper ($Parent, $Command) {
        $CmdCheck = mysql_query('SELECT * FROM commands
                                 WHERE cmd_class_function = "' . $Command . '" AND
                                 cmd_security_clearence <> 0');
        
        if (mysql_num_rows($CmdCheck) > 0) {
            $CmdFetch = mysql_fetch_assoc($CmdCheck);
            return (int) $CmdFetch['cmd_security_clearence'];
        }
        else {
            return 0;
        }
    }
    
    /**
     * SecurityClearence::CheckClearenceChannel()
     * 
     * @param object $Parent
     * @param string $Command
     * @param string $Channel
     * @return integer
     */
     
    protected function CheckClearenceChannel ($Parent, $Command, $Channel = null) {
        // if ($Channel == null) {
        //    return false;
        // }
        // else {
            $CmdCheck = mysql_query('SELECT * FROM commands
                                     WHERE cmd_class_function = "' . $Command . '" AND
                                     cmd_channel_clearence <> 0');
            
            if (mysql_num_rows($CmdCheck) > 0) {
                $CmdFetch = mysql_fetch_assoc($CmdCheck);
                
                return (int) $CmdFetch['cmd_channel_clearence'];
            }
            else {
                return 0;
            }
        // }
    }
    
    /**
     * SecurityClearence::HasSecurityClearence()
     * 
     * @param object $Parent
     * @param string $Command
     * @param integer $Level
     * @param string $NickName
     * @return boolean
     */
     
    public function HasSecurityClearence ($Parent, $Command, $Level, $NickName) {
        if ($this->CheckClearence($Parent, $Command, $Level) > $this->GetClearenceOfUser($Parent, $NickName)) {
            return false;
        }
        else {
            return true;
        }
    }
}

?> 