<?php


//判断是线上还是测试
if (strpos($_SERVER['REMOTE_ADDR'], '192.168') !== false) {
    // HTTP
    define('HTTP_SERVER', 'http://shop.codepay.cc/');
    // HTTPS
    define('HTTPS_SERVER', 'http://shop.codepay.cc/');
    // DIR
    define('DIR_APPLICATION', '/home/web/shop/catalog/');
    define('DIR_SYSTEM', '/home/web/shop/system/');
    define('DIR_IMAGE', '/home/web/shop/image/');
}else{
    // HTTP
    define('HTTP_SERVER', 'http://mall.szyimafu.com/');
    // HTTPS
    define('HTTPS_SERVER', 'http://mall.szyimafu.com/');
    // DIR
    define('DIR_APPLICATION', '/home/wwwroot/shop/catalog/');
    define('DIR_SYSTEM', '/home/wwwroot/shop/system/');
    define('DIR_IMAGE', '/home/wwwroot/shop/image/');
}


define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_DATABASE', 'ymfShop');
define('DB_PORT', '3306');
define('DB_PREFIX', 'ymf_');


//支付通道
define('GOLD_APPID', 800003);//接入APPID

define('GOLD_BANK', 'bank'); //bank值

define('GOLD_PAYURL', 'https://api.goldpayment.net/pay/');//支付请求的url

define('GOLD_KEY', 'UVIXnJb7W4kpFevdDum5MHNBZSj06q39');//商户秘钥

define('GOLD_NOTIFY', HTTP_SERVER . 'index.php?route=payment/notify');//支付回调地址

define('GOLD_NOTICE', HTTP_SERVER . 'index.php?route=checkout/success');//支付成功跳转地址