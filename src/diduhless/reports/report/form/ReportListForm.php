<?php

declare(strict_types=1);


namespace diduhless\reports\report\form;


use diduhless\reports\report\ReportFactory;
use diduhless\reports\session\SessionFactory;
use EasyUI\element\Button;
use EasyUI\variant\SimpleForm;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ReportListForm extends SimpleForm {

    public function __construct() {
        parent::__construct("Report List", "Select a report to execute an action:");
    }

    protected function onCreation(): void {
        foreach(ReportFactory::getReports() as $report) {
            $target = $report->getTarget();
            $target_name = $target->getUsername();

            $button = new Button(
                TextFormat::RED . $target_name . TextFormat::EOL .
                TextFormat::RESET . TextFormat::BOLD . $report->getReason()
            );
            $button->setSubmitListener(function(Player $player) use ($report, $target, $target_name) {
                if(!$target->getPlayer()->isOnline()) {
                    $session = SessionFactory::getSession($player);
                    $session->message("{RED}The player {WHITE}$target_name {RED}is not online!");
                } else {
                    $player->sendForm(new ReportOptionsForm($report));
                }
            });
            $this->addButton($button);
        }
    }

}