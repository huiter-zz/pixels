			<!-- Breadcrumb -->
			<ul class="breadcrumb">
			  <li>
			    像素の逆袭<span class="divider"><i class="icon-chevron-right"></i></span>
			  </li>
			  <li>
			    活动区<span class="divider"><i class="icon-chevron-right"></i></span>
			  </li>
			  <li class="active"><span class="label">第二期</span>一起来玩俄罗斯方块。</li>
			</ul>

      <div id="myModal" class="modal hide fade in">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" >×</button>
              <h3>游戏结束</h3>
            </div>
            <div class="modal-body">
              <h4>你的成绩不错哦，留个名吧。</h4>
              <p><span class="label label-info">注意!</span>输入名字，点确认，也许你就可以登上左侧的排行榜啦。太荣耀是不是！</p>
              <br>
              <form class="form-horizontal">
                <fieldset>
                  <div class="control-group">
                    <label class="control-label" for="focusedInput">名字</label>
                    <div class="controls">
                      <input class="input-xlarge focused" id="name" type="text" placeholder="最多可输入8个字" maxlength="8">
                    </div>
                  </div>
                </fieldset>
              </form>
            </div>
            <div class="modal-footer">
              <a href="#" class="btn btn-primary" id="scoresubmit">确定</a>
            </div>
          </div>

                <div id="tetris">
                     <div class="row"> 

                    <div class="span3 offset1">
                      <div id="preview" class="well"><canvas id="preview_area" height="150px" width="150px" class=""></canvas></div>
                          <table class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th colspan="3">排行榜</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td width="15px"><span class="badge badge-success">1</span></td>
                                <td width="15px"><code><?php echo $tetris[0]['score'];?></code></td>
                                <td><?php echo $tetris[0]['name'];?></td>
                              </tr>
                              <tr>
                                <td><span class="badge badge-warning">2</span><td><code><?php echo $tetris[1]['score'];?></code></td>
                                <td><?php echo $tetris[1]['name'];?></td>
                              </tr>
                              <tr>
                                <td><span class="badge badge-important">3</span></td><td><code><?php echo $tetris[2]['score'];?></code></td>
                                <td><?php echo $tetris[2]['name'];?></td>
                              </tr>
                              <tr>
                                <td><span class="badge">4</span></td><td><code><?php echo $tetris[3]['score'];?></code></td>
                                <td><?php echo $tetris[3]['name'];?></td>
                              </tr>
                              <tr>
                                <td><span class="badge">5</span></td><td><code><?php echo $tetris[4]['score'];?></code></td>
                                <td><?php echo $tetris[4]['name'];?></td>
                              </tr>
                              <tr>
                                <td><span class="badge">6</span></td><td><code><?php echo $tetris[5]['score'];?></code></td>
                                <td><?php echo $tetris[5]['name'];?></td>
                              </tr>
                              <tr>
                                <td><span class="badge">7</span></td><td><code id="lowscore" value="<?php echo $tetris[6]['score'];?>"><?php echo $tetris[6]['score'];?></code></td>
                                <td><?php echo $tetris[6]['name'];?></td>
                              </tr>
                            </tbody>
                        </table>
 			 	 					 
                    </div>


                      <div class="span4">
                          <!-- 游戏区域 -->
                          <div id="game">
                            <canvas id="game_area" class="thumbnail" height="480" width="300" >您的浏览器不给力呀，换个Chrome吧！</canvas>                                        
                          </div>
                      </div>

                      <div class="span3">
                        <!-- 预览区域 -->
                        <table class="table table-bordered table-striped">
                            <tbody>
                              <tr>
                                <td>
                                  <span class="label label-inverse">等级</span>
                                </td>
                                <td>
                                  <code><span id="level_area"></span></code>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <span class="label label-inverse">分数</span>
                                </td>
                                <td>
                                  <code><span id="score_area">0</span></code>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <span class="label label-inverse">本级目标</span>
                                </td>
                                <td>
                                  <code><span id="levelscore">1</span></code>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        <div id="cxprogressbar" class="progress">
                          <div id="progressbar" class="bar" style="width: 0%;"></div>
                        </div> 
                          <table class="table table-bordered table-condensed">
                            <thead>
                              <tr>
                                <th>变形</th>
                                <th>加速</th>
                                <th>向左</th>
                                <th>向右</th>
                                <th>暂停</th>
                                <th>重来</th>
                                <th>声音</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td><code><i class="icon-arrow-up"></i></code></td>
                                <td><code><i class="icon-arrow-down"></i></code></td>
                                <td><code><i class="icon-arrow-left"></i></code></td>
                                <td><code><i class="icon-arrow-right"></i></code></td>
                                <td><code>P</code></td>
                                <td><code>N</code></td>
                                <td><code>M</code></td>
                              </tr>
                            </tbody>
                          </table>


                      <div id ="buttonarea">
                        
                        <div id="pause" class="btn" title="暂停游戏"></div>
                        <div id="restart"class="btn" title="重新开始"></div>
                        <div id="sound" class="btn" title="关闭声音"></div>
                      </div>  
                        <table id="testarea">
                          <tbody> 
                            <tr>
                            </tr>  
                            <tr>
                              <td id="up" class="btn"></td>
                              <td></td>
                            </tr>
                            <tr>
                              <td id="left" class="btn"></td>
                              <td id="down" class="btn"></td>
                              <td id="right" class="btn"></td>
                            </tr>
                            <tr>
                              <td id="space" class="btn" colspan="3"></td>
                            </tr>
                          </tbody>
                        </table>  
                    </div>
                </div>
				<audio src="http://storage.aliyun.com/pixels/assets/sounds/drop.mp3" id="drop_sound"></audio>
				<audio src="http://storage.aliyun.com/pixels/assets/sounds/clear.mp3" id="clear_sound"></audio>
				<audio src="http://storage.aliyun.com/pixels/assets/sounds/gameover.mp3" id="gameover_sound"></audio>
			  <audio src="http://storage.aliyun.com/pixels/assets/sounds/clap.mp3" id="clap_sound"></audio>

            </div>
            