<?php

declare(strict_types=1);


namespace diduhless\reports;


use diduhless\reports\session\SessionListener;
use pocketmine\plugin\PluginBase;

class Reports extends PluginBase {

    /** @var self */
    static private $instance;

    public function onLoad() {
        self::$instance = $this;
        $this->saveDefaultConfig();
    }

    public function onEnable() {
        $server = $this->getServer();
        $server->getPluginManager()->registerEvents(new SessionListener(), $this);
        $server->getCommandMap()->register("reports", new ReportCommand());

    }

    static public function getInstance(): self {
        return self::$instance;
    }

}