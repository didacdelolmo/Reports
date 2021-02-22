<?php
/*
 * Copyright (C) Diduhless - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 */

declare(strict_types=1);


namespace diduhless\reports;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class ReportCommand extends Command implements PluginIdentifiableCommand {

    /** @var Reports */
    private $plugin;

    public function __construct() {
        parent::__construct("report", "Reports a player");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if($sender instanceof Player) {
            $sender->sendForm(new ReportForm());
        }
    }

    public function getPlugin(): Plugin {
        return $this->plugin;
    }

}