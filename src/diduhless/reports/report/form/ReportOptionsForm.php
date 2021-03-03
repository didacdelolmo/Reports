<?php

declare(strict_types=1);


namespace diduhless\reports\report\form;


use diduhless\reports\ColorUtils;
use diduhless\reports\report\Report;
use diduhless\reports\report\ReportFactory;
use EasyUI\element\Button;
use EasyUI\variant\SimpleForm;
use pocketmine\Player;

class ReportOptionsForm extends SimpleForm {

    /** @var Report */
    private $report;

    public function __construct(Report $report) {
        $this->report = $report;
        parent::__construct("Report Options", ColorUtils::translate(
            "The player {RED}" . $report->getTarget()->getUsername() .
            " {RESET}was reported by {RED}" . $report->getSender()->getUsername() .
            " {RESET}for {RED}" . $report->getReason() .
            "{RESET}.\n\nWhat do you want to do with this report?"
        ));
    }

    public function onCreation(): void {
        $this->addSpectateButton();
        $this->addDismissButton();
    }

    private function addSpectateButton(): void {
        $button = new Button("Spectate");
        $button->setSubmitListener(function(Player $player) {
            $player->setGamemode(Player::SPECTATOR);
            $player->teleport($this->report->getTarget()->getPlayer());
        });
        $this->addButton($button);
    }

    private function addDismissButton(): void {
        $button = new Button("Dismiss");
        $button->setSubmitListener(function(Player $player) {
            ReportFactory::dismissReport($this->report->getTarget()->getUsername());
        });
        $this->addButton($button);
    }

}