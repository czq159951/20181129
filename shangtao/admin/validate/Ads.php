<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 广告验证器
 */
class Ads extends Validate{
	protected $rule = [
        'adName' => 'require|max:30',
        'adFile' => 'require',
        'adStartDate' => 'require',
        'adEndDate'  => 'require',
    ];

    protected $message = [
        'adName.require' => '请输入广告标题',
        'adName.max' => '广告标题不能超过10个字符',
        'adFile.require' => '请上传广告图片',
        'adStartDate.require' => '请输入广告开始时间',
        'adEndDate.require' => '请输入广告结束时间',
    ];

    protected $scene = [
        'add'   =>  ['adName','adURL','subTitle','adStartDate','adEndDate'],
        'edit'  =>  ['adName','adURL','subTitle','adStartDate','adEndDate'],
    ]; 
}