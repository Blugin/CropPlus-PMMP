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

use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\level\Level;
use pocketmine\event\block\BlockGrowEvent;

class PumpkinStem extends Crops{
	protected $id = self::PUMPKIN_STEM;

	public function getName(){
		return "Pumpkin Stem";
	}

 	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->getID() !== self::FARMLAND){
				$this->getLevel()->useBreakOn($this);
				return Level::BLOCK_UPDATE_NORMAL;
			}
		}elseif($type === Level::BLOCK_UPDATE_RANDOM){
			if($this->meta < 0x07){
				parent::onUpdate(Level::BLOCK_UPDATE_RANDOM);
			}else{
				for($side = 2; $side <= 5; $side++){
					if($this->getSide($side)->getID() === self::PUMPKIN){
						return Level::BLOCK_UPDATE_RANDOM;
					}
				}
				$side = $this->getSide(mt_rand(2, 5));
				if($side->getID() === self::AIR && in_array($side->getSide(0)->getID(), [self::FARMLAND, self::GRASS, self::DIRT])){
					Server::getInstance()->getPluginManager()->callEvent($ev = new BlockGrowEvent($side, self::get(self::PUMPKIN)));
					if(!$ev->isCancelled()){
						$this->getLevel()->setBlock($side, $ev->getNewState(), true);
					}
				}
			}
			return Level::BLOCK_UPDATE_RANDOM;
		}
		return false;
	}

	public function getDrops(Item $item){
		return [
			[Item::PUMPKIN_SEEDS, 0, 1],
		];
	}
}