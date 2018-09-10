<?php
require_once 'BaseModel.php';
require_once 'mission.php';

class Mission extends BaseModel{
	private $id;
	private $game_id;
	private $players;
	private $cards;
	private $result;
	private $success;

	function __construct($attributes = NULL){
		if($attributes) {
			$this->id = empty($attributes['id']) ? null : $attributes['id'];
			$this->game_id = empty($attributes['game_id']) ? null : $attributes['game_id'];
			$this->players = empty($attributes['players']) ? null : $attributes['players'];
			$this->cards = empty($attributes['cards']) ? null : $attributes['cards'];
			$this->result = empty($attributes['result']) ? null : $attributes['result'];
			$this->success = empty($attributes['success']) ? null : $attributes['success'];
		}
	}

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getGame_id(){
		return $this->game_id;
	}

	public function getPlayers(){
		return unserialize($this->players);
	}

	public function getCards(){
		return unserialize($this->cards);
	}

	public function getResult(){
		return $this->result;
	}

	public function getSuccess(){
		return $this->success;
	}

	public static function readMission($game_id){
		return Mission::select('game_id',$game_id);
	}

	public static function create($att){
		if(!isset($_POST['mission']['id'])){
			$mission = new Mission($att['mission']);
			try {
				$mission->insert();
			} catch (PDOException $e) {
				echo $e->getMessage();
				exit();
			}
		}
	}

	public static function atualizar($att){
		if(!empty($att['mission']['id'])){
			$mission = new Mission($att['mission']);
			try {
				$mission->update();
			} catch (PDOException $e) {
				echo $e->getMessage();
				exit();
			}
		}
	}

	public static function delete($id) {
			if(!empty($id)) {
				try {
					mission::delete(['id' => $id]);
				}
				catch (PDOException $e) {
					echo $e->getMessage();
					exit();
				}
			}
		}

}