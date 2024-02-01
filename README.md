# PHP Gateway 客户端管理器

一个简单的 PHP Gateway 客户端连接管理工具

## 安装
~~~
composer require axguowen/gateway-client
~~~

## 使用

### 配置连接
~~~php
use \axguowen\facade\GatewayClient;

// Gateway配置信息设置（全局有效）
GatewayClient::setConfig([
    // 默认连接本机
    'default' => 'localhost',
    // 连接配置
    'connections' => [
        // 本机连接参数
        'localhost' => [
            // Gateway注册服务地址
            'register_address' => '127.0.0.1:1236',
            // 密钥, 为对应Register服务设置的密钥
            'secret_key' => '',
            // 连接超时时间，单位：秒
            'connect_timeout' => 3,
            // 与Gateway是否是长链接
            'persistent_connection' => false,
            // 禁用服务注册地址缓存
            'addresses_cache_disable' => false,
        ],
        // 其它主机连接参数
        'other' => [
            // Gateway注册服务地址
            'register_address' => '192.168.0.89:1236',
        ],
    ],
]);
~~~

### 简单使用
~~~php
use \axguowen\facade\GatewayClient;

// GatewayClient支持GatewayWorker中的所有接口(closeCurrentClient和sendToCurrentClient除外)
GatewayClient::sendToAll('{"message": "hello gateway!"}');
~~~

### 切换连接其它主机
~~~php
use \axguowen\facade\GatewayClient;
// 连接其它服务器
$gatewayClientOther = GatewayClient::connect('other');
$gatewayClientOther->sendToAll('{"message": "hello gateway!"}');
~~~

### 动态传入连接的主机参数
~~~php
use \axguowen\facade\GatewayClient;
// 动态连接
$gatewayClient = GatewayClient::connect([
    // Gateway注册服务地址
    'register_address' => '192.168.0.90:1236',
    // 密钥, 为对应Register服务设置的密钥
    'secret_key' => '',
    // 连接超时时间，单位：秒
    'connect_timeout' => 3,
    // 与Gateway是否是长链接
    'persistent_connection' => false,
    // 禁用服务注册地址缓存
    'addresses_cache_disable' => false,
]);
$gatewayClient->sendToAll('{"message": "hello gateway!"}');
~~~

### 其它方法
~~~php
use \axguowen\facade\GatewayClient;
$data = '{"message": "hello gateway!"}';
GatewayClient::sendToAll($data);
GatewayClient::sendToClient($client_id, $data);
GatewayClient::closeClient($client_id);
GatewayClient::isOnline($client_id);
GatewayClient::bindUid($client_id, $uid);
GatewayClient::isUidOnline($uid);
GatewayClient::isUidsOnline($uids);
GatewayClient::getClientIdByUid($uid);
GatewayClient::unbindUid($client_id, $uid);
GatewayClient::sendToUid($uid, $dat);
GatewayClient::joinGroup($client_id, $group);
GatewayClient::sendToGroup($group, $data);
GatewayClient::leaveGroup($client_id, $group);
GatewayClient::getClientCountByGroup($group);
GatewayClient::getClientSessionsByGroup($group);
GatewayClient::getAllClientCount();
GatewayClient::getAllClientSessions();
GatewayClient::setSession($client_id, $session);
GatewayClient::updateSession($client_id, $session);
GatewayClient::getSession($client_id);
~~~