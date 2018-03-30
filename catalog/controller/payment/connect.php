<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/30 0030
 * Time: 11:16
 */
class ControllerPaymentConnect extends Controller
{
    function index()
    {
        if (isset($_POST['payment_method'])
            && isset($_POST['order_id'])
            && in_array($_POST['payment_method'], ['quick', 'gateway'])) {
            $result = $this->load->controller('extension/payment/' . $_POST['payment_method'], $_POST['order_id']);
            header('Location:'.$result);
        }
    }
}
