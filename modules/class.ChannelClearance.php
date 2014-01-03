<?php

/**
 * @author Patrick Rennings
 * @copyright 2010
 * @company 

 */

class ChannelClearance {
    public function AddClearance($Parent, $Args = null) {
        if ($this->is_channel($Args[1])) {
            if ($Parent->SecurityClearance->HasChannelClearance($Parent, 'AddClearance', 'chan', $Parent->Parameter['hostmask']['nickname'], $Parent->Parameter['location'])) {
                $Parent->PrivateMessage($Parent->Parameter['location'], 'Build mode...');
            }
            else {
                $Parent->PrivateMessage($Parent->Parameter['location'], 'Not enough clearence.');
            }
        }
        else {
            $Parent->PrivateMessage($Parent->Parameter['location'], 'You must specified a channel.');
        }
    }
    
    protected function is_channel($Channel) {
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