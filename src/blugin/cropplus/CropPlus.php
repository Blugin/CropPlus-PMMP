<?php

declare(strict_types=1);

namespace blugin\cropplus;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\level\LevelLoadEvent;
use pocketmine\level\Level;
use pocketmine\item\Item;
use pocketmine\block\Block;
use blugin\cropplus\item\Dye;
use blugin\cropplus\item\NetherWartSeeds;
use pocketmine\block\Grass;
use pocketmine\block\Mycelium;
use blugin\cropplus\block\Farmland;
use pocketmine\block\Sapling;
use pocketmine\block\Leaves;
use pocketmine\block\Leaves2;
use blugin\cropplus\block\Wheat;
use blugin\cropplus\block\Beetroot;
use blugin\cropplus\block\Carrot;
use blugin\cropplus\block\Potato;
use pocketmine\block\Cactus;
use pocketmine\block\Sugarcane;
use blugin\cropplus\block\MelonStem;
use blugin\cropplus\block\PumpkinStem;
use blugin\cropplus\block\CocoaBeans;
use blugin\cropplus\block\NetherWart;

class CropPlus extends PluginBase implements Listener{ 
	const NETHER_WART_BLOCK = 115;
	const NETHER_WART_SEEDS = 372;
	const COCOA_BEANS_BLOCK = 127;

	public $randomTickBlocksProperty, $randomTickBlocks = [
// Dirts
		Block::GRASS => Grass::class,
		Block::MYCELIUM => Mycelium::class,
		Block::FARMLAND => Farmland::class,
// Crops
		Block::SAPLING => Sapling::class,
		Block::LEAVES => Leaves::class,
		Block::LEAVES2 => Leaves2::class,
		Block::WHEAT_BLOCK => Wheat::class,
		Block::BEETROOT_BLOCK => Beetroot::class,
		Block::CARROT_BLOCK => Carrot::class,
		Block::POTATO_BLOCK => Potato::class,
		Block::CACTUS => Cactus::class,
		Block::SUGARCANE_BLOCK => Sugarcane::class,
		Block::MELON_STEM => MelonStem::class,
		Block::PUMPKIN_STEM => PumpkinStem::class,
	 	self::COCOA_BEANS_BLOCK => CocoaBeans::class,
		self::NETHER_WART_BLOCK => NetherWart::class
	];

 	public function onLoad(){
 		$reflectionLevel = new \ReflectionClass(Level::class);
		$this->randomTickBlocksProperty = $reflectionLevel->getProperty("randomTickBlocks");
		$this->randomTickBlocksProperty->setAccessible(true);
		$this->registerBlock(Block::FARMLAND, Farmland::class);
		$this->registerBlock(Block::WHEAT_BLOCK, Wheat::class);
		$this->registerBlock(Block::BEETROOT_BLOCK, Beetroot::class);
		$this->registerBlock(Block::CARROT_BLOCK, Carrot::class);
		$this->registerBlock(Block::POTATO_BLOCK, Potato::class);
		$this->registerBlock(Block::MELON_STEM, MelonStem::class);
		$this->registerBlock(Block::PUMPKIN_STEM, PumpkinStem::class);
		$this->registerBlock(self::COCOA_BEANS_BLOCK, CocoaBeans::class);
		$this->registerBlock(self::NETHER_WART_BLOCK, NetherWart::class);
		$this->registerItem(Item::DYE, Dye::class);
		$this->registerItem(self::NETHER_WART_SEEDS, NetherWartSeeds::class);
 	}

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

 	public function onLevelLoad(LevelLoadEvent $event){
 		$this->randomTickBlocksProperty->setValue($event->getLevel(), $this->randomTickBlocks);
	}

	public function registerBlock($id, $class){
		Block::$list[$id] = $class;
		if($id < 255){
			Item::$list[$id] = $class;
			if(!Item::isCreativeItem($item = Item::get($id))){
				Item::addCreativeItem($item);
			}
		}
		for($data = 0; $data < 16; ++$data){
			Block::$fullList[($id << 4) | $data] = new $class($data);
		}		
	}

	public function registerItem($id, $class){
		Item::$list[$id] = $class;
		if(Item::isCreativeItem($item = new $class())){
			Item::addCreativeItem($item);
		}
	}
}