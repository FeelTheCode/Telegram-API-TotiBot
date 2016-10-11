<?php
/*
Copy Right: http://feelthecode.com
*/
ob_start();
define('API_KEY','TOKEN');
function FeelTheCode($method,$datas){
	$url = "https://api.telegram.org/bot".API_KEY."/".$method;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));
	$res = curl_exec($ch);
	if(curl_error($ch)){
		var_dump(curl_error($ch));
	}else{
		return json_decode($res);
	}
}
$update = json_decode(file_get_contents('php://input'));

$upp = json_decode(file_get_contents('php://input'),true);
if(isset($update->message)){
	$members_file = file_get_contents("members.txt");
	$members = explode("\n", $members_file);
	if(!in_array($chat_id, $members)){
		file_put_contents("members.txt", $members_file."\n".$chat_id);
	}
	if(isset($update->message->chat->id))
		$chat_id = $update->message->chat->id;
	else
		$chat_id = null;
	$userTEXT = isset($update->message->text)?$update->message->text:'';
	$matches = explode(" ", $userTEXT);
	$message_id = $update->message->message_id;
	if(isset($update->message->reply_to_message->from->id))
		$rpto = $update->message->reply_to_message->from->id;
	else
		$rpto = null;
	if(isset($update->message->document->file_id))
		$file_id = $update->message->document->file_id;
	else
		$file_id = null;

	if(isset($update->message->audio->file_id))
		$audio_id = $update->message->audio->file_id;
	else
		$audio_id = null;

	if(isset($update->message->sticker->file_id))
		$sticker_id = $update->message->sticker->file_id;
	else
		$sticker_id = null;
	if(isset($update->message->voice->file_id))
		$voice_id = $update->message->voice->file_id;
	else
		$voice_id = null;
	if(isset($update->message->video->file_id))
		$video_id = $update->message->video->file_id;
	else
		$video_id = null;
	if(isset($update->message->contact->phone_number))
		$phone_number = $update->message->contact->phone_number;
	else
		$phone_number = null;
	if(isset($update->message->contact->first_name))
		$first_name = $update->message->contact->first_name;
	else
		$first_name = null;
	if(isset($update->message->contact->last_name))
		$last_name = $update->message->contact->last_name;
	else
		$last_name = null;
	if(isset($update->message->photo))
		$image_id = isset(end($update->message->photo)->file_id)?end($update->message->photo)->file_id:false;
	else
		$image_id = null;

if ($userTEXT=="/STATS"){
$file="members.txt";
$linecount = 0;
$handle = fopen($file, "r");
while(!feof($handle)){
$line = fgets($handle);
$linecount++;
die();
}

fclose($handle);
		FeelTheCode('sendmessage',array(
			'chat_id'=>$update->message->chat->id,
			'text'=>"total members: ".$linecount
			));
	}

	if(isset($userTEXT)){
		FeelTheCode('sendmessage',array(
			'chat_id'=>$update->message->chat->id,
			'text'=>$userTEXT
			));
	}
	if(isset($file_id)){
		FeelTheCode('senddocument',array(
			'chat_id'=>$update->message->chat->id,
			'document'=>$file_id,
			'captin'=>$caption
			));
	}

	if(isset($video_id)){
		FeelTheCode('sendvideo',array(
			'chat_id'=>$update->message->chat->id,
			'video'=>$video_id,
			'captin'=>$caption
			));
	}
	if(isset($image_id)){
		FeelTheCode('sendphoto',array(
			'chat_id'=>$update->message->chat->id,
			'photo'=>$image_id,
			'captin'=>$caption
			));
	}
	if(isset($audio_id)){
		FeelTheCode('sendaudio',array(
			'chat_id'=>$update->message->chat->id,
			'audio'=>$audio_id,
			'captin'=>$caption
			));
	}
	if(isset($voice_id)){
		FeelTheCode('sendvoice',array(
			'chat_id'=>$update->message->chat->id,
			'voice'=>$voice_id,
			'captin'=>$caption
			));
	}

	if(isset($sticker_id)){
		FeelTheCode('sendsticker',array(
			'chat_id'=>$update->message->chat->id,
			'sticker'=>$sticker_id
			));
	}

	if(isset($phone_number)){
		FeelTheCode('sendcontact',array(
			'chat_id'=>$update->message->chat->id,
			'first_name'=>$first_name,
			'last_name'=>$last_name,
			'phone_number'=>$phone_number,
			'caption'=>$caption
			));
	}

}
file_put_contents('log',ob_get_clean());