<?php

declare(strict_types=1);

namespace hcf;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

/**
 * Class HCFLoader
 * @package hcf
 */
class HCFLoader extends PluginBase
{
    use SingletonTrait;
    
    public function onLoad()
    {
        self::setInstance($this);
    }
    
    public function onEnable()
    {
        # Register Listener
        $this->getServer()->getPluginManager()->registerEvents(new HCFListener(), $this);
    }
}