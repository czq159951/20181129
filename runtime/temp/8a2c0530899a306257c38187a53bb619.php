<?php /*a:2:{s:69:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/register.html";i:1536569719;s:65:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/base.html";i:1536569719;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>注册 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/user.css?v=<?php echo $v; ?>">
<style>body{background:#fff}</style>

<script type='text/javascript' src='__MOBILE__/frozenui/js/zepto.min.js'></script>
<script type='text/javascript' src='__MOBILE__/frozenui/js/frozen.js'></script>
<script type='text/javascript' src='__MOBILE__/js/laytpl/laytpl.js?v=<?php echo $v; ?>'></script>
<script src="__MOBILE__/js/echo.min.js"></script>
<script type='text/javascript' src='__MOBILE__/js/common.js?v=<?php echo $v; ?>'></script>
<script>
window.conf = {"ROOT":"","MOBILE":"__MOBILE__","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","SMS_OPEN":"<?php echo WSTConf('CONF.smsOpen'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","ROUTES":'<?php echo WSTRoute(); ?>',"IS_CRYPTPWD":"<?php echo WSTConf('CONF.isCryptPwd'); ?>",HTTP:"<?php echo WSTProtocol(); ?>"}
</script>
</head>
<body ontouchstart="">

       <header style="background:#ffffff;" class="ui-header ui-header-positive wst-header">
       	   <i id="return" class="ui-icon-return" onclick="javascript:history.go(-1)" ></i><h1 id="login-w">注册</h1>
       </header>


		
		<div class="ui-loading-block" id="Loadl">
		    <div class="ui-loading-cnt">
		        <i class="ui-loading-bright"></i>
		        <p id="j-Loadl">正在加载中...</p>
		    </div>
		</div>


      
      <input type="hidden" value="<?php echo WSTConf('CONF.pwdModulusKey'); ?>" id="key" autocomplete="off">
      <section class="ui-container" id="login0">
      	 <div class="wst-lo-frame">
			<div class="frame"><input id="regName" type="text" placeholder="手机号" onkeyup="javascript:onTesting(this)"></div>
			<div class="frame"><input id="regPwd" type="password" placeholder="密码"></div>
			<div class="frame"><input id="regcoPwd" type="password" placeholder="确认密码"></div>
			
			<?php if((WSTConf('CONF.smsVerfy')==1)): ?>
			<div class="verify phone">
				<input id="smsVerfy" type="text" placeholder="输入验证码" maxlength="10">
				<img id='verifyImg3' src="<?php echo url('mobile/users/getVerify'); ?>" onclick='javascript:WST.getVerify("#verifyImg3")'>
			</div>
			<?php endif; ?>
			
			<div class="verify phone">
				<input id="phoneCode" type="text" placeholder="输入短信验证码" maxlength="8">
				<button id="obtain" class="ui-btn ui-btn-primary" onclick="javascript:obtainCode()">获取验证码</button>
			</div>
    	</div>
    	<div class="wst-lo-agreement">
	       <i id="defaults" class="ui-icon-chooses ui-icon-success-block wst-active" onclick="javascript:inAgree(this)"></i>我已阅读并同意<span onclick="javascript:wholeShow('protocol');">《用户注册协议》</span>
	    </div>
    	<div class="wst-lo-button">
			<button id="regButton" class="button" onclick="javascript:register();">注册</button>
		</div>
      </section>



<div class="wst-fr-protocol" id="protocol">
    <div class="title"><span>用户注册协议</span><i class="ui-icon-close-page" onclick="javascript:wholeHide('protocol');"></i><div class="wst-clear"></div></div>
    <div class="content">

<h4><span><?php echo WSTConf('CONF.mallName'); ?></span>用户注册协议</h4>
<p>本协议是您与<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>所有者之间就<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>网站服务等相关事宜所订立的契约，请您仔细阅读本注册协议，您点击"同意并继续"按钮后，本协议即构成对双方有约束力的法律文件。</p>
<h4>第1条 本站服务条款的确认和接纳</h4>
<p><strong>1.1</strong>本站的各项电子服务的所有权和运作权归<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>所有。用户同意所有注册协议条款并完成注册程序，才能成为本站的正式用户。用户确认：本协议条款是处理双方权利义务的契约，始终有效，法律另有强制性规定或双方另有特别约定的，依其规定。<p></p>
<p><strong>1.2</strong>用户点击同意本协议的，即视为用户确认自己具有享受本站服务、下单购物等相应的权利能力和行为能力，能够独立承担法律责任。</p>
<p><strong>1.3</strong>如果您在18周岁以下，您只能在父母或监护人的监护参与下才能使用本站。<p>
<p><strong>1.4</strong><span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>保留在中华人民共和国大陆地区法施行之法律允许的范围内独自决定拒绝服务、关闭用户账户、清除或编辑内容或取消订单的权利。</p>
<h4>第2条 本站服务</h4>
<p><strong>2.1</strong><span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>通过互联网依法为用户提供互联网信息等服务，用户在完全同意本协议及本站规定的情况下，方有权使用本站的相关服务。</p>
<p><strong>2.2</strong>用户必须自行准备如下设备和承担如下开支：</p>
<p>（1）上网设备，包括并不限于电脑或者其他上网终端、调制解调器及其他必备的上网装置；</p>
<p>（2）上网开支，包括并不限于网络接入费、上网设备租用费、手机流量费等。</p>
<h4>第3条 用户信息</h4>
<p><strong>3.1</strong>用户应自行诚信向本站提供注册资料，用户同意其提供的注册资料真实、准确、完整、合法有效，用户注册资料如有变动的，应及时更新其注册资料。如果用户提供的注册资料不合法、不真实、不准确、不详尽的，用户需承担因此引起的相应责任及后果，并且<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>保留终止用户使用<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>各项服务的权利。</p>
<p><strong>3.2</strong>用户在本站进行浏览、下单购物等活动时，涉及用户真实姓名/名称、通信地址、联系电话、电子邮箱等隐私信息的，本站将予以严格保密，除非得到用户的授权或法律另有规定，本站不会向外界披露用户隐私信息。</p>
<p><strong>3.3</strong>用户注册成功后，将产生用户名和密码等账户信息，您可以根据本站规定改变您的密码。用户应谨慎合理的保存、使用其用户名和密码。用户若发现任何非法使用用户账号或存在安全漏洞的情况，请立即通知本站并向公安机关报案。</p>
<p><strong>3.4</strong>用户同意，<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>拥有通过邮件、短信电话等形式，向在本站注册、购物用户、收货人发送订单信息、促销活动等告知信息的权利。</p>
<p><strong>3.5</strong>用户不得将在本站注册获得的账户借给他人使用，否则用户应承担由此产生的全部责任，并与实际使用人承担连带责任。</p>
<p><strong>3.6</strong>用户同意，<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>有权使用用户的注册信息、用户名、密码等信息，登录进入用户的注册账户，进行证据保全，包括但不限于公证、见证等。</p>
<h4>第4条 用户依法言行义务</h4>
<p>本协议依据国家相关法律法规规章制定，用户同意严格遵守以下义务：</p>
<p>（1）不得传输或发表：煽动抗拒、破坏宪法和法律、行政法规实施的言论，煽动颠覆国家政权，推翻社会主义制度的言论，煽动分裂国家、破坏国家统一的的言论，煽动民族仇恨、民族歧视、破坏民族团结的言论；</p>
<p>（2）从中国大陆向境外传输资料信息时必须符合中国有关法规；</p>
<p>（3）不得利用本站从事洗钱、窃取商业秘密、窃取个人信息等违法犯罪活动；</p>
<p>（4）不得干扰本站的正常运转，不得侵入本站及国家计算机信息系统；</p>
<p>（5）不得传输或发表任何违法犯罪的、骚扰性的、中伤他人的、辱骂性的、恐吓性的、伤害性的、庸俗的，淫秽的、不文明的等信息资料；</p>
<p>（6）不得传输或发表损害国家社会公共利益和涉及国家安全的信息资料或言论；</p>
<p>（7）不得教唆他人从事本条所禁止的行为；</p>
<p>（8）不得利用在本站注册的账户进行牟利性经营活动；</p>
<p>（9）不得发布任何侵犯他人著作权、商标权等知识产权或合法权利的内容；</p>
<p>用户应不时关注并遵守本站不时公布或修改的各类合法规则规定。</p>
<p>本站保有删除站内各类不符合法律政策或不真实的信息内容而无须通知用户的权利。</p>
<p>若用户未遵守以上规定的，本站有权作出独立判断并采取暂停或关闭用户帐号等措施。用户须对自己在网上的言论和行为承担法律责任。</p>
<h4>第5条 店铺义务</h4>
<p><strong>5.1</strong>店铺经营者可通过本站申请店铺，发布全新或二手商品及/或服务信息并与其他用户达成交易，但必须保证商品信息真实。如有发现商品假冒或者其他违反国家法律规定的商品，本站有权对商品进行禁售。</p>
<p><strong>5.2</strong>若店铺经营者发生改变，店铺经营者需及时联系本站进行信息的变更，若未及时联系本站而导致消费者与原店铺经营者产生交易纠纷或者违法国家规定的事情，本站不负任何连带责任。</p>
<p><strong>5.3</strong>店铺经营者有权通过使用店铺设置短暂关停店铺，但店铺经营者应当对自己店铺关停前已达成的交易继续承担发货、退换货及质保维修、维权投诉处理等交易保障责任。</p>
<p><strong>5.4</strong>店铺经营者如有不实交易信息或者违反国家相关法律的行为，本站有权对店铺进行关停，并对关停期间所产生的损失不负任何责任。</p>
<p>依据上述约定关停店铺均不会影响您已经累积的信用。</p>
</p>
<h4>第6条 商品信息</h4>
<p>本站上的商品价格、数量、是否有货等商品信息随时都有可能发生变动，本站不作特别通知。由于网站上商品信息的数量极其庞大，虽然本站会尽最大努力保证您所浏览商品信息的准确性，但由于众所周知的互联网技术因素等客观原因存在，本站网页显示的信息可能会有一定的滞后性或差错，对此情形您知悉并理解；<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>欢迎纠错，并会视情况给予纠错者一定的奖励。</p>
<p>为表述便利，商品和服务简称为"商品"或"货物"。</p>
<h4>第7条 订单</h4>
<p><strong>7.1</strong>在您下订单时，请您仔细确认所购商品的名称、价格、数量、规格、联系地址、电话、收货人等信息。收货人与用户本人不一致的，收货人的行为和意思表示视为用户的行为和意思表示，用户应对收货人的行为及意思表示的法律后果承担连带责任。</p>
<p><strong>7.2</strong>除法律另有强制性规定外，双方约定如下：本站上销售方展示的商品和价格等信息仅仅是要约邀请，您下单时须填写您希望购买的商品数量、价款及支付方式、收货人、联系方式、收货地址（合同履行地点）、合同履行方式等内容；系统生成的订单信息是计算机信息系统根据您填写的内容自动生成的数据，仅是您向销售方发出的合同要约；销售方收到您的订单信息后，只有在销售方将您在订单中订购的商品从仓库实际直接向您发出时（ 以商品出库为标志），方视为您与销售方之间就实际直接向您发出的商品建立了合同关系；如果您在一份订单里订购了多种商品并且销售方只给您发出了部分商品时，您与销售方之间仅就实际直接向您发出的商品建立了合同关系；只有在销售方实际直接向您发出了订单中订购的其他商品时，您和销售方之间就订单中该其他已实际直接向您发出的商品才成立合同关系。您可以随时登录您在本站注册的账户，查询您的订单状态。</p>
<p><strong>7.3</strong>由于市场变化及各种以合理商业努力难以控制的因素的影响，本站无法保证您提交的订单信息中希望购买的商品都会有货；如您拟购买的商品，发生缺货，您有权取消订单。</p>
<h4>第8条 配送</h4>
<p><strong>8.1</strong>销售方将会把商品（货物）送到您所指定的收货地址，所有在本站上列出的送货时间为参考时间，参考时间的计算是根据库存状况、正常的处理过程和送货时间、送货地点的基础上估计得出的。</p>
<p><strong>8.2</strong>因如下情况造成订单延迟或无法配送等，销售方不承担延迟配送的责任：</p>
<p>（1）用户提供的信息错误、地址不详细等原因导致的；</p>
<p>（2）货物送达后无人签收，导致无法配送或延迟配送的；</p>
<p>（3）情势变更因素导致的；</p>
<p>（4）不可抗力因素导致的，例如：自然灾害、交通戒严、突发战争等。</p>
<h4>第9条 交易争议处理</h4>
<p>您在<?php echo WSTConf('CONF.mallName'); ?>平台交易过程中与其他用户发生争议的，您或其他用户中任何一方均有权选择以下途径解决：</p>
<p>（1）与争议相对方自主协商；</p>
<p>（2）使用<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>网站提供的争议调处服务；</p>
<p>（3）请求消费者协会或者其他依法成立的调解组织调解；</p>
<p>（4）向有关行政部门投诉；</p>
<p>（5）根据与争议相对方达成的仲裁协议（如有）提请仲裁机构仲裁；</p>
<p>（6）向人民法院提起诉讼。</p>
<h4>第10条 责任限制及不承诺担保</h4>
<p><strong>10.1</strong>除非另有明确的书面说明,本站及其所包含的或以其它方式通过本站提供给您的全部信息、内容、材料、产品（包括软件）和服务，均是在"按现状"和"按现有"的基础上提供的。</p>
<p><strong>10.2</strong>除非另有明确的书面说明,<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>不对本站的运营及其包含在本网站上的信息、内容、材料、产品（包括软件）或服务作任何形式的、明示或默示的声明或担保（根据中华人民共和国法律另有规定的以外）。</p>
<p><span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>不担保本站所包含的或以其它方式通过本站提供给您的全部信息、内容、材料、产品（包括软件）和服务、其服务器或从本站发出的电子信件、信息没有病毒或其他有害成分。</p>
<p>如因不可抗力或其它本站无法控制的原因使本站销售系统崩溃或无法正常使用导致网上交易无法完成或丢失有关的信息、记录等，<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>会合理地尽力协助处理善后事宜。</p>
<h4>第11条 协议更新及用户关注义务</h4>
<p>根据国家法律法规变化及网站运营需要，<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>有权对本协议条款不时地进行修改，修改后的协议一旦被张贴在本站上即生效，并代替原来的协议。用户可随时登录查阅最新协议；用户有义务不时关注并阅读最新版的协议及网站公告。如用户不同意更新后的协议，可以且应立即停止接受<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>网站依据本协议提供的服务；如用户继续使用本网站提供的服务的，即视为同意更新后的协议。<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>建议您在使用本站之前阅读本协议及本站的公告。 如果本协议中任何一条被视为废止、无效或因任何理由不可执行，该条应视为可分的且并不影响任何其余条款的有效性和可执行性。</p>
<h4>附则</h4>
<p><strong>1</strong><span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>尊重用户和消费者的合法权利，本协议及本网站上发布的各类规则、声明等其他内容，均是为了更好的、更加便利的为用户和消费者提供服务。本站欢迎用户和社会各界提出意见和建议，<span class='wst-mall'><?php echo WSTConf('CONF.mallName'); ?></span>将虚心接受并适时修改本协议及本站上的各类规则。</p>
<p><strong>2</strong>本协议内容中以黑体、加粗、下划线、斜体等方式显著标识的条款，请用户着重阅读。</p>
<p><strong>3</strong>您点击本协议下方的"同意并注册"按钮即视为您完全接受本协议，在点击之前请您再次确认已知悉并完全理解本协议的全部内容。</p>

    </div>
</div>


<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type="text/javascript" src="/static/js/rsa.js"></script>
<script type='text/javascript' src='__MOBILE__/js/login.js?v=<?php echo $v; ?>'></script>

</body>
</html>