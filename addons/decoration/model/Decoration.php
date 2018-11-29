<?php
namespace addons\decoration\model;
use think\addons\BaseModel as Base;
use shangtao\common\model\GoodsCats;
use think\Db;
/**
 * 拍卖活动插件
 */
class Decoration extends Base{
	/***
     * 安装插件
     */
    public function installMenu(){
    	Db::startTrans();
		try{
			$hooks = ['homeBeforeGoShopHome','homeBeforeGoSelfShop'];
			$this->bindHoods("Decoration", $hooks);
			$now = date("Y-m-d H:i:s");
			//商家中心
			Db::name('home_menus')->insert(["parentId"=>38,"menuName"=>"店铺装修","menuUrl"=>"addon/decoration-decoration-setting","menuOtherUrl"=>"addon/decoration-decoration-build,addon/decoration-decoration-settingsave,addon/decoration-decoration-edit,addon/decoration-decoration-bgsettingsave,addon/decoration-decoration-navsave,addon/decoration-decoration-blockadd,addon/decoration-decoration-blocksort,addon/decoration-decoration-blocksave,addon/decoration-decoration-blockdel,addon/decoration-decoration-goodssearch","menuType"=>1,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"decoration"]);
			installSql("decoration");
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
    }

	/**
	 * 删除菜单
	 */
	public function uninstallMenu(){
		Db::startTrans();
		try{
			$hooks = ['homeBeforeGoShopHome','homeBeforeGoSelfShop'];
			$this->unbindHoods("Decoration", $hooks);
			Db::name('menus')->where("menuMark",'=',"decoration")->delete();
			Db::name('home_menus')->where("menuMark",'=',"decoration")->delete();
			uninstallSql("decoration");//传入插件名
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
	}

	
	/**
	 * 菜单显示隐藏
	 */
	public function toggleShow($isShow = 1){
		Db::startTrans();
		try{
			Db::name('menus')->where("menuMark",'=',"decoration")->update(["isShow"=>$isShow]);
			Db::name('home_menus')->where("menuMark",'=',"decoration")->update(["isShow"=>$isShow]);
			Db::commit();
			return true;
		}catch (\Exception $e) {
			Db::rollback();
			return false;
		}
	}
	
	//装修块布局数组
	private $block_layout_array = array('block_1');
	
	//装修块类型数组
	private $block_type_array = array('html', 'slide', 'hot_area', 'goods');
	
	/**
	 * 列表
	 * @param array $where 查询条件
	 * @return array
	 */
	public function getDecorationList($where) {
		$list = Db::name('shop_decorations')->where($where)->select();
		return $list;
	}
	
	/**
	 * 查询基本数据
	 * @param array $where 查询条件
	 * @param int $shopId 店铺编号
	 * @return array
	 */
	public function getDecorationInfo($where, $shopId = null) {
		$info = Db::name('shop_decorations')->where($where)->find();
		//如果提供了$shopId，验证是否符合，不符合返回false
		if($shopId !== null) {
			if($info['shopId'] != $shopId) {
				return false;
			}
		}
		return $info;
	}
	
	/**
	 * 获取完整装修数据
	 * @param array $decorationId 装修编号
	 * @param int $shopId 店铺编号
	 * @return array
	 */
	public function getDecorationInfoDetail($decorationId, $shopId) {
		if($decorationId <= 0) {
			return false;
		}
		$where = array();
		$where['decorationId'] = $decorationId;
		$where['shopId'] = $shopId;
		$store_decoration_info = $this->getDecorationInfo($where);
		if(!empty($store_decoration_info)) {
			$data = array();
			//处理装修背景设置
			$decoration_setting = array();
			if(empty($store_decoration_info['decorationSetting'])) {
				$decoration_setting['background_color'] = '';
				$decoration_setting['background_image'] = '';
				$decoration_setting['background_image_url'] = '';
				$decoration_setting['background_image_repeat'] = '';
				$decoration_setting['background_position_x'] = '';
				$decoration_setting['background_position_y'] = '';
				$decoration_setting['background_attachment'] = '';
			} else {
				$setting = unserialize($store_decoration_info['decorationSetting']);
				$decoration_setting['background_color'] = $setting['background_color'];
				$decoration_setting['background_image_url'] =  $setting['background_image_url'];
				$decoration_setting['background_image_repeat'] = $setting['background_image_repeat'];
				$decoration_setting['background_position_x'] = $setting['background_position_x'];
				$decoration_setting['background_position_y'] = $setting['background_position_y'];
				$decoration_setting['background_attachment'] = $setting['background_attachment'];
			}
			$data['decoration_setting'] = $decoration_setting;
	
			//处理块列表
			$block_list = array();
			$block_list = $this->getDecorationBlockList(array('decorationId' => $decorationId));
			$data['block_list'] = $block_list;
	
			//处理导航条样式
			$data['decoration_nav'] = unserialize($store_decoration_info['decorationNav']);
	
			//处理banner
			$decoration_banner = unserialize($store_decoration_info['decorationBanner']);
			$decoration_banner['image_url'] = "";
			$data['decoration_banner'] = $decoration_banner;
	
			return $data;
		} else {
			return false;
		}
	}
	
	/**
	 * 生成装修背景样式规则
	 * @param array $decoration_setting 样式规则数组
	 * @return string 样式规则
	 */
	public function getDecorationBackgroundStyle($decoration_setting) {
		$decoration_background_style = '';
		if($decoration_setting['background_color'] != '') {
			$decoration_background_style .= 'background-color: ' . $decoration_setting['background_color'] . ';';
		}
		if($decoration_setting['background_image_url'] != '') {
			$decoration_background_style .= 'background-image: url(' . $decoration_setting['background_image_url'] . ');';
		}
		if($decoration_setting['background_image_repeat'] != '') {
			$decoration_background_style .= 'background-repeat: ' . $decoration_setting['background_image_repeat'] . ';';
		}
		if($decoration_setting['background_position_x'] != '' || $decoration_setting['background_position_y'] != '') {
			$decoration_background_style .= 'background-position: ' . $decoration_setting['background_position_x'] . ' ' . $decoration_setting['background_position_y'] . ';';
		}
		if($decoration_setting['background_attachment'] != '') {
			$decoration_background_style .= 'background-attachment: ' . $decoration_setting['background_attachment'] .';';
		}
		return $decoration_background_style;
	}
	
	/**
	 * 添加
	 * @param array $param 信息
	 * @return bool
	 */
	public function addDecoration($param){
		return Db::name('shop_decorations')->insertGetId($param);
	}
	
	/**
	 * 编辑
	 * @param array $update 更新信息
	 * @param array $where 条件
	 * @return bool
	 */
	public function editDecoration($obj, $where){
		Db::name('shop_decorations')->where($where)->update($obj);
		return WSTReturn('编辑成功',1);
	}
	
	/**
	 * 查询装修块列表
	 *
	 * @param array $where 查询条件
	 * @return array
	 */
	public function getDecorationBlockList($where) {
		$list = Db::name('shop_decoration_blocks')->where($where)->order('blockSort asc')->select();
		foreach ($list as $key => $value) {
			$list[$key]['blockContent'] = str_replace("\r", "", $value['blockContent']);
			$list[$key]['blockContent'] = str_replace("\n", "", $value['blockContent']);
		}
		return $list;
	}
	
	/**
	 * 查询装修块信息
	 *
	 * @param array $where 查询条件
	 * @param int $shopId 店铺编号
	 * @return array
	 */
	public function getDecorationBlockInfo($where, $shopId = null) {
		$info = Db::name('shop_decoration_blocks')->where($where)->find();
		//如果提供了$shopId，验证是否符合
		if($shopId !== null) {
			if($info['shopId'] != $shopId) {
				return false;
			}
		}
		return $info;
	}
	
	/**
	 * 添加装修块
	 * @param array $param 信息
	 * @return bool
	 */
	public function addDecorationBlock($param){
		 return Db::name('shop_decoration_blocks')->insertGetId($param);
	}
	
	/**
	 * 编辑装修块
	 * @param array $obj 更新信息
	 * @param array $where 条件
	 * @return bool
	 */
	public function editDecorationBlock($obj, $where){
		Db::name('shop_decoration_blocks')->where($where)->update($obj);
		return WSTReturn('编辑成功',1);
	}
	
	/**
	 * 删除装修块
	 * @param array $where 条件
	 * @return bool
	 */
	public function delDecorationBlock($where){
		Db::name('shop_decoration_blocks')->where($where)->delete();
		return WSTReturn('删除成功',1);
	}
	
	/**
	 * 返回装修块布局数组
	 */
	public function getDecorationBlockLayoutArray() {
		return $this->block_layout_array;
	}
	
	/**
	 * 返回装修块模块类型数组
	 */
	public function getDecorationBlockTypeArray() {
		return $this->block_type_array;
	}
	
	/**
	 * 搜索商品
	 */
	public function searchGoods(){
		$shopId = (int)session('WST_USER.shopId');
		$shopCatId1 = (int)input('post.shopCatId1');
		$shopCatId2 = (int)input('post.shopCatId2');
		$goodsName = input('post.goodsName');
		$where = [];
		$where['goodsStatus'] = 1;
		$where['dataFlag'] = 1;
		$where['isSale'] = 1;
		$where['shopId'] = $shopId;
		if($shopCatId1>0)$where['shopCatId1'] = $shopCatId1;
		if($shopCatId2>0)$where['shopCatId2'] = $shopCatId2;
		if($goodsName!='')$where[] = ['goodsName','like','%'.$goodsName.'%'];
		$rs = Db::name('goods')->where($where)->field('goodsName,goodsId,goodsImg,marketPrice,shopPrice')->paginate(10)->toArray();
		return $rs;
	}
	
	/**
	 * 获取店铺设置
	 * @return unknown
	 */
	public function getShopConf($shopId=0){
		$shopId = ($shopId>0)?$shopId:(int)session('WST_USER.shopId');
		$rs = Db::name('shop_configs')->where(["shopId"=>$shopId])->find();
		return $rs;
	}
	
	/**
	 * 保存店铺装修设置
	 */
	public function decorationSettingSave($obj){
		$shopId = (int)session('WST_USER.shopId');
		Db::name('shop_configs')->where(["shopId"=>$shopId])->update($obj);
		return WSTReturn('保存成功',1);
	}
	
	/**
	 * 获取癌修改
	 * @param $goodsId
	 */
	public function getGoodsInfo($goodsId){
		$rs = Db::name('goods')->where(["goodsId"=>$goodsId])->field(["goodsId","goodsName","goodsImg","shopPrice"])->find();
		return $rs;
	}

}
