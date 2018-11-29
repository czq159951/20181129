<?php
namespace addons\integral\controller;

use think\addons\Controller;
use addons\integral\model\Integrals as M;
/**
 * 积分商城插件
 */
class Apis extends Controller{
    /**
    * APP请求检测是否有安装插件
    */
    public function index(){
        return json_encode(['status'=>1]);
    }
    /**
     * 域名
     */
    public function domain(){
        return url('/','','',true);
    }
    /**
     * 积分商品列表查询
     */
    public function integralListQuery(){
        $userId = model('app/index')->getUserId();
        $userScore = 0;// 用户积分
        $userMoney = 0;
        if($userId>0){
            $user = model('common/users')->getFieldsById($userId,["userScore","userMoney","userId"]);
            $userScore = $user['userScore'];
            $userMoney = $user['userMoney'];
        }
        $m = new M();
        $rs = $m->pageQuery();
        if(!empty($rs['data'])){
            foreach ($rs['data'] as $key =>$v){
                $rs['data'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
            }
        }
        // 域名
        $rs['domain'] = $this->domain();
        // 用户积分
        $rs['userScore'] = $userScore;
        $rs['userMoney'] = $userMoney;
        return json_encode(WSTReturn('ok',1,$rs));
    }
    /**
    * 积分商品详情
    */
    public function getIntegralDetail(){
        $m = new M();
        $userId = model('app/index')->getUserId();
        $id = input('id/d',0);
        $goods = $m->getBySale($id,$userId);
        // 找不到商品记录
        if(empty($goods))return json_encode(WSTReturn('未找到商品记录',-1));
        // 删除无用字段
        WSTUnset($goods,'goodsSn,goodsDesc,productNo,isSale,isBest,isHot,isNew,isRecom,goodsCatIdPath,goodsCatId,shopCatId1,shopCatId2,brandId,goodsStatus,saleTime,goodsSeoKeywords,illegalRemarks,dataFlag,createTime,read');

        $goods['domain'] = $this->domain();
        // 猜你喜欢6件商品
        $like = model('common/Tags')->listByGoods('best',$goods['shop']['catId'],6);
        foreach($like as $k=>$v){
            // 删除无用字段
            unset($like[$k]['shopName']);
            unset($like[$k]['shopId']);
            unset($like[$k]['goodsSn']);
            unset($like[$k]['goodsStock']);
            unset($like[$k]['saleNum']);
            unset($like[$k]['marketPrice']);
            unset($like[$k]['isSpec']);
            unset($like[$k]['appraiseNum']);
            unset($like[$k]['visitNum']);
            // 替换商品图片
            $like[$k]['goodsImg'] = WSTImg($v['goodsImg'],3);
        }
        $goods['like'] = $like;
        return json_encode(WSTReturn('请求成功',1,$goods));
    }
    /******************************************************************* 结算页面start ****************************************************************************/
    /**
     * 下单
     * bayNum:
     * id:积分商品Id
     * tokenId:
     */
    public function addCart(){
        $userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
        $m = new M();
        return json_encode($m->addCart($userId));
    }
    /**
     * 计算运费、积分和总商品价格
     */
    public function getCartMoney(){
        $userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
        $m = new M();
        $data = $m->getCartMoney($userId);
        return json_encode($data);
    }

    /**
     * 提交订单
     */
    public function submit(){
        $userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
        $m = new M();
        $orderSrc = input('orderSrc');
        $orderSrcArr = ['android'=>3,'ios'=>4];
        if(!isset($orderSrcArr[$orderSrc])){
            return json_encode(WSTReturn('非法订单来源~',-1));
        }
        $orderSrc = $orderSrcArr[$orderSrc];
        $rs = $m->submit($orderSrc,$userId);
        return json_encode($rs);
    }
    /**
     * 结算页面
     */
    public function settlement(){
        $CARTS = session('INTEGRAL_CARTS');
        if(empty($CARTS)){
            return json_encode(WSTReturn('暂无积分商品结算~',-1));
            exit;
        }
        $userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
        $m = new M();
        $carts = $m->getCarts();
        //获取一个用户地址
        $addressId = (int)input('addressId');
        $ua = model('common/userAddress');
        if($addressId>0){
            $userAddress = $ua->getById($addressId,$userId);
        }else{
            $userAddress = $ua->getDefaultAddress($userId);
        }
        $carts['userAddress'] = $userAddress;
        //获取用户积分
        $user = model('common/users')->getFieldsById($userId,'userScore');
        //计算可用积分和金额
        $goodsTotalMoney = $carts['goodsTotalMoney'];
        $goodsTotalScore = WSTScoreToMoney($goodsTotalMoney,true);
        $useOrderScore =0;
        $useOrderMoney = 0;
        if($user['userScore']>$goodsTotalScore){
            $useOrderScore = $goodsTotalScore;
            $useOrderMoney = $goodsTotalMoney;
        }else{
            $useOrderScore = $user['userScore'];
            $useOrderMoney = WSTScoreToMoney($useOrderScore);
        }

        $carts['userOrderScore'] = $useOrderScore;
        $carts['userOrderMoney'] = $useOrderMoney;
        $carts['domain'] = $this->domain();
        //获取支付方式
        $payments = model('common/payments')->getByGroup('4',1, true);
        $carts['payments'] = $payments;
        return json_encode(WSTReturn('ok',1,$carts));
    }





    /******************************************************************* 结算页面end ****************************************************************************/
}