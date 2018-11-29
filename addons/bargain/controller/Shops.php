<?php
namespace addons\bargain\controller;

use think\addons\Controller;
use addons\bargain\model\Shops as M;
/**
 * 全民砍价插件-商家端
 */
class Shops extends Controller{
    /**
     * 跳去商家砍价商品列表页
     */
    public function index(){
        return $this->fetch("/home/shops/list");
    }
    /**
     * 查看砍价商品列表
     */
    public function pageQuery(){
         $m = new M();
         return $m->pageQuery();
    }

    /**
     * 新增活动商品
     */
    public function edit(){
        $id = (int)input('id');
        $object = [];
        $m = new M();
        if($id>0){
            $object = $m->getById($id);
        }else{
            $object = $m->getEModel('bargains');
            $object['goodsName'] = '请选择活动商品';
            $object['startTime'] = date('Y-m-d H:00:00',strtotime("+2 hours"));
            $object['endTime'] = date('Y-m-d H:00:00',strtotime("+1 month"));
        }
        $this->assign("object",$object);
        return $this->fetch("/home/shops/edit");
    }
    /**
     * 查询商品
     */
    public function searchGoods(){
        $m = new M();
        return $m->searchGoods();
    }

    /**
     * 保存拍卖信息
     */
    public function toEdit(){
        $id = (int)input('post.bargainId');
        $m = new M();
        if($id==0){
            return $m->add();
        }else{
            return $m->edit();
        }
    }

    /**
     * 删除拍卖
     */
    public function del(){
        $m = new M();
        return $m->del();
    }

    /**
     * 获取参与者记录
     */
    public function joins(){
        $this->assign("bargainId",input('bargainId/d'));
        return $this->fetch("/home/shops/list_users");
    }
    /**
     *  获取参与者记录
     */
    public function pageByJoins(){
       $m = new M();
       return $m->pageByJoins();
    }
    /**
     * 查看帮助砍价人
     */
    public function showHelps(){
        $this->assign("bargainId",input('bargainId/d'));
        $this->assign("bargainJoinId",input('bargainJoinId/d'));
        return $this->fetch("/home/shops/list_helps");
    }
    public function pageByHelps(){
        $m = new M();
        return $m->pageByHelps();
    }
    /**
     * 查看订单数
     */
    public function orders(){
        $this->assign("bargainId",input('bargainId/d'));
        return $this->fetch("/home/shops/list_orders");
    }
    public function pageByOrders(){
        $m = new M();
        return $m->pageByOrders();
    }
}