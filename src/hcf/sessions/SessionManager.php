<?php

declare(strict_types=1);

namespace hcf\sessions;

/**
 * Class SessionManager
 * @package hcf\sessions
 */
class SessionManager
{
    
    /** @var Session[] */
    private array $sessions = [];
    
    public function startup(): void
    {
        foreach (HCFLoader::getInstance()->getYamlProvider()->getPlayers() as $xuid => $data)
            $this->createSession($xuid, $data['name'], $data, false);
    }
    
    /**
     * @return Session[]
     */
    public function getSessions(): array
    {
        return $this->sessions;
    }
    
    /**
     * @param string $xuid
     * @return bool
     */
    public function hasSession(string $xuid): bool
    {
        return isset($this->sessions[$xuid]);
    }
    
    /**
     * @param string $xuid
     * @return Session
     */
    public function getSession(string $xuid): Session
    {
        return $this->sessions[$xuid];
    }
    
    /**
     * @param string $xuid
     * @param string $name
     * @param array $data
     * @param bool $first
     */
    public function createSession(string $xuid, string $name, array $data, bool $first = true): void
    {
        $this->sessions[$xuid] = new Session($xuid, $name, $data, $first);
    }
}