<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\builder\ZBuilder;
use app\admin\model\Tidings as TidingsModel;
use think\Db;

/**
 * 消息管理控制器
 * @package app\admin\controller
 */
class Tidings extends Admin
{
    /**
     * 消息中心
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function index()
    {
        //TidingsModel::where(['status'=>0])->update(['read_time'=>0]);
        // 查询
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder('id DESC');
        // 数据列表
        $data_list = TidingsModel::where($map)->order($order)->paginate();

        //分页数组
        $nav = Db::table("dp_project")->order('sort')->field(['id','name'])->select();
        foreach ($nav as $key => $val){
            $nav_data[$val['id']] = $val['name'];
        }
        $btn_access = [
            'title' => '详情',
            'icon'  => 'fa fa-fw fa-file-text-o',
            'href'  => url('details', ['id' => '__id__'])
        ];

        return ZBuilder::make('table')
            ->setTableName('tidings')
            //->addTopButton('enable', ['title' => '设置已阅读'])
            ->setSearchArea([
                ['select', 'pid', '所属项目', '', '', $nav_data],
            ])
            ->setSearch(['mobile' => '电话号码'],'','',true) // 设置搜索框
            ->addTimeFilter('create_time', '', ['开始申请时间', '结束申请时间'])
            ->addTopButton('delete')
            ->addRightButton('enable', ['title' => '设置已阅读'])
            ->addRightButton('details', $btn_access)
            ->addRightButton('delete')
            ->replaceRightButton(['status' => ['in', '1']], '', ['enable'])
            ->addColumns([
                ['id', 'ID'],
                ['pid', '项目名称', '', '', $nav_data],
                ['name', '姓名'],
                ['mobile', '电话号码'],
                ['status', '状态', 'status', '', ['未读', '已读']],
                ['create_time', '申请时间', 'datetime'],
                ['read_time', '阅读时间', 'datetime'],
                ['right_button', '操作', 'btn'],
            ])
            ->addFilter('status', ['未读', '已读'])
            ->addOrder('id,create_time,read_time')
            ->setRowList($data_list)
            ->css('custom')//引入自定义的css文件
            ->fetch();
    }

    /**
     * 设置已阅读
     * @param array $ids
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function enable($ids = [])
    {
        empty($ids) && $this->error('参数错误');
        $map = [
            ['id', 'in', $ids]
        ];
        $result = TidingsModel::where($map)
            ->update(['status' => 1, 'read_time' => $this->request->time()]);
        if (false !== $result) {
            $this->success('设置成功');
        } else {
            $this->error('设置失败');
        }
    }

    /**查看详情
     * @param $id
     */
    public function details($id){
        $info = TidingsModel::get($id);
        if($info['status']){
            $class = '';
        }else{
            $class = 'hidden';
        }
        $info['ip'] = long2ip($info['where_ip']);
        $pro = Db::table("dp_project")->where(['id'=>$info['pid']])->field(['name'])->find();
        $info['p_name'] = $pro['name'];
        // 显示页面
        return ZBuilder::make('form')
            ->addFormItems([
                ['text', 'id', 'ID'],
                ['text', 'p_name', '项目名称'],
                ['text', 'name', '姓名'],
                ['text', 'mobile', '电话'],
                ['text', 'wechat', '微信[: ]'],
                ['text', 'email', '邮箱[: ]'],
                ['text', 'address', '地址[: ]'],
                ['text', 'device', '留言设备'],
                ['textarea', 'content', '消息内容[: ]'],
                ['textarea', 'other', '其他[: ]'],
                ['text', 'ip', 'IP地址'],
                ['text', 'url', '超链接'],
                ['radio', 'status', '状态', '', ['未读', '已读']]
            ])
            ->addTime('create_time', '申请时间','','','YYYY-MM-DD HH:mm:ss')
            ->addTime('read_time', '阅读时间', '', '','YYYY-MM-DD HH:mm:ss','',$class)
            ->hideBtn('submit')
            ->setFormdata($info)
            ->fetch();
    }
}