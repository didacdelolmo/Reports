<?php

declare(strict_types=1);


namespace diduhless\reports\command;


use diduhless\reports\Reports;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

abstract class PlayerCommand extends Command implements PluginOwned {

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if($this->testPermission($sender) and $sender instanceof Player) {
            $this->onCommand($sender, $args);
        }
    }

    abstract public function onCommand(Player $player, array $args): void;

    public function getOwningPlugin(): Plugin {
        return Reports::getInstance();
    }

}