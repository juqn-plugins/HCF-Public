<?php

declare(strict_types=1);

namespace hcf;

use hcf\provider\YamlProvider;
use hcf\sessions\SessionManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

/**
 * Class HCFLoader
 * @package hcf
 */
class HCFLoader extends PluginBase
{
    use SingletonTrait;
    
    /** @var YamlProvider */
    private YamlProvider $yamlProvider;
    
    /** @var SessionManager */
    private SessionManager $sessionManager;
    
    public function onLoad()
    {
        self::setInstance($this);
    }
    
    public function onEnable()
    {
        # Provider
        $this->yamlProvider = new YamlProvider;
        
        # Managers
        $this->sessionManager = new SessionManager;
        
        # Startup
        $this->yamlProvider->startup();
        $this->sessionManager->startup();
        
        # Register Listener
        $this->getServer()->getPluginManager()->registerEvents(new HCFListener(), $this);
    }
    
    public function onDisable()
    {
        $this->yamlProvider->close();
    }
    
    /**
     * @return YamlProvider
     */
    public function getYamlProvider(): YamlProvider
    {
        return $this->yamlProvider;
    }
    
    /**
     * @return SessionManager
     */
    public function getSessionManager(): SessionManager
    {
        return $this->sessionManager;
    }
}