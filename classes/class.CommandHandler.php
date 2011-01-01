<?php



/**
 * CommandHandler
 * 
 * @package ViiIrcBot
 * @author Patrick Rennings
 * @copyright Patrick Rennings
 * @version 1.0
 * @access public
 */

class CommandHandler {
    
    /**
     * CommandHandler::FetchCommand()
     * 
     * @param object $Parent
     * @return boolean
     */
     
    public function FetchCommand ($Parent) {
        if (!empty($Parent->Parameter['command'])) {
            $this->ExecuteCommand($Parent, $this->CheckCommandQuery($Parent, $Parent->Parameter['command']));
        }     
    }
    
    /**
     * CommandHandler::CheckCommandQuery()
     * 
     * @param object $Parent
     * @param string $Command
     * @return boolean
     */
     
    protected function CheckCommandQuery ($Parent, $Command) {
        $CmdQuery = mysql_query('SELECT * FROM
                                 commands WHERE 
                                 cmd_name = "' . $Parent->Parameter['command'] . '"
                                 LIMIT 1');
        
        if (mysql_num_rows($CmdQuery) > 0) {
            $CmdFetch = mysql_fetch_assoc($CmdQuery);
            
            if (!empty($CmdFetch['cmd_alias'])) {
                return $this->AliasCommand($Parent, $CmdFetch['cmd_alias']);
            }
            else {
                $LengthString = strlen(trim($CmdFetch['cmd_class']));
                
                foreach ($Parent->Modules as $ModName => $Instance) {
    
                    $uIdModule = substr($ModName, $LengthString );
                    $WholeModule = trim($CmdFetch['cmd_class']) . $uIdModule;
                 
                    if ($ModName == $WholeModule) {
                        return $ModName . '-' . $CmdFetch['cmd_class_function'];
                    }
                }
            }
        }
        else {
            return false;
        }
    }
    
    /**
     * CommandHandler::ExecuteCommand()
     * 
     * @param object $Parent
     * @param string $Command
     * @return boolean
     */
     
    protected function ExecuteCommand ($Parent, $Command) {
        if ($Command == false) {
            return false;
        }
        
        $ExplodeClassCommand = explode('-', $Command);
        
        // Executing command with parameters (in array form)
        if (!empty($Parent->Parameter['parameters'])) {
            $Args = explode(' ', $Parent->Parameter['parameters']);
            
            $Parent->Modules[$ExplodeClassCommand[0]]->$ExplodeClassCommand[1]($Parent, $Args);
            return true;
        }
        else {
            // Executing command without parameters
            $Parent->Modules[$ExplodeClassCommand[0]]->$ExplodeClassCommand[1]($Parent);
            return true;
        }
    }
    
    /**
     * CommandHandler::AliasCommand()
     * 
     * @param object $Parent
     * @param int $CmdId
     * @return boolean
     */
    
    protected function AliasCommand ($Parent, $CmdId) {
        if (!is_object($Parent)) {
            return false;
        }
        else {
            $QryCommand = mysql_query(' SELECT * FROM commands
                                        WHERE cmd_id = "' . intval($CmdId) . '" 
                                        LIMIT 1');
            
            if (mysql_num_rows($QryCommand) > 0) {
                $CmdFetch = mysql_fetch_assoc($QryCommand);
                $LengthString = strlen(trim($CmdFetch['cmd_class']));
                
                foreach ($Parent->Modules as $ModName => $Instance) {
    
                    $uIdModule = substr($ModName, $LengthString );
                    $WholeModule = trim($CmdFetch['cmd_class']) . $uIdModule;
                 
                    if ($ModName == $WholeModule) {
                        return $ModName . '-' . $CmdFetch['cmd_class_function'];
                    }
                }
                return false;
            }
            else {
                return false;
            }
        }
    }
}

?> 