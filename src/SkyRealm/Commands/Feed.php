<?php 

declare(strict_types=1);

namespace SkyRealm\Commands;

use pocketmine\command\CommandSender;

use SkyRealm\Core;
use pocketmine\Player;

class Feed extends BaseCommand {

    private $plugin;

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
        parent::__construct($plugin, "feed", "§aUse SkyRealm's /feed plugin", "/feed <player>", ["feed"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender->hasPermission("skycore.command.feef")) {
            $nopermission = $this->plugin->langcfg->get("no.permission");
            $sender->sendMessage("$nopermission");
            return true;
        }

        if ((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1) {
            $sender->sendMessage("Usage: /feed <player>");
            return true;
        }

        $player = $sender;
        if (isset($args[0]) && !($player = $this->getPlugin()->getServer()->getPlayer($args[0]))) {
            $playernotfound = $this->plugin->langcfg->get("player.not.found");
            $sender->sendMessage("$playernotfound");
            return true;
        }

        if ($player->getName() !== $sender->getName() && !$sender->hasPermission("skyrealmpe.command.feed.other")) {
            $nopermission = $this->plugin->langcfg->get("no.permission");
            $sender->sendMessage("$nopermission");
            return true;
        }

        #player fed
        $playerfed = $this->plugin->langcfg->get("player.fed");

        #sender fed
        $senderfed = $this->plugin->langcfg->get("sender.fed");
        $senderfed = str_replace("{player}", $player->getName(), $senderfed);

        $player->setFood(20);
        $player->sendMessage("$playerfed");

        if ($player !== $sender) {
            $sender->sendMessage("$senderfed");
        }

        return true;
    }
}
