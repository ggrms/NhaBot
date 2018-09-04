<?php
require_once dirname(__FILE__).'/card.php';
require_once dirname(__FILE__).'/avalon.php';

class Config {

	public static function sendAction($method, $parameters) {
	  $options = array(
	  	'http' => array(
	    	'method'  => 'POST',
	    	'content' => json_encode($parameters),
	    	'header'=>  "Content-Type: application/json\r\n" .
	                "Accept: application/json\r\n"
	    	)
		);
		$context  = stream_context_create( $options );
		file_get_contents(API_URL.$method, false, $context );
	}

	public function showConfigs($players,$cards, $game_id){
		$ordem = 0;
		$message = "Esses é a ordem de Jogo: ";
		foreach($player in $players){
			$player = explode(":", $player);
			$message += $player[0] . ", ";
		}
		$message = " e essas são as cartas escolhidas: "
		for($x = 0;$x<sizeof($cards);$x++){
			if($x == sizeof($cards)-1){
				$message += $cards[$x];
			}
			else{
				$message += $cards[$x] . ", ";
			}
		}
		self::sendAction("sendMessage", array('chat_id' => $game_id, 'text' => $message));
		self::sendAction("sendMessage", array('chat_id' => $game_id, 'text' => "Estarei enviando uma mensagem ao jogador da vez para que ele possa decidir qual time irá para a missão. Logo em seguida mandarei mensagem a todos os jogadores para que possam decidir se aceitam ou não."));
		//Criar missão de acordo com numero de players e mencionar quantas pessoas em cada missão de acordo com o numero de jogadores
		$player = $players[$ordem++];
		$player = explode(":",$player);
		$player_id = $player[1];
		$player_name = $player[0];

		$options = array('inline_keyboard' => array());
		array_push($options['inline_keyboard'], array());
		$x = 0;
		foreach($p as $players){
			$p = explode(":", $p);
			$p = $p[0]
  			if(sizeof($options['inline_keyboard'][$x]) > 1){
  				array_push($options['inline_keyboard'], array());
  				$x++;
  			}
  			//Adicionar Ao banco a classe MISSÃO e adicionar os jogadores escolhidos a missão.
  			array_push($options['inline_keyboard'][$x],array('text' => $p, 'callback_data' => $chat_id . ' ' . 'config' . ' ' . 'add' . ' ' . $game_id . ' ' .  $card->getName()));
  		}
		self::sendAction("sendMessage", array('chat_id' => $player_id, 'text' => "Olá " . $player_name . ". Você é o jogador da rodada. Por favor escolha quais jogadores irão participar da missão."));


	}

	public function configuration($button){
		if(!empty($button['callback_query'])){
			$data = explode(" ", $button['callback_query']['data']);
		  	$chat_id = $data[0];
		  	$game_id = $data[3];
		  	if($data[2] == "add"){
		  		$avalon = Avalon::readGame($game_id)[0];
		  		$cards = $avalon->getCards();
		  		if(!$cards){
			      $cards = array();
			    }
			    if(in_array($data[4],$cards)){
			      self::sendAction("sendMessage", array('chat_id' => $chat_id, 'text' => $data[4] . ' já está no jogo!'));
			    }
			    else {
			    	self::sendAction("sendMessage", array('chat_id' => $chat_id, 'text' => 'a carta ' . $data[4] . ' foi inserida ao jogo!'));
			        array_push($cards,$data[4]);
			        $avalon->atualizar($game_id, serialize($avalon->getPlayers()), serialize($cards));
			    }
		  	}
		}
		
	}
	
}



