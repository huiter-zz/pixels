
<!--上面是colorpicker的资源文件-->
		<script type="text/javascript">
			var Pixels2D = (function() {
				var API ={};
			    var action = [];
			    var timecount=[];//标记settimeout
				var pcount=0;
				
			    var canvas;
			    var context;
				
			    var worldwidth=$("#world").width();
			    var worldheight=$("#world").height();
			    var playspeed=800;
				var backgroundcolor="#ffffff";
								
				API.Initialize = function(){
					var tempjson=$("#cubejson").text();
					loadJSON(tempjson);
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
					paintworld();
					repaint();
				}
				
				API.SetPlayspeed = function(value){
					playspeed=value;
				}
				
				function show(){
					canvas = document.getElementById('world');
			   		context = canvas.getContext('2d');
					if(pcount<action.length){
						var current = action[pcount],
						a = current.a,
						rx = current.x,
						ry = current.y,
						rc = current.c,
						rw= current.w;
						if(a == 'a')
						{							
							context.fillStyle = rc;
							context.fillRect(rx,ry,rw,rw);
						}
						else
						{
							context.clearRect(rx,ry,rw,rw);
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
					var context = document.getElementById('world').getContext('2d');
					context.fillStyle=color;
					context.fillRect(0,0,worldwidth,worldheight);
				}
				 
				function loadJSON(mapJson){
					action.length=0;//clear action
					action=JSON.parse(mapJson);
					};	
					
				function clearworld(){
					canvas = document.getElementById('world');
			   		context = canvas.getContext('2d');
					context.clearRect(0,0,worldwidth,worldheight);
			   }//清除画布
			   
			    function repaint(){
					canvas = document.getElementById('world');
			   		context = canvas.getContext('2d');
					$.each(action,function(index){
						var a = $(this).attr('a'),
						x = $(this).attr('x');
						y= $(this).attr('y');
						c= $(this).attr('c');
						w = $(this).attr('w');
						if(a == 'a')
						{							
							context.fillStyle = c;
							context.fillRect(x,y,w,w);				
						}
						else
						{
							context.clearRect(x,y,w,w);
						}

					});
			   }
				return API;
			})();
			Pixels2D.Initialize();
		</script>
		<script type="text/javascript">
			 
			$("#colorPicker").ColorPicker({
                
                color:"#ffffff",
                
                onShow:function(colpkr){
                    
                    $(colpkr).fadeIn(500);                  
                    return false;
                },
                
                onHide:function(colpkr){                   
                    $(colpkr).fadeOut(500);
                    return false;
                },
                
                onChange:function(hsb, hex, rgb){
					Pixels2D.SetBackgroundColor("#"+hex);
					Pixels2D.ChangeBackground();	
                },
                
                onSubmit:function(hsb, hex, rgb, el){                       					
                    $(el).ColorPickerHide()
					Pixels2D.SetBackgroundColor("#"+hex);
					Pixels2D.ChangeBackground();
                }
            });
				
			$("#play").click(function(){
				Pixels2D.Play();		
			});
			$("#stop").click(function(){					
				Pixels2D.Stop();
			});
			$("#speedslider").slider({
				orientation: "vertical",
				range: "min",
				min: 0,
				max: 800,
				value: 0,
				change: function(event, ui) { 
					Pixels2D.SetPlayspeed(800-ui.value);
				}
			});
		</script>