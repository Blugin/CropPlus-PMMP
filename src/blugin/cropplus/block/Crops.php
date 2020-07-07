<?php

/*
 *
 *  ____  _             _         _____
 * | __ )| |_   _  __ _(_)_ __   |_   _|__  __ _ _ __ ___
 * |  _ \| | | | |/ _` | | '_ \    | |/ _ \/ _` | '_ ` _ \
 * | |_) | | |_| | (_| | | | | |   | |  __/ (_| | | | | | |
 * |____/|_|\__,_|\__, |_|_| |_|   |_|\___|\__,_|_| |_| |_|
 *                |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  Blugin team
 * @link    https://github.com/Blugin
 * @license https://www.gnu.org/licenses/lgpl-3.0 LGPL-3.0 License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace blugin\cropplus\block;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\block\Crops as PMCrops;
use pocketmine\Server;
use pocketmine\level\Level;
use pocketmine\event\block\BlockGrowEvent;

class Crops extends PMCrops{
	protected $id = self::AIR;

	public function __construct($meta = 0){
		$this->id = (int) $this->id;
		$this->meta = (int) $meta;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if($block->getSide(0)->getID() === self::FARMLAND){
			$this->getLevel()->setBlock($block, $this, true, true);
			return true;
		}
		return false;
	}

	public function onActivate(Item $item, Player $player = null){
		if($this->meta < 0x07 && $item->getID() === Item::DYE && $item->getDamage() === 0x0F){ //Bonemeal
			$block = clone $this;
			$block->meta += mt_rand(2, 5);
			if($block->meta > 0x07){
				$block->meta = 0x07;
			}
			Server::getInstance()->getPluginManager()->callEvent($ev = new BlockGrowEvent($this, $block));
			if(!$ev->isCancelled()){
				$this->getLevel()->setBlock($this, $ev->getNewState(), true, true);
				if($player->isSurvival()){
					$item->count--;
				}
				return true;
			}
		}
		return false;
	}

 	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->getID() !== self::FARMLAND){
				$this->getLevel()->useBreakOn($this);
				return Level::BLOCK_UPDATE_NORMAL;
			}
		}elseif($type === Level::BLOCK_UPDATE_RANDOM){
			if($this->meta < 0x07 && ($this->getSide(0)->getDamage() == 1 ? mt_rand(1, 2) : mt_rand(1, 3)) === 1){ // If wet farm : 1/2, else : 1/3
				$block = clone $this;
				$block->meta += $this->getSide(0)->getDamage() == 1 ? 2 : 1;
				Server::getInstance()->getPluginManager()->callEvent($ev = new BlockGrowEvent($this, $block));
				if(!$ev->isCancelled()){
					$this->getLevel()->setBlock($this, $ev->getNewState(), true, true);
				}
			}
			return Level::BLOCK_UPDATE_RANDOM;
		}
		return false;
	}
}