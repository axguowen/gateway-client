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

namespace axguowen\facade;

use axguowen\Facade;

/**
 * @see \axguowen\GatewayClient
 * @mixin \axguowen\GatewayClient
 */
class GatewayClient extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'axguowen\GatewayClient';
    }
}
