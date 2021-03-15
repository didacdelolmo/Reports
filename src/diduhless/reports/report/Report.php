<?php

declare(strict_types=1);


namespace diduhless\reports\report;


use CortexPE\DiscordWebhookAPI\Embed;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use diduhless\reports\Reports;
use diduhless\reports\session\Session;
use diduhless\reports\session\SessionFactory;
use pocketmine\Player;
use pocketmine\Server;

class Report {

    /** @var Session */
    private $sender;

    /** @var Session */
    private $target;

    /** @var string */
    private $reason;

    public function __construct(Session $sender, Session $target, string $reason) {
        $this->sender = $sender;
        $this->target = $target;
        $this->reason = $reason;
    }

    public function getSender(): Session {
        return $this->sender;
    }

    public function getTarget(): Session {
        return $this->target;
    }

    public function getReason(): string {
        return $this->reason;
    }

    public function send(): void {
        if($this->sender->canReport()) {
            $this->broadcastReport();
        } else {
            $this->sender->message("{RED}You must wait to send another report!");
        }
    }

    public function dismiss(Player $author): void {
        $target = $this->target->getUsername();
        $this->broadcastMessage("{BOLD}{RED}[!] {RESET}{RED}$target{WHITE}'s report was dismissed by {RED}{$author->getName()}{WHITE}.");

        ReportFactory::dismissReport($target);
    }

    private function broadcastReport(): void {
        $target = $this->target->getUsername();
        $sender = $this->sender->getUsername();
        $reason = $this->reason;

        $this->broadcastMessage("{BOLD}{RED}[!] {RESET}{YELLOW}$target {RED}has been reported by {YELLOW}$sender {RED}for {YELLOW}$reason{RED}!");
        $this->target->addReportCount();

        $config = Reports::getInstance()->getConfig();
        if(!$config->get("webhook.enable")) {
            return;
        }

        $embed = new Embed();
        $embed->setColor(16574595); // Yellow
        $embed->setTitle("A new report has been sent!");
        $embed->addField("Reported player", $target);
        $embed->addField("Reported by", $sender);
        $embed->addField("Reason", $reason);
        $embed->addField("Count", (string) $this->target->getReportsCount());

        $message = new Message();
        $message->addEmbed($embed);

        $webhook = new Webhook($config->get("webhook.url"));
        $webhook->send($message);
    }

    private function broadcastMessage(string $message): void {
        foreach(Server::getInstance()->getOnlinePlayers() as $player) {
            if($player->hasPermission(Reports::getInstance()->getConfig()->get("report.permission"))) {
                SessionFactory::getSession($player)->message($message);
            }
        }
    }

}