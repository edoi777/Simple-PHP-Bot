<?php

/**
 * @author Patrick Rennings
 * @copyright 2010
 * @package Modules
 * 
 * @purpose Showing how the module system works.
 * @param $Parent = Object of parent class (Main socket class)
 * @param $Args = Arguments given by the user string
 */

class HelloWorld {
    
    public function core($Parent, $Args = null) {
        if (!$Parent->SecurityClearance->HasSecurityClearance($Parent, 'core', 'oper', $Parent->Parameter['hostmask']['nickname'])) {
            $Parent->PrivateMessage($Parent->Parameter['location'], 'Not enough access.');
        }
        else {
            $Parent->PrivateMessage($Parent->Parameter['location'], 'Welcome my friend!');
        }
    }
    
    public function hug($Parent, $Args = null) {
         if (!$Parent->SecurityClearance->HasChannelClearance($Parent, 'hug', 'chan', $Parent->Parameter['hostmask']['nickname'], $Parent->Parameter['location'])) {
            $Parent->PrivateMessage($Parent->Parameter['location'], 'Not enough access.');   
        }
        else {
            if ($Args[1]) {
                $Parent->PrivateMessage($Parent->Parameter['location'], chr(1)  . 'ACTION knuffelt ' . $Args[1]. chr(1));
            }
            else {
                $Parent->PrivateMessage($Parent->Parameter['location'], chr(1)  . 'ACTION knuffelt ' . $Parent->Parameter['hostmask']['nickname']. chr(1));
            }
        }
    }
    
    public function FunKill($Parent, $Args = null) {
        if ($Args[1]) {
            $Parent->PrivateMessage($Parent->Parameter['location'], chr(1) . 'ACTION kills ' . trim($Args[1]) . ' with a bloody knife ' . chr(1));    
        }
        else {
            $Parent->PrivateMessage($Parent->Parameter['location'], chr(1)  . 'ACTION kills ' . $Parent->Parameter['hostmask']['nickname']. ' with a bloody knife ' . chr(1));
        }
    }
    
    public function UserAccess($Parent, $Args = null) {
        if ($Args[1]) {
            $Parent->PrivateMessage($Parent->Parameter['location'], 'The user ' . trim($Args[1]) . ' has an security clearence of: ' 
            . $Parent->SecurityClearance->GetClearanceOfUser($Parent, trim($Args[1])));
        }
        else {
            $Parent->PrivateMessage($Parent->Parameter['location'], 'Security clearence of: ' 
            . $Parent->SecurityClearance->GetClearanceOfUser($Parent, $Parent->Parameter['hostmask']['nickname']));
        }
    }
}

?> 