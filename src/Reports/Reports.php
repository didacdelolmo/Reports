<?php


namespace Reports;


use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;
use Reports\command\ReportCommand;

class Reports extends PluginBase {

    public function onEnable() {
        $this->getServer()->getCommandMap()->register("report", new ReportCommand($this));

        $logger = $this->getLogger();
        $logger->info(
            TF::DARK_GRAY . "[" . TF::DARK_AQUA . "Reports by @Diduhless" . TF::DARK_GRAY . "] " .
            TF::YELLOW . "Reports has been enabled. Updates are released on https://github.com/Diduhless/Reports"
        );
    }

}