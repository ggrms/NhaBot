<?php
require_once 'BaseModel.php';

class Avalon extends BaseModel{
	private $id;
	private $players;
	private $cards;

	function __construct($attributes = NULL){
		if($attributes) {
			$this->id = empty($attributes['id']) ? null : $attributes['id'];
			$this->players = empty($attributes['players']) ? null : $attributes['players'];
			$this->cards = empty($attributes['cards']) ? null : $attributes['cards'];
		}
	}

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getPlayers(){
		return unserialize($this->players);
	}

	public function getCards(){
		return unserialize($this->cards);
	}

	public static function readGame($chat_id=null){
		return Avalon::select($chat_id);
	}

	public static function readCards($chat_id){
		return Card::select($chat_id);
	}

	public static function create($chat_id){
		$att['avalon']['id'] = $chat_id;
		if(!isset($_POST['avalon']['id'])){
			$avalon = new Avalon($att['avalon']);
			try {
				$avalon->insert();
			} catch (PDOException $e) {
				echo $e->getMessage();
				exit();
			}
		}
		//header("Location: configs.php");
	}

	public static function atualizar($chat_id, $players=null, $cards=null){
		$att['avalon']['id'] = $chat_id;
		$att['avalon']['players'] = $players;
		$att['avalon']['cards'] = $cards;
		if(!empty($att['avalon']['id'])){
			$avalon = new Avalon($att['avalon']);
			try {
				$avalon->update();
			} catch (PDOException $e) {
				echo $e->getMessage();
				exit();
			}
		}
	}

	public static function delete($id) {
			if(!empty($id)) {
				try {
					Avalon::delete(['id' => $id]);
				}
				catch (PDOException $e) {
					echo $e->getMessage();
					exit();
				}
			}
		}

}