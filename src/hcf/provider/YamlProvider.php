<?php

declare(strict_types=1);

namespace hcf\provider;

use hcf\HCFLoader;
use pocketmine\utils\Config;

/**
 * Class YamlProvider
 * @package hcf\provider
 */
class YamlProvider
{
    
    public function startup(): void
    {
        $plugin = HCFLoader::getInstance();
        
        if (!is_dir($plugin->getDataFolder() . 'players'))
            @mkdir($plugin->getDataFolder() . 'players');
            
        if (!is_dir($plugin->getDataFolder() . 'factions'))
            @mkdir($plugin->getDataFolder() . 'factions');
            
        $plugin->saveDefaultConfig();
    }
    
    public function close(): void
    {
        $this->savePlayers();
    }
    
    /**
     * @return array
     */
    public function getPlayers(): array
    {
        $players = [];
        $files = glob(HCFLoader::getInstance()->getDataFolder() . 'players/*.yml');
        
        foreach ($files as $file)
            $players[basename($file, '.yml')] = (new Config(HCFLoader::getInstance()->getDataFolder() . 'players/' . basename($file), Config::YAML))->getAll();
        return $players;
    }
    
    public function savePlayers(): void
    {
        foreach (HCFLoader::getInstance()->getSessionManager()->getSessions() as $xuid => $session) {
            $config = new Config(HCFLoader::getInstance()->getDataFolder() . 'players/' . $xuid . '.yml', Config::YAML);
            $config->setAll($session->getData());
            $config->save();
        }
    }
}