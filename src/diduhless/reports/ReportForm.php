<?php

declare(strict_types=1);


namespace diduhless\reports;


use diduhless\reports\session\SessionFactory;
use EasyUI\element\Dropdown;
use EasyUI\element\Option;
use EasyUI\utils\FormResponse;
use EasyUI\variant\CustomForm;
use pocketmine\Player;
use pocketmine\Server;

class ReportForm extends CustomForm {

    public function __construct() {
        parent::__construct("Report a player");
    }

    protected function onCreation(): void {
        $players_dropdown = new Dropdown("Select a player");
        foreach(Server::getInstance()->getOnlinePlayers() as $player) {
            $players_dropdown->addOption(new Option($username = $player->getName(), $username));
        }

        $reasons_dropdown = new Dropdown("Select a reason");
        foreach(Reports::getInstance()->getConfig()->get("report.reasons") as $reason) {
            $reasons_dropdown->addOption(new Option($reason, $reason));
        }

        $this->addElement("players_dropdown", $players_dropdown);
        $this->addElement("reasons_dropdown", $reasons_dropdown);
    }

    protected function onSubmit(Player $player, FormResponse $response): void {
        $target_username = $response->getDropdownSubmittedOptionId("players_dropdown");

        $sender_session = SessionFactory::getSession($player);
        $target_session = SessionFactory::getSessionByName($target_username);

        if($target_session === null) {
            $sender_session->message("{RED}The player {WHITE}$target_username {RED}is offline!");
            return;
        }
        $reason = $response->getDropdownSubmittedOptionId("reasons_dropdown");

        $report = new Report($sender_session, $target_session, $reason);
        $report->send();
    }

}