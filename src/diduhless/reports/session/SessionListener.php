<?php
/*
 * Copyright (C) Diduhless - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 */

declare(strict_types=1);


namespace diduhless\reports\session;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

class SessionListener implements Listener {

    public function onLogin(PlayerLoginEvent $event): void {
        SessionFactory::startSession($event->getPlayer());
    }

    public function onQuit(PlayerQuitEvent $event): void {
        SessionFactory::closeSession($event->getPlayer());
    }

}