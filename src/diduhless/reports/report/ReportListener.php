<?php

declare(strict_types=1);


namespace diduhless\reports\report;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class ReportListener implements Listener {

    public function onQuit(PlayerQuitEvent $event): void {
        ReportFactory::dismissReport($event->getPlayer()->getName());
    }

}