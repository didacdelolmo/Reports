<?php

declare(strict_types=1);


namespace diduhless\reports;


use diduhless\reports\command\ReportCommand;
use diduhless\reports\command\ReportListCommand;
use diduhless\reports\report\ReportListener;
use diduhless\reports\session\SessionListener;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Reports extends PluginBase {
    use SingletonTrait;

    public function onLoad() {
        self::setInstance($this);
        $this->saveDefaultConfig();
    }

    public function onEnable() {
        $this->registerEvents(new SessionListener());
        $this->registerEvents(new ReportListener());

        $this->registerCommand(new ReportCommand());
        $this->registerCommand(new ReportListCommand());
    }

    private function registerEvents(Listener $listener): void {
        $this->getServer()->getPluginManager()->registerEvents($listener, $this);
    }

    private function registerCommand(Command $command): void {
        $this->getServer()->getCommandMap()->register("reports", $command);
    }

}