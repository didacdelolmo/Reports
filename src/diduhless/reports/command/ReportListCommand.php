<?php

declare(strict_types=1);


namespace diduhless\reports\command;


use diduhless\reports\report\form\ReportListForm;
use diduhless\reports\Reports;
use pocketmine\Player;

class ReportListCommand extends PlayerCommand {

    public function __construct() {
        $this->setPermission(Reports::getInstance()->getConfig()->get("report.permission"));
        parent::__construct("reportlist", "Views all the reported players", null, ["reports"]);
    }

    public function onCommand(Player $player, array $args) {
        $player->sendForm(new ReportListForm());
    }

}