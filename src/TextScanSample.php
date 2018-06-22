<?php
/**
 * Created by PhpStorm.
 * User: hyliu
 * Date: 2017/4/21
 * Time: 10:02
 */

include_once  './AliCS.php';
use Alics\AliCS as aliCS;



new aliCS();















function findImage($str){
    $regImg = '/\[img.*?\](.*?)\[\/img\]/';
    $res = preg_match_all($regImg,$str,$matchAll);
    $imgs = is_array($matchAll[1]) ? $matchAll[1] : [];
    return $imgs;
}
var_dump(findImage('[img]http:a.jpg[/img][img]http:a.jpg[/img]'));
//include_once 'aliyuncs/aliyun-php-sdk-core/Config.php';
//include_once './AliCS.php';
include_once(dirname(__FILE__).'/AliCS.php');
include_once dirname(__FILE__).'/aliyuncs/aliyun-php-sdk-core/Config.php';
use Green\Request\V20170112 as Green;
date_default_timezone_set("PRC");
//use Green\Request\V20170112 as Green;
//date_default_timezone_set("PRC");
echo dirname(__FILE__);
//$ak = parse_ini_file("aliyun.ak.ini");
//
//$iClientProfile = DefaultProfile::getProfile("cn-beijing", $ak["accessKeyId"], $ak["accessKeySecret"]);
//DefaultProfile::addEndpoint("cn-beijing", "cn-beijing", "Green", "green.cn-beijing.aliyuncs.com");
////$this->client = new DefaultAcsClient($iClientProfile);
////请替换成你自己的accessKeyId、accessKeySecret
////$iClientProfile = DefaultProfile::getProfile("cn-shanghai", $ak["accessKeyId"], $ak["accessKeySecret"]); // TODO
////DefaultProfile::addEndpoint("cn-shanghai", "cn-shanghai", "Green", "green.cn-shanghai.aliyuncs.com");
//$client = new DefaultAcsClient($iClientProfile);
//
//$request = new Green\TextScanRequest();
//$request->setMethod("POST");
//$request->setAcceptFormat("JSON");
//
$task1 = array('dataId' =>  uniqid(),
    'content' => '你真棒'
);
//$task2 = array('dataId' =>  uniqid(),
//    'content' => '色情'
//);
//$request->setContent(json_encode(array(
//    "tasks" => array($task1),
//    "scenes" => array("antispam"))));
//
//try {
//    $response = $client->getAcsResponse($request);
//
//    if(200 == $response->code){
//        $taskResults = $response->data;
//        foreach ($taskResults as $taskResult) {
//            if(200 == $taskResult->code){
//                $sceneResults = $taskResult->results;
//                foreach ($sceneResults as $sceneResult) {
//                    $scene = $sceneResult->scene;
//                    $suggestion = $sceneResult->suggestion;
//                    //根据scene和suggetion做相关的处理
//                    //do something
//                    $sceneResult->dataId = $taskResult->dataId;
//                    $sceneResult->taskId = $taskResult->taskId;
//                    $ret =  array(
//                        "code"=>200,
//                        "ret"=>json_decode(json_encode($sceneResult),true)
//                    );
//                }
//            }else{
//                print_r("task process fail:" + $response->code);
//            }
//        }
//    }else{
//        print_r("detect not success. code:" + $response->code);
//    }
//} catch (Exception $e) {
//    print_r($e);
//}
//
//
//if ($ret["code"] == 200) {
////    print_r( $ret["ret"]);
//    $action = $ret["ret"]["suggestion"];
//    $taskId = $ret["ret"]["taskId"];
//    $labelArray = $ret["ret"]["label"];
//    if ($action == 'pass') {
//        echo 'pass';
//        $_G['anti_check']['review'] = true;
//    }
//    else
//    {
//        echo 'failed';
//        $_G['anti_check']['review'] = false;
//    }
//}
//$avatar = strip_tags('http://wx3.sinaimg.cn/mw690/60ade0f3ly1frl8v72hoaj206o06ot8x.jpg');
//$reg = '/((http|https):\/\/)+(\w+\.)+(\w+)[\w\/\.\-]*(jpg|gif|png)/';
//$images= array();
//preg_match_all($reg, $avatar, $matches);
//if(!empty($matches[0])) {
//    foreach ($matches[0] as $curl) {
//
//        array_push($images, array(
//            "url" => $curl,
//            "dataId" => uniqid("",23),
//            "time" => round(microtime(true) * 1000),
//        ));
//    }
//}else{
//    showmessage('对不起，头像地址无效！');
//}
//print_r($images);
$images = array();
array_push($images, array(
            "url" => 'http://a.lpg',
            "dataId" => uniqid("",23),
            "time" => round(microtime(true) * 1000),
        ));
echo '<hr>';

$params["time"] =round(microtime(true) * 1000);
$filename = './safe.log';
$iClientProfile = DefaultProfile::getProfile("cn-shanghai", 'LTAIBAoNEOLpk0HB', 'SmTGBz8nJ4pF4XjMawpyKIUzhZVN88'); // TODO
DefaultProfile::addEndpoint("cn-shanghai", "cn-shanghai", "Green", "green.cn-shanghai.aliyuncs.com");
$client = new DefaultAcsClient($iClientProfile);
$request = new Green\TextScanRequest();
$request->setMethod("POST");
$request->setAcceptFormat("JSON");
$request->setContent(json_encode(array("tasks" => array($task1),
    "scenes" => array("antispam"))));
try {
    $response = $client->getAcsResponse($request);
    file_put_contents($filename,'$response:参数'.var_export($response,true).PHP_EOL,FILE_APPEND);
    if(200 == $response->code){
        $taskResults = $response->data;
        $return = array();
        foreach ($taskResults as $taskResult) {
            if(200 == $taskResult->code){
                $sceneResults = $taskResult->results;
                foreach ($sceneResults as $sceneResult) {

                    $sceneResult->dataId = $taskResult->dataId;
                    $sceneResult->taskId = $taskResult->taskId;
                    //根据scene和suggetion做相关的处理
                    //do something
                    print_r( array(
                        "code"=>200,
                        "ret"=>json_decode(json_encode($sceneResult),true)
                    ));
                }
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

//$filename = DISCUZ_ROOT.'data/log/safe.log';
//file_put_contents($filename,'check_img:参数'.var_export($params,true).PHP_EOL,FILE_APPEND);
$iClientProfile = DefaultProfile::getProfile("cn-shanghai", 'LTAIBAoNEOLpk0HB', 'SmTGBz8nJ4pF4XjMawpyKIUzhZVN88'); // TODO
//file_put_contents($filename,'iClientProfile:参数'.var_export($iClientProfile,true).PHP_EOL,FILE_APPEND);
DefaultProfile::addEndpoint("cn-shanghai", "cn-shanghai", "Green", "green.cn-shanghai.aliyuncs.com");
$client = new DefaultAcsClient($iClientProfile);
//file_put_contents($filename,'client:参数'.var_export($client,true).PHP_EOL,FILE_APPEND);
$request = new Green\ImageSyncScanRequest();
//file_put_contents($filename,'$request:参数'.var_export($request,true).PHP_EOL,FILE_APPEND);
$request->setMethod("POST");
$request->setAcceptFormat("JSON");
$request->setContent(json_encode(array("tasks" => $images,
    "scenes" => array("porn","terrorism"))));

try {
    $response = $client->getAcsResponse($request);

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
        print_r( array(
            "code"=>200,
            "ret"=>$return,
        ));
    }else{
        print_r( array(
            "code"=>$response->code,
            "ret"=>$response->msg,
        ));
    }
} catch (Exception $e) {
    return array(
        "code"=>-1,
        "ret"=>$e,
    );
}
//$alics = new AliCS('LTAIBAoNEOLpk0HB','SmTGBz8nJ4pF4XjMawpyKIUzhZVN88');
$alics = new AliCS('LTAIBAoNEOLpk0HB','SmTGBz8nJ4pF4XjMawpyKIUzhZVN88');
echo 'aaaaaaaaaaaaaaaaaaaaaaaaaa';
$ret = $alics->imageSyncScan($images);
echo '<pre>';
print_r($ret);
if ($ret["code"] == 200) {
    $result = $ret["ret"];
    foreach($result as $index => $image_ret){
        $name = $image_ret["url"];
        $taskId = $image_ret["taskId"];
        foreach( $image_ret["results"] as $v){
            echo "scene:{$v['scene']},suggestion:{$v['suggestion']}label:{$v["label"]}, rate={$v["rate"]}\n";
            if($v['suggestion'] == 'pass'){
							echo 'pass';
            }else{
                showmessage('对不起，头像违禁！');
            };
        }
    }
}