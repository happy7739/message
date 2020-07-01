<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------

namespace app\index\controller;

use think\Request;
use app\admin\model\Tidings as TidingsModel;

/**
 * 留言添加控制器
 * @package app\index\controller
 */
class Insert extends Home
{
    public function index(Request $request)
    {
        if($request->isPost()) {
            $param = $request->post();
            $param['ip'] = $_SERVER["REMOTE_ADDR"];
            $param = array(
                'pid'           => 0,
                'device'        => '1',
                'where_ip'      => 2130706432,
                'name'          => '2',
                'mobile'        => '3',
                'address'       => '4',
                'wechat'        => '5',
                'content'       => '6',
                'status'        => 4,
                'read_time'     => '1589261811',
                'url'           => '7',
                'other'         => '8',
                'test'          => 123
            );
            if(TidingsModel::create($param)){
                return json(['code'=>1,'msg'=>'申请成功']);
            }else{
                return  json(['code'=>0,'msg'=>'申请失败']);
            }
        }
    }
}
