<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 文章分类验证器
 */
class ArticleCats extends Validate{
	protected $rule = [
	    'catName' => 'require|max:30',
	    'catSort' => 'require|max:16',
    ];
    
    protected $message = [
        'catName.require' => '请输入文章分类名称',
        'catName.max' => '文章分类名称不能超过10个字符',
        'catSort.require' => '请输入排序号',
        'catSort.max' => '排序号不能超过8个字符',
    ];
    protected $scene = [
        'add'   =>  ['catName','catSort'],
        'edit'  =>  ['catName','catSort'],
    ]; 
}