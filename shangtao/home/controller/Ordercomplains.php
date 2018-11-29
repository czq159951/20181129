<?php
namespace shangtao\home\controller;
/**
 * 订单投诉控制器
 */
class OrderComplains extends Base{
    protected $beforeActionList = [
       'checkAuth'=>['only'=>'index,queryusercomplainbypage,complain,savecomplain,getusercomplaindetail'],
       'checkShopAuth'=>['only'=>'shopcomplain,queryshopcomplainbypage,getshopcomplaindetail,respond,saverespond']
    ];
    /******************************** 用户 ******************************************/
    /**
    * 查看投诉列表
    */
	public function index(){
		return $this->fetch('users/orders/list_complain');
	}
    /**
    * 获取用户投诉列表
    */    
    public function queryUserComplainByPage(){
        $m = model('OrderComplains');
        return $m->queryUserComplainByPage();
        
    }
    /**
     * 订单投诉页面
     */
    public function complain(){
        $data = model('OrderComplains')->getOrderInfo();
        $this->assign("data",$data);
        return $this->fetch("users/orders/complain");
    }
    /**
     * 保存订单投诉信息
     */
    public function saveComplain(){
        return model('OrderComplains')->saveComplain();
    }
    /**
     * 用户查投诉详情
     */
    public function getUserComplainDetail(){
        $data = model('OrderComplains')->getComplainDetail(0);
        $this->assign("data",$data);
        return $this->fetch("users/orders/complain_detail");
    }


    /******************************* 商家  ****************************************/
    /**
    * 商家-查看投诉列表
    */
    public function shopComplain(){
        return $this->fetch("shops/orders/list_complain");
    }

    /**
     * 获取商家被投诉订单列表
     */
    public function queryShopComplainByPage(){
        return model('OrderComplains')->queryShopComplainByPage();
    }

    /**
     * 查投诉详情
     */
    public function getShopComplainDetail(){
        $data = model('OrderComplains')->getComplainDetail(1);
        $this->assign("data",$data);
        return $this->fetch("shops/orders/complain_detail");
    }

     /**
     * 订单应诉页面
     */
    public function respond(){
        $data = model('OrderComplains')->getComplainDetail(1);
        $this->assign("data",$data);
        return $this->fetch("shops/orders/respond");
    }
    /**
     * 保存订单应诉
     */
    public function saveRespond(){
        return model('OrderComplains')->saveRespond();
    }


    


}
