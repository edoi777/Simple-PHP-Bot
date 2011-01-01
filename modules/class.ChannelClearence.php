<?php

/**
 * @author Patrick Rennings
 * @copyright 2010
 * @company 

 */

class ChannelClearence {
    public function AddUserClearence($Parent, $Args = null) {
        if ($this->is_channel($Args[1])) {
            if ($Parent->SecurityClearence->HasChannelClearence($Parent, 'AddUserClearence', 'chan', $Parent->Parameter['hostmask']['nickname'], $Parent->Parameter['location'])) {
                
            }
            else {
                $Parent->PrivateMessage($Parent->Parameter['location'], 'Not enough clearence.');
            }
        }
        else {
            $Parent->PrivateMessage($Parent->Parameter['location'], 'You must specified a channel.');
        }
    }
    
    protected function is_channel ($Channel) {
        if (!is_string($Channel)) {
            return false;
        }
        else {
            if ($Channel[0] == '#') {
                return true;
            }
            else {
                return false;
            }
        }
    }
}

?> 