<?php

declare(strict_types=1);

namespace greek\scoreboard;

use hcf\HCFLoader;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\Player;
use pocketmine\Server;

/**
 * Class Scoreboard
 * @package greek\scoreboard
 */
class Scoreboard
{

    /** @var int */
    private const SORT_ASCENDING = 0;
    /** @var string */
    private const SLOT_SIDEBAR = 'sidebar';

    /** @var string */
    private string $xuid;
    /** @var string */
    private string $title;
    /** @var ScorePacketEntry[] */
    private array $lines = [];
    
    /** @var bool */
    private bool $spawned = false;

    /**
     * Scoreboard constructor.
     * @param string $xuid
     * @param string $title
     */
    public function __construct(string $xuid, string $title)
    {
        $this->xuid = $xuid;
        $this->title = $title;
    }
    
    /**
     * @return bool
     */
    public function isSpawned(): bool
    {
        return $this->spawned;
    }
    
    /**
     * @return array
     */
    public function getLines(): array
    {
        return $this->lines;
    }
    
    /**
     * @return Player|null
     */
    private function getPlayer(): ?Player
	{
		return Server::getInstance()->getPlayer(HCFLoader::getInstance()->getSessionManager()->getPlayer($this->xuid)->getName());
	}

    public function initScoreboard() : void
    {
        $pkt = new SetDisplayObjectivePacket();
        $pkt->objectiveName = $this->getPlayer()->getName();
        $pkt->displayName = $this->title;
        $pkt->sortOrder = self::SORT_ASCENDING;
        $pkt->displaySlot = self::SLOT_SIDEBAR;
        $pkt->criteriaName = 'dummy';
        $this->getPlayer()->dataPacket($pkt);
        
        if (!$this->spawned) $this->spawned = true;
    }

    public function clearScoreboard(): void
    {
        $pkt = new SetScorePacket();
        $pkt->entries = $this->lines;
        $pkt->type = SetScorePacket::TYPE_REMOVE;
        $this->getPlayer()->dataPacket($pkt);
        $this->lines = [];
    }
	
	/**
	 * @param int $id
	 * @param string $line
	 */
    public function addLine(int $id, string $line): void
    {
        $entry = new ScorePacketEntry();
        $entry->type = ScorePacketEntry::TYPE_FAKE_PLAYER;

        if (isset($this->lines[$id])) {
            $pkt = new SetScorePacket();
            $pkt->entries[] = $this->lines[$id];
            $pkt->type = SetScorePacket::TYPE_REMOVE;
            $this->getPlayer()->dataPacket($pkt);
            unset($this->lines[$id]);
        }
        $entry->score = $id;
        $entry->scoreboardId = $id;
        $entry->entityUniqueId = $this->getPlayer()->getId();
        $entry->objectiveName = $this->getPlayer()->getName();
        $entry->customName = $line;
        $this->lines[$id] = $entry;

        $pkt = new SetScorePacket();
        $pkt->entries[] = $entry;
        $pkt->type = SetScorePacket::TYPE_CHANGE;
        $this->getPlayer()->dataPacket($pkt);
    }
	
	/**
	 * @param int $id
	 */
    public function removeLine(int $id): void
    {
        if (isset($this->lines[$id])) {
            $line = $this->lines[$id];
            $packet = new SetScorePacket();
            $packet->entries[] = $line;
            $packet->type = SetScorePacket::TYPE_REMOVE;
            $this->getPlayer()->dataPacket($packet);
            unset($this->lines[$id]);
        }
    }

    public function removeScoreboard(): void
    {
        $packet = new RemoveObjectivePacket();
        $packet->objectiveName = $this->getPlayer()->getName();
        $this->getPlayer()->dataPacket($packet);
        
        if ($this->spawned) $this->spawned = false;
    }
}