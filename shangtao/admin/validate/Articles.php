<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 文章验证器
 */
class Articles extends Validate{
	protected $rule = [
	    'articleTitle' => 'require|max:150',
		'articleKey' => 'require|max:300',
	    'articleContent' => 'require',
    ];
     
    protected $message = [
        'articleTitle.require' => '请输入文章标题',
        'articleTitle.max' => '文章标题不能超过50个字符',
        'articleKey.require' => '请输入关键字',
        'articleKey.max' => '关键字不能超过100个字符',
        'articleContent.require' => '请输入文章内容',

    ];
    protected $scene = [
        'add'   =>  ['articleTitle','articleKey','articleContent'],
        'edit'  =>  ['articleTitle','articleKey','articleContent']
    ]; 
}