<?php
 
 
 
class CallbackSDK {
 
    private $app_secret = "";
 
 
 
    /**
 
     * 设置app_key对应的app_secret。
 
     * @param $app_secret
 
     */
 
    public function setAppSecret($app_secret) {
 
        $this->app_secret = $app_secret;
 
    }
 
 
 
    /**
 
     * 获取推送来的的数据
 
     * 必须使用 $GLOBALS['HTTP_RAW_POST_DATA']方法获取post过来的原始数据来解析.
 
     * @return mixed
 
     */
 
    public function getPostMsgStr() {
 
        return json_decode($GLOBALS['HTTP_RAW_POST_DATA'], true);
 
    }
 
 
 
    /**
 
     * 验证签名
 
     * @param $signature
 
     * @param $timestamp
 
     * @param $nonce
 
     * @return bool
 
     */
 
    function checkSignature($signature, $timestamp, $nonce) {
 
        $tmpArr = array($this->app_secret, $timestamp, $nonce);
 
        sort($tmpArr, SORT_STRING);
 
        $tmpStr = sha1(implode($tmpArr));
 
        if ($tmpStr == $signature) {
 
            return true;
 
        } else {
 
            return false;
 
        }
 
    }
 
 
 
    /**
 
     * 组装返回数据
 
     * @param $receiver_id
 
     * @param $sender_id
 
     * @param $data
 
     * @param $type
 
     * @return array
 
     */
 
    function buildReplyMsg($receiver_id, $sender_id, $data, $type) {
 
        return $msg = array(
 
            "sender_id" => $sender_id,
 
            "receiver_id" => $receiver_id,
 
            "type" => $type,
 
            //data字段需要进行urlencode编码
 
            "data" => urlencode(json_encode($data))
 
        );
 
    }
 
 
 
    /**
 
     * 生成text类型的回复消息内容
 
     * @param $text
 
     * @return array
 
     */
 
    function textData($text) {
 
        return $data = array("text" => $text);
 
    }
 
 
 
    /**
 
     * 生成article类型的回复消息内容
 
     * @param $article
 
     * @return array
 
     */
 
    function articleData($articles) {
 
        return $data = array(
 
            'articles' => $articles
 
        );
 
    }
 
 
 
    /**
 
     * 生成position类型的回复消息内容
 
     * @param $longitude
 
     * @param $latitude
 
     * @return array
 
     */
 
    function positionData($longitude, $latitude) {
 
        return $data = array(
 
            "longitude" => $longitude,
 
            "latitude" => $latitude
 
        );
 
    }
 
}
