<?php

namespace xtakumatutix\deathmoney;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Server;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\utils\Config;

use metowa1227\moneysystem\api\core\API;

class Main extends PluginBase implements Listener{

    public function onEnable(){
        $this->getlogger()->info("読み込み完了");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->Config = new Config($this->getDataFolder() . "Config.yml", Config::YAML, array(
            'メッセージ' => '§a死亡したので、{Money}円を失いました',
            'お金が足りない場合' => '§a死亡しましたが、お金が足りないので、ペナルティーとして0円にセットされました。',
            '料金' => '100'
        ));
    }

    public function onDeacth(PlayerDeathEvent $event)
    {
        $player = $event->getPlayer();
        $mymoney = API::getInstance()->get($player);
        $money = $this->Config->get("料金");
        $nomoney = $this->Config->get("お金が足りない場合");
        $message = $this->Config->get("メッセージ");
        $message = str_replace("{Money}", "{$money}", $message);
        if($mymoney < $money){
            $player->sendMessage("{$nomoney}");
            API::getInstance()->set($player,0);
        }else{
            $player->sendMessage("{$message}");
            API::getInstance()->reduce($player,$money);
        }
    }
}