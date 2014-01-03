<?php
        
/**
 * @filesource bot.php
 * @version 0.2-alpha
 * @author Patrick Rennings
 * @copyright 2010
 * 
 * @usage Basic IRC Bot class
 */
ini_set('max_execution_time', 0);
        
Class ViiIrcBot {
    
    /**
     * @param $BotSocket
     * @usage Holding the socket of the bot
     */
     
    protected $BotSocket;
        
    /**
     * @param $BodData
     * @usage Holding the data of the raw lines of irc
     */
     
    protected $BotData;
            
    /**
     * @param $CommandHandler
     * @usage Holding the class of command handler (For usage of command)
     */
     
    protected $CommandHandler;
            
    /**
     * @param $SqlConnection
     * @usage Holding the SQL connection of the bot
     */
    
    protected $NickHandler;
            
    /**
     * @param $SqlConnection
     * @usage Holding the SQL connection of the bot
     */
     
    protected $SqlConnection;
    
    /**
     * @param $SqlConnectionHolder
     * @usage Holding the SQL connection of the bot
     */
     
    protected $SqlConnectionHolder;
            
    /**
     * @param $ModuleSystem
     * @usage Holding the class of the module handler
     */
     
    public $ModuleSystem;
    
    /**
     * @param $SecurityClearance
     * @usage Holding the class of the security handler
     */
     
    public $SecurityClearance;
    
    /**
     * @param $Basics
     * @usage Holding the class of the basic commands (Needed to run the bot)
     */
     
    public $Basics;
            
    /**
     * @param $Parameter
     * @usage Defining usable data of the raw line of irc and put them in here
     */
         
    public $Parameter;
            
    /**
     * @param $BotConfig
     * @usage Configuration lines for the bot (editable)
     */
     
    public $BotConfig;
            
    /**
     * @param $Modules
     * @usage Holding loaded modules for the bot
     */
     
    public $Modules = array();

    
    /**
     * @name    ViiIrcBot::__construct
     * @param   void
     * @return  void
     * 
     * @usage  Main part for creating the bot
     */
    
    public function __construct () {
        
        
        $this->BotConfig = array(
        
            /**
             * Bot configuration for information
             */
             
            'nickname' => 'Muts',
            'realname' => 'Vii\'s personal funbot',
            'ident'    => 'Muts',
            
            /**
             * Bot server configration
             */
             
            'hostname' => 0,
            'server'   => 'irc.ogamenet.net',
            'port'     => 6667,
            
            /**
             * Other bot configuration
             */
             
            'serverpath'  => 'D:/xampp/htdocs/ModularBot/Simple-PHP-Bot/',
            'logpath'     => 'D:/xampp/htdocs/ModularBot/Simple-PHP-Bot/logs/',
            'classpath'   => 'D:/xampp/htdocs/ModularBot/Simple-PHP-Bot/classes/',
            'modulepath'  => 'D:/xampp/htdocs/ModularBot/Simple-PHP-Bot/modules/',

            /**
             * MySQL Database configuration
             */
             
             'dbhtname'   => 'localhost',
             'username'   => 'root',
             'password'   => '',
             'database'   => 'ircbot'                                                                             
        );
         
         /**
          * Connect to mysql database                                               
          */
          
            $this->SqlConnectionHolder = mysql_connect($this->BotConfig['dbhtname'], $this->BotConfig['username'], $this->BotConfig['password']);
            $this->SqlConnection = mysql_select_db($this->BotConfig['database'], $this->SqlConnectionHolder);
            
            if (!$this->SqlConnection) {
                die('Mysql connection error');
            } 
                                     
         /**
          * Include and create classes for usage
          */
          
          include_once($this->BotConfig['classpath'] . 'class.Modules.php');
          include_once($this->BotConfig['classpath'] . 'class.CommandHandler.php');
          include_once($this->BotConfig['classpath'] . 'class.NickHandler.php');
          include_once($this->BotConfig['classpath'] . 'class.SecurityClearance.php');
          include_once($this->BotConfig['classpath'] . 'class.Basics.php');
          
          $this->ModuleSystem = new Modules($this);
          $this->CommandHandler = new CommandHandler($this);
          $this->NickHandler = new NickHandler($this);
          $this->SecurityClearance = new SecurityClearance($this);
          $this->Basics = new Basics($this);
          
          $this->ModuleSystem->LoadModule($this, 'Modules');
          $this->ModuleSystem->LoadModule($this, 'Basics');
          
          /**
           * Update SQL tabels
           */
           
           mysql_query('DELETE FROM nicknames');
          
         /**
          * Prepare for bot connection
          */
          
          if(!$this->ConnectViiBot()) {
                die('Connection failed.');
          }
          else {
            
                $this->ConnectToIrc();
                $this->BotMainLoop();
          }
    }
    
    /**
     * @name    ViiIrcBot::ConnectViiBot
     * @param   void
     * @return  boolean
     * 
     * @usage  login to the irc server
     */
    
    protected function ConnectViiBot() {
        
        /**
         * Create a socket for bot connection
         */
         
        $this->BotSocket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        if (!$this->BotSocket) {
            return false;
        }
        
        /**
         * Bind the socket to make it irc'able 
         */
         
        if(!socket_bind($this->BotSocket, $this->BotConfig['hostname'])) { 
            return false;
        } 
        if(!socket_connect($this->BotSocket, $this->BotConfig['server'], $this->BotConfig['port'])) { 
            return false;
        } 
        
        /**
         * Return positive result
         */
        return $this->BotSocket;
    }
    
    /**
     * @name    ViiIrcBot::ConnectToIrc
     * @param   void
     * @return  boolean
     * 
     * @usage  Connect to the irc server
     */
    
    protected function ConnectToIrc () {
        
        /**
         * Utilize connection
         */
        
        $this->RawWrite('USER ' . $this->BotConfig['ident']. ' ' . $this->BotConfig['hostname'] . ' ' . $this->BotConfig['server'] . ' :'. $this->BotConfig['realname']); 
                        
        /**
         * Define bot nickname to use
         * on the server
         */
                        
        $this->RawWrite('NICK ' . $this->BotConfig['nickname']);
        return true; 
    }
    
    /**
     * @name    ViiIrcBot::RawWrite
     * @param   $Parameter
     * @return  void
     * 
     * @usage   dump an raw line in to the server
     */  
    
    public function RawWrite ($Parameter) {
        
        socket_write($this->BotSocket, $Parameter . "\r\n");
        
    }

    /**
     * @name    ViiIrcBot::DefineRawLine
     * @param   void
     * @return  Void
     * 
     * @usage   Defining usable parameters for commands
     */ 
    
    protected function DefineRawLine($RawLine) {
        $this->Parameter['hostmask'] = $RawLine[0];
        $this->Parameter['servercmd'] = $RawLine[1];
        
        if ($RawLine[2] != '') {
            $this->Parameter['location'] = $RawLine[2];
            $this->Parameter['command'] = trim(substr($RawLine[3], 1));
        }
        if (!empty($this->Parameter['command'])) {
            $ExplodingPar = explode($RawLine[3], $this->BotData);
            $this->Parameter['parameters'] = $ExplodingPar[1];
        }
        
        /**
         * Splitting hostmask for usable things
         * $this->Parameter['hostmask'] = Vii`!Vii@Vii.gameoperator.OGameNl
         */
         
         if (preg_match('/^([^!@]+)!(?:[ni]=)?([^@]+)@([^ ]+)/', $this->Parameter['hostmask'])) {
            $TempStorage = preg_match('/^([^!@]+)!(?:[ni]=)?([^@]+)@([^ ]+)/', $this->Parameter['hostmask'], $HostArray);
            $this->Parameter['hostmask'] = array();
            
            $this->Parameter['hostmask']['hostmask'] = substr($HostArray[0], 1);
            $this->Parameter['hostmask']['nickname'] = substr($HostArray[1], 1);
            $this->Parameter['hostmask']['ident'] = $HostArray[2];
            $this->Parameter['hostmask']['banmask'] = '*!*@' . $HostArray[3];
         }
    }
    
    /**
     * @name    ViiIrcBot::BotMainLoop
     * @param   void
     * @return  boolean
     * 
     * @usage   Main loop of usage of the bot
     */ 
    
    protected function BotMainLoop () {
        if (!file_exists($this->BotConfig['logpath'] . 'log' . date('d-m-Y') . '.txt')) {
            $LogFile = fopen($this->BotConfig['logpath'] . 'log' . date('d-m-Y') . '.txt', 'x+' );
        } else {
            $LogFile = fopen($this->BotConfig['logpath'] . 'log' . date('d-m-Y') . '.txt', 'a+' );
        }

        while($this->BotData = socket_read($this->BotSocket,65000,PHP_NORMAL_READ)) { 
            
            /**
             * Even when the line is empty, just continue
             */
             
            if($this->BotData == "\n") { continue; } 
            
            /**
             * Get usable data from the raw output line of irc
             */
            
            $ConfiguratingData = explode(' ', $this->BotData);
            $this->DefineRawLine($ConfiguratingData);
            
            /**
             * Go play ping pong with the server
             */

            $this->NickHandler->core($this, $this->BotData);
            $this->CommandHandler->FetchCommand($this);
            $this->Basics->CheckHandling($this);

            if ($this->BotData) {
                $LogWrite = fwrite($LogFile, $this->BotData);
            }
        }
    }
    
    public function PrivateMessage ($Location, $Message) {
        $this->RawWrite('PRIVMSG ' . $Location . ' :' . $Message);
    }
	
	public function NoticeMessage ($Location, $Message) {
        $this->RawWrite('NOTICE ' . $Location . ' :' . $Message);
    }
}

$ViiBot = new ViiIrcBot();
?>