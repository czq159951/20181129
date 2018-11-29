<?php 
namespace addons\reward\validate;
use think\Validate;
/**
 * 优惠券验证器
 */
class Rewards extends Validate{
	protected $rule = [
        'rewardTitle' => 'require',
        'startDate' => 'require',
        'endDate' => 'require',
        'rewardType' => 'require',
        'rewardType' => 'checkRewardType:1',
        'useMoney' => 'requireIf:useCondition,1',
        'useObjects' => 'in:,0,1',
        'useObjectIds' => 'requireIf:useObjects,1',
        'useObjectIds' => 'checkUseObjectIds:1'
    ];
	
	protected $message  =   [
        'rewardTitle.require' => '请输入活动标题',
        'startDate.require' => '请输入活动开始日期',
        'endDate.require' => '请输入活动结束日期',
        'rewardType.require' => '请选择优惠方式',
        'rewardType.checkRewardType' => '',
        'useMoney.requireIf' => '请输入满减金额',
        'useObjects.in' => '非法的参数',
        'useObjectIds.requireIf' => '请选择优惠券适用的商品',
        'useObjectIds.checkUseObjectIds' => ''
	];
	
    /**
     * 检测面值
     */
    protected function checkRewardType($value){
    	$rewardType = Input('post.rewardType/d',0);
    	if(!in_array($rewardType,[0,1]))return '非法的优惠方式参数';
        $no = Input('post.no/d',0);
        if($no<=0)return '请填写优惠内容';
        for($i=0;$i<$no;$i++){
            if((int)input('money-'.$i)<=0)return '消费金额必须大于0';
            if((int)input('chk-0-'.$i)!=0){
                if((int)input('j-reward-c-0-'.$i)<=0)return '所减金额必须大于0';
                if((int)input('money-'.$i)<(int)input('j-reward-c-0-'.$i))return '优惠金额不能大于消费金额';
            }
            if((int)input('chk-1-'.$i)!=0){
                if((int)input('j-reward-c-1-'.$i)<=0)return '请选择要赠送的赠品';
            }
            if((int)input('chk-3-'.$i)!=0){
                if((int)input('j-reward-c-3-'.$i)<=0)return '请选择要赠送的优惠券';
            }
        }
    	return true;
    }
    
    public function checkUseObjectIds(){
        $useObjects = input('post.useObjects/d');
        $useObjectIds = input('post.useObjectIds');
        if($useObjects==1 && $useObjectIds=='')return '请选择优惠券适用的商品';
        return true;
    }
}