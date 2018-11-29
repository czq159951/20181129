<?php
namespace shangtao\admin\behavior;
/**
 * 检测有没有访问权限
 */
class ListenPrivilege 
{
    public function run($params){
        $privileges = session('WST_STAFF.privileges');
        $staffId = (int)session('WST_STAFF.staffId');
        if($staffId!=1){
            $urls = WSTConf('listenUrl');
            $request = request();
            $visit = strtolower($request->module()."/".$request->controller()."/".$request->action());
            if(array_key_exists($visit,$urls) && !$this->checkUserCode($urls[$visit],$privileges)){
            	if($request->isAjax()){
            		echo json_encode(['status'=>-998,'msg'=>'对不起，您没有操作权限，请与管理员联系']);
            	}else{
            		header("Content-type: text/html; charset=utf-8");
            	    echo "对不起，您没有操作权限，请与管理员联系";
            	}
            	exit();
            }
        }
    }
    private function checkUserCode($urlCodes,$userCodes){
        foreach ($urlCodes as $key => $value) {
            if(in_array($key,$userCodes))return true;
        }
        return false;
    }
}