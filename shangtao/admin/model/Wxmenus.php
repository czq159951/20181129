<?php
namespace shangtao\admin\model;
use think\Db;
/**
 * 微信菜单业务处理
 */
class WxMenus extends Base{
	/**
	 * 获取树形分类
	 */
	public function pageQuery(){
		$list = $this->where(['dataFlag'=>1,'parentId'=>input('menuId/d',0)])->order('menuSort asc,menuId desc')->paginate(input('post.limit/d'))->toArray();
		foreach($list['data'] as $key=>$v){
			$list['data'][$key]['menuUrl'] = htmlspecialchars_decode($v['menuUrl']);
		}
		return $list;
	}
	
	/**
	 * 获取列表
	 */
	public function listQuery(){
		$listMenu = $this->where(['dataFlag'=>1,'parentId'=>0])->field('menuId,menuName')->order('menuSort asc')->select();
		for ($i = 0; $i < count($listMenu); $i++) {
			$parentId = $listMenu[$i]["menuId"];
			$listSon = $this->where(['dataFlag'=>1,'parentId'=>$parentId])->field('menuId,menuName')->order('menuSort asc')->select();
			$listMenu[$i]['listSon'] = $listSon;
		}	
		return $listMenu;
	}
	
	/**
	 * 获取指定对象
	 */
	public function getById($id){
		$data = $this->where(['menuId'=>$id])->find();
		$data['menuUrl'] = htmlspecialchars_decode($data['menuUrl']);
		return $data;
	}
	
    /**
     * 与微信菜单同步
     */
	public function synchroWx(){
		$this->where('menuId','>','0')->delete();
		$wx = WXAdmin();
		$data = $wx->wxMenuGet();
		if(isset($data['errcode'])){
			if($data['errcode']!=0)return WSTReturn('与微信同步失败:'.$data['errcode']);
		}
		if($data){
			$data = $data['menu']['button'];
			$type = array('click'=>1,'view'=>2,'scancode_push'=>3,'scancode_waitmsg'=>4,'pic_sysphoto'=>5,'pic_photo_or_album'=>6,'pic_weixin'=>7,'location_select'=>8,'media_id'=>9,'view_limited'=>10);
			$dataList = [];
			foreach( $data as $key=>$v){
				$data = [];
				$data['menuName'] = $v['name'];
				$data['createTime'] = date('Y-m-d H:i:s');
				$data['menuType'] = (isset($v['type']))?$type[$v['type']]:'';
				$data['menuKey'] = (isset($v['key']))?$v['key']:'';
				$data['menuSort'] = $key;
				$data['menuUrl'] = '';
				$data['parentId'] = '';
				$rs = $this->insert($data,false,true);
				if($v['sub_button']){
					foreach($v['sub_button'] as $keys=>$vs){
						$datas = [];
						$datas['menuName'] = $vs['name'];
						$datas['parentId'] = $rs;
						$datas['menuSort'] = $keys;
						$datas['createTime'] = date('Y-m-d H:i:s');
						$datas['menuType'] = (isset($vs['type']))?$type[$vs['type']]:'';
						$datas['menuKey'] = (isset($vs['key']))?$vs['key']:'';
						$datas['menuUrl'] = (isset($vs['url']))?$vs['url']:'';
						$dataList[] = $datas;
					}
				}
			}
			$this->insertAll($dataList);
			return WSTReturn("与微信同步成功", 1);
		}
	}
	
    /**
     * 同步到微信菜单
     */
    public function synchroAd(){
    	$rs = Db::name('wx_menus')->where('dataFlag=1')->order('menuSort asc')->select();
    	$arr = $this->makeNewArr($rs,0);
    	header('content-type:text/html;charset=utf-8');
    	$arr = json_encode($arr,JSON_UNESCAPED_UNICODE);
    	$wx = WXAdmin();
    	$data = $wx->wxMenuCreate($arr);
        if($data['errcode']==0){
            return WSTReturn('菜单同步成功',1);
        }
        return WSTReturn('菜单同步失败:'.$data['errcode']);
    }
    function makeNewArr($data,$pId){
    	$type = array(1=>'click',2=>'view',3=>'scancode_push',4=>'scancode_waitmsg',5=>'pic_sysphoto',6=>'pic_photo_or_album',7=>'pic_weixin',8=>'location_select',9=>'media_id',10=>'view_limited');
    	$c=0;
    	$newArr = [];
    	foreach($data as $k=>$v){
    		if($v['parentId']==$pId){
    			$sub_button = $this->makeNewArr($data,$v['menuId']);
    			if($pId==0){
    				$arr = ['name'=>$v['menuName']];
    				if(!empty($sub_button)){
    					$arr['sub_button'] = $sub_button;
    				}else{
    					$arr['key']=$v['menuKey'];
    					$arr['type']=$type[$v['menuType']];
                        if($v['menuUrl']!='')
    					   $arr['url']= htmlspecialchars_decode($v['menuUrl']);
    				}
    				$newArr['button'][] = $arr;
    			}else{
    				$newArr[$c]['name'] = $v['menuName'];
    				$newArr[$c]['key'] = $v['menuKey'];
    				$newArr[$c]['type'] =$type[$v['menuType']];
                    if($v['menuUrl']!='')
    				    $newArr[$c]['url'] = htmlspecialchars_decode($v['menuUrl']);
    				++$c;
    			}
    		}
    	}
    	return $newArr;
    }
    
    /**
     * 查询菜单个数
     */
    function menuNum($parentId){
    	$rs = $this->where(['parentId'=>$parentId,'dataFlag'=>1])->field('menuId')->select();
    	return count($rs);
    }
    
    /**
     * 新增
     */
    public function add(){
    	$data = input('post.');
    	if($data['content']==0){
    		$data['menuType'] = 2;
    	}
    	WSTUnset($data,'menuId,dataFlag,content');
    	if(!$data['menuName'])return WSTReturn("请输入菜单名称");
    	$num = $this->menuNum($data['parentId']);
    	if($data['parentId']==0){
			if($num>=3)return WSTReturn("一级菜单数，个数应为1~3个 ");
    	}else{
    		if($num>=5)return WSTReturn("二级菜单数，个数应为1~5个 ");
    	}
    	$data['parentId'] = $data['parentId'];
    	$data['createTime'] = date('Y-m-d H:i:s');
    	$result = $this->allowField(true)->save($data);
    	if(false !== $result){
    		return WSTReturn("新增成功", 1);
    	}else{
    		return WSTReturn($this->getError(),-1);
    	}
    }
    
    /**
     * 编辑
     */
    public function edit(){
    	$menuId = input('post.menuId/d');
    	$data = input('post.');
    	if($data['content']==0){
    		$data['menuType'] = 2;
    	}
    	WSTUnset($data,'menuId,dataFlag,createTime,content');
    	if(!$data['menuName'])return WSTReturn("请输入菜单名称");
    	$result = $this->allowField(true)->save($data,['menuId'=>$menuId]);
    	if(false !== $result){
    		return WSTReturn("修改成功", 1);
    	}else{
    		return WSTReturn($this->getError(),-1);
    	}
    }
    
    /**
     * 删除
     */
    public function del(){
    	$ids = array();
    	$ids[] = input('post.id/d');
    	$ids = $this->getChild($ids,$ids);
    	$data = [];
    	$data['dataFlag'] = -1;
    	$result = $this->where("menuId in(".implode(',',$ids).")")->update($data);
    	if(false !== $result){
    		return WSTReturn("删除成功", 1);
    	}else{
    		return WSTReturn($this->getError(),-1);
    	}
    }
    
    /**
     * 迭代获取下级
     */
    public function getChild($ids = array(),$pids = array()){
    	$result = $this->where("dataFlag=1 and parentId in(".implode(',',$pids).")")->field('menuId')->select();
    	if(count($result)>0){
    		$cids = array();
    		foreach ($result as $key =>$v){
    			$cids[] = $v['menuId'];
    		}
    		$ids = array_merge($ids,$cids);
    		return $this->getChild($ids,$cids);
    	}else{
    		return $ids;
    	}
    }
}
