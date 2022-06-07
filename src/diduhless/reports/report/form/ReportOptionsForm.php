<?php

declare(strict_types=1);


namespace diduhless\reports\report\form;


use diduhless\reports\ColorUtils;
use diduhless\reports\report\Report;
use EasyUI\element\Button;
use EasyUI\variant\SimpleForm;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class ReportOptionsForm extends SimpleForm {

    private Report $report;

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
        $this->addGobackButton();
    }

    private function addSpectateButton(): void {
        $button = new Button("Spectate");
        $button->setSubmitListener(function(Player $player) {
            $player->setGamemode(GameMode::SPECTATOR());
            $player->teleport($this->report->getTarget()->getPlayer()->getPosition());
        });
        $this->addButton($button);
    }

    private function addDismissButton(): void {
        $button = new Button(TextFormat::RED . "Dismiss");
        $button->setSubmitListener(function(Player $player) {
            $this->report->dismiss($player);
        });
        $this->addButton($button);
    }

    private function addGobackButton(): void {
        $button = new Button("Go back");
        $button->setSubmitListener(function(Player $player) {
            $player->sendForm(new ReportListForm());
        });
    }

}