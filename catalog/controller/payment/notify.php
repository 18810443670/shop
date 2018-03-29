<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29 0029
 * Time: 14:05
 */

class ControllerPaymentNotify extends Controller
{
    public function index()
    {
        //记录数据日志
        $notifyStr = trim(file_get_contents("php://input"));
        if (empty($notifyStr)) exit('empty data');
        $notifyArr = json_decode($notifyStr, true);

        $sign = $this->getSign($notifyArr);
        if (!hash_equals(strtoupper($sign), strtoupper($notifyArr['sign']))) exit('sign error');

        $order_sn = $notifyArr ['order_id']; //订单号
        $order_type = intval($notifyArr['state']); //订单状态
        $total_fee = intval($notifyArr['payment']) / 100; //实付金额
        if ($order_type === 0) exit('success');
        //修改订单状态
        $sql ="UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . 13 . "' where billno = " . $order_sn;
        $this->model_checkout_order->editOrderNotify($sql);
        exit($sql.'success');
    }

    //验证签名
    function getSign($params)
    {
        ksort($params);
        $signStr = "";
        foreach ($params as $k => $v) {
            if (in_array(strtolower($k), ['sign'])) continue;
            if (is_null($v) or $v === '') continue;
            $signStr .= $k . '=' . $v . '&';
        }
        $str = $signStr . 'key=' . GOLD_KEY;
        return md5($str);
    }


}