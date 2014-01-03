<?php



/**
 * Modules
 * 
 * @author Patrick Rennings
 * @copyright Patrick Rennings
 * @version 1.0
 * @access public
 */
 
class Modules {
    
    /**
     * Modules::__construct()
     * 
     * @return
     */
     
    public function __construct() {
        
    }

    /**
     * Modules::LoadModule()
     * 
     * @param mixed $Parent
     * @param mixed $ModuleName
     * @return
     */
     
    public function LoadModule($Parent, $ModuleName) {
        $UniqId = trim($ModuleName) . uniqid();
        $ModuleName = trim($ModuleName) . '.php';
        
        if (file_exists($Parent->BotConfig['modulepath'] . 'class.' . $ModuleName)) {
                        
            $ModuleContent = file_get_contents($Parent->BotConfig['modulepath'] . 'class.' . $ModuleName);
            $ModuleContent = preg_replace('@class\s+(\w+)@i','class '. $UniqId, $ModuleContent, 1);
            
            eval('?>' . $ModuleContent);
                        
            $ModuleName = substr($ModuleName, 0, -4);
            $Parent->Modules[$UniqId] = new $UniqId($this);
                    
            $Parent->Temp = $UniqId;
        }
        else {
            return false;
        }          
    }
    
    /**
     * Modules::ReloadModule()
     * 
     * @param mixed $Parent
     * @param mixed $ModuleName
     * @return
     */
     
    public function ReloadModule ($Parent, $ModuleName) {
        $LengthString = strlen(trim($ModuleName));

        foreach ($Parent->Modules as $ModName => $Instance) {
            
            $uIdModule = substr($ModName, $LengthString );
            $WholeModule = trim($ModuleName) . $uIdModule;
            

            if ($ModName == $WholeModule) {
                unset($Parent->Modules[$ModName]);

                $UniqId = trim($ModuleName) . uniqid();
                $ModuleName = trim($ModuleName) . '.php';
                
                if (file_exists($Parent->BotConfig['modulepath'] . 'class.' . $ModuleName)) {
                            
                    $ModuleContent = file_get_contents($Parent->BotConfig['modulepath'] . 'class.' . $ModuleName);
                    $ModuleContent = preg_replace('@class\s+(\w+)@i','class ' . $UniqId, $ModuleContent, 1);
                            
                    eval('?>' . $ModuleContent);
                            
                    $ModuleName = substr($ModuleName, 0, -4);
                    $Parent->Modules[$UniqId] = new $UniqId($this);
                    
                    $Parent->Temp = $UniqId;
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * Modules::UnloadModule()
     * 
     * @param mixed $Parent
     * @param mixed $ModuleName
     * @return
     */
     
    public function UnloadModule ($Parent, $ModuleName) {
        $LengthString = strlen(trim($ModuleName));
        foreach ($Parent->Modules as $ModName => $Instance) {

            $uIdModule = substr($ModName, $LengthString );
            $WholeModule = trim($ModuleName) . $uIdModule;
                    
            if ($ModName == $WholeModule) {
                unset($Parent->Modules[$ModName]);
                return true;
            }
        }
        return false;
    }
}
?> 