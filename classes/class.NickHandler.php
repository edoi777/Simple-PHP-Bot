<?php

/**
 * @author Patrick Rennings
 * @copyright 2010
 * @company 

 */

class NickHandler {
    
    public function core ($Parent, $RawLine) {
        if ($Parent->Parameter['servercmd'] == 'NICK') {
            $this->RetrieveNickWithNickEvent($Parent, $RawLine);
            return true;
        }
        
        // JOIN event
        elseif ($Parent->Parameter['servercmd'] == 'JOIN') {
            $this->RetrieveNickWithJoinEvent($Parent, $RawLine);
            return true;
        }
        
        // QUIT event
        elseif ($Parent->Parameter['servercmd'] == 'QUIT') {
            $this->NickUpdateQuitEvent($Parent, $RawLine);
            return true;
        }
        
        // /whois command line
        elseif ($Parent->Parameter['servercmd'] == '330') {
            $this->RetrieveNickWithWhoisEvent($Parent, $RawLine);
            return true;
        }
        
        // /who command line
        elseif ($Parent->Parameter['servercmd'] == '354') {
            $this->RetrieveNickWithWhoEvent($Parent, $RawLine);
            return true;
        }
        
        else {
            return;
        }
    }
    
    protected function RetrieveNickWithNickEvent ($Parent, $RawLineFromServer) {
        //:Vii!Vii@Vii.gameoperator.OGameNl NICK :Vii`
        
        $RawLine = substr($RawLineFromServer, 1);
                    
        $NewNickRaw = explode(':', $RawLine);
        $NickLength = strlen($NewNickRaw[1]);
        $NewNick = substr($NewNickRaw[1], 0, -1);
                    
        $OldNickRaw = explode('!', $RawLine);
        $OldNick = $OldNickRaw[0];
        
        $NickCheck = mysql_query('SELECT nick_nickname
                                  FROM nicknames 
                                  WHERE nick_nickname = "' . $OldNick . '"');
        
        if (mysql_num_rows($NickCheck) > 0) {
            mysql_query('UPDATE nicknames
                         SET nick_nickname = "' . $NewNick . '",
                             nick_old_nickname = "' . $OldNick . '"
                         WHERE nick_nickname = "' . $OldNick . '"');
                         
            return true;
        }
        else {
            mysql_query('INSERT INTO nicknames (nick_nickname, nick_old_nickname)
                         VALUES
                         ("' . $NewNick . '", "' . $OldNick . '")');
            
            return true;
        }
    }
    
    protected function RetrieveNickWithWhoisEvent ($Parent, $RawLineFromServer) {
        // :OGN1.OnlineGamesNet.net 330 Muts Vii`Loves`Snow Vii :is logged in as
        
        $RawLine = substr($RawLineFromServer, 1);
        $ExplodeRaw = explode(' ',   $RawLine);
                    
        $NickName = trim($ExplodeRaw[3]);
        $AuthName = trim($ExplodeRaw[4]);
        
        $AuthCheck = mysql_query('SELECT account_id, account_name FROM accounts
                                  WHERE account_name = "' . $AuthName . '"');
        
        if (mysql_num_rows($AuthCheck) > 0) {
            $NickCheck = mysql_query('SELECT nick_nickname, nick_auth_id FROM nicknames
                                      WHERE nick_nickname = "' . $NickName . '"');
                                      
            if (mysql_num_rows($NickCheck) > 0) {
                $NickFetch = mysql_fetch_assoc($NickCheck);
                $AuthFetch = mysql_fetch_assoc($AuthCheck);
                
                if ($NickFetch['nick_auth_id'] == $AuthFetch['account_id']) {
                    return true;
                }
                else {
                    mysql_query('UPDATE nicknames SET
                                 nick_auth_id = "' . $AuthFetch['account_id'] . '"
                                 WHERE nick_nickname = "' . $NickName . '"');
                    
                    return true;
                }
            }
            else {
                $AuthFetch = mysql_fetch_assoc($AuthCheck);
                
                mysql_query('INSERT INTO nicknames
                             (nick_nickname, nick_auth_id)
                             VALUES
                             ("' . $NickName . '", "' . $AuthFetch['account_id'] . '")');
                             
                return true;
            }
        }
        else {
            mysql_query('INSERT INTO accounts
                         (account_name)
                         VALUES
                         ("' . $AuthName . '")');
                         
            $AccountId = mysql_insert_id();
                         
            $NickCheck = mysql_query('SELECT nick_nickname, nick_auth_id FROM nicknames
                                      WHERE nick_nickname = "' . $NickName . '"');
                                      
            if (mysql_num_rows($NickCheck) > 0) {
                
                mysql_query('UPDATE nicknames SET
                             nick_auth_id = "' . $AccountId . '"
                             WHERE nick_nickname = "' . $NickName . '"');
                
                return true;
            }
            else {
                mysql_query('INSERT INTO nicknames
                             (nick_nickname, nick_auth_id)
                             VALUES
                             ("' . $NickName . '", "' . $AccountId . '")');
                
                return true;
            }
        }
    }
    
    protected function RetrieveNickWithWhoEvent ($Parent, $RawLineFromServer) {
        // :OGN1.OnlineGamesNet.net 354 Muts Vii.gameoperator.OGameNl Vii` Vii
        
        $RawLine = substr($RawLineFromServer, 1);
        $ExplodeRaw = explode(' ', $RawLine);
        
        $NickName = $ExplodeRaw[4];
        $AuthName = substr($ExplodeRaw[5], 0, -1);
        
        $AuthCheck = mysql_query('SELECT account_id, account_name FROM accounts
                                  WHERE account_name = "' . $AuthName . '"');
        
        if (mysql_num_rows($AuthCheck) > 0) {
            $NickCheck = mysql_query('SELECT nick_nickname, nick_auth_id FROM nicknames
                                      WHERE nick_nickname = "' . $NickName . '"');
                                      
            if (mysql_num_rows($NickCheck) > 0) {
                $NickFetch = mysql_fetch_assoc($NickCheck);
                $AuthFetch = mysql_fetch_assoc($AuthCheck);
                
                if ($NickFetch['nick_auth_id'] == $AuthFetch['account_id']) {
                    return true;
                }
                else {
                    mysql_query('UPDATE nicknames SET
                                 nick_auth_id = "' . $AuthFetch['account_id'] . '"
                                 WHERE nick_nickname = "' . $NickName . '"');
                    
                    return true;
                }
            }
            else {
                $AuthFetch = mysql_fetch_assoc($AuthCheck);
                
                mysql_query('INSERT INTO nicknames
                             (nick_nickname, nick_auth_id)
                             VALUES
                             ("' . $NickName . '", "' . $AuthFetch['account_id'] . '")');
                             
                return true;
            }
        }
        else {
            mysql_query('INSERT INTO accounts
                         (account_name)
                         VALUES
                         ("' . $AuthName . '")');
                         
            $AccountId = mysql_insert_id();
                         
            $NickCheck = mysql_query('SELECT nick_nickname, nick_auth_id FROM nicknames
                                      WHERE nick_nickname = "' . $NickName . '"');
                                      
            if (mysql_num_rows($NickCheck) > 0) {
                
                mysql_query('UPDATE nicknames SET
                             nick_auth_id = "' . $AccountId . '"
                             WHERE nick_nickname = "' . $NickName . '"');
                
                return true;
            }
            else {
                mysql_query('INSERT INTO nicknames
                             (nick_nickname, nick_auth_id)
                             VALUES
                             ("' . $NickName . '", "' . $AccountId . '")');
                
                return true;
            }
        }
    }
    
    protected function NickUpdateQuitEvent ($Parent, $RawLineFromServer) {
        // :LieveRena!~Rena@Rena.admin.BiteFightNl QUIT :Quit
        
        $RawLine = substr($RawLineFromServer, 1);
        $ExplodeRaw = explode(' ', $RawLine);
        
        $RetrieveNick = explode('!', $RawLine);
        $NickName = $RetrieveNick[0];
        
        mysql_query('DELETE FROM nicknames
                     WHERE nick_nickname = "' . $NickName . '"');
    }
    
    protected function RetrieveNickWithJoinEvent ($Parent, $RawLineFromServer) {
        // :Vii`!Vii@Vii.gameoperator.OGameNl JOIN #Vii
        
        $RawLine = substr($RawLineFromServer, 1);
        $ExplodeRaw = explode(' ', $RawLine);
        
        if ($NickName = $Parent->BotConfig['nickname']) {
            $Parent->RawWrite('WHO ' . trim($ExplodeRaw[2]) . ' %nha');
        }
        
        $RetrieveNick = explode('!', $RawLine);
        $NickName = $RetrieveNick[0];
        
        if ($Parent->BotConfig['nickname'] == $NickName) {
            $Parent->RawWrite('WHO ' . $ExplodeRaw[2] . ' %nha');
        }
        $Parent->RawWrite('WHO ' . $NickName . ' %nha');
    }
}

?> 