<?php 
/**
 *  微信接口类
 */
namespace wechat;
use think\Db;

class WSTWechat{
	public $appId;
	public $secret;
	private $tokenId;
	private $error;
	private $tpl = [
		'text' => "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>0</FuncFlag>
					</xml>",
		'image' => " <xml>
					 <ToUserName><![CDATA[%s]]></ToUserName>
					 <FromUserName><![CDATA[%s]]></FromUserName>
					 <CreateTime>%s</CreateTime>
					 <MsgType><![CDATA[image]]></MsgType>
					 <PicUrl><![CDATA[this is a url]]></PicUrl>
					 <MediaId><![CDATA[media_id]]></MediaId>
					 <MsgId>1234567890123456</MsgId>
					 </xml>",
		'news'  => '<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<ArticleCount>%s</ArticleCount>
					<Articles>
					%s
					</Articles>
					</xml> ',
		'content' => '<item>
						<Title><![CDATA[%s]]></Title> 
						<Description><![CDATA[%s]]></Description>
						<PicUrl><![CDATA[%s]]></PicUrl>
						<Url><![CDATA[%s]]></Url>
						</item>',
	];
	
	/**
	 * 初始微信配置信息
	 */
    public function __construct($appId, $secret) {
        $this->appId = $appId;
        $this->secret = $secret;
        $this->getToken();
    }
    /**
     * http访问
     * @param $url 访问网址
     */
	private function http($url,$data = null){
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    if($data){
	    	curl_setopt($curl,CURLOPT_POST,1);
	    	curl_setopt($curl,CURLOPT_POSTFIELDS,$data);//如果要处理的数据，请在处理后再传进来 ，例如http_build_query这里不要加
	    }
	    $res = curl_exec($curl);
	    if(!$res){
	    	$error = curl_errno($curl);
	    	echo $error;
	    }
	    curl_close($curl);
	    return $res;
	}
	
	/**
	 * 获取访问令牌
	 */
	public function getToken(){
		$access_token = cache('access_token');
		if($access_token!=false) { //已缓存，直接使用
			$url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$access_token;
			$data = $this->http($url);
			$data = json_decode($data, true);
			if(isset($data['errcode'])){
				cache('access_token',null);
				return $this->getToken();
			}else{
				$this->tokenId = $access_token;
				return $this->tokenId;
			}
		} else { //获取access_token
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appId.'&secret='.$this->secret;
			$data = $this->http($url);
			$data = json_decode($data, true);
			if($data['access_token']!=''){
				cache('access_token',$data['access_token'],600);
				$this->tokenId = $data['access_token'];
				return $this->tokenId;
			}else{
				$this->error = $data;
			}
			return false;
		}
	}
	
	/**
	 * 获取openid和access_token/微信端
	 */
	public function getUserInfo($code){
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appId.'&secret='.$this->secret.'&code='.$code.'&grant_type=authorization_code';
		$data = $this->http($url);
		return json_decode($data, true);
	}
	
	/**
	 * 获取用户详细信息/微信端
	 */
	public function UserInfo($wdata){
		$url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$wdata['access_token'].'&openid='.$wdata['openid'].'&lang=zh_CN';
		$data = $this->http($url);
		return json_decode($data, true);
	}
	
	/**
	 * 创建自定义菜单
	 */
	public function wxMenuCreate($wdata){
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->getToken();
		$data = $this->http($url,$wdata);
		return json_decode($data, true);
	}
	
	/**
	 * 获取自定义菜单
	 */
	public function wxMenuGet(){
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$this->getToken();
		$data = $this->http($url);
		return json_decode($data, true);
	}	
	
	/**
	 * 获取用户基本信息/后台
	 */
	public function wxUserInfo($openid){
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->getToken().'&openid='.$openid.'&lang=zh_CN';
		$data = $this->http($url);
		return json_decode($data, true);
	}
	
	/**
	 * 获取用户列表
	 */
	public function wxUserGet($nextOpenid=''){
		$url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->getToken().'&next_openid='.$nextOpenid;
		$data = $this->http($url);
		return json_decode($data, true);
	}
	
	/**
	 * 设置备注名
	 */
	public function wxUpdateremark($wdata){
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token='.$this->getToken();
		$data = $this->http($url,$wdata);
		return json_decode($data, true);
	}
	
	/**
	 * 发送模板消息
	 */
	public function sendTemplateMessage($data){
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->getToken();
		return $this->http($url,$data);
		//return json_decode($rdata, true);
	}
	/**
     * 客服消息
     */
    public function sendCustomMessage($data){
    	$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$this->getToken();
		return $this->http($url,$data);
    }

	// 响应用户操作
	public function responseMsg()
    {
		//get post data, May be due to the different environments
		//$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		$postStr = file_get_contents('php://input');

		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		//判断用户发送的什么类型的消息
		switch($postObj->MsgType)
		{
			case 'event':
				$this->_doEvent($postObj);
				break;
			case 'text':
				$this->_doText($postObj);
				break;
			case 'image':
				$this->_doImage($postObj);
				break;
			case 'voice':
				$this->_doVoice($postObj);
				break;
			case 'video':
				$this->_doVideo($postObj);
				break;
			case 'location':
				$this->_doLocation($postObj);
				break;
			case 'shortvideo':
				$this->_doShortVideo($postObj);
				break;
			case 'link':
				$this->_doLink($postObj);
				break;
			default:exit;
		}		
    }
    /*************** 用户事件     ****************/
    public function _doEvent($postObj){
    	$contentStr = "";
    	switch($postObj->Event){
            case "subscribe":$this->subscribeEvent($postObj);
		                     break;
    	}
    }
    /**
     * 关注回复事件第一条用关注事件推送，之后的用客服消息推送
     */
    public function subscribeEvent($postObj){
    	$m = Db::name('wx_passive_replys');
    	$subscribes = $m->where([['dataFlag','=',1],['isSubscribe','=',1]])->order('subscribeSort asc,id desc')->limit(10)->select();
    	$userOpenId = (string)$postObj->FromUserName;
		foreach ($subscribes as $key => $v) {
			 if($key==0){
			 	 $resultStr = '';
                 switch ($v['msgType']) {
                 	case 'text':$resultStr = sprintf($this->tpl['text'], $postObj->FromUserName, $postObj->ToUserName, time(), $v['content'], 0);
                 		        break;
                 	case 'news':$newC = sprintf($this->tpl['content'], $v['title'], $v['description'], WSTDomain().$v['picUrl'], $v['url']);
                 	            $resultStr = sprintf($this->tpl['news'], $postObj->FromUserName, $postObj->ToUserName, time(), 1, $newC);
                 		        break;
                 }
                 echo $resultStr;
			 }else{
			 	 $sendData = [];
			 	 $sendData['touser'] = $userOpenId;
                 switch ($v['msgType']) {
                 	case 'text':$sendData['msgtype'] = 'text';
                 	            $sendData['text'] = ['content'=>urlencode($v['content'])];
                 		        break;
                 	case 'news':$sendData['msgtype'] = 'news';
                 	            $sendData['news'] = ['articles'=>[[
                 	                 'title'=>urlencode($v['title']),
                 	                 'description'=>urlencode($v['description']),
                 	                 'url'=>$v['url'],
                 	                 'picurl'=>WSTDomain().$v['picUrl']
                 	               ]]
                 	            ];
                 		        break;
                 }
                 $this->sendCustomMessage(urldecode(json_encode($sendData)));
			 }
		}	
    }

	/*************** 被动回复消息 ****************/

	public function _doText($postObj)
	{
		$time = time();            
		$msgType = "text";
		$keyword = trim($postObj->Content);// 用户发送过来的关键字
		$keyword = "$keyword";
		$m = Db::name('wx_passive_replys');
        
		$msgType = $m->where([['keyword','=',$keyword]])->value('msgType');

		if($msgType=='text'){
			$contentStr = $m->where([['keyword','=',$keyword],['dataFlag','=',1]])->value('content');
			$resultStr = sprintf($this->tpl['text'], $postObj->FromUserName, $postObj->ToUserName, $time, $contentStr);
			echo $resultStr;
		}elseif($msgType=='news'){
			// 图文消息最多发送10条
			$news = $m->field('title,description,picurl,url')->where([['keyword','=',$keyword],['dataFlag','=',1]])->limit(10)->select();
			$count = count($news);
			$newC='';
			for($i=0;$i<$count;++$i){
				$newC .= sprintf($this->tpl['content'], $news[$i]['title'], $news[$i]['description'], WSTDomain().$news[$i]['picurl'], $news[$i]['url']);
			}
			//将内容输出到新闻模板
			$news = sprintf($this->tpl['news'], $postObj->FromUserName, $postObj->ToUserName, $time, $count, $newC);
			echo $news;
		}
		exit;
	}
	/**********************  模板消息相关  ************************/
	// 查询模板列表
	public function getTemplates(){
		$url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=".$this->getToken();
		return $this->http($url);
	}
	//  发送模板消息
	public function sendTemplate(){
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->getToken();
		$data = '{
           "touser":"oCRQgwemDVQvHuAVgCBCjQjvRo3I",
           "template_id":"8tsGZ3xPapfft_llrPgsy6CKe_iK6oMQ9LeO-uoR2D0",
           "data":{
                   "first": {
                       "value":"您收到了一条新的订单",
                       "color":"#173177"
                   },
                   "keynote1":{
                       "value":"这个是订单时间",
                       "color":"#173177"
                   },
                   "keynote2": {
                       "value":"这个是订单类型",
                       "color":"#173177"
                   },
                   "keynote3": {
                       "value":"客户信息",
                       "color":"#173177"
                   },
                   "keynote4": {
                       "value":"测试一下",
                       "color":"#173177"
                   },
                   "keynote5": {
                       "value":"测试一下2",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"订单备注。",
                       "color":"#173177"
                   }
           }
       }';
		return $this->http($url,$data);
	}



		
	/*******************************************************************
	 * 
	 *                      JS SDK相关接口
	 * 
	 ******************************************************************/
	/**
	 * 获取随机字符加数值
	 * @param len 需要返回的字符串长度
	 */
	public function getRadomStr($len = 16){
	    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	    $str = "";
	    for ($i = 0; $i < $len; $i++) {
	       $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	    }
	    return $str;
	}
	/**
	 * 
	 */
	/**
	 * 获取jsapi_ticket
	 */
	public function getJsApiTicket(){
		$tokenId = $this->getToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$tokenId.'&type=jsapi';
		$data = $this->http($url);
		return json_decode($data, true);
	}
	/**
	 * 获取签名
	 * @param url 调用的网址
	 * @return array('status'=>-1/1)
	 */
	public function getJsSignature($url){
		//如果jsapi_ticket过期的话就重新获取，否则就继续用原来的
		$jsapi_ticket = cache('jsapi_ticket_'.md5($url));
		if($jsapi_ticket){
			return $jsapi_ticket;
		}else{
			$ticket = $this->getJsApiTicket();
			if($ticket['errcode']==0){
				$data = array();
				$data['status'] = 1;
				$data['noncestr'] = $this->getRadomStr();
				$data['timestamp'] = time();
				$data['jsapi_ticket'] = $ticket['ticket'];
			    $data['signature'] = sha1('jsapi_ticket='.$ticket['ticket'].'&noncestr='.$data['noncestr'].'&timestamp='.$data['timestamp'].'&url='.$url);
			    cache('jsapi_ticket_'.md5($url),$data,600);
			    return $data;
			}else{
				$this->error = $ticket;
			}
			return array('status'=>-1,'errcode'=>$ticket['errcode'],'errmsg'=>$ticket['errmsg']);
		}
	}
	
    /**
     * 记录出错日志
     * @return array
     */
    public function getError(){
    	return $this->error;
    }
}