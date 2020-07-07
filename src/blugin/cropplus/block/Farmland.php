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

use pocketmine\block\Block;
use pocketmine\block\Farmland as PMFarmland;
use pocketmine\level\Level;

class Farmland extends PMFarmland{
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_RANDOM){
			$level = $this->getLevel();
			$this->meta = 0;
			for($x = $this->x - 4; $x <= $this->x + 4; $x++){
				for($z = $this->z - 4; $z <= $this->z + 4; $z++){
					if(in_array($level->getBlockIdAt($x, $this->y, $z), [Block::WATER, Block::STILL_WATER])){
						$this->meta = 1;
						break 2;
					}
				}
			}
			if($this->meta == 0){
				$this->getLevel()->setBlock($this, Block::get(Block::DIRT), true, true);
			}else{
				$this->getLevel()->setBlock($this, $this, true, true);
			}
			return Level::BLOCK_UPDATE_RANDOM;
		}
		return false;
	}
}