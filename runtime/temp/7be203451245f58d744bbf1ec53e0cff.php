<?php /*a:1:{s:82:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/users/orders/box_cancel.html";i:1536627217;}*/ ?>
<table class='wst-form' style='margin-top:30px;'>
   <tr>
     <td colspan='2' style='padding-left:70px;'>请选择您取消订单的原因，以便我们能更好的为您服务。</td>
   </tr>
   <tr>
     <th width='120'>取消原因：</th>
     <td>
        <select id='reason'>
           <?php $_result=WSTDatas('ORDER_CANCEL');if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
           <option value='<?php echo $vo["dataVal"]; ?>'><?php echo $vo["dataName"]; ?></option>
           <?php endforeach; endif; else: echo "" ;endif; ?>
        </select>
     </td>
   </tr>
</table>