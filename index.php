<?php

require_once dirname(__FILE__).'/models/avalon.php';
require_once dirname(__FILE__).'/models/card.php';

define('BOT_TOKEN', 'My_Bot_Key');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);

if (isset($update["message"])) {
  processMessage($update["message"]);
}

if($update['callback_query'] != null){
	$data = explode(" ", $update['callback_query']['data']);
	$chat_id = $data[0];
	$username = $update['callback_query']['from'];
	sendMessage("sendMessage", array('chat_id' => $chat_id, 'text' => $username['first_name'] . ' juntou-se ao jogo!'));

}

function sendMessage($method, $parameters) {
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
  var_dump($message);
  if (isset($message['text'])) {

    $text = $message['text'];//texto recebido na mensagem

    if (strpos($text, "/eu") === 0 && $message['from']['first_name'] == "Lucas") {
		//envia a mensagem ao usuário
      sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Todo mundo sabe que todo Lucas é otário'));
  	}
  	else if(strpos($text, "/eu") === 0 && $message['from']['first_name'] == "Natália"){
  		sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Minha gêmea do bem que é má. Você é babaka'));
  	}
  	else if(strpos($text, "/eu") === 0 && $message['from']['first_name'] === "Gabriel"){
  		sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Você é o mais barril do grupo '));
  	}
  	else if(strpos($text, "/eu") === 0 && $message['from']['first_name'] === "Luan"){
  		sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Luanzito você é barril bo sair no soco Nhã'));
  	}
  	else if(strpos($text, "/nha") === 0){
  		sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Nhã'));
  	}
  	else if(strpos($text, "/parabens") === 0){
  		sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Parabéns amiguinhos. Vê se assim ficou bonitinho'));
  		sendMessage("sendPhoto", array('chat_id' => $chat_id, 'photo' => "AgADAQAD06cxGzmRMERkLn36v--wdkYd9y8ABARGwO4kUlV_CgEEAAEC"));
  	}
  	else if(strpos($text, "/startavalon") === 0){
  		$avalon = new Avalon();
  		$keyboard = [
		    'inline_keyboard' => [
		        [
		            ['text' => 'Entrar no Jogo', 'callback_data' => $chat_id . ' ' . $message['from']['first_name']]
		        ]
		    ]
		];
		$keyboard = json_encode($keyboard);
  		sendMessage("sendMessage",array('chat_id' => $chat_id, "text" => 'Um jogo de avalon foi iniciado. Clique no botão para juntar-se!', 'reply_markup' => $keyboard));	
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
  		sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Cartas disponíveis: ' . $message));
  	}
}
}
