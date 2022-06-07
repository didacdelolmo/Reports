<?php

declare(strict_types=1);


namespace diduhless\reports\command;


use diduhless\reports\report\form\ReportListForm;
use diduhless\reports\Reports;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\player\Player;

class ReportListCommand extends PlayerCommand {

    public function __construct() {
        $permission = Reports::getInstance()->getConfig()->get("report.permission");
        $manager = PermissionManager::getInstance();
        $manager->addPermission(new Permission($permission));
        $manager->getPermission(DefaultPermissions::ROOT_OPERATOR)->addChild($permission, true);

        parent::__construct("reportlist", "Views all the reported players", null, ["reports"]);
    }

    public function onCommand(Player $player, array $args): void {
        $player->sendForm(new ReportListForm());
    }

}