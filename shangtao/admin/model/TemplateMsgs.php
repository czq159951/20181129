<?php
namespace shangtao\admin\model;
/**
 * 消息模板业务处理
 */
class TemplateMsgs extends Base{
	/**
	 * 分页
	 */
	public function pageQuery($tplType,$dataType){
		$page =  $this->where(['dataFlag'=>1,'tplType'=>$tplType])->paginate(input('limit/d'))->toArray();
		if(count($page['data'])>0){
			foreach($page['data'] as $key =>$data){
                $d = WSTDatas($dataType,$data['tplCode']);
                $page['data'][$key]['tplCode'] = $d[$data['tplCode']]['dataName'];
			}
		}
		return $page;
	}
	/**
	 * 显示是否显示/隐藏
	 */
	public function editiIsShow(){
		//获取子集
		$id = input('post.id/d');
		$status = input('post.status/d',0)?0:1;
		$result = $this->where('id',$id)->update(['status' => $status]);
		if(false !== $result){
			cache('WST_MSG_TEMPLATES',null);
			return WSTReturn("操作成功", 1);
		}else{
			return WSTReturn($this->getError(),-1);
		}
	}
	public function pageEmailQuery(){
		$page =  $this->where(['dataFlag'=>1,'tplType'=>1])->paginate(input('limit/d'))->toArray();
		if(count($page['data'])>0){
			foreach($page['data'] as $key =>$data){
                $d = WSTDatas('TEMPLATE_EMAIL',$data['tplCode']);
                $page['data'][$key]['tplCode'] = $d[$data['tplCode']]['dataName'];
                $page['data'][$key]['tplContent'] = strip_tags(htmlspecialchars_decode($data['tplContent']));
			}
		}
		return $page;
	}
	/**
	 * 获取角色权限
	 */
	public function getById($id){
		return $this->get(['dataFlag'=>1,'id'=>$id]);
	}
    /**
	 * 编辑
	 */
	public function edit(){
		$id = (int)input('post.id/d');
		$tplCode = input('post.tplCode');
		$data = [];
		$data['tplContent'] = input('post.tplContent');
		$data['status'] = input('post.seoMallSwitch');
	    $result = $this->save($data,['id'=>$id,'tplCode'=>$tplCode]);
        if(false !== $result){
        	cache('WST_MSG_TEMPLATES',null);
        	return WSTReturn("编辑成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	
}
