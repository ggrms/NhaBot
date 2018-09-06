<?php
require_once dirname(__FILE__).'/card.php';
require_once dirname(__FILE__).'/avalon.php';
require_once dirname(__FILE__).'/mission.php';

class Game {

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
		foreach($player as $players){
			$player = explode(":", $player);
			$message += $player[0] . ", ";
		}
		$message = " e essas são as cartas escolhidas: ";
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
		self::start();
	}

	public static function start($game_id){
		$avalon = Avalon::readGame($game_id)[0];
		//Criar missão de acordo com numero de players e mencionar quantas pessoas em cada missão de acordo com o numero de jogadores
		$att['mission']['game_id'] = $game_id;
		$mission = Mission::create($att);
		$missioncount = $avalon->getCurrentMission();
		$player = $players[$ordem++];
		$player = explode(":",$player);
		$player_id = $player[1];
		$player_name = $player[0];
  		if($avalon->getTotal() == 5){
  			if($missioncount == 0){
				$options = array('inline_keyboard' => array());
				array_push($options['inline_keyboard'], array());
				$x = 0;
				foreach($p as $players){
					$p = explode(":", $p);
		  			if(sizeof($options['inline_keyboard'][$x]) > 1){
		  				array_push($options['inline_keyboard'], array());
		  				$x++;
		  			}
		  			//Adicionar Ao banco a classe MISSÃO e adicionar os jogadores escolhidos a missão.
		  			array_push($options['inline_keyboard'][$x],array('text' => $p[0], 'callback_data' => $chat_id . ' ' . 'game' . ' ' . 'add' . ' ' . $game_id . ' ' .  $p[0] . ':' . $p[1]. ' ' . $mission->getId() . ' ' . $total-3));
		  		}
  				
  			}
  		}


		self::sendAction("sendMessage", array('chat_id' => $player_id, 'text' => "Olá " . $player_name . ". Você é o jogador da rodada. Por favor escolha quais jogadores irão participar da missão.", 'reply_markup' => $options));
	}

	public function play($button){
		if(!empty($button['callback_query'])){
			$data = explode(" ", $button['callback_query']['data']);
			if($data[2] == 'add'){
				$mission = Mission::readMission($data[3]);
				$players = $mission->getPlayers();
				if(!$players){
			      $players = array();
			    }
			    if(sizeof($players) < $data[6]){
			    	if(in_array($data[4],$players)){
						$player = explode(":", $data[4]);
						$key = array_search($data[4],$players);
						unset($players[$key]);
				      	sendAction("sendMessage", array('chat_id' => $chat_id, 'text' => $player[0] . ' foi removido da missão!'));
				        $att['mission']['players'] = $players;
				        $mission->atualizar($att);
				    }
				    else{
				    	$player = explode(":", $data[4]);
				        array_push($players,$data[4]);
				      	sendAction("sendMessage", array('chat_id' => $chat_id, 'text' => $player[0] . ' foi colocado na missão!'));
				        $att['mission']['players'] = $players;
				        $mission->atualizar($att);
				    }    	
			    }
			    else{
			    	//deletar msg com opções e iniciar missão.
			    	self::startMission($button);
			    }
			}
		}
	}
	
}



