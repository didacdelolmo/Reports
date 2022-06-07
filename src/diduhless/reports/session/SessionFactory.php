<?php

declare(strict_types=1);


namespace diduhless\reports\session;


use pocketmine\player\Player;
use pocketmine\Server;

class SessionFactory {

    /** @var Session[] */
    static private array $sessions;

    static public function getSession(Player $player): ?Session {
        return self::$sessions[$player->getName()] ?? null;
    }

    static public function getSessionByName(string $username): ?Session {
        $player = Server::getInstance()->getPlayerExact($username);
        return $player !== null ? self::getSession($player) : null;
    }

    static public function startSession(Player $player): void {
        self::$sessions[$player->getName()] = new Session($player);
    }

    static public function closeSession(Player $player): void {
        if(isset(self::$sessions[$username = $player->getName()])) {
            unset(self::$sessions[$username]);
        }
    }

}