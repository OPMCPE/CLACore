<?php

declare(strict_types=1);

namespace SkyRealm;

use SkyRealm\Events\PlayerChat;
use SkyRealm\Commands\CommandManager;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\{Config, TextFormat as C};

class Loader extends PluginBase{

    public $prefix = "SkyRealmCore";

    private static $instance;

    public $language;
    public $cfg;
    public $langcfg;
    public $languagename;
    public $loggerservername;
    public $loggerlanguage;

    public $staffchat = array();

    public static function getInstance() : Loader{
        return self::$instance;
    }

    public function onEnable(){
        $this-getLogger->info("SkyRealm's Core Plugin has been successfully installed");
        $this->RegisterConfig();
        $this->RegisterManager();
        $this->RegisterEvents();

        #instance
        self::$instance = $this;

        #logger
        $this->loggerservername = C::YELLOW . "\n"."MOTD: " . C::AQUA . $this->getServer()->getNetwork()->getName();
        $this->loggerlanguage = C::YELLOW ."\n"."Language: " . C::AQUA . $this->languagename;
        $this->getLogger()->info(C::GREEN . "Loaded" . C::AQUA . $this->prefix . $this->loggerservername . $this->loggerlanguage);
    }

    private function RegisterConfig() : void{
        @mkdir($this->getDataFolder());

        $this->saveResource("config.yml");
        $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        #Language
        $this->language = $this->cfg->get("language");
        $this->saveResource("lang/{$this->language}.yml");
        $this->langcfg = new Config($this->getDataFolder() . "lang/{$this->language}.yml", Config::YAML);
        $this->languagename = $this->langcfg->get("language.name");
    }

    public function RegisterManager() : CommandManager{
        $cmdmngr = new CommandManager($this);
        return $cmdmngr;
    }

    private function RegisterEvents() : void{
        $plmngr = $this->getServer()->getPluginManager();
        $plmngr->registerEvents(new PlayerChat($this), $this);
    }

    public function onDisable(){
        $this->getLogger()->info(C::RED . "Disabled" . C::AQUA . $this->prefix . $this->loggerservername . $this->loggerlanguage);
    }
}
