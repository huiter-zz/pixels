<script src="http://storage.aliyun.com/pixels/assets/colorpicker/bootstrap-colorpicker.js"></script>
<script src="http://storage.aliyun.com/pixels/assets/js/close-pixelate.js"></script>
<script src="http://storage.aliyun.com/pixels/assets/js/canvas2image.js"></script>
<script src="http://storage.aliyun.com/pixels/assets/js/base64.js"></script>
<script src="http://storage.aliyun.com/pixels/assets/Jquery-ui/jquery-ui-1.8.20.js"></script>

<!--上面是colorpicker的资源文件-->
		<script type="text/javascript">
			var Pixels2D = (function() {
				var API ={};
			    var action = [];
			    var timecount=[];//标记settimeout
				var pcount=0;
				
			    var canvas;
			    var context;
				
				var cubewidth;
				var movex=0;
				var movey=0;
				
			    var worldwidth=$("#world").width();
			    var worldheight=$("#world").height();
			    var playspeed=800;
				var backgroundcolor="transparent";
								
				API.Initialize = function(){
					var tempjson=$("#cubejson").text();
					loadJSON(tempjson);
					canvas = document.getElementById('world');
			   		context = canvas.getContext('2d');
					context.translate(worldwidth/2,worldheight/2);
					clearworld();
					repaint();	
				}
				
				API.SetBackgroundColor = function(color){
					backgroundcolor=color;
				}
				
				API.ChangeBackground = function(){
					paintworld(backgroundcolor);
					repaint();	
				}
				
				API.Play = function(){
					clearTimeCount();
					pcount=0;
					clearworld();
					paintworld(backgroundcolor);
					show();
				}
				
				API.Stop = function(){
					clearTimeCount();
					clearworld();
					paintworld(backgroundcolor);
					repaint();
				}
				
				API.SetPlayspeed = function(value){
					playspeed=value;
				}
				
				var angle=0;
				API.Rotate2left = function(){
					clearTimeCount();
					pcount=0;
					clearworld();
					paintworld(backgroundcolor);
					context.rotate(-Math.PI/18);
					angle-=Math.PI/18;
					repaint();
				}
				
				API.Rotate2right = function(){
					clearTimeCount();
					pcount=0;
					clearworld();
					paintworld(backgroundcolor);
					context.rotate(Math.PI/18);
					angle+=Math.PI/18;
					repaint();
				}
				
				API.Move2left = function(){
					clearworld();
					movex-=cubewidth*Math.cos(angle)
					movey+=cubewidth*Math.sin(angle)
					paintworld(backgroundcolor);
					repaint();
				}
				
				API.Move2right = function(){
					clearworld();
					movex+=cubewidth*Math.cos(angle)
					movey-=cubewidth*Math.sin(angle)
					paintworld(backgroundcolor);
					repaint();
				}
				API.Move2down = function(){
					clearworld();
					movey+=cubewidth*Math.cos(angle)
					movex+=cubewidth*Math.sin(angle)
					paintworld(backgroundcolor);
					repaint();
				}
				API.Move2up = function(){
					clearworld();
					movey-=cubewidth*Math.cos(angle)
					movex-=cubewidth*Math.sin(angle)
					paintworld(backgroundcolor);
					repaint();
				}
				API.Refresh = function(){
					movex=0;
					movey=0;
					context.rotate(-angle);
					angle=0;
					clearworld();
					paintworld(backgroundcolor);
					repaint();
				}
				function show(){
					if(pcount<action.length){
						var current = action[pcount],
						a = current.a,
						rx = current.x+movex,
						ry = current.y+movey,
						rc = current.c,
						rw= current.w;
						if(a == 'a')
						{							
							context.fillStyle = rc;
							context.fillRect(rx,ry,rw,rw);
						}
						else
						{
							//context.clearRect(rx,ry,rw,rw);
							context.fillStyle=backgroundcolor;
							context.fillRect(rx,ry,rw,rw);
						}
						pcount++;
						var temp=setTimeout(function(){show();},playspeed);
						timecount.push(temp);
					}
					else{
						pcount=0;
					}
				
			   }
				function clearTimeCount(){
					$.each(timecount,function(index){
						clearTimeout(timecount[index]);
					});
					timecount.length=0;
			    }
				function paintworld(color){
					context.save();
					context.rotate(angle);
					context.fillStyle=color;
					context.fillRect(-worldwidth,-worldheight,2*worldwidth,2*worldheight);
					context.restore();
				}
				 
				function loadJSON(mapJson){
					action.length=0;//clear action
					action=JSON.parse(mapJson);
					};	
					
				function clearworld(){
					context.clearRect(-worldwidth/2,-worldheight/2,worldwidth,worldheight);
			   }//清除画布
			   
			    function repaint(){
					/*context.lineWidth=1;
					context.moveTo(-worldwidth/2,-worldheight/2);
					context.lineTo(-worldwidth/2,worldheight/2);					
					context.lineTo(worldwidth/2,worldheight/2);
					context.lineTo(worldwidth/2,-worldheight/2);
					context.lineTo(-worldwidth/2,-worldheight/2);
					context.stroke();本来也是为了去白线，结果都变成了黑线...为什么...*/
					
					$.each(action,function(index){
						var a = $(this).attr('a'),
						x = $(this).attr('x')+movex;
						y= $(this).attr('y')+movey;
						c= $(this).attr('c');
						w = $(this).attr('w');
						cubewidth=w;
						if(a == 'a')
						{							
							context.fillStyle = c;
							context.fillRect(x,y,w,w);				
						}
						else
						{
							//context.clearRect(x,y,w,w);
							context.fillStyle=backgroundcolor;
							context.fillRect(x,y,w,w);
						}

					});
			   }
				return API;
			})();
			Pixels2D.Initialize();
		</script>
		<script type="text/javascript">
			$("#play").click(function(){
				Pixels2D.Play();		
			});
			$("#stop").click(function(){					
				Pixels2D.Stop();
			});

			$('#cp').colorpicker().on('changeColor', function(ev){
				Pixels2D.SetBackgroundColor(ev.color.toHex());
				Pixels2D.ChangeBackground(); 
				});

			$("#save").click(function(){					
				var oCanvas = document.getElementById("world");
				var work = Canvas2Image.saveAsPNG(oCanvas);  
			});
			$("#rotate2left").click(function(){
				Pixels2D.Rotate2left();
			});
			$("#rotate2right").click(function(){
				Pixels2D.Rotate2right();
			});
			$("#move2left").click(function(){
				Pixels2D.Move2left();
			});
			$("#move2right").click(function(){
				Pixels2D.Move2right();
			});
			$("#move2up").click(function(){
				Pixels2D.Move2up();
			});
			$("#move2down").click(function(){
				Pixels2D.Move2down();
			});
			$("#refresh").click(function(){
				Pixels2D.Refresh();
			});
			$("#speedslider").slider({
				//orientation: "vertical",
				range: "min",
				min: 0,
				max: 800,
				value: 0,
				slide: function(event, ui) { 
					Pixels2D.SetPlayspeed(800-ui.value);
				}
			});
		</script>   