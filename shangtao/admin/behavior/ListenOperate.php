<?php
namespace shangtao\admin\behavior;
/**
 * 记录用户的访问日志
 */
class ListenOperate 
{
    public function run($params){
        $urls = WSTConf('listenUrl');
        $request = request();
        $visit = strtolower($request->module()."/".$request->controller()."/".$request->action());
        if(array_key_exists($visit,$urls)&& $visit!='admin/logoperates/pagequery'){
            $privilege = current($urls[$visit]);
            $data = [];
            $data['menuId'] = $privilege['menuId'];
            $data['operateUrl'] = $_SERVER['REQUEST_URI'];
            $data['operateDesc'] = $privilege['name'];
            $data['content'] = !empty($_REQUEST)?json_encode($_REQUEST):'';
            $data['operateIP'] = $request->ip();
            model('admin/LogOperates')->add($data);
        }
    }
}