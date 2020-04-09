<?php


namespace Reports\command;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;
use Reports\Reports;

class ReportCommand extends Command {

    /** @var Reports */
    private $plugin;

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
            $sender->sendMessage("Usage: " . $this->getUsage());
            return;
        }
        $server = $this->plugin->getServer();
        $player = $server->getPlayer(array_shift($args));

        if($player == null) {
            $sender->sendMessage("This player is not online!");
            return;
        }
        $sender->sendMessage("Your report has been sent.");

        foreach($server->getOnlinePlayers() as $onlinePlayer) {
            if(!$player->hasPermission("reports.logs")) {
                continue;
            }

            $onlinePlayer->sendMessage(TF::DARK_GRAY . "***************");
            $onlinePlayer->sendMessage(TF::DARK_PURPLE . "NEW REPORT");
            $onlinePlayer->sendMessage(TF::DARK_AQUA . "Reported player: " . TF::GRAY . $player->getName());
            $onlinePlayer->sendMessage(TF::DARK_AQUA . "Reported by: " . TF::GRAY . $sender->getName());
            $onlinePlayer->sendMessage(TF::DARK_AQUA . "Reason: " . TF::GRAY . implode(" ", $args));
            $onlinePlayer->sendMessage(TF::DARK_GRAY . "***************");
        }
    }

}