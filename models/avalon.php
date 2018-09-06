<?php
require_once 'BaseModel.php';
require_once 'mission.php';

class Avalon extends BaseModel{
	private $id;
	private $players;
	private $cards;
	private $total;
	private $result;
	private $startedGame;
	private $currentMission;

	function __construct($attributes = NULL){
		if($attributes) {
			$this->id = empty($attributes['id']) ? null : $attributes['id'];
			$this->players = empty($attributes['players']) ? null : $attributes['players'];
			$this->cards = empty($attributes['cards']) ? null : $attributes['cards'];
			$this->total = empty($attributes['total']) ? null : $attributes['total'];
			$this->result = empty($attributes['result']) ? null : $attributes['result'];
			$this->startedGame = empty($attributes['startedGame']) ? null : $attributes['startedGame'];
			$this->currentMission = empty($attributes['currentMission']) ? null : $attributes['currentMission'];
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

	public function getTotal(){
		return $this->total;
	}

	public function getResult(){
		return $this->result;
	}

	public function getstartedGame(){
		return $this->startedGame;
	}

	public function getCurrentMission(){
		return $this->currentMission;
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
	}

	public static function atualizar($att){
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