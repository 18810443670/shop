<?php







//判断是线上还是测试
if (strpos($_SERVER['REMOTE_ADDR'], '192.168') !== false) {
    // HTTP
    define('HTTP_SERVER', 'http://shop.codepay.cc/admin/');
    define('HTTP_CATALOG', 'http://shop.codepay.cc/');
    // HTTPS
    define('HTTPS_SERVER', 'http://shop.codepay.cc/admin/');
    define('HTTPS_CATALOG', 'http://shop.codepay.cc/');
    // DIR
    define('DIR_APPLICATION', '/home/web/shop/admin/');
    define('DIR_SYSTEM', '/home/web/shop/system/');
    define('DIR_IMAGE', '/home/web/shop/image/');
}else{
    // HTTP
    define('HTTP_SERVER', 'http://mall.szyimafu.com/admin/');
    define('HTTP_CATALOG', 'http://mall.szyimafu.com/');
    // HTTPS
    define('HTTPS_SERVER', 'http://mall.szyimafu.com/admin/');
    define('HTTPS_CATALOG', 'http://mall.szyimafu.com/');
    // DIR
    define('DIR_APPLICATION', '/home/wwwroot/shop/admin/');
    define('DIR_SYSTEM', '/home/wwwroot/shop/system/');
    define('DIR_IMAGE', '/home/wwwroot/shop/image/');
}





define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
define('DIR_CATALOG', '/home/web/opencart/upload/catalog/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
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

// OpenCart API
define('OPENCART_SERVER', 'https://www.opencart.com/');
