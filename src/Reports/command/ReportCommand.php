<?php


namespace Reports\command;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;
use Reports\Reports;

class ReportCommand extends Command {

    private const REPORT_COOLDOWN = 20;

    /** @var Reports */
    private $plugin;

    /** @var int[] */
    private $reportsCooldown = [];

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
            $sender->sendMessage("This player is not online!");
            return;
        }
        $name = $player->getName();
        $cooldown = $this->reportsCooldown[$name];

        if(isset($cooldown) and time() - $cooldown < self::REPORT_COOLDOWN) {
            $sender->sendMessage(TF::RED . "You can't send multiple reports at once!");
            return;
        }

        $sender->sendMessage("Your report has been sent.");
        $this->reportsCooldown[$name] = time();

        foreach($server->getOnlinePlayers() as $onlinePlayer) {
            if(!$player->hasPermission("reports.logs")) {
                continue;
            }

            $this->announceReport($onlinePlayer, $sender, $player, implode(" ", $args));
        }
    }

    /**
     * @param Player $admin
     * @param CommandSender $sender
     * @param Player $player
     * @param string $reason
     */
    private function announceReport(Player $admin, CommandSender $sender, Player $player, string $reason): void {
        $admin->sendMessage(TF::DARK_GRAY . "***************");
        $admin->sendMessage(TF::DARK_PURPLE . "NEW REPORT");
        $admin->sendMessage(TF::DARK_AQUA . "Reported player: " . TF::GRAY . $player->getName());
        $admin->sendMessage(TF::DARK_AQUA . "Reported by: " . TF::GRAY . $sender->getName());
        $admin->sendMessage(TF::DARK_AQUA . "Reason: " . TF::GRAY . $reason);
        $admin->sendMessage(TF::DARK_GRAY . "***************");
    }

}