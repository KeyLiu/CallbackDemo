<?php
//接口要求返回的字符串需要是utf8编码。
header( 'Content-type: text/html; charset=utf-8' );
//加载SDK
require_once 'CallbackSDK.php';
//设置app_key对应的app_secret
define("APP_SECRET", "b692c951b93889f575e23176695c15ea");
//初始化SDK
$call_back_SDK = new CallbackSDK();
$call_back_SDK->setAppSecret(APP_SECRET);
//签名验证
$signature = $_GET["signature"];
$timestamp = $_GET["timestamp"];
$nonce = $_GET["nonce"];
if (!$call_back_SDK->checkSignature($signature, $timestamp, $nonce)) {
    die("check signature error");
}
//首次验证url时会有'echostr'参数，后续推送消息时不再有'echostr'字段
//若存在'echostr'说明是首次验证,则返回'echostr'的内容。
if (isset($_GET["echostr"])) {
    die($_GET["echostr"]);
}
//处理开放平台推送来的消息,首先获取推送来的数据.
$post_msg_str = $call_back_SDK->getPostMsgStr();
/**
 * 设置接口默认返回值为空字符串。
 * 请注意数据编码类型。接口要求返回的字符串需要是utf8编码
 * 需要说明的是开放平台判断推送成功的标志是接口返回的http状态码,
 * 只要应用的接口返回的状态为200就会认为消息推送成功，如果http状态码不为200则会重试，共重试3次。
 */
$str_return = '';
if (!empty($post_msg_str)) {
    //sender_id为发送回复消息的uid，即蓝v自己
    $sender_id = $post_msg_str['receiver_id'];
    //receiver_id为接收回复消息的uid，即蓝v的粉丝
    $receiver_id = $post_msg_str['sender_id'];
    //回复text类型的消息示例。
 
    $keyword= $post_msg_str['text'];
    //图灵API
     $apiKey = "39d83864ce6940d4acac4a7de06da0fb";
     $apiURL = "http://www.tuling123.com/openapi/api?key=KEY&info=INFO";
 
// 设置报文头, 构建请求报文
    $reqInfo = $keyword;
    $url = str_replace("INFO", $reqInfo, str_replace("KEY", $apiKey, $apiURL)); 
    $res =file_get_contents($url);
    $result = json_decode($res);
    $jiaoyan=$result->{'code'};
    switch($jiaoyan){
        case "200000":
        $data_type = "text";
        $wz=$result->{'text'};
        $lj=$result->{'url'};
        $xx=$wz.$lj;
        $data = $call_back_SDK->textData("$xx");
        break;
        case "302000":
        //$data_type = "text";
        //$lb=$result->{'list'};
        //$xx=var_export ($lb, TRUE);
        //$data = $call_back_SDK->textData("$xx");
        $data_type = "articles";
        $length = count($result['list']) > 9 ? 9 :count($result['list']);
            for($i= 0;$i< $length;$i++){
                $articles [$i] = array (
                        'display_name' => $result['list'][$i]['article'],
                        'summary' => $result['list'][$i]['article'],
                        'image' => $result['list'][$i]['icon'],
                        'url' => $result['list'][$i]['detailurl'] 
                );
            }
        $data = $call_back_SDK->articleData($articles);
        break;
        case "40001":
        $data_type = "text";
        $xx="（＞﹏＜）我累了，需要休息一下。";
        $data = $call_back_SDK->textData("$xx");
        break;
        case "40002":
        $data_type = "text";
        $xx="（＞﹏＜）我累了，需要休息一下。";
        $data = $call_back_SDK->textData("$xx");
        break;
        case "40003":
        $data_type = "text";
        $xx="（＞﹏＜）我累了，需要休息一下。";
        $data = $call_back_SDK->textData("$xx");
        break;
        case "40004":
        $data_type = "text";
        $xx="（＞﹏＜）我累了，需要休息一下。";
        $data = $call_back_SDK->textData("$xx");
        break;
        case "40005":
        $data_type = "text";
        $xx="（＞﹏＜）我累了，需要休息一下。";
        $data = $call_back_SDK->textData("$xx");
        break;
        case "40006":
        $data_type = "text";
        $xx="（＞﹏＜）我累了，需要休息一下。";
        $data = $call_back_SDK->textData("$xx");
        break;
        case "40007":
        $data_type = "text";
        $xx="（＞﹏＜）我累了，需要休息一下。";
        $data = $call_back_SDK->textData("$xx");
        break;
        default:
        $data_type = "text";
        $xx=$result->{'text'};
        $data = $call_back_SDK->textData("$xx");
    }
    $str_return = $call_back_SDK->buildReplyMsg($receiver_id, $sender_id, $data, $data_type);
}
echo json_encode($str_return);
