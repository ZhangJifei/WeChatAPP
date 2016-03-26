<?php
/**
 * 微信公众平台 PHP SDK 示例文件
 *
 * @author NetPuter <netputer@gmail.com>
 */

  require('../src/Wechat.php');

  /**
   * 微信公众平台演示类
   */
  class MyWechat extends Wechat {

      
    protected function curlrequest(){
        $ch = curl_init(); //初始化CURL句柄 
        curl_setopt($ch, CURLOPT_URL, "http://api.yeelink.net/v1.0/device/340881/sensor/377799/datapoints"); //设置请求的URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); //设置请求方式
         
        curl_setopt($ch,CURLOPT_HTTPHEADER,array("U-ApiKey: ceac065fb361418532cbbdf69d93adbf"));//设置HTTP头信息
        curl_setopt($ch, CURLOPT_POSTFIELDS, array("value: 0"));//设置提交的字符串
        $document = curl_exec($ch);//执行预定义的CURL 
        
        curl_close($ch);
         
        return $document;
	}
      
    /**
     * 用户关注时触发，回复「欢迎关注」
     *
     * @return void
     */
    protected function onSubscribe() {
      $this->responseText('欢迎关注瑶芳祛痘公众号！'."\n".'瑶芳专注祛痘10年，全国千家连锁。'."\n".'我们还你一个精彩的无痘青春！'
                         ."\n".'【1】 回复“测试”，开始免费面部皮肤小测试。'
                         ."\n".'【2】 回复“服务”，了解我们的三周皮肤恢复计划。');
    }

    /**
     * 用户取消关注时触发
     *
     * @return void
     */
    protected function onUnsubscribe() {
      // 「悄悄的我走了，正如我悄悄的来；我挥一挥衣袖，不带走一片云彩。」
    }

    /**
     * 收到文本消息时触发，回复收到的文本消息内容
     *
     * @return void
     */
    protected function onText() {
        
        $keyword = trim($this->getRequest('content'));
        
         if(!empty( $keyword)) {
            if($keyword=="测试") {
                 $this->responseText('等一小下，测试程序正在速速开发中 ~~~');
            }
            else if($keyword=="服务") {
     			$items = array(
            		new NewsResponseItem('瑶芳的服务', '仅供示例，需要重新制作服务清单', "http://1.zxfwechat.sinaapp.com/source/weather.jpg", "http://1.zxfwechat.sinaapp.com/source/瑶芳价格单大图.jpg")
       			);
                
                $this->responseNews($items);
            }      
             else if(($keyword=="开") || ($keyword=="关")) {     // test for yeelink datapoint put
                 
                $ch = curl_init(); //初始化CURL句柄 
                curl_setopt($ch, CURLOPT_URL, "http://api.yeelink.net/v1.0/device/340881/sensor/377799/datapoints"); //设置请求的URL
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出 
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); //设置请求方式
                 
                curl_setopt($ch,CURLOPT_HTTPHEADER,array("U-ApiKey: ceac065fb361418532cbbdf69d93adbf"));//设置HTTP头信息
                 
                 
                 if ($keyword=="开") {
                     $data = array("value" => "1");
                 }
                 else {
                     $data = array("value" => "0");
                 }
                 
                $data_string = json_encode($data);
                
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);//设置提交的字符串
                 
                $document = curl_exec($ch);//执行预定义的CURL 
                  
                if(!curl_errno($ch)){ 
                  $info = curl_getinfo($ch); 
                  $this->responseText( 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url']); 
                } else { 
                  $this->responseText( 'Curl error: ' . curl_error($ch)); 
                }
                 
				curl_close($ch);

             }              
            else {
                $this->responseText('小二收到了客官的吩咐：' . $this->getRequest('content')
                                   ."\n". '小二会尽快回复客官哦 ~~~');
            }
        }       
    }

    /**
     * 收到图片消息时触发，回复由收到的图片组成的图文消息
     *
     * @return void
     */
    protected function onImage() {
      $items = array(
        new NewsResponseItem('标题一', '描述一', $this->getRequest('picurl'), $this->getRequest('picurl')),
        new NewsResponseItem('标题二', '描述二', $this->getRequest('picurl'), $this->getRequest('picurl')),
      );

      $this->responseNews($items);
    }

    /**
     * 收到地理位置消息时触发，回复收到的地理位置
     *
     * @return void
     */
    protected function onLocation() {
      $num = 1 / 0;
      // 故意触发错误，用于演示调试功能

      $this->responseText('收到了位置消息：' . $this->getRequest('location_x') . ',' . $this->getRequest('location_y'));
    }

    /**
     * 收到链接消息时触发，回复收到的链接地址
     *
     * @return void
     */
    protected function onLink() {
      $this->responseText('收到了链接：' . $this->getRequest('url'));
    }

    /**
     * 收到未知类型消息时触发，回复收到的消息类型
     *
     * @return void
     */
    protected function onUnknown() {
      $this->responseText('收到了未知类型消息：' . $this->getRequest('msgtype'));
    }

  }

  $wechat = new MyWechat('zxf_zjf', TRUE);
  $wechat->run();
