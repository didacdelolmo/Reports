<?php


namespace diduhless\reports;


use CortexPE\DiscordWebhookAPI\Embed;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class ReportCommand extends Command {

    /** @var Reports */
    private $plugin;

    /** @var int[] */
    private $cooldowns = [];

    /**
     * ReportCommand constructor.
     * @param Reports $plugin
     */
    public function __construct(Reports $plugin) {
        $this->plugin = $plugin;
        parent::__construct("report", "Report a player", "/report <player> <reason>");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!isset($args[1])) {
            $sender->sendMessage(TF::RED ."Usage: " . $this->getUsage());
            return;
        }

        $server = $this->plugin->getServer();
        $player = $server->getPlayer(array_shift($args));

        if($player === null) {
            $sender->sendMessage(TF::RED . "This player is not online!");
            return;
        }

        $username = $player->getName();

        if(isset($this->cooldowns[$username])) {
            $cooldown = $this->cooldowns[$username];
            if(isset($cooldown) and time() - $cooldown < Reports::getInstance()->getConfig()->get("report-cooldown")) {
                $sender->sendMessage(TF::RED . "You cannot send multiple reports at once!");
                return;
            }
        }

        $sender->sendMessage(TF::GREEN . "The report has been sent.");
        $this->cooldowns[$username] = time();

        $reason = implode(" ", $args);
        foreach($server->getOnlinePlayers() as $online_player) {
            if($player->hasPermission("reports.logs")) {
                $this->announceReport($online_player, $sender, $player, $reason);
            }
        }

        $this->sendWebhook($sender, $player, $reason);
    }

    /**
     * @param Player $admin
     * @param CommandSender $sender
     * @param Player $target
     * @param string $reason
     */
    private function announceReport(Player $admin, CommandSender $sender, Player $target, string $reason): void {
        $admin->sendMessage(TF::DARK_GRAY . "***************");
        $admin->sendMessage(TF::DARK_PURPLE . "NEW REPORT");
        $admin->sendMessage(TF::DARK_AQUA . "Reported player: " . TF::GRAY . $target->getName());
        $admin->sendMessage(TF::DARK_AQUA . "Reported by: " . TF::GRAY . $sender->getName());
        $admin->sendMessage(TF::DARK_AQUA . "Reason: " . TF::GRAY . $reason);
        $admin->sendMessage(TF::DARK_GRAY . "***************");
    }

    public function sendWebhook(CommandSender $sender, Player $target, string $reason): void {
        $config = $this->plugin->getConfig();
        if(!$config->get("enable-webhook")) return;

        $embed = new Embed();
        $embed->setColor(16574595); // Yellow
        $embed->setTitle("A new report has been sent!");
        $embed->addField("Reported player", $target->getName());
        $embed->addField("Reported by", $sender->getName());
        $embed->addField("Reason", $reason);
        $embed->setFooter("Developed by Didah#4145");

        $message = new Message();
        $message->addEmbed($embed);

        $webhook = new Webhook($config->get("webhook-url"));
        $webhook->send($message);
    }

}