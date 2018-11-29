<?php

namespace think\addons;

use think\facade\Request;
use think\facade\Config;
use think\Loader;
/**
 * 插件基类控制器
 * Class Controller
 * @package think\addons
 */
class Controller extends \think\Controller
{
    // 当前插件操作
    protected $addon = null;
    protected $controller = null;
    protected $action = null;
    // 当前template
    protected $template;
    // 模板配置信息
    protected $config = [
        'type' => 'Think',
        'view_path' => '',
        'view_suffix' => 'html',
        'strip_space' => true,
        'view_depr' => DS,
        'tpl_begin' => '{',
        'tpl_end' => '}',
        'taglib_begin' => '{',
        'taglib_end' => '}',
    ];

    /**
     * 架构函数
     * @param Request $request Request对象
     * @access public
     */
    public function __construct()
    {
        WSTConf('CONF.seoMallSwitch');
        if(WSTConf('CONF.seoMallSwitch')==0){
            $this->redirect('home/switchs/index');
            exit;
        }

        // 初始化配置信息
        $this->config = Config::pull('template') ?: $this->config;
        // 处理路由参数
        $param = [request()->param('module', ''),request()->param('action', ''),request()->param('method', '')];
        // 是否自动转换控制器和操作名
        $convert = Config::get('app.url_convert');
        if('wechat/weixinpays/toaddonpay' !=  request()->path()){
            // 格式化路由的插件位置
            $this->action = $convert ? strtolower(array_pop($param)) : array_pop($param);
            $this->controller = $convert ? strtolower(array_pop($param)) : array_pop($param);
            $this->addon = $convert ? strtolower(array_pop($param)) : array_pop($param);
            WSTSwitchs($this->addon,$this->controller,$this->action,'addon');
            $base = new \think\addons\BaseModel();
            $status = $base->getAddonStatus(ucfirst($this->addon));
            if(isset($status) && $status!=1 && $this->addon!=""){
                $module = WSTVisitModule();
                header("Location:".url($module.'/error/message'));
            	exit();
            }
        }

        $this->checkPrivileges();
        parent::__construct();
        // 生成view_path
        $view_path = $this->config['view_path'] ?: 'view';
        // 重置配置
        Config::set('template.view_path', ADDON_PATH . $this->addon . DS . $view_path . DS);
        $this->filter(function($content){
            $content = str_replace("__STYLE__",str_replace('/index.php','',Request::root()).'/shangtao/home/view/'.WSTConf('CONF.wsthomeStyle'),$content);
            $content = str_replace("__ADMIN__",str_replace('/index.php','',Request::root()).'/shangtao/admin/view',$content);
            $content = str_replace("__WECHAT__",str_replace('/index.php','',Request::root()).'/shangtao/wechat/view/'.WSTConf('CONF.wstwechatStyle'),$content);
            $content = str_replace("__MOBILE__",str_replace('/index.php','',Request::root()).'/shangtao/mobile/view/default',$content);
            return $content;
        });
        $this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wsthomeStyleId'));
        $this->initLayout();
    }
    /**
     * 定义模板
     */
    public function initLayout(){
        $wsthomeStyle = WSTConf('CONF.wsthomeStyle')?WSTConf('CONF.wsthomeStyle'):'default';
        $wstmobileStyle = WSTConf('CONF.wstmobileStyle')?WSTConf('CONF.wstmobileStyle'):'default';
        $wstwechatStyle = WSTConf('CONF.wstwechatStyle')?WSTConf('CONF.wstwechatStyle'):'default';
        $this->assign('LAYOUT_HOME_BASE','home@'.$wsthomeStyle.'/base');
        $this->assign('LAYOUT_HOME_TOP','home@'.$wsthomeStyle.'/top');
        $this->assign('LAYOUT_HOME_HEADER','home@'.$wsthomeStyle.'/header');
        $this->assign('LAYOUT_HOME_SHOP_APPLY','home@'.$wsthomeStyle.'/shop_apply');
        $this->assign('LAYOUT_HOME_RIGHT_CART','home@'.$wsthomeStyle.'/right_cart');
        $this->assign('LAYOUT_HOME_FOOTER','home@'.$wsthomeStyle.'/footer');
        $this->assign('LAYOUT_HOME_SHOP_BASE','home@'.$wsthomeStyle.'/shops/base');
        $this->assign('LAYOUT_HOME_SHOP_HEADER','home@'.$wsthomeStyle.'/shops/header');
        $this->assign('LAYOUT_HOME_USER_BASE','home@'.$wsthomeStyle.'/users/base');
        $this->assign('LAYOUT_HOME_USER_HEADER','home@'.$wsthomeStyle.'/users/header');

        $this->assign('LAYOUT_ADMIN_BASE','admin@base');

        $this->assign('LAYOUT_MOBILE_BASE','mobile@'.$wstmobileStyle.'/base');
        $this->assign('LAYOUT_MOBILE_DIALOG','mobile@'.$wstmobileStyle.'/dialog');
        $this->assign('LAYOUT_MOBILE_FOOTER','mobile@'.$wstmobileStyle.'/footer');

        $this->assign('LAYOUT_WECHAT_BASE','wechat@'.$wstwechatStyle.'/base');
        $this->assign('LAYOUT_WECHAT_DIALOG','wechat@'.$wstwechatStyle.'/dialog');
        $this->assign('LAYOUT_WECHAT_FOOTER','wechat@'.$wstwechatStyle.'/footer');
    }
	/**
     * @deprecated 建议使用 checkAuth和checkShopAuth区分商家 2017.04.25
     */
    public function checkPrivileges(){
    	$urls = model('home/HomeMenus')->getMenusUrl();
    	$request = request();
    	$visit = strtolower($request->path());
    	if(isset($urls[$visit])){
    		$menuType = (int)$urls[$visit];
    		$userType = -1;
    		if((int)session('WST_USER.userId')>0)$userType = 0;
    		if((int)session('WST_USER.shopId')>0)$userType = 1;
    		//未登录不允许访问受保护的资源
    		if($userType==-1){
    			if($request->isAjax()){
    				echo json_encode(['status'=>-999,'msg'=>'对不起，您还没有登录，请先登录']);
    			}else{
    				header("Location:".url('home/users/login'));
    			}
    			exit();
    		}
    		//已登录但不是商家 则不允许访问受保护的商家资源
    		if($userType==0 && $menuType==1){
    			if($request->isAjax()){
    				echo json_encode(['status'=>-999,'msg'=>'对不起，您不是商家，请先申请为商家再访问']);
    			}else{
    				header("Location:".url('home/shops/login'));
    			}
    			exit();
    		}
    	}
    }
    public function checkAdminAuth(){
        $STAFF = session('WST_STAFF');
        $request = request();
        if(empty($STAFF)){
            if($request->isAjax()){
                echo json_encode(['status'=>-999,'msg'=>'对不起，您还没有登录，请先登录']);
            }else{
                header("Location:".url('admin/index/login'));
            }
            exit();
        }else{
            $urls = WSTVisitPrivilege();
            $privileges = session('WST_STAFF.privileges');
            $visit = $request->path();
            if(!$privileges || (array_key_exists($visit,$urls) && !$this->checkUserCode($urls[$visit],$privileges))){
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
    /**
     * @deprecated 建议使用checkAdminAuth，将来可能会移除. 2017.04.25
     */
    public function checkAdminPrivileges(){
    	$this->checkAdminAuth();
    }
    // 登录验证方法--用户
    protected function checkAuth(){
        $USER = session('WST_USER');
        if(empty($USER)){
            if(request()->isAjax()){
                die('{"status":-999,"msg":"您还未登录"}');
            }else{
                $module = WSTVisitModule();
                $this->redirect($module.'/users/login');
                exit;
            }
        }
    }
    //登录验证方法--商家
    protected function checkShopAuth(){
        $USER = session('WST_USER');
        if(empty($USER) || $USER['userType']!=1){
            if(request()->isAjax()){
                die('{"status":-998,"msg":"您还未登录"}');
            }else{
                $module = WSTVisitModule();
                $this->redirect($module.'/shops/login');
                exit;
            }
        }
    }
    /**
    * 重写父类前置方法判断
    */
    protected function beforeAction($method, $options = [])
    {
        // 设置当前访问的controller、action
        request()->controller($this->controller);
        request()->action($this->action);
        if (isset($options['only'])) {
            if (is_string($options['only'])) {
                $options['only'] = explode(',', $options['only']);
            }
            if (!in_array($this->request->action(), $options['only'])) {
                return;
            }
        } elseif (isset($options['except'])) {
            if (is_string($options['except'])) {
                $options['except'] = explode(',', $options['except']);
            }
            if (in_array($this->request->action(), $options['except'])) {
                return;
            }
        }

        call_user_func([$this, $method]);
    }
    

    /**
     * 加载模板输出
     * @access protected
     * @param string $template 模板文件名
     * @param array $vars 模板输出变量
     * @param array $replace 模板替换
     * @param array $config 模板参数
     * @return mixed
     */
    public function fetch($template = '', $vars = [], $config = []){
        $controller = Loader::parseName($this->controller);
        if ('think' == strtolower($this->config['type']) && $controller && 0 !== strpos($template, '/')) {
            $depr = $this->config['view_depr'];
            $template = str_replace(['/', ':'], $depr, $template);
            if ('' == $template) {
                // 如果模板文件名为空 按照默认规则定位
                $template = str_replace('.', DS, $controller) . $depr . $this->action;
            } elseif (false === strpos($template, $depr)) {
                $template = str_replace('.', DS, $controller) . $depr . $template;
            }
        }
        return \Think\Controller::fetch($template, $vars, $config);
    }
    
    /**
     * 模板变量赋值
     * @access protected
     * @param mixed $name  要显示的模板变量
     * @param mixed $value 变量的值
     * @return void
     */
    public function assign($name, $value = ''){
    	$this->view->assign($name, $value);
    }
    
}
