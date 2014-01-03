<?php

/**
 * @author Patrick Rennings
 * @copyright 2010
 * @company 

 */

class Basics {
    public function DoRawLine($Parent, $Args = null) {
         if (!$Parent->SecurityClearance->HasSecurityClearance($Parent, 'DoRawLine', 'oper', $Parent->Parameter['hostmask']['nickname'], $Parent->Parameter['location'])) {
            $Parent->PrivateMessage($Parent->Parameter['location'], 'Not enough access.');   
        }
        else {
            if ($Args[1]) {
                $Parent->RawWrite($Parent->Parameter['parameters']);
            }
        }
    }
}

?> 