<?php
namespace shangtao\admin\model;
/**
 * 微信消息参数模板业务处理
 */
class WxTemplateParams extends Base{
	/**
	 * 分页
	 */
	public function listQuery($parentId){
		$rs =  $this->where('parentId',$parentId)->select();
		return WSTReturn('',1,$rs);
	}
    /**
	 * 编辑
	 */
	public function edit(){
		$id = (int)input('post.id/d');
		$tplCode = input('post.tplCode');
		$data = [];
		$data['tplContent'] = input('post.tplContent');
		$data['tplExternaId'] = input('post.tplExternaId');
		$data['status'] = input('post.seoMallSwitch');
	    $result = model('admin/TemplateMsgs')->save($data,['id'=>$id,'tplCode'=>$tplCode]);
        if(false !== $result){
            cache('WST_MSG_TEMPLATES',null);
        	$this->where('parentId',$id)->delete();
        	$num = (int)input('num');
        	if($num>0){
        		$tdata = [];
        		for($i=0;$i<=$num;$i++){
        			$code = input('code_'.$i);
        			if($code=='')continue;
        			$data = [];
        			$data['parentId'] = $id;
                    $data['fieldCode'] = $code;
                    $data['fieldVal'] = input('val_'.$i);
                    $tdata[] = $data;
        		}
        		if(count($tdata)>0)$this->saveAll($tdata);
        	}
        	return WSTReturn("编辑成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	
}
