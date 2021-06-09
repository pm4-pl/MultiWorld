<?php

/**
 * MultiWorld - PocketMine plugin that manages worlds.
 * Copyright (C) 2018 - 2021  CzechPMDevs
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace czechpmdevs\multiworld\command\subcommand;

use czechpmdevs\multiworld\MultiWorld;
use czechpmdevs\multiworld\util\LanguageManager;
use czechpmdevs\multiworld\utils\WorldUtils;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class DeleteSubcommand implements SubCommand {

    public function executeSub(CommandSender $sender, array $args, string $name): void {
        if (!isset($args[0])) {
            $sender->sendMessage(MultiWorld::getPrefix() . LanguageManager::getMsg($sender, "delete-usage"));
            return;
        }

        if (!$this->getServer()->isLevelGenerated($args[0]) || !file_exists($this->getServer()->getDataPath() . "worlds/{$args[0]}")) {
            $sender->sendMessage(MultiWorld::getPrefix() . str_replace("%1", $args[0], LanguageManager::getMsg($sender, "delete-levelnotexists")));
            return;
        }

        if (!$this->getServer()->isLevelLoaded($args[0])) $this->getServer()->loadLevel($args[0]);

        if ($this->getServer()->getDefaultLevel()->getFolderName() == $this->getServer()->getLevelByName($args[0])->getFolderName()) {
            $sender->sendMessage("§cCould not remove default level!");
            return;
        }

        $files = WorldUtils::removeLevel($args[0]);
        $sender->sendMessage(MultiWorld::getPrefix() . LanguageManager::getMsg($sender, "delete-done", [$files]));
    }

    private function getServer(): Server {
        return Server::getInstance();
    }
}
