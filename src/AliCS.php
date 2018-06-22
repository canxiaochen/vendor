<?php
namespace Alics;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/28
 * Time: 17:22
 */
include_once(dirname(__FILE__).'/aliyuncs/aliyun-php-sdk-core/Config.php');
date_default_timezone_set("PRC");

use Green\Request\V20170112 as Green;
class AliCS
{
    private $accessKeyId;
    private $accessKeySecret;
    private $client;
    private $textScanrequest;
    private $imageSyncScanrequest;
    public function  __construct($accessKeyId,$accessKeySecret)
    {

        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
        $this->getClient();
        $this->textScanrequest = new Green\TextScanRequest();
        $this->textScanrequest->setMethod("POST");
        $this->textScanrequest->setAcceptFormat("JSON");
        $this->imageAsyncScanrequest = new Green\ImageAsyncScanRequest();
        $this->imageAsyncScanrequest->setMethod("POST");
        $this->imageAsyncScanrequest->setAcceptFormat("JSON");
        $this->imageSyncScanrequest = new Green\ImageSyncScanRequest();
        $this->imageSyncScanrequest->setMethod("POST");
        $this->imageSyncScanrequest->setAcceptFormat("JSON");
//        $filename = DISCUZ_ROOT.'data/log/safe.log';
//        file_put_contents($filename,'check_img:参数'.'alics实例化'.PHP_EOL,FILE_APPEND);
    }
    private function getClient()
    {
        $iClientProfile = DefaultProfile::getProfile("cn-beijing", $this->accessKeyId, $this->accessKeySecret);
        DefaultProfile::addEndpoint("cn-beijing", "cn-beijing", "Green", "green.cn-beijing.aliyuncs.com");
        $this->client = new DefaultAcsClient($iClientProfile);
    }

    //文本检测
    public function textScan($task)
    {

        $this->textScanrequest->setContent(json_encode(array(
            "tasks" => array($task),
            "scenes" => array("antispam"))));

        try {
            $response = $this->client->getAcsResponse($this->textScanrequest);

            if(200 == $response->code){
                $taskResults = $response->data;
                foreach ($taskResults as $taskResult) {
                    if(200 == $taskResult->code){
                        $sceneResults = $taskResult->results;
                        foreach ($sceneResults as $sceneResult) {

                            $sceneResult->dataId = $taskResult->dataId;
                            $sceneResult->taskId = $taskResult->taskId;
                            //根据scene和suggetion做相关的处理
                            //do something
                            return array(
                                "code"=>200,
                                "ret"=>json_decode(json_encode($sceneResult),true)
                            );
                        }
                    }else{
                        return array(
                            "code"=>$response->code,
                            "ret"=>'task process fail',
                        );
                    }
                }
            }else{
                return array(
                    "code"=>$response->code,
                    "ret"=>$response->msg,
                );
            }
        } catch (Exception $e) {
            return array(
                "code"=>-1,
                "ret"=>$e,
            );
        }
    }

    //同步图片扫描
    public function imageSyncScan($tasks){

        $this->imageSyncScanrequest->setContent(json_encode(array("tasks" => $tasks,
            "scenes" => array("porn","terrorism"))));

        try {
            $response = $this->client->getAcsResponse($this->imageSyncScanrequest);

            if(200 == $response->code){
                $taskResults = $response->data;
                $return = array();
                foreach ($taskResults as $taskResult) {
                    if(200 == $taskResult->code){
                        $return[] = json_decode(json_encode($taskResult),true);
                    }else{
                        return array(
                            "code"=>$response->code,
                            "ret"=>'task process fail',
                        );
                    }
                }
                return array(
                    "code"=>200,
                    "ret"=>$return,
                );
            }else{
                return array(
                    "code"=>$response->code,
                    "ret"=>$response->msg,
                );
            }
        } catch (Exception $e) {
            return array(
                "code"=>-1,
                "ret"=>$e,
            );
        }
    }

    //异步图片扫描
    public function imageAsyncScan($tasks){

        $task1 = array('dataId' =>  uniqid(),
            'url' => 'http://file1.a9vg.com/data/attachment/common/3a/common_661_icon.jpg',
            'time' => round(microtime(true)*1000)
        );
        $this->imageAsyncScanrequest->setContent(json_encode(array("tasks" => array($task1),
            "scenes" => array("porn","terrorism"))));

        try {
            $response = $this->client->getAcsResponse($this->imageAsyncScanrequest);
//            print_r($response);
            if(200 == $response->code){
                $taskResults = $response->data;
                foreach ($taskResults as $taskResult) {
                    if(200 == $taskResult->code){
                        $taskId = $taskResult->taskId;
                        print_r($taskId);
                        echo '<pre>';
                        print_r($taskResult);
                        // 将taskId 保存下来，间隔一段时间来轮询结果, 参照ImageAsyncScanResultsRequest
                    }else{
                        return array(
                            "code"=>$response->code,
                            "ret"=>'task process fail',
                        );
                    }
                }
            }else{
                return array(
                    "code"=>$response->code,
                    "ret"=>$response->msg,
                );
            }
        } catch (Exception $e) {
            return array(
                "code"=>-1,
                "ret"=>$e,
            );
        }
    }

}
