<?php
// +----------------------------------------------------------------------
// | GatewayClient [Simple Gateway Client For PHP]
// +----------------------------------------------------------------------
// | Gateway客户端
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: axguowen <axguowen@qq.com>
// +----------------------------------------------------------------------

namespace axguowen;

use axguowen\gatewayclient\Connection;

/**
 * Gateway客户端类
 */
class GatewayClient
{
    /**
     * 连接实例
     * @var array
     */
    protected $instance = [];

    /**
     * 配置
     * @var array
     */
    protected $config = [
        // 默认连接, 默认为连接配置里面的localhost
        'default' => 'localhost',
        // 连接配置
        'connections' => [],
    ];

    /**
     * 架构方法
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct($options = [])
    {
        if(!empty($options)){
            // 合并配置
            $this->config = array_merge($this->config, $options);
        }
    }

    /**
     * 初始化配置参数
     * @access public
     * @param array $config 连接配置
     * @return void
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * 获取配置参数
     * @access public
     * @param string $name 配置参数
     * @param mixed $default 默认值
     * @return mixed
     */
    public function getConfig($name = '', $default = null)
    {
        if ('' === $name) {
            return $this->config;
        }

        return isset($this->config[$name]) ? $this->config[$name] : $default;
    }

    /**
     * 创建/切换连接
     * @access public
     * @param string|array|null $connection 连接配置标识
     * @return Connection
     */
    public function connect($connection = null)
    {
        // 如果是数组
        if(is_array($connection)){
            // 连接参数
            $options = array_merge([
                // Gateway注册服务地址
                'register_address' => '',
                // 密钥, 为对应Register服务设置的密钥
                'secret_key' => '',
                // 连接超时时间，单位：秒
                'connect_timeout' => 3,
                // 与Gateway是否是长链接
                'persistent_connection' => false,
                // 禁用服务注册地址缓存
                'addresses_cache_disable' => false,
            ], $connection);
            // 连接标识
            $name = hash('md5', json_encode($options));
            // 连接不存在
            if (!isset($this->instance[$name])) {
                // 创建连接
                $this->instance[$name] = $this->createConnection($options);
            }

            return $this->instance[$name];
        }
        
        // 标识为空
        if (empty($connection)) {
            $connection = $this->getConfig('default', 'localhost');
        }
        // 连接不存在
        if (!isset($this->instance[$connection])) {
            // 获取配置中的全部连接配置
            $connections = $this->getConfig('connections');
            // 配置不存在
            if (!isset($connections[$connection])) {
                throw new \Exception('Undefined gatewayclient connections config:' . $connection);
            }
            // 创建链接
            $this->instance[$connection] = $this->createConnection($connections[$connection]);
        }
        
        // 返回已存在连接实例
        return $this->instance[$connection];
    }

    /**
     * 创建连接
     * @access protected
     * @param array $options
     * @return Connection
     */
    protected function createConnection($options)
    {
        // 实例化连接类
        $connection = new Connection($options);
        // 设置当前客户端对象实例并返回
        return $connection->setClient($this);
    }
    
    /**
     * 获取所有连接实列
     * @access public
     * @return array
     */
    public function getInstance()
    {
        return $this->instance;
    }

    public function __call($method, array $args)
    {
        return call_user_func_array([$this->connect(), $method], $args);
    }
}
