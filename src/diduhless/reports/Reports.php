<?php


namespace diduhless\reports;


use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;

class Reports extends PluginBase {

    /** @var Reports */
    static private $instance;

    public function onLoad() {
        self::$instance = $this;

        $dataFolder = $this->getDataFolder();
        if(!is_dir($dataFolder)) {
            mkdir($dataFolder);
        }
        $this->saveDefaultConfig();
    }

    public function onEnable() {
        $this->getServer()->getCommandMap()->register("reports", new ReportCommand($this));
        $this->getLogger()->info(
            TF::DARK_GRAY . "[" . TF::DARK_AQUA . "Reports by @Diduhless" . TF::DARK_GRAY . "] " .
            TF::GOLD . "Reports has been enabled. Updates are released on https://github.com/Diduhless/diduhless/releases"
        );
    }

    public static function getInstance(): Reports {
        return self::$instance;
    }

}