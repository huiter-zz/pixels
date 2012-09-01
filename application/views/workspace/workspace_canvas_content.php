<ul class="breadcrumb">
  <li>
    像素の逆袭<span class="divider"><i class="icon-chevron-right"></i></span>
  </li>
  <li class="active"><span class="label">工作间</span></li>
</ul>
<?php if(!empty($work)):?>
<span id="cubejson" style="display:none;"><?php echo file_get_contents($work['cubejson']);?></span>
<?php endif;?>

<?php if($this->session->userdata('userdata')):?>
	<div class="row" style="margin: 0 auto 10px 15px;">
		<div class="offset2 thumbnail span7" ><span class="label label-important">说明</span>拖动工具栏到合适的位置，选择合适的格子密度，然后开始创作吧。</div>
		<a data-toggle="modal" href="#worksubmit" class="btn btn-danger" style="margin:-2px 0 0 10px;" id="workpush">发布作品</a>
	</div>	
<?php else:?>
	<div class="row" style="margin: 0 auto 10px 15px;">
		<div class="offset2 thumbnail span7" ><span class="label label-important">注意!</span>你还没有登录，作品是无法保存的。Pixels支持新浪微博、人人、QQ多种账号登录。</div>
	</div>	
<?php endif;?>
<!--画布-->
	<div style="height:600px;position:relative;">
		<div id="workplace" style="margin:0 auto; width:840px;">
			<canvas id="worldbackground" width="840 px" height="540 px" style="background:transparent;z-index:1;margin:0 auto;position:absolute;top:0px;"></canvas>
			<canvas id="world" width="840 px" height="540 px" style="z-index:999999999;"></canvas>
		</div>
					<!--工具栏图标-->
		<div  id="tool-window" style="position:absolute; left:5px; top:100px;">
			<div class="cx-dialog cx-toolbox">
				<div class="cx-toolbar">
					<div id="singleCube" class="cx-button cx-icon-addcube" title="添加方块"></div>
					<div id="clean" class="cx-button cx-icon-eraser" title="清除方块"></div>
					<div id="undo" class="cx-button cx-icon-undo" title="撤销"></div>	
					<div id="redo" class="cx-button cx-icon-redo" title="重做"></div>
					<div id="dustbin" class="cx-button cx-icon-dustbin" style="background-position: -102px -34px;width: 34px;height: 34px; background-image: url('http://storage.aliyun.com/pixels/assets/img/button/iconset1_v4.png');background-repeat: no-repeat;" title="清除全部"></div>					
				    <div id="grid" class="cx-button cx-icon-grid" style="background-position: -34px 0;width: 34px;height: 34px; background-image: url('http://storage.aliyun.com/pixels/assets/img/button/iconset1_v4.png');background-repeat: no-repeat;" title="显示网格"></div>
					<div id="save" class="cx-button cx-icon-save" style="background-position: 0 0;height: 34px;width: 34px; background-image: url('http://storage.aliyun.com/pixels/assets/img/button/iconset1_v4.png');background-repeat: no-repeat;" title="保存模型"></div>					
				    <div id="open" class="cx-button cx-icon-open" style="display:none;background-position: -34px -136px;height: 34px;width: 34px; background-image: url('http://storage.aliyun.com/pixels/assets/img/button/iconset1_v4.png');background-repeat: no-repeat;" title="显示网格"></div>
					</div>
			</div>
		</div>
			
			<!-- 取色部分图标 -->
			<div  id="color-window" style="position:absolute; left: 260px; top: 480px; ">
				<div class="cx-dialog cx-colorpicker">
						<div class="cx-colorpalette">				
							<div id="3D" class="cx-button cx-icon-colorpicker colorpick" style="display:none;"title="取色管"></div>
							<div id="colorp" class="cx-icon-palette" title="调色板"></div>
							<div id="ocolor0" class="ocolor" title="历史颜色" style="background-color: transparent "></div>
							<div id="ocolor1" class="ocolor" title="历史颜色" style="background-color: transparent"></div>
							<div id="ocolor2" class="ocolor" title="历史颜色" style="background-color: transparent"></div>
							<div id="ocolor3" class="ocolor" title="历史颜色" style="background-color: transparent"></div>
							<div id="ocolor4" class="ocolor" title="历史颜色" style="background-color: transparent"></div>
							<div id="ocolor5" class="ocolor" title="历史颜色" style="background-color: transparent"></div>
							<div id="ocolor6" class="ocolor" title="历史颜色" style="background-color: transparent"></div>
							<div id="ocolor7" class="ocolor" title="历史颜色" style="background-color: transparent"></div>
							<div id="ocolor8" class="ocolor" title="历史颜色" style="background-color: transparent"></div>
							<div id="ocolor9" class="ocolor" title="历史颜色" style="background-color: transparent"></div>
							<div id="ocolor10" class="ocolor" title="历史颜色" style="background-color: transparent"></div>
							<div id="ocolor11" class="ocolor" title="历史颜色" style="background-color: transparent"></div>

						</div>
			    </div>
			</div>

			<!-- 调色板 -->
			<div  id="colorpad-window"style="position:absolute;left: 500px; top: 100px;">
				<div id="colorpad" class="cx-dialog " style="z-index: 11; display: none;width:212 px; height: 272 px; ">
					<table class="cx-colorpad" cellpadding="0" cellspacing="0">
						<tbody>	
							<tr>
								<td class="cx-dialog-title" colspan="6" >编辑颜色</td>
								<td id="cx-colorpad-close" class="cx-dialog-close cx-icon-sketchdel" ></td>
							</tr>
							<tr>
								<td rowspan="12" class="cx-colorpad-colorclum">	
									<div class="cx-colorpad-grid">
											<span class="QuickColor" title="ffaaaa" style="background-color: #ffaaaa;">&nbsp;</span>
											<span class="QuickColor" title="ff5656" style="background-color: #ff5656;">&nbsp;</span>
											<span class="QuickColor" title="ff0000" style="background-color: #ff0000;">&nbsp;</span>
											<span class="QuickColor" title="#bf0000" style="background-color: #bf0000;">&nbsp;</span>
											<span class="QuickColor" title="7f0000" style="background-color: #7f0000;">&nbsp;</span>
											<span class="QuickColor" title="ffffff" style="background-color: #ffffff;">&nbsp;</span>
											<span class="QuickColor" title="ffd4aa" style="background-color: #ffd4aa;">&nbsp;</span>
											<span class="QuickColor" title="ffaa56" style="background-color: #ffaa56;">&nbsp;</span>
											<span class="QuickColor" title="ff7f00" style="background-color: #ff7f00;">&nbsp;</span>
											<span class="QuickColor" title="bf5f00" style="background-color: #bf5f00;">&nbsp;</span>
											<span class="QuickColor" title="7f3f00" style="background-color: #7f3f00;">&nbsp;</span>
											<span class="QuickColor" title="e5e5e5" style="background-color: #e5e5e5;">&nbsp;</span>
											<span class="QuickColor" title="ffffaa" style="background-color: #ffffaa;">&nbsp;</span>
											<span class="QuickColor" title="ffff56" style="background-color: #ffff56;">&nbsp;</span>
											<span class="QuickColor" title="ffff00" style="background-color: #ffff00;">&nbsp;</span>
											<span class="QuickColor" title="bfbf00" style="background-color: #bfbf00;">&nbsp;</span>
											<span class="QuickColor" title="7f7f00" style="background-color: #7f7f00;">&nbsp;</span>
											<span class="QuickColor" title="cccccc" style="background-color: #cccccc;">&nbsp;</span>
											<span class="QuickColor" title="d4ffaa" style="background-color: #d4ffaa;">&nbsp;</span>
											<span class="QuickColor" title="aaff56" style="background-color: #aaff56;">&nbsp;</span>
											<span class="QuickColor" title="7fff00" style="background-color: #7fff00;">&nbsp;</span>
											<span class="QuickColor" title="5fbf00" style="background-color: #5fbf00;">&nbsp;</span>
											<span class="QuickColor" title="3f7f00" style="background-color: #3f7f00;">&nbsp;</span>
											<span class="QuickColor" title="b2b2b2" style="background-color: #b2b2b2;">&nbsp;</span>
											<span class="QuickColor" title="aaffaa" style="background-color: #aaffaa;">&nbsp;</span>
											<span class="QuickColor" title="56ff56" style="background-color: #56ff56;">&nbsp;</span>
											<span class="QuickColor" title="00ff00" style="background-color: #00ff00;">&nbsp;</span>
											<span class="QuickColor" title="00bf00" style="background-color: #00bf00;">&nbsp;</span>
											<span class="QuickColor" title="007f00" style="background-color: #007f00;">&nbsp;</span>
											<span class="QuickColor" title="999999" style="background-color: #999999;">&nbsp;</span>
											<span class="QuickColor" title="aaffd4" style="background-color: #aaffd4;">&nbsp;</span>
											<span class="QuickColor" title="56ffaa" style="background-color: #56ffaa;">&nbsp;</span>
											<span class="QuickColor" title="00ff7f" style="background-color: #00ff7f;">&nbsp;</span>
											<span class="QuickColor" title="00bf5f" style="background-color: #00bf5f;">&nbsp;</span>
											<span class="QuickColor" title="007f3f" style="background-color: #007f3f;">&nbsp;</span>
											<span class="QuickColor" title="7f7f7f" style="background-color: #7f7f7f;">&nbsp;</span>
											<span class="QuickColor" title="aaffff" style="background-color: #aaffff;">&nbsp;</span>
											<span class="QuickColor" title="56ffff" style="background-color: #56ffff;">&nbsp;</span>
											<span class="QuickColor" title="00ffff" style="background-color: #00ffff;">&nbsp;</span>
											<span class="QuickColor" title="00bfbf" style="background-color: #00bfbf;">&nbsp;</span>
											<span class="QuickColor" title="007f7f" style="background-color: #007f7f;">&nbsp;</span>
											<span class="QuickColor" title="666666" style="background-color: #666666;">&nbsp;</span>
											<span class="QuickColor" title="aad4ff" style="background-color: #aad4ff;">&nbsp;</span>
											<span class="QuickColor" title="56aaff" style="background-color: #56aaff;">&nbsp;</span>
											<span class="QuickColor" title="007fff" style="background-color: #007fff;">&nbsp;</span>
											<span class="QuickColor" title="005fbf" style="background-color: #005fbf;">&nbsp;</span>
											<span class="QuickColor" title="003f7f" style="background-color: #003f7f;">&nbsp;</span>
											<span class="QuickColor" title="4c4c4c" style="background-color: #4c4c4c;">&nbsp;</span>
											<span class="QuickColor" title="aaaaff" style="background-color: #aaaaff;">&nbsp;</span>
											<span class="QuickColor" title="5656ff" style="background-color: #5656ff;">&nbsp;</span>
											<span class="QuickColor" title="0000ff" style="background-color: #0000ff;">&nbsp;</span>
											<span class="QuickColor" title="0000bf" style="background-color: #0000bf;">&nbsp;</span>
											<span class="QuickColor" title="00007f" style="background-color: #00007f;">&nbsp;</span>
											<span class="QuickColor" title="333333" style="background-color: #333333;">&nbsp;</span>
											<span class="QuickColor" title="d4aaff" style="background-color: #d4aaff;">&nbsp;</span>
											<span class="QuickColor" title="aa56ff" style="background-color: #aa56ff;">&nbsp;</span>
											<span class="QuickColor" title="7f00ff" style="background-color: #7f00ff;">&nbsp;</span>
											<span class="QuickColor" title="5f00bf" style="background-color: #5f00bf;">&nbsp;</span>
											<span class="QuickColor" title="3f007f" style="background-color: #3f007f;">&nbsp;</span>
											<span class="QuickColor" title="191919" style="background-color: #191919;">&nbsp;</span>
											<span class="QuickColor" title="ffaaff" style="background-color: #ffaaff;">&nbsp;</span>
											<span class="QuickColor" title="ff56ff" style="background-color: #ff56ff;">&nbsp;</span>
											<span class="QuickColor" title="ff00ff" style="background-color: #ff00ff;">&nbsp;</span>
											<span class="QuickColor" title="bf00bf" style="background-color: #bf00bf;">&nbsp;</span>
											<span class="QuickColor" title="7f007f" style="background-color: #7f007f;">&nbsp;</span>
											<span class="QuickColor" title="000000" style="background-color: #000000;">&nbsp;</span>
											<span class="QuickColor" title="ffaad4" style="background-color: #ffaad4;">&nbsp;</span>
											<span class="QuickColor" title="ff56aa" style="background-color: #ff56aa;">&nbsp;</span>
											<span class="QuickColor" title="ff007f" style="background-color: #ff007f;">&nbsp;</span>
											<span class="QuickColor" title="bf005f" style="background-color: #bf005f;">&nbsp;</span>
											<span class="QuickColor" title="7f003f" style="background-color: #7f003f;">&nbsp;</span>
											<span class="QuickColor" title="透明色" style="background-color: transparent; background-image: url(http://storage.aliyun.com/pixels/assets/img/button/NoColor.png);">&nbsp;</span>
										</div>			
								</td>
								<td colspan="2">
									<div class="cx-colorad-preview">new
										<div style="background-image: url(http://storage.aliyun.com/pixels/assets/img/button/preview-opacity.png); ">
											<span class="Active" title="新颜色-点击确定按钮使用" style="background-color: rgb(255, 86, 86); visibility: visible; ">&nbsp;</span>
											<span class="Current" title="单击返回上一颜色" style="background-color: rgb(255, 86, 86); ">&nbsp;</span>
										</div>current
									</div>
								</td>    
							</tr>
							<tr>
								<td colspan="2"><input id="cx-applyColor" type="button" class="cx-button-colorpad" value="确定" title="应用当前颜色">	</td>
							</tr>	
							<tr>
								<td><label class="cx-dialog-label" for="cx-colorvalue" title="输入颜色值(#000000-#ffffff)">#: </label></td>
								<td class="cx-text-colorpad">
									<input type="text" maxlength="6" class="cx-colorvalue" value="ff5656" title="输入颜色值(#000000-#ffffff)" style="ime-mode:Disabled">&nbsp;
								</td>	
							</tr>	
							<tr>
								<td colspan="2">
								   <input id="cx-previewColor" type="button" class="cx-button-colorpad" value="预览" title="查看数值对应颜色">
								</td>
							</tr>	
						</tbody>
					</table>
				</div>
			</div>
	
		<?php if($this->session->userdata('userdata')):?>
			<div id="worksubmit" class="modal hide fade">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">×</button>
              <h3>发布作品</h3>
            </div>
            <div class="modal-body">
            	<img id="picture" width=420 height=270 style="display:block;margin:0 auto;border:1px solid #BBBBBB;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;">
            	<hr>
			       <form class="form-horizontal">
			        <fieldset>
			          <div class="control-group">
			            <label class="control-label">标签:</label>
			            <div class="controls docs-input-sizes">
			              <input class="input-mini" type="text" placeholder="" id="tag1" value="">
			              <input class="input-mini" type="text" placeholder="" id="tag2" value="">
			              <input class="input-mini" type="text" placeholder="" id="tag3" value="">
			              <p class="help-block"> 为作品添加上标签,使别人更容易发现它们。</p>
			            </div>
			          </div>
        			</fieldset>
      			</form>
            </div>
            <div class="modal-footer">
              <a href="#" class="btn btn-danger"  id="workpost">提交</a>
            </div>
          </div>
      <?php endif;?>
			<div class="pull-right">
				<a data-toggle="modal" href="#changenumber" class="btn cx-changebtn btn-mini" id="num0">84×54</a>
				<a data-toggle="modal" href="#changenumber" class="btn cx-changebtn btn-mini" id="num1">56×36</a>
				<a data-toggle="modal" href="#changenumber" class="btn cx-changebtn btn-mini" id="num2">42×27</a>
				<a data-toggle="modal" href="#changenumber" class="btn cx-changebtn btn-mini" id="num3">28×18</a>				
			</div>

		<table style="width:70px;height:60px;">
			<tr colspan="3">
				<td width="33%"></td>
				<td width="33%"><button id="up" class="btn"><i class="icon-arrow-up"></i></button></td>
				<td width="33%"></td>
			</tr>
			<tr>
				<td ><button id="left" class="btn"><i class="icon-arrow-left"></i></button></td>
				<td ><button id="down" class="btn"><i class="icon-arrow-down"></i></button></td>
				<td ><button id="right" class="btn"><i class="icon-arrow-right"></i></button></td>
			</tr>
		</table>
			
			<div id="changenumber" class="modal hide fade">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">×</button>
				  <div>
					<h3><span class="label label-wang">友情提示</span>真的要切换格子的数目？</h3>
			      </div>
			    </div>
				<div class="modal-body">
				  <h4>切换后当前未提交作品会被清空的！！</h6>
				</div>
				<div class="modal-footer">
				  <div class="btn btn-primary" data-dismiss="modal" id="changegrid">确定</div>
				</div>
			</div>
		</div>
		<hr>
		