<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/28 0028
 * Time: 16:11
 */


class ControllerExtensionPaymentQuick extends Controller
{
    private $action = 'quick';  //支付方式

    /**
     * @param $order
     * @param array $payment
     * @param bool $is_getsign
     * @return mixed
     */
    function index($order)
    {
        header('Content-type:text/html;charset=utf-8');
        //生成新的通道订单号
        $orderSign = $this->model_checkout_order->editOrderSign($order['order_id']);
        $params = [
            'appid' => GOLD_APPID,
            'amount' => intval($order['total']) * 100,
            'order_id' => $orderSign,
            'bank' => GOLD_BANK,
            'action' => $this->action,
            'notify' => GOLD_NOTIFY,
            'notice' => GOLD_NOTICE,
            'remark' => 'abcd',
            'ip' => $this->getIp(),
            'mobile' => $this->is_wap() ? 1 : 0,
            'username' => hash('md5', $order['order_id']),
        ];
        $params['sign'] = $this->getSign($params);
        $html = self::send(GOLD_PAYURL, json_encode($params));
//        var_dump($html);die;
        return json_decode($html)->url ?? '';
    }

    /**
     * 获取签名
     * @param $params
     * @return string
     */
    protected function getSign($params)
    {
        ksort($params);
        $signStr = "";
        foreach ($params as $k => $v) {
            if ($v === '') continue;
            $signStr .= $k . '=' . $v . '&';
        }
        $str = $signStr . 'key=' . GOLD_KEY;
        return md5($str);
    }


    /**
     * @param $url
     * @param $data
     * @param string $type
     * @param array $option
     * @param null $autoVal
     * @return bool|float|int|mixed|null|string
     * 若在已知目标主机IP的情况下，修改/etc/hosts可以获得更快的速度
     */
    public static function send($url, $data = null, $type = 'post', array $option = null, $autoVal = null)
    {
        if (empty($url)) return 'empty API url';

        $type = strtoupper($type);
        if (!in_array($type, ['GET', 'POST', 'PUT', 'HEAD', 'DELETE'])) $type = 'GET';
        $cURL = curl_init();   //初始化一个cURL会话，若出错，则退出。
        if ($cURL === false) return 'Create Protocol Object Error';
        if (!isset($option['headers'])) $option['headers'] = [];
        if (!is_array($option['headers'])) $option['headers'] = [$option['headers']];

//        curl_setopt($cURL, CURLOPT_STDERR, root('/cache/curl'));
//        curl_setopt($cURL, CURLOPT_VERBOSE, true);//TRUE 会输出所有的信息，写入到STDERR，或在CURLOPT_STDERR中指定的文件。CURL报告每一件意外的事情

        switch ($type) {
            case "POST":
                curl_setopt($cURL, CURLOPT_POST, true);
                $headers[] = "X-HTTP-Method-Override: POST";
                break;
            case "GET" :
            case "HEAD" :   //这三种不常用，使用前须确认对方是否接受
            case "PUT" :
            case "DELETE":
                break;
        }
        curl_setopt($cURL, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($cURL, CURLOPT_URL, $url);                 //接收页
        if (isset($option['port'])) {
            curl_setopt($cURL, CURLOPT_PORT, intval($option['port']));                  //端口
        }
        if (isset($option['agent'])) {
            if ($option['agent'] === 'default') $option['agent'] = 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/63.0.1364.172 Safari/537.22';
            else if ($option['agent'] === 'mobile') $option['agent'] = 'Mozilla/5.0 (Linux; Android 7.0; CPN-AL00 Build/HUAWEICPN-AL00) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30';
            else if ($option['agent'] === 'firefox') $option['agent'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0';
            curl_setopt($cURL, CURLOPT_USERAGENT, $option['agent']);
        }
        if (isset($option['proxy'])) {
            curl_setopt($cURL, CURLOPT_PROXY, $option['proxy']);
        }
        if (isset($option['cookies'])) {
            curl_setopt($cURL, CURLOPT_COOKIE, $option['cookies']);
        }
        if (isset($option['referer']) and $option['referer']) {
            curl_setopt($cURL, CURLOPT_REFERER, $option['referer']);
        }

        curl_setopt($cURL, CURLOPT_HEADER, (isset($option['transfer']) and $option['transfer']));

        if (!empty($option['headers'])) {
            curl_setopt($cURL, CURLOPT_HTTPHEADER, $option['headers']);
        }
        if (isset($option['gzip']) and $option['gzip']) {// 被读取的页面有gzip压缩
            curl_setopt($cURL, CURLOPT_ENCODING, "gzip");
        }
        curl_setopt($cURL, CURLOPT_DNS_CACHE_TIMEOUT, 120);     //内存中保存DNS信息，默认120秒
        curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 10);         //在发起连接前等待的时间，如果设置为0，则无限等待
        curl_setopt($cURL, CURLOPT_TIMEOUT, 10);                //允许执行的最长秒数，若用毫秒级，用CURLOPT_TIMEOUT_MS
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, TRUE);       //返回文本流
        curl_setopt($cURL, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);//指定使用IPv4解析

        if (strtoupper(substr($url, 0, 5)) === "HTTPS") {
//            curl_setopt($cURL, CURLOPT_HTTP_VERSION, CURLOPT_HTTP_VERSION_2_0);
//            curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, true);
//            curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, false);

            if (isset($option['cert'])) {//证书

//                curl_setopt($cURL, CURLOPT_CERTINFO, true);//TRUE 将在安全传输时输出 SSL 证书信息到 STDERR。

                curl_setopt($cURL, CURLOPT_SSLCERTTYPE, 'PEM');
                curl_setopt($cURL, CURLOPT_SSLKEYTYPE, 'PEM');
                if (isset($option['cert']['cert'])) curl_setopt($cURL, CURLOPT_SSLCERT, $option['cert']['cert']);
                if (isset($option['cert']['key'])) curl_setopt($cURL, CURLOPT_SSLKEY, $option['cert']['key']);
                if (isset($option['cert']['ca'])) curl_setopt($cURL, CURLOPT_CAINFO, $option['cert']['ca']);
            }
        } else {
            curl_setopt($cURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        }

        //从可靠的角度，推荐指定CURL_SAFE_UPLOAD的值，明确告知php禁止旧的@语法。
        if ($type === 'UPLOAD') {
            curl_setopt($cURL, CURLOPT_UPLOAD, true);

            if (defined('CURLOPT_SAFE_UPLOAD')) {
                //PHP5.6以后，需要指定此值，且须在附加数据之前，否则对方得不到上传的数据文件
                curl_setopt($cURL, CURLOPT_SAFE_UPLOAD, false);
            } elseif (class_exists('\CURLFile')) {//低版本
                curl_setopt($cURL, CURLOPT_SAFE_UPLOAD, true);
            }
        }

        //提交上传数据放在最后
        if (!!$data) curl_setopt($cURL, CURLOPT_POSTFIELDS, $data);

        $html = curl_exec($cURL);
        $err = curl_error($cURL);
        if ($err) return $err;
        curl_close($cURL);
        $html = trim($html);
        if (empty($html)) return $autoVal;
        if (is_int($autoVal)) return intval($html);
        if (is_float($autoVal)) return floatval($html);
        if (is_bool($autoVal)) return boolval($html);
        if (is_array($autoVal)) return self::to_array($html);
        return $html;
    }


    /**
     * 获取用户的真实IP
     * @return array|false|string
     */
    private function getIp()
    {
        if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else {
            $ip = getenv("REMOTE_ADDR");
        }
        return $ip;
    }


    private function is_wap($browser = null)
    {
        $browser = $browser ?: ($_SERVER['HTTP_USER_AGENT'] ?? '');
        if (empty($browser)) return true;

        $uaKey = ['MicroMessenger', 'android', 'mobile', 'iphone', 'ipad', 'ipod', 'opera mini', 'windows ce', 'windows mobile', 'symbianos', 'ucweb', 'netfront'];
        foreach ($uaKey as $i => $k) if (stripos($browser, $k)) return true;

        $mobKey = ['Noki', 'Eric', 'WapI', 'MC21', 'AUR ', 'R380', 'UP.B', 'WinW', 'UPG1', 'upsi', 'QWAP', 'Jigs', 'Java', 'Alca', 'MITS', 'MOT-', 'My S', 'WAPJ', 'fetc', 'ALAV', 'Wapa', 'Oper'];
        if (in_array(substr($browser, 0, 4), $mobKey)) return true;

        $isWap = ['HTTP_X_WAP_PROFILE', 'HTTP_UA_OS', 'HTTP_VIA', 'HTTP_X_NOKIA_CONNECTION_MODE', 'HTTP_X_UP_CALLING_LINE_ID'];
        foreach ($isWap as $i => $k) if (isset($_SERVER[$k])) return true;

        if (stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'vnd.wap')) return true;

        return false;
    }


}