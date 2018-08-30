<?php
require_once 'BaseModel.php';

class Card extends BaseModel{
	private $id;
	private $name;
	private $description;
	private $team;

	function __construct($attributes = NULL){
		if($attributes) {
			$this->id = empty($attributes['id']) ? null : $attributes['id'];
			$this->name = empty($attributes['name']) ? null : $attributes['name'];
			$this->description = empty($attributes['description']) ? null : $attributes['description'];
			$this->team = empty($attributes['team']) ? null : $attributes['team'];
		}
	}

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function getDescription(){
		return $this->description;
	}

	public function getTeam(){
		return $this->team;
	}

	public function readAll(){
		return Card::select();
	}

}