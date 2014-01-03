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
        if ($Parent->SecurityClearance->HasSecurityClearance($Parent, 'CmdLoadModule', 'oper', $Parent->Parameter['hostmask']['nickname'])) {
            if (empty($Args[1])) {
                $Parent->PrivateMessage($Parent->Parameter['location'], 'Please enter an module name.');
            }
            else {
                if (file_exists($Parent->BotConfig['modulepath'] . 'class.' . trim($Args[1]) . '.php')) {
                   // die('it exists...');
                }
                if (!$Parent->ModuleSystem->LoadModule($Parent, $Args[1])) {
                    $Parent->PrivateMessage($Parent->Parameter['location'], 'Module ' . trim($Args[1]) . ' could not be found.');
                }
                else {
                    $Parent->PrivateMessage($Parent->Parameter['location'], 'Module ' . trim($Args[1]) . ' loaded.');
                }
            }
        }
        else {
            $Parent->PrivateMessage($Parent->Parameter['location'], 'Not enough clearance.');
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
        if ($Parent->SecurityClearance->HasSecurityClearance($Parent, 'CmdReloadModule', 'oper', $Parent->Parameter['hostmask']['nickname'])) {
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
        if ($Parent->SecurityClearance->HasSecurityClearance($Parent, 'CmdUnloadModule', 'oper', $Parent->Parameter['hostmask']['nickname'])) {
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
    
    public function CmdViewLoadedModules ($Parent, $Args = null) {
        if ($Parent->SecurityClearance->HasSecurityClearance($Parent, 'CmdViewLoadedModules', 'oper', $Parent->Parameter['hostmask']['nickname'])) {
            $Parent->PrivateMessage($Parent->Parameter['location'], 'The following modules are loaded: N/O');
        }
        else {
            $Parent->PrivateMessage($Parent->Parameter['location'], 'Not enough clearence.');
        }
    }
}

?> 