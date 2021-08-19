<?php

declare(strict_types=1);

namespace hcf\sessions;

use addon\scoreboard\Scoreboard;
use hcf\HCFLoader;
use pocketmine\utils\TextFormat;

/**
 * Class Session
 * @package hcf\sessions
 */
class Session
{

    /** @var string */
    private string $name;
    /** @var string|null */
    private ?string $faction;
    
    /** @var int */
    private int $balance;
    
    /** @var Scoreboard */
    private Scoreboard $scoreboard;
    
    /**
     * Session construct.
     * @param string $xuid
     * @param string $name
     * @param array $data
     * @param bool $firstConnect
     */
    public function __construct(string $xuid, string $name, array $data, bool $firstConnect)
    {
        $this->name = $name;
        $this->faction = $data['faction'];
        $this->balance = $data['balance'];
        $this->scoreboard = new Scoreboard($xuid, TextFormat::colorize(HCFLoader::getInstance()->getConfig()->get('scoreboard-title')));
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @return string|null
     */
    public function getFaction(): ?string
    {
        return $this->faction;
    }
    
    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }
    
    /**
     * @return Scoreboard
     */
    public function getScoreboard(): Scoreboard
    {
        return $this->scoreboard;
    }
    
    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    
    /**
     * @param string|null $factionName
     */
    public function setFaction(?string $factionName): void
    {
        $this->faction = $factionName;
    }
    
    /**
     * @param int $balance
     */
    public function setBalance(int $balance = 0): void
    {
        $this->balance = $balance;
    }
    
    /**
     * @return array
     */
    public function getData(): array
    {
        $data = [
            'name' => $this->getName(),
            'faction' => $this->getFaction(),
            'balance' => $this->getBalance()
        ];
        return $data;
    }
}