<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once dirname(__FILE__).'/models/avalon.php';
require_once dirname(__FILE__).'/models/card.php';
require_once dirname(__FILE__).'/models/configs.php';
require_once dirname(__FILE__).'/models/game.php';

define('BOT_TOKEN', 'token');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

//$update_response = file_get_contents("php://input");
//$update = json_decode($update_response, true);

$update_response = file_get_contents(API_URL."getupdates");
$response = json_decode($update_response, true);
$length = count($response["result"]);

$update = $response["result"][$length-1];

if (isset($update["message"])) {
  processMessage($update["message"]);
}

//Join Button or Configs Button
if(!empty($update['callback_query'])){
  $data = explode(" ", $update['callback_query']['data']);
  $chat_id = $data[0];
  if($data[1] == "config"){
    $config = new Config();
    $config->configuration($update);
  }
  else if($data[1] == "game"){
    $game = new Game();
    $game->play($update);
  }
  else{
    $avalon = Avalon::readGame($chat_id)[0];
    $username = $update['callback_query']['from'];
    $cards = $avalon->getCards();
    $players = $avalon->getPlayers();
    $total = $avalon->getTotal();
    if(!$cards){
      $cards = array();
    }
    if(!$players){
      $players = array();
    }
    if(in_array($username['first_name'] . " " . $username['last_name'] . ":" . $username['id'],$players)){
      sendAction("sendMessage", array('chat_id' => $chat_id, 'text' => $username['first_name'] . ' você já está no jogo!'));
    }
    else{
      sendAction("sendMessage", array('chat_id' => $chat_id, 'text' => $username['first_name'] . ' juntou-se ao jogo!'));
        array_push($players,$username['first_name'] . " " . $username['last_name'] . ":" . $username['id']);
        $att['avalon']['id'] = $chat_id;
        $att['avalon']['players'] = serialize($players);
        $att['avalon']['cards'] = serialize($cards);
        $att['avalon']['total'] = $total+1;
        $avalon->atualizar($att);
    }
  }
}

function sendAction($method, $parameters) {
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




function processMessage($message) {
  // processa a mensagem recebida
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];

  if (isset($message['text'])) {

    $text = $message['text'];

    if (strpos($text, "/eu") === 0 && $message['from']['first_name'] == "Lucas") {

      sendAction("sendMessage", array('chat_id' => $chat_id, "text" => 'Todo mundo sabe que todo Lucas é otário'));
  	}
  	else if(strpos($text, "/eu") === 0 && $message['from']['first_name'] == "Natália"){
  		sendAction("sendMessage", array('chat_id' => $chat_id, "text" => 'Minha gêmea do bem que é má. Você é babaka'));
  	}
  	else if(strpos($text, "/eu") === 0 && $message['from']['first_name'] === "Gabriel"){
  		sendAction("sendMessage", array('chat_id' => $chat_id, "text" => 'Você é o mais barril do grupo '));
  	}
  	else if(strpos($text, "/eu") === 0 && $message['from']['first_name'] === "Luan"){
  		sendAction("sendMessage", array('chat_id' => $chat_id, "text" => 'Luanzito você é barril bo sair no soco Nhã'));
  	}
  	else if(strpos($text, "/nha") === 0){
  		sendAction("sendMessage", array('chat_id' => $chat_id, "text" => 'Nhã'));
  	}
  	else if(strpos($text, "/parabens") === 0){
  		sendAction("sendMessage", array('chat_id' => $chat_id, "text" => 'Parabéns amiguinhos. Vê se assim ficou bonitinho'));
  		sendAction("sendPhoto", array('chat_id' => $chat_id, 'photo' => "AgADAQAD06cxGzmRMERkLn36v--wdkYd9y8ABARGwO4kUlV_CgEEAAEC"));
  	}
  	else if(strpos($text, "/startavalon") === 0){
      if(Avalon::select($chat_id) != null){
          sendAction("sendMessage",array('chat_id' => $chat_id, "text" => 'Já existe um jogo em andamento. Por favor aguarde o fim do jogo ou que o criador do jogo termine o jogo!'));  
      }
      else{
        $keyboard = [
          'inline_keyboard' => [
              [
                  ['text' => 'Entrar no Jogo', 'callback_data' => $chat_id . ' ' . $message['from']['first_name']],
              ]
          ]
        ];
      $keyboard = json_encode($keyboard);
        sendAction("sendMessage",array('chat_id' => $chat_id, "text" => 'Um jogo de avalon foi iniciado. Clique no botão para juntar-se! O dono do jogo deve configurar e iniciar o jogo no chat privado com o bot.', 'reply_markup' => $keyboard));
        $keyboard = [
          'inline_keyboard' => [
              [
                  ['text' => 'Configurar Jogo', 'callback_data' => $message['from']['id'] . ' ' .'config' . ' ' . 'config' . ' ' . $chat_id]
              ]
          ]
        ];
        sendAction("sendMessage",array('chat_id' => $message['from']['id'], "text" => 'Para configurar aperte no botão de configurar jogo', 'reply_markup' => $keyboard));
      }
      try{
        Avalon::create($chat_id);
      } catch (PDOException $e){
        sendAction("sendMessage",array('chat_id' => $chat_id, "text" => 'Ocorreu um erro ao tentar criar o jogo. Tente novamente.'));
      }
      
  	}
  	else if(strpos($text, "/avaloncards") === 0){
  		$cards = Card::select();
  		$message = "";
  		foreach($cards as $card){
  			if($card->getName() == "Minion do Mordred"){
	  			$message = $message . " " . $card->getName();
  			} else{
	  			$message = $message . " " . $card->getName() . ",";
  			}
  		}
  		sendAction("sendMessage", array('chat_id' => $chat_id, "text" => 'Cartas disponíveis: ' . $message));
  	}
    else{

    }

}
}