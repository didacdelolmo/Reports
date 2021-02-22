<?php

declare(strict_types=1);


namespace diduhless\reports;


use CortexPE\DiscordWebhookAPI\Embed;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use diduhless\reports\session\Session;
use diduhless\reports\session\SessionFactory;
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

    private function broadcastReport(): void {
        $target = $this->sender->getUsername();
        $sender = $this->target->getUsername();
        $reason = $this->reason;

        $config = Reports::getInstance()->getConfig();
        foreach(Server::getInstance()->getOnlinePlayers() as $player) {
            if(!$player->hasPermission($config->get("report.permission"))) {
                continue;
            }

            $session = SessionFactory::getSession($player);
            $session->message("{BOLD}{RED}[!] {RESET}{YELLOW}$target {RED}has been reported by {YELLOW}$sender {RED}for {YELLOW}$reason{RED}!");
        }
        $this->target->addReportCount();

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

}