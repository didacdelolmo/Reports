<?php

declare(strict_types=1);


namespace diduhless\reports\session;


use diduhless\reports\Reports;
use diduhless\reports\ColorUtils;
use pocketmine\player\Player;

class Session {

    private Player $player;
    private int $reports_count = 0;
    private int $last_report_time = 0;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function getUsername(): string {
        return $this->player->getName();
    }

    public function addReportCount(): void {
        $this->reports_count++;
    }

    public function getReportsCount(): int {
        return $this->reports_count;
    }

    public function canReport(): bool {
        if(time() - $this->last_report_time >= Reports::getInstance()->getConfig()->get("report.cooldown")) {
            $this->last_report_time = time();
            return true;
        }
        return false;
    }

    public function message(string $message): void {
        $this->player->sendMessage(ColorUtils::translate($message));
    }

}