order表添加字段

order_sn  订单号

billno  通道的订单号

alter table ymf_order add order_sn VARCHAR(50) not null comment '订单号';
alter table ymf_order add billno  VARCHAR(50) not null comment '通道订单号订单号';
