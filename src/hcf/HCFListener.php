<?php

declare(strict_types=1);

namespace hcf;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerLoginEvent;

/**
 * Class HCFListener
 * @package hcf
 */
class HCFListener implements Listener
{
    
    /**
     * @param PlayerCreationEvent $event
     */
    public function handleCreation(PlayerCreationEvent $event): void
    {
        $event->setPlayerClass(HCFPlayer::class);
    }
    
    /**
     * @param PlayerLoginEvent $event
     */
    public function handleLogin(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();
        
        if (!HCFLoader::getInstance()->getSessionManager()->hasSession($player->getXuid()))
            HCFLoader::getInstance()->getSessionManager()->createSession($player->getXuid(), $player->getName(), [
                'faction' => null,
                'balance' => 0
            ], true);
        else {
            $session = HCFLoader::getInstance()->getSessionManager()->getSession($player->getXuid());
            
            if ($session->getName() != $player->getName())
                $session->setName($player->getName());
        }
    }
}