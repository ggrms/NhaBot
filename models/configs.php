<?php
require_once dirname(__FILE__).'/card.php';
require_once dirname(__FILE__).'/avalon.php';
require_once dirname(__FILE__).'/game.php';

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

	public function configuration($button){
		if(!empty($button['callback_query'])){
			$data = explode(" ", $button['callback_query']['data']);
		  	$chat_id = $data[0];
		  	$game_id = $data[3];
		  	if($data[2] == "config"){
			  	$keyboard = [
		          'inline_keyboard' => [
		              [
		                  ['text' => 'Escolher cartas', 'callback_data' => $chat_id . ' ' .'config' . ' ' . 'cards' . ' ' . $game_id],
		                  ['text' => 'Iniciar Jogo', 'callback_data' => $chat_id . ' ' .'config' . ' ' . 'start' . ' ' . $game_id],
		                  ['text' => 'Finalizar Jogo', 'callback_data' => $chat_id . ' ' .'config' . ' ' . 'end' . ' ' . $game_id]
		              ]
		          ]
		        ];
				self::sendAction("sendMessage", array('chat_id' => $chat_id, "text" => "Escolha um dos botões", 'reply_markup' => $keyboard));
		  	}
		  	else if($data[2] == "cards"){
		  		$cards = Card::select();
		  		$options = array('inline_keyboard' => array());
		  		array_push($options['inline_keyboard'], array());
		  		$x = 0;
		  		foreach($cards as $card){
		  			if(sizeof($options['inline_keyboard'][$x]) > 1){
		  				array_push($options['inline_keyboard'], array());
		  				$x++;
		  			}
		  			array_push($options['inline_keyboard'][$x],array('text' => $card->getName(), 'callback_data' => $chat_id . ' ' . 'config' . ' ' . 'add' . ' ' . $game_id . ' ' .  $card->getName()));
		  		}
		  		self::sendAction("sendMessage", array('chat_id' => $chat_id, "text" => "Escolha uma carta para adicionar ao jogo", 'reply_markup' => $options));
		  	}
		  	else if($data[2] == "start"){
		  		$avalon = Avalon::readGame($game_id)[0];
		  		$cards = $avalon->getCards();
		  		$players = $avalon->getPlayers();
		  		if(sizeof($players) >= 5 && sizeof($players) == sizeof($cards)){
		  			self::sendAction("sendMessage", array('chat_id' => $game_id, "text" => "O jogo irá começar em instantes!"));
		  			//call game object
		  			$game = new Game();
		  			self::sendAction("deleteMessage", array('chat_id' => $chat_id, "message_id" => $button['callback_query']['inline_message_id']))
		  			$game->showConfigs($players,$cards, $game_id);
		  		}
		  		else{
		  			self::sendAction("sendMessage", array('chat_id' => $chat_id, "text" => "O número de jogadores precisa ser maior ou igual a 5, e o número de cartas precisa ser igual ao número de jogadores!!"));
		  		}
		  	}
		  	else if($data[2] == "end"){

		  	}
		  	else if($data[2] == "add"){
		  		$avalon = Avalon::readGame($game_id)[0];
		  		$cards = $avalon->getCards();
		  		if(!$cards){
			      $cards = array();
			    }
			    if(in_array($data[4],$cards)){
			      self::sendAction("sendMessage", array('chat_id' => $chat_id, 'text' => $data[4] . ' já está no jogo!'));
			    }
			    else {
			        array_push($cards,$data[4]);
			        $att['avalon']['id'] = $avalon->getId();
			        $att['avalon']['players'] = serialize($avalon->getPlayers());
			        $att['avalon']['cards'] = serialize($cards);
			        $avalon->atualizar($att);
			    	self::sendAction("sendMessage", array('chat_id' => $chat_id, 'text' => 'a carta ' . $data[4] . ' foi inserida ao jogo!'));
			    }
		  	}
		}
		
	}
	
}



