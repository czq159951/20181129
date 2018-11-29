<?php
namespace addons\decoration\controller;

use think\addons\Controller;
use addons\decoration\model\Decoration as M;

class Decoration extends Controller{
	
	public function __construct(){
		parent::__construct();
		$m = new M();
		$data = $m->getConf('Decoration');
		$this->assign("seoDecorationKeywords",$data['seoDecorationKeywords']);
		$this->assign("seoDecorationDesc",$data['seoDecorationDesc']);
	}

	/**
     * 店铺装修设置
     */
    public function setting() {
        $m = new M();
       	$shopId = (int)session('WST_USER.shopId');
        $decorationInfo = $m->getDecorationInfo(array('shopId' => $shopId));
        if(empty($decorationInfo)) {
            //创建默认装修
            $param = array();
            $param['decorationName'] = '默认装修';
            $param['shopId'] = (int)session('WST_USER.shopId');
            $decorationId = $m->addDecoration($param);
        } else {
            $decorationId = $decorationInfo['decorationId'];
        }
        $conf = $m->getShopConf($shopId);
        $this->assign('userDecoration', (int)$conf["userDecoration"]);
        $this->assign('decorationId', $decorationId);
       	return $this->fetch('/home/shops/shop_set');
    }

    /**
     * 店铺装修设置保存
     */
    public function settingSave() {
        $m = new M();
        $obj = array();
        $obj['userDecoration'] = (int)input("userDecoration");
        $rs = $m->decorationSettingSave($obj, array('shopId' => (int)session('WST_USER.shopId')));
        if($rs["status"]==1) {
            return WSTReturn("保存成功",1);
        } else {
            return WSTReturn("保存失败",-1);
        }
    }

    /**
     * 店铺装修
     */
    public function edit() {
        $decorationId = (int)input("id");
        $this->assign('decorationId', $decorationId);
        $m = new M();
        $s = model('home/shops');
        $shopId = (int)session('WST_USER.shopId');
        $data['shop'] = $s->getShopInfo($shopId);
        $data['shopcats'] = model('home/ShopCats','model')->getShopCats($shopId);
        $decoration_info = $m->getDecorationInfoDetail($decorationId, $shopId);
        if($decoration_info) {
            $this->getDecorationInfo($decoration_info);
        } else {
        	$this->redirect('home/error/message');
        }
        $this->assign('data', $data);
        return $this->fetch('/home/shops/shop_home');
    }

    /**
     * 输出装修设置
     */
    private function getDecorationInfo($decoration_info) {
        $m = new M();
        $decoration_background_style = $m->getDecorationBackgroundStyle($decoration_info['decoration_setting']);
        $this->assign('decoration_background_style', $decoration_background_style);
        $this->assign('decoration_nav', $decoration_info['decoration_nav']);
        $this->assign('decoration_banner', $decoration_info['decoration_banner']);
        $this->assign('decoration_setting', $decoration_info['decoration_setting']);
        $this->assign('block_list', $decoration_info['block_list']);
    }

    /**
     * 保存店铺装修背景设置
     */
    public function bgSettingSave() {
        $decorationId = (int)input("id");
        if($decorationId <= 0) {
            return WSTReturn("保存失败",-1);
        }
        $setting = array();
        $setting['background_color'] = input("background_color");
        $setting['background_image_url'] = input("background_image_url");
        $setting['background_image_repeat'] = input("background_image_repeat");
        $setting['background_position_x'] = input("background_position_x");
        $setting['background_position_y'] = input("background_position_y");
        $setting['background_attachment'] = input("background_attachment");
        //背景设置保存验证
        $validate_setting = $this->checkBgSetting($decorationId, $setting);
        if(isset($validate_setting['error'])) {
            return WSTReturn("保存失败",-1);
        }
        $data = array();
        $m = new M();
        $condition = array();
        $condition['decorationId'] = $decorationId;
        $condition['shopId'] = (int)session('WST_USER.shopId');
        $update = array();
        $update['decorationSetting'] = serialize($setting);
        $rs = $m->editDecoration($update, $condition);
        if($rs["status"]==1) {
            $data['decoration_background_style'] = $m->getDecorationBackgroundStyle($validate_setting);
            return WSTReturn("保存成功",1,$data);
        } else {
            return WSTReturn("保存失败",-1);
        }
    }

    /**
     * 背景设置保存验证
     */
    private function checkBgSetting($decorationId, $setting) {
        if(!empty($setting['background_color'])) {
            if(strlen($setting['background_color']) > 7) {
                return array('msg', '请输入正确的背景颜色');
            }
        } else {
            $setting['background_color'] = '';
        }
        if(!empty($setting['background_image_url'])) {
            if($setting['background_image_url'] == '') {
                return array('msg', '请选择正确的背景图片');
            }
        } else {
           	$setting['background_image_url'] = '';
        }
        if(!in_array($setting['background_image_repeat'], array('no-repeat', 'repeat', 'repeat-x', 'repeat-y'))) {
            $setting['background_image_repeat'] = '';
        }
        if(strlen($setting['background_position_x']) > 8) {
            $setting['background_position_x'] = '';
        }
        if(strlen($setting['background_position_y']) > 8) {
            $setting['background_position_y'] = '';
        }
        if(strlen($setting['background_attachment']) > 8) {
            $setting['background_attachment'] = '';
        }
        return $setting;
    }

    /**
     * 装修导航保存
     */
    public function navSave() {
        $decorationId = (int)input("id");
        $nav = array();
        $nav['style'] = $_POST['content'];
        $data = array();
        if($decorationId <= 0) {
            return WSTReturn("保存失败",1);
        }
        $m = new M();
        $condition = array();
        $condition['decorationId'] = $decorationId;
        $condition['shopId'] = (int)session('WST_USER.shopId');

        $update = array();
        $update['decorationNav'] = serialize($nav);

        $rs = $m->editDecoration($update, $condition);
        if($rs["status"]==1) {
            return WSTReturn("保存成功",1);
        } else {
            return WSTReturn("保存失败",-1);
        }
    }

    /**
     * 装修banner保存
     */
    public function bannerSave() {
        $decorationId = (int)input("id");
        $banner = array();
        $banner['display'] = $_POST['banner_display'];
        $banner['image'] = $_POST['content'];
        $data = array();
        if($decorationId <= 0) {
            return WSTReturn("保存失败",-1);
        }
        $m = new M();
        $condition = array();
        $condition['decorationId'] = $decorationId;
        $condition['shopId'] = (int)session('WST_USER.shopId');

        $update = array();
        $update['decoration_banner'] = serialize($banner);

        $rs = $m->editDecoration($update, $condition);
        if($rs["status"]==1) {
            $data['image_url'] = getDecorationImageUrl($banner['image'], (int)session('WST_USER.shopId'));
            return WSTReturn("保存成功",1,$data);
        } else {
            return WSTReturn("保存失败",-1);
        }
    }

    /**
     * 装修添加块
     */
    public function blockAdd() {
        $decorationId = (int)input("id");
        $block_layout = input("block_layout");
        $data = array();
        $m = new M();
        //验证装修编号
        $condition = array();
        $condition['decorationId'] = $decorationId;
        $decoration_info = $m->getDecorationInfo($condition, (int)session('WST_USER.shopId'));
        if(!$decoration_info) {
            return WSTReturn("添加失败",-1);
        }
        //验证装修块布局
        $block_layout_array = $m->getDecorationBlockLayoutArray();
        if(!in_array($block_layout, $block_layout_array)) {
            return WSTReturn("添加失败",-1);
        }
        $param = array();
        $param['decorationId'] = $decorationId;
        $param['shopId'] = (int)session('WST_USER.shopId');
        $param['blockLayout'] = $block_layout;
        $blockId = $m->addDecorationBlock($param);
        if($blockId) {
            $this->assign('block', array('blockId' => $blockId));
            $temp = $this->fetch('/home/shops/shop_decoration_block');
            $data['html'] = $temp;
            return WSTReturn("添加成功",1,$data);
        } else {
            return WSTReturn("添加失败",-1);
        }
    }

    /**
     * 装修块删除
     */
    public function blockDel() {
        $blockId = (int)input("block_id");
        $m = new M();
        $condition = array();
        $condition['blockId'] = $blockId;
        $condition['shopId'] = (int)session('WST_USER.shopId');
        $rs = $m->delDecorationBlock($condition);
        if($rs["status"]==1) {
            return WSTReturn("删除成功",1);
        } else {
            return WSTReturn("删除失败",-1);
        }
    }

    /**
     * 装修块保存
     */
    public function blockSave() {
        $blockId = (int)input("block_id");
        $module_type = input("module_type");
        $data = array();
        $m = new M();
        //验证模块类型
        $block_type_array = $m->getDecorationBlockTypeArray();
        if(!in_array($module_type, $block_type_array)) {
        	return WSTReturn("保存失败",-1);
        }
        switch ($module_type) {
            case 'html':
                $content = $_POST['content'];
                break;
            default:
                $content = serialize($_POST['content']);
        }
        $condition = array();
        $condition['blockId'] = $blockId;
        $condition['shopId'] = (int)session('WST_USER.shopId');
        $param = array();
        $param['blockContent'] = $content;
        $param['blockFullWidth'] = intval(input('full_width'));
        $param['blockModuleType'] = $module_type;
        $rs = $m->editDecorationBlock($param, $condition);
        if($rs["status"]==1) {
            $data = array();
            $data['html'] = $this->getBlockHtml($content, $module_type);
            return WSTReturn("保存成功",1,$data);
        } else {
            return WSTReturn("保存失败",-1);
        }
    }

    /**
     * 装修块排序
     */
    public function blockSort() {
        $sort_array = explode(',', rtrim($_POST['sort_string'], ','));
        $m = new M();
        $condition = array();
        $condition['shopId'] = (int)session('WST_USER.shopId');
        $sort = 1;
        foreach ($sort_array as $value) {
            $condition['blockId'] = $value;
            $m->editDecorationBlock(array('blockSort' => $sort), $condition);
            $sort = $sort + 1;
        }
        return WSTReturn("保存成功",1);
    }

    /**
     * 获取页面
     */
    private function getBlockHtml($content, $module_type) {
        ob_start();
        $this->assign('block_content', $content);
        $temp = $this->fetch('/home/shops/shop_decoration_module_' . $module_type);
        return $temp;
    }

    /**
     * 商品搜索
     */
    public function goodsSearch() {
        $m = new M();
        $goods_list = $m->searchGoods();
        $this->assign('gdata', $goods_list);
       	$goods= $this->fetch('/home/shops/shop_decoration_module_goods');
       	echo $goods;
    }

    /**
     * 更新商品模块的商品价格
     */
    private function updateGoodsInfo($decorationId, $shopId,$rootPath) {
        $m = new M();
        $condition = array();
        $condition['decorationId'] = $decorationId;
        $condition['blockModuleType'] = 'goods';
        $condition['shopId'] = $shopId;
        $block_list_goods = $m->getDecorationBlockList($condition);
        if(!empty($block_list_goods) && is_array($block_list_goods)) {
            foreach ($block_list_goods as $block) {
                $goodslist = unserialize($block['blockContent']);
                foreach ($goodslist as $gkey => $goods) {
                    //商品信息
                    $goods_info = $m->getGoodsInfo($goods['goodsId']);
                    $new_goods_price = $goods_info['shopPrice'];
                    $new_goods_img = $rootPath."/".$goods_info['goodsImg'];
                    $new_goods_name = $goods_info['goodsName'];
                    $goodslist[$gkey]['shopPrice'] = $new_goods_price;
                    $goodslist[$gkey]['goodsImg'] = $new_goods_img;
                    $goodslist[$gkey]['goodsName'] = $new_goods_name;
                }
                //更新块数据
                $update = array();
                $update['blockContent'] = serialize($goodslist);
                $m->editDecorationBlock($update, array('blockId' => $block['blockId']));
            }
        }
    }

    /**
     * 装修预览
     */
    public function preview() {
        $decorationId = (int)input("id");
        $m = new M();
        $decoration_info = $m->getDecorationInfoDetail($decorationId, (int)session('WST_USER.shopId'));
        if($decoration_info) {
            $this->getDecorationInfo($decoration_info);
        } else {
            $this->redirect('home/error/message');
        }

        $s = model('home/shops');
        $shopId = (int)session('WST_USER.shopId');
        $data['shop'] = $s->getShopInfo($shopId);
        
        $ct1 = input("param.ct1/d",0);
        $ct2 = input("param.ct2/d",0);
        $goodsName = input("param.goodsName");
       
        if(empty($data['shop']))return $this->fetch('error_lost');
        $data['shopcats'] = model('home/ShopCats','model')->getShopCats($shopId);
        $g = model('home/goods');
        $data['list'] = $g->shopGoods($shopId);
        $this->assign('msort',input("param.msort/d",0));//筛选条件
        $this->assign('mdesc',input("param.mdesc/d",1));//升降序
        $this->assign('sprice',input("param.sprice"));//价格范围
        $this->assign('eprice',input("param.eprice"));
        $this->assign('ct1',$ct1);//一级分类
        $this->assign('ct2',$ct2);//二级分类
        $this->assign('goodsName',urldecode($goodsName));//搜索
        $this->assign('data',$data);

		return $this->fetch('/home/shops/shop_decoration_preview');
    }

    /**
     * 装修静态文件生成
     */
    public function build() {
        //静态文件路径
        $html_path = WSTRootPath()."/addons/decoration/shoptpl/";
        if(!is_dir($html_path)){
            if (!@mkdir($html_path, 0755)){
                return WSTReturn("页面生成失败",-1);
            }
        }

        $decorationId = (int)input("id");
        $rootPath = input("rootPath");
        //更新商品数据
        $this->updateGoodsInfo($decorationId, (int)session('WST_USER.shopId'),$rootPath);

        $m = new M();
        $shopId = (int)session('WST_USER.shopId');
        $decoration_info = $m->getDecorationInfoDetail($decorationId, $shopId);
        if($decoration_info) {
            $this->getDecorationInfo($decoration_info);
        } else {
            return WSTReturn("页面失败",-1);
        }
        $file_name = md5((int)session('WST_USER.shopId'));
        $s = model('home/shops');
        $data['shop'] = $s->getShopInfo($shopId);
        
        $ct1 = input("param.ct1/d",0);
        $ct2 = input("param.ct2/d",0);
        $goodsName = input("param.goodsName");
         
        if(empty($data['shop']))return $this->fetch('error_lost');
        $data['shopcats'] = model('home/ShopCats','model')->getShopCats($shopId);
        $g = model('home/goods');
        $data['list'] = $g->shopGoods($shopId);
        $this->assign('msort',input("param.msort/d",0));//筛选条件
        $this->assign('mdesc',input("param.mdesc/d",1));//升降序
        $this->assign('sprice',input("param.sprice"));//价格范围
        $this->assign('eprice',input("param.eprice"));
        $this->assign('ct1',$ct1);//一级分类
        $this->assign('ct2',$ct2);//二级分类
        $this->assign('goodsName',urldecode($goodsName));//搜索
        $this->assign('data',$data);
		$temp = $this->fetch('/home/shops/shop_decoration_preview');
        $start = stripos($temp,'<div class="wst-header">');
        $sphtml = '<div id="header-split" style="display:none">';
        $len = stripos($temp,$sphtml)-$start;
        $php = '{include file="../../shangtao/home/view/default/top" /}';
        $temp = substr_replace($temp, $php,$start,$len);

		//替换店铺分类
		$html  = '<li class="liselect wst-lfloat wst-nav-boxa">店铺首页</li></a>';
		$a = stripos($temp,$html)+strlen($html);
		$b = stripos($temp,'<a class="homepage"')-$a;
		$php = '';
		$php .='{wst:shopscats cat="0" num="10" shop="'.$shopId.'" id="ca"}';
		$php .='<a href="{:url("home/shops/cat",array("shopId"=>$ca["shopId"],"ct1"=>$ca["catId"]))}"><li class="liselect wst-lfloat">{$ca["catName"]}</li></a>';
		$php .='{/wst:shopscats}';
		$temps = substr_replace($temp, $php,$a,$b);
        $rs = file_put_contents($html_path . $file_name . '.html', $temps);
        if($rs) {
            return WSTReturn("页面生成成功",1);
        } else {
            return WSTReturn("页面生成失败",-1);
        }
    }
}