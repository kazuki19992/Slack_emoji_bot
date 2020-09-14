<?php
$body = file_get_contents('php://input');
$json = json_decode($body, true);

// slackのWebhookのURLを読み込む
$webhook_url = file_get_contents('./url.txt');

header('Content-type: text/plain; charset=utf-8');

if($json['type']==="url_verification"){
    // URL認証
    echo $json['challenge'];
    exit;
}else{
    // イベントコールバック
    if(strpos($json['event']['subtype'], 'add') !== false){
        // 絵文字追加orエイリアス追加
        if(strpos($json['event']['value'], 'alias:') === false){
            // 絵文字追加
            $emoji = $json['event']['name'];
            $msg = "新しい絵文字`:{$emoji}:`が追加されたよ！\n:{$emoji}:\n";
            $msg_json = json_encode(['text' => $msg]);
            $command = "curl -X POST -H 'Content-type: application/json' --data '". $msg_json. " '". $webhook_url ."'";

            // 送信コマンド実行
            exec($command);
            exit;
        }else{
            // エイリアス追加
            $msg = "新しいエイリアスが追加されたよ！\n";
            $msg_json = json_encode(['text' => $msg]);
            $command = "curl -X POST -H 'Content-type: application/json' --data '". $msg_json. " '". $webhook_url ."'";
            exec($command);
            exit;
        }
    }
}

