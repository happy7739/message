<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------

namespace app\admin\validate;

use think\Validate;

/**
 * 表格验证器
 * @package app\admin\validate
 * @author 蔡伟明 <314013107@qq.com>
 */
class Table extends Validate
{
    //定义验证规则
    protected $rule = [
        'sort|排序'       => 'require|number',
        'title|标题'      => 'require',
    ];

    //定义验证提示
    protected $message = [
        'sort.require'   => '不能为空',
        'sort.number'    => '只能是数字',
        'title.require'  => '不能为空',
    ];

    // 定义场景，供快捷编辑时验证
    protected $scene = [
        'sort'  => ['sort'],
        'title' => ['title'],
    ];
}
