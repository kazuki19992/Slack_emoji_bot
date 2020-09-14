<?php
$body = file_get_contents('php://input');
$json = json_decode($body, true);

// slackのWebhookのURLを読み込む
$slack_url = file_get_contents('./url.txt');
define('WEBHOOK_URL', $slack_url);

header('Content-type: text/plain; charset=utf-8');

if($json['type']==="url_verification"){
    // URL認証
    echo $json['challenge'];
    exit;
}elseif($json['type']==="event_callback"){
    // イベントコールバック
    if($json['event']['type']==="emoji_changed" && $json['event']['subtype']==="add"){
        // 絵文字追加orエイリアス追加
        if(strpos($json['event']['value'], 'alias:') === false){
            // 絵文字追加
            $emoji = $json['event']['name'];
            $msg = "新しい絵文字`:{$emoji}:`が追加されたよ！\n:{$emoji}:\n";
            $msg_json = json_encode(['text' => $msg]);

            // 送信コマンド実行
            exec("curl -X POST -H 'Content-type: application/json' --data '". $msg_json. " '". WEBHOOK_URL ."'");
        }else{
            // エイリアス追加

        }
    }
}
