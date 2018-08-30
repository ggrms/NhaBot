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

	public function getPlayers(){
		return $this->players;
	}

	public static function getCards(){
		return $this->cards;
	}


}