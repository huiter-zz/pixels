							<!-- Breadcrumb -->
				<ul class="breadcrumb">
				  <li>
					像素の逆袭<span class="divider"><i class="icon-chevron-right"></i></span>
				  </li>
				  <li>
					活动区<span class="divider"><i class="icon-chevron-right"></i></span>
				  </li>
				  <li class="active"><span class="label">第三期</span>来把图片像素化吧。</li>
				</ul>	

			
				<div class="row">
					<div class="span10">
						<input id="imageUrl" type="text" class="span10" placeholder="http://example.png">
						<p><span class="label label-info">注意</span> <code>请在上面输入你想导入的图片的网路地址。</code></p>
					</div>
					<div class="span2">
						<a onclick="getImageFromUrl();" class="btn btn-primary">导入图片</a>
					</div>
				</div>
				<hr>
				<div class="row"> 
					<div class="span12">
						<h4>原始图片</h4>
						<div class="thumbnail " >
							<div id="original" style="display:block;width:400px;margin:0 auto;">
							<a > 
								<img id="imgOriginal"  src="http://placehold.it/400x200" alt=""> 
							</a>
							</div>
							<hr>
							<div class="btn-toolbar" >
								<a onclick="pixelate(this);" class="btn btn-primary btn-large "style="display:block;margin:0 auto;width:100px" >处理图片</a>
							</div>
						</div>
					</div>
					<div id="placeForImage" class="modal hide fade" style="left:40%;width:800px;top:50%;">
						<div class="modal-header">
			                <button type="button" class="close" data-dismiss="modal">×</button>
			                <h3>等待10秒左右。<code>进度条是假的。</code></h3>
			                <div class="progress progress-striped active">
        						<div class="bar" id="progressbar" style="width: 25%"></div>
     						 </div>
			              </div>
			             <div class="modal-body" style="">
			             	<div id="hack">
                				<img id="imgProcessed" src="http://placehold.it/400x200" alt="">
                			</div>
              			 </div>
						
					</div>
				</div>
			
