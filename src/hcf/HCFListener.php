<?php

declare(strict_types=1);

namespace hcf;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;

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
}