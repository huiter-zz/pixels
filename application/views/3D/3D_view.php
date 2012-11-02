<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<link href="http://storage.aliyun.com/pixels/assets/css/bootstrap.css" rel="stylesheet">
	<link href="http://storage.aliyun.com/pixels/assets/colorpicker/colorpicker.css" rel="stylesheet">
	<title>像素の逆袭，By Shortytall</title>
</head>
<body>
	<span id="cubejson" style="display:none;"><?php echo file_get_contents($work['cubejson']);?></span>
	<div class="container-fluid">
		<div class="row-fluid">
			<!--tool bar-->
			<div class="span3">
				<div style="margin: 0;" class="btn-toolbar">
					<hr>
					<div data-toggle="buttons-radio" class="btn-group btn-group-vertical">
						<a id="brush" class="btn btn-large"> <i class="icon-stop"></i>
							画笔
						</a>
						<a id="eraser" class="btn btn-large"> <i class="icon-remove"></i>
							橡皮
						</a>
					</div>
					<hr>
					<div class="btn-group btn-group-vertical">
						<a id="undo" class="btn btn-large">
							<i class="icon-arrow-left"></i>
							撤销
						</a>
						<a id="redo" class="btn btn-large">
							<i class="icon-arrow-right"></i>
							恢复
						</a>
						<a id="clear" class="btn btn-large">
							<i class="icon-refresh"></i>
							清空
						</a>
						<a id="zoomIn" class="btn btn-large">
							<i class="icon-zoom-in"></i>
							放大
						</a>
						<a id="zoomOut" class="btn btn-large">
							<i class="icon-zoom-out"></i>
							缩小
						</a>
					</div>
					<hr>
					<div class="btn-group">
						<a class="btn btn-large">
							<i class="icon-resize-full"></i>
							画布大小
						</a>
						<button data-toggle="dropdown" class="btn dropdown-toggle btn-large">
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li>
								<a id="size20" href="#">20x20</a>
							</li>
							<li>
								<a id="size40" href="#">40x40</a>
							</li>
							<li>
								<a id="size80" href="#">80x80</a>
							</li>
							<li>
								<a id="size160" href="#">160x160</a>
							</li>
						</ul>
					</div>
					<hr>
					<div class="btn-group btn-group-vertical">
						<a id="saveAsImg" class="btn btn-large">
							<i class="icon-camera"></i>
							另存为图片
						</a>
					</div>
					<hr>
					<div id="cp" data-color="rgb(255,255,255)" data-color-format="rgb" class="input-append color">
						<!--color picker-->
						<input type="text">
						<span class="add-on">
							<i style="background-color: rgb(255, 255, 255); "></i>
						</span>
					</div>
				</div>
			</div>
			<div id="canvas_container" class="span9"></canvas>
		</div>
	</div>
</div>
<script src="http://storage.aliyun.com/pixels/assets/js/jquery-1.7.2.min.js"></script>
<script src="http://storage.aliyun.com/pixels/assets/js/bootstrap.min.js"></script>
<script src="http://storage.aliyun.com/pixels/assets/js/application.js"></script>
<script src="http://storage.aliyun.com/pixels/assets/colorpicker/bootstrap-colorpicker.js"></script>
<script src="http://storage.aliyun.com/pixels/assets/js/undo.js"></script>
<script src="http://storage.aliyun.com/pixels/assets/js/Three.js"></script>
<script src="http://storage.aliyun.com/pixels/assets/js/main.js"></script>
</body>
</html>