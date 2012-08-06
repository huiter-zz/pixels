<ul class="breadcrumb">
  <li>
    像素の逆袭<span class="divider"><i class="icon-chevron-right"></i></span>
  </li>
  <li>
    活动区<span class="divider"><i class="icon-chevron-right"></i></span>
  </li>
  <li class="active"><span class="label">第一期</span>用像素去说那些话。</li>
</ul>

<div class="row">
<div class="span4" style="margin-top:20px">
<p><textarea id="qrtext" name="qrtext"  class="span4" rows="14" placeholder="Pixels提示：在这写下你想说的话。" ></textarea></p>
</div>

<div class="span4">
<form id="thisform" name="thisform" method="post" action="">
<div style="height:100px;margin-top:50px;">
<span for="imageside">二维码边长：</span><input  class="span2" name="imageside" id="imageside" placeholder="不大于500哦！" size="5" maxlength="3" value="300" />
<div class="pull-right">
<input type="button" name="tj" id="tj" value="立即生成二维码" onclick="shengchengtext()" class="btn btn-primary"  title="立即生成二维码" />
</div>
</div>
<hr>

</form>
<div id="noticefirst" class="alert alert-info">请点击"立即生成二维码"生成您的二维码。</div>
<div id="noticeerror" class="alert alert-error" style="display:none;">请点击"立即生成二维码"生成您的二维码。</div>
<div id="notice" class="alert alert-success" style="display:none;">请点击"立即生成二维码"生成您的二维码。</div>
</div>

<div class="span4">
<div id="showqr" style="text-align:center;"><img id="qrimg" class="JIATHIS_IMG_OK" style="hegiht:300px;width:300px;" src="https://chart.googleapis.com/chart?cht=qr&chs=300x300&choe=UTF-8&chld=L&chl=手贱了吧。不过我们还是该对这个世界充满好奇心！^-^"/></div>
</div>
</div>

<hr>
<p><a class="btn qrchange" onclick="qrchange()">换一换</a></p>
<div class="row" name="qrlist">
	<?php foreach ($qr as $key => $value) :?>
		<div class="span2">
			<img width="100" height="100" src="<?php echo $value['message'];?>"/>
			<span class="label"><?php echo $key+1;?></span>
		</div>
	<?php endforeach;?>

</div>
<hr>
  <div>感谢<a class="hottag" href="http://weibo.com/kandisheng"><strong>@阚迪生</strong></a>在github上分享QREncoder的代码。</div>