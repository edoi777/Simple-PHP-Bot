<?php

/**
 * modules
 * 
 * @package IrcBot
 * @author Patrick Rennings
 * @copyright Patrick Rennings
 * @version 2010
 * @access public
 */
 
class Modules {
    
    /**
     * modules::CmdLoadModule()
     * 
     * @param mixed $Parent
     * @param mixed $Args
     * @return
     */
     
    public function CmdLoadModule ($Parent, $Args = null) {
        if ($Parent->SecurityClearence->HasSecurityClearence($Parent, 'CmdLoadModule', 'oper', $Parent->Parameter['hostmask']['nickname'])) {
            if (empty($Args[1])) {
                $Parent->PrivateMessage($Parent->Parameter['location'], 'Please enter an module name.');
            }
            else {
                $Parent->ModuleSystem->LoadModule($Parent, $Args[1]);
            }
        }
        else {
            $Parent->PrivateMessage($Parent->Parameter['location'], 'Not enough clearence.');
        }
    }
    
    /**
     * modules::CmdReloadModule()
     * 
     * @param mixed $Parent
     * @param mixed $Args
     * @return
     */
     
    public function CmdReloadModule ($Parent, $Args = null) {
        if ($Parent->SecurityClearence->HasSecurityClearence($Parent, 'CmdReloadModule', 'oper', $Parent->Parameter['hostmask']['nickname'])) {
            if (empty($Args[1])) {
                $Parent->PrivateMessage($Parent->Parameter['location'], 'Please enter an module name.');
            }
            else {
                $Parent->ModuleSystem->ReloadModule($Parent, $Args[1]);
                $Parent->PrivateMessage($Parent->Parameter['location'], 'Module reloaded.');
            }
        }
        else {
            $Parent->PrivateMessage($Parent->Parameter['location'], 'Not enough clearence.');
        }
    }
    
    /**
     * modules::CmdUnloadModule()
     * 
     * @param mixed $Parent
     * @param mixed $Args
     * @return
     */
     
    public function CmdUnloadModule ($Parent, $Args = null) {
        if ($Parent->SecurityClearence->HasSecurityClearence($Parent, 'CmdUnloadModule', 'oper', $Parent->Parameter['hostmask']['nickname'])) {
            if (empty($Args[1])) {
                $Parent->PrivateMessage($Parent->Parameter['location'], 'Please enter an module name.');
            }
            else {
                $Parent->ModuleSystem->UnloadModule($Parent, $Args[1]);
            }
        }
        else {
            $Parent->PrivateMessage($Parent->Parameter['location'], 'Not enough clearence.');
        }
    }
}

?> 