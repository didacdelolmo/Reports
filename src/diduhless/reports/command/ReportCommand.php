<?php

declare(strict_types=1);


namespace diduhless\reports\command;


use diduhless\reports\report\form\ReportForm;
use pocketmine\Player;

class ReportCommand extends PlayerCommand {

    public function __construct() {
        parent::__construct("report", "Reports a player");
    }

    public function onCommand(Player $player, array $args) {
        $player->sendForm(new ReportForm());
    }

}