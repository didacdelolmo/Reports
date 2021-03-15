<?php

declare(strict_types=1);


namespace diduhless\reports\command;


use diduhless\reports\Reports;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

abstract class PlayerCommand extends Command implements PluginIdentifiableCommand {

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if($this->testPermission($sender) and $sender instanceof Player) {
            $this->onCommand($sender, $args);
        }
    }

    abstract public function onCommand(Player $player, array $args);

    public function getPlugin(): Plugin {
        return Reports::getInstance();
    }

}