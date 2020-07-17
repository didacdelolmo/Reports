<?php


namespace diduhless\reports;


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
        if(!isset($args[0]) or !isset($args[1])) {
            $sender->sendMessage(TF::RED ."Usage: " . $this->getUsage());
            return;
        }

        $server = $this->plugin->getServer();
        $player = $server->getPlayer(array_shift($args));

        if($player == null) {
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

        foreach($server->getOnlinePlayers() as $onlinePlayer) {
            if($player->hasPermission("reports.logs")) {
                $this->announceReport($onlinePlayer, $sender, $player, implode(" ", $args));
            }
        }
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

}