<script type="text/javascript">
  var Tetris = (function(){
      //游戏区域
    var gameArea,
      //预览区
      previewArea,

      //游戏区域上下文

      gameCtx,

      //预览区域上下文

      previewCtx,

      //方格宽度

      space,

      //游戏区域总行数

      rows,

      //游戏区域总列数

      cols,

      //方块下落延时时间长，越小越快

      gameSpeed,

      //方块正常下落的时延长

      normalGameSpeed,

      //方块快速下落的时延长

      fastGameSpeed,

      //各等级方块下落时延的数组

      gameSpeedRange,

      //方块下落延时时间句柄

      delay,

      //游戏是否进行中，当暂停或结束时为true

      isGameGoing,

      //7种方块的形状，每种形状为一个数组，每种变型为一个十六进制数

      shapes,

      //游戏区域的矩阵容器，二维数组，数组项为1代表该位置有方块，为0代表没有方块

      map,

      //当前正在下落的方块形状

      currentShape,

      //当前正在下落的方块下标

      currentShapeIndex,

      //当前正在下落的方块的变型下标

      currentShapeTransformIndex,

      //当前正在下落的方块的位置

      currentPosition,

      //预览区域的方块的下标

      previewShapeIndex,

      //游戏得分

      score,

      //当前等级

      level,

      //是否允许播放声音

      isSoundPermit,

      //达到每一级的分数

      scoreToUpgrade,

      //每一级的颜色，数组长度必须与scoreToUpgrade长度相等

      colors = [];
      
      //progressbar 的样式
      barstyle =[];

      //初始化数据

      function initData(){

        gameArea = document.getElementById('game_area');

        previewArea = document.getElementById('preview_area');

        if(gameArea == null || previewArea == null){

          return;

        }

        gameCtx = gameArea.getContext('2d');

        previewCtx = previewArea.getContext('2d');

        space = 25;

        rows = parseInt(gameArea.height / space, 10);

        cols = parseInt(gameArea.width / space, 10);

        fastGameSpeed = 40;

        //gameSpeedRange = [500, 440, 420, 400, 380, 360, 350, 340, 330, 320, 300, 200, 100];
        gameSpeedRange = [500,400,350,200,100];

        gameSpeed = gameSpeedRange[0];

        normalGameSpeed = gameSpeed;

        if(delay){clearTimeout(delay);}

        isGameGoing = true,

        shapes = [

          [0xF000,0x4444],//一字型

          [0x4E00,0x8C80,0xE400,0x2620],//T字型

          [0x4460,0xE800,0x6220,0x2E00],//L字型

          [0x2260,0x8E00,0x6440,0xE200],//反L字型

          [0x4620,0x6C00],//S字型

          [0x2640,0xC600],//反S字型

          [0x6600]//田字型

        ];

        map = [];

        for(var i = 0; i < rows; i++){

          map.push([]);

          for(var j = 0; j < cols; j++){

            map[i].push(0);

          }

        }

        currentShape = 0;

        currentShapeIndex = 0;

        currentShapeTransformIndex = 0;

        currentPosition = {x: 0, y: 0};

        previewShapeIndex = 0;

        score = 0;

        level = 1;

        isSoundPermit = true;

        scoreToUpgrade = [0,50,250,1000,2000];

        colors = ['#3A87AD','#468847','#B94A48','#F89406','#000'];//'rgb(247,198,41)','rgb(108,192,96)','rgb(213,63,44)','rgb(151,163,176)','rgb(111,70,138)''#00FF99','#00BB00','#6666FF','#FF9900','#FF0000','#CC66FF','#FF6600','#FF66FF','#9966FF','#CC33FF','#CC0000'

        barstyle = ['progress progress-info','progress progress-success','progress progress-danger','progress progress-warning'];

        EventUtil.addHandler(document, 'keydown', keyDownFunc);

        EventUtil.addHandler(document, 'keyup', keyUpFunc);
        
        EventUtil.addHandler(document.getElementById('left'),'mousedown',mousedown);
        
        EventUtil.addHandler(document.getElementById('right'),'mousedown',mousedown);
        
        EventUtil.addHandler(document.getElementById('up'),'mousedown',mousedown);
        
        EventUtil.addHandler(document.getElementById('down'),'mousedown',mousedown);
        
        EventUtil.addHandler(document.getElementById('space'),'mousedown',mousedown);
        
        EventUtil.addHandler(document.getElementById('pause'),'mousedown',mousedown);
        
        EventUtil.addHandler(document.getElementById('restart'),'mousedown',mousedown);
        
        EventUtil.addHandler(document.getElementById('sound'),'mousedown',mousedown);
        
        EventUtil.addHandler(document.getElementById('down'),'mouseup',mouseup);
        
        EventUtil.addHandler(document.getElementById('up'),'mouseup',mouseup);
        
        EventUtil.addHandler(document.getElementById('space'),'mouseup',mouseup);
        
        EventUtil.addHandler(document.getElementById('left'),'mouseup',mouseup);
        
        EventUtil.addHandler(document.getElementById('right'),'mouseup',mouseup);
        
        EventUtil.addHandler(document.getElementById('restart'),'mouseup',mouseup);
        
        changeIcon('sound');
        changeIcon('pause');
      }
      function changeIcon(id){
        if(id=='sound'){
          var buttonstyle= document.getElementById('sound');
              if(isSoundPermit)
              buttonstyle.style.background="url(http://storage.aliyun.com/pixels/assets/img/tetris/sound.png) no-repeat" ;        
            else
              buttonstyle.style.background="url(http://storage.aliyun.com/pixels/assets/img/tetris/nosound.png) no-repeat" ;
        }
        else if(id=='pause'){
          var buttonstyle= document.getElementById('pause');
          if(isGameGoing)
            buttonstyle.style.background="url(http://storage.aliyun.com/pixels/assets/img/tetris/pause.png) no-repeat" ;        
          else
            buttonstyle.style.background="url(http://storage.aliyun.com/pixels/assets/img/tetris/start.png) no-repeat" ;
        }
      }
            function mousedown(event){
        var e = EventUtil.getEvent(event);
          var buttonId=this.id;
        var currentbutton= document.getElementById(buttonId)
        currentbutton.style.background="transparent" ;
        switch(buttonId){
          case 'left':
            moveLeft();
            break;
          case 'right':
            moveRight();
            break;
          case 'up':
            EventUtil.preventDefault(e);
            transform();
            break;
          case 'down':
            EventUtil.preventDefault(e);
            speedUp();
            break;
          case 'space':
            EventUtil.preventDefault(e);
            moveToBottom();
            break;
          case 'pause':
            toggleGameGoing();
            changeIcon('pause');
            break;
          case 'restart':
            gameOver();
            start();
            break;
          case 'sound':
            toggleSoundPermit();
            changeIcon('sound');
              break;              
        }
      } 
            function mouseup(event){
        var e = EventUtil.getEvent(event);
        var buttonId=this.id;
        var currentbutton= document.getElementById(buttonId)
        currentbutton.style.background="url(http://storage.aliyun.com/pixels/assets/img/tetris/"+buttonId+".png) no-repeat"  ;
        switch(buttonId){
          case 'up':
            EventUtil.preventDefault(e);
            break;
          case 'down':
            EventUtil.preventDefault(e);
            speedDown();
            break;
          case 'space':
            EventUtil.preventDefault(e);
            break;
        }
      }     
      
      //开始游戏

      function start(){

        initData();

        showScore();

        showLevel();

        setColor();

        createPreviewShape();

        getNewShape();

              $(document).ready(function() {
          $("#scoresubmit").click(function() {
             event.preventDefault();     
             var name = $("#name").attr('value');
             $.post("/api/v1/tetris",{name:name,score:score},function( data ) {
                         history.go(0);
                   },"json")
          });
      });

        /*

        delay = setTimeout(EventUtil.bind(this, this.moveDown), gameSpeed);

        */

        delay = setTimeout(moveDown, gameSpeed);

      }

      //键盘按下的执行函数
      function keyDownFunc(event){

        var e = EventUtil.getEvent(event),

          key = EventUtil.getCharCode(e);


        switch(key){

          // left

          case 37:

            moveLeft();

            break;

          // up

          case 38:

            EventUtil.preventDefault(event);

            transform();

            break;

          // right

          case 39:

            moveRight();

            break;

          // down

          case 40:

            EventUtil.preventDefault(e);

            speedUp();

            break;

          // space

          case 32:
    
            EventUtil.preventDefault(e);

            moveToBottom();
      
            break;

          //N

          case 78:
             if($('#myModal').attr('style') == "display: block; ")
             break;
            gameOverwithN();

            start();

            break;

          //P

          case 80:
           if($('#myModal').attr('style') == "display: block; ")
             break;
            toggleGameGoing();
            changeIcon('pause');

            break;

          //M

          case 77:
             if($('#myModal').attr('style') == "display: block; ")
             break;
              toggleSoundPermit();
              changeIcon('sound');
      
            
            break;

        }

      }

      //键盘松开的执行函数

      function keyUpFunc(event){

        var e = EventUtil.getEvent(event),

          key = EventUtil.getCharCode(e);

        switch(key){

          // up

          case 38:

            EventUtil.preventDefault(e);

            break;

          // down

          case 40:

            EventUtil.preventDefault(e);

            speedDown();

            break;

          // space

          case 32:

            EventUtil.preventDefault(e);

            break;

        }

      }

      //从预览区域获取新方块，判断是否游戏结束
      function getNewShape(){
      
        currentShape = previewShape;

        currentShapeIndex = previewShapeIndex;

        currentShapeTransformIndex = 0;

        

        currentPosition = {x: parseInt(cols / 2, 10) - 2, y: 0};

        if(isCrash()){

          //若产生新方块即碰撞，则结束游戏

          drawCurrentShape();

          gameOver();
          
          playSound('gameover');

        }else{

          //否则产生新的预览方块

          drawCurrentShape();

          createPreviewShape();

        }

      }

      //产生新的预览方块
   
      function createPreviewShape(){
      
        var index = Math.floor(Math.random() * shapes.length);

        previewShape = shapes[index][0];

        previewShapeIndex = index;

        previewCtx.clearRect(0, 0, previewArea.width, previewArea.height);

        drawPreviewArea();

      }

      //描绘当前下落的方块，并更新map矩阵
      
      function drawCurrentShape(){  
        
        var tmpSpace = space,

          tmpX = currentPosition.x,

          tmpY = currentPosition.y,

          context = gameCtx;

        var tmpCurrentShape = currentShape;

        var bytes = 1;

        for(var i = 3; i >= 0; i--){

          for(var j = 3; j >= 0; j--){

            if(tmpCurrentShape & bytes){

              context.save();

              context.translate(tmpSpace*(j+tmpX), tmpSpace*(i+tmpY));

              context.fillRect(1, 1, tmpSpace-2, tmpSpace-2);

              context.restore();

              map[i+tmpY][j+tmpX] = 1;

            }

            bytes <<= 1;

          }

        }

      }

      //擦除当前下落的方块，并更新map矩阵

      function eraseShape(){

        var x = currentPosition.x,

          y = currentPosition.y,

          tmpCurrentShape = currentShape,

          tmpSpace = space,

          bytes = 1,

          context = gameCtx;

        for(var i = 3; i >= 0; i--){

          for(var j = 3; j >= 0; j--){

            if(tmpCurrentShape & bytes){

              context.clearRect(tmpSpace*(j+x), tmpSpace*(i+y), tmpSpace, tmpSpace);

              map[i+y][j+x] = 0;

            }

            bytes <<= 1;

            

          }

        }

      }

      //描绘预览区域的预览方块

      function drawPreviewArea(){

        var tmpCtx = previewCtx,

          tmpPreviewShape = previewShape,

          tmpSpace = space;

        var bytes = 1;

        for(var i = 4; i > 0; i--){

          for(var j = 3; j >= 0; j--){

            if(tmpPreviewShape & bytes){

              tmpCtx.save();

              tmpCtx.translate(tmpSpace*(j),tmpSpace*i);

              tmpCtx.fillRect(28, 10, tmpSpace-2, tmpSpace-2);

              tmpCtx.restore();

            }

            bytes <<= 1;

          }

        }

      }

      //根据map矩阵，重新描绘游戏区域
  
      function refreshGameArea(){
         
        var tmpSpace = space,

          tmpCtx = gameCtx;
          

        for(var i = 0; i < rows; i++){

          for(var j = 0; j < cols; j++){

            if(map[i][j] & 1){

              tmpCtx.save();

              tmpCtx.translate(j * tmpSpace, i * tmpSpace);

              tmpCtx.fillRect(1, 1, tmpSpace-2, tmpSpace-2);

              tmpCtx.restore();

            }else{

              tmpCtx.clearRect(j * tmpSpace, i * tmpSpace, tmpSpace, tmpSpace);

            }

          }

        }

      }

      //游戏速度加快

      function speedUp(){

        if(gameSpeed == normalGameSpeed && isGameGoing){

          clearTimeout(delay);

          gameSpeed = fastGameSpeed;

          /*

          delay = setTimeout(EventUtil.bind(this, this.moveDown), gameSpeed);

          */

          delay = setTimeout(moveDown, gameSpeed);

        }

      }

      //游戏速度恢复

      function speedDown(){

        if(gameSpeed == fastGameSpeed && isGameGoing){

          clearTimeout(delay);

          gameSpeed = normalGameSpeed;

          /*

          delay = setTimeout(EventUtil.bind(this, this.moveDown), gameSpeed);

          */

          delay = setTimeout(moveDown, gameSpeed);

        }

      }

      //当前方块是否产生碰撞

      function isCrash(){

        var shape = currentShape,

          x = currentPosition.x,

          y = currentPosition.y,

          rowsMax = rows - 1,

          colsMax = cols - 1,

          bytes = 1;

        var tmpRow, tmpCol;

        for(var i = 3; i >= 0; i--){

          for(var j = 3; j >= 0; j--){

            if(shape & bytes){

              tmpRow = i + y;

              tmpCol = j + x;

              if(tmpRow >= rows || map[tmpRow][tmpCol] & 1 || tmpRow > rowsMax || tmpCol < 0 || tmpCol > colsMax){

                return true;

              }

            }

            bytes <<= 1;

          }

        }

        return false;

      }

      // 判断是否有满行，有则清行，并返回所清行数，无则返回0

      function clearRows(){

        var shape = currentShape,

          x = currentPosition.x,

          y = currentPosition.y,

          oneRow = null,

          fullColCount = 0,

          rowsToClear = [];

          bytes = 1;

        for(var i = 3; i >= 0; i--){

          for(var j = 3; j >= 0; j--){

            if(shape & bytes){

              oneRow = map[i+y];

              fullColCount = cols;

              for(var k = 0, kLen = fullColCount; k < kLen; k++){

                if(oneRow[k] == 0){

                  break;

                }else{

                  fullColCount--;

                }

              }

              if(fullColCount == 0){

                rowsToClear.push(i);

              }

              bytes <<= (j + 1);

              break;

            }

            bytes <<= 1;

          }

        }

        var clearRows = rowsToClear.length;

        if(clearRows != 0){

          for(var len = 0; len < clearRows; len++){

            var clearColCount = cols - 1;

            var clearRow = rowsToClear[len] + y;

            map.splice(clearRow, 1);

          }

          for(var i = 0; i < clearRows; i++){

            var arrayToAppend = [],

              tmpCol = cols;

            while(tmpCol--){

              arrayToAppend.push(0);

            }

            map.splice(0, 0, arrayToAppend);

          }

          refreshGameArea();
        }

        return clearRows;

      }

      //方块左移

      function moveLeft(){

        if(isGameGoing){

          eraseShape();

          currentPosition.x --;

          if(isCrash()){

            currentPosition.x ++;

          }

          drawCurrentShape();

        }

      }

      //方块右移

      function moveRight(){

        if(isGameGoing){

          eraseShape();

          currentPosition.x ++;

          if(isCrash()){

            currentPosition.x --;

          }

          drawCurrentShape();

        }

      }

      //方块下落一格

      function moveDown(){

        if(isGameGoing){

          eraseShape();

          currentPosition.y ++;

          var crash = isCrash();

          if(crash){

            currentPosition.y --;

          }

          drawCurrentShape();

          if(crash){

            

            var clearRowsCount = clearRows();

            if(clearRowsCount > 0){

              addScore(clearRowsCount);
              
              if(clearRowsCount > 3)
                playSound('clap');
              else
                 playSound('clear');

            }
            
            else
              playSound('drop');
            getNewShape();

          }

        }

        /*

        delay = setTimeout(EventUtil.bind(this, arguments.callee), gameSpeed);

        */

        delay = setTimeout(arguments.callee, gameSpeed);

      }

      //方块立即下落到底

      function moveToBottom(){

        if(isGameGoing){

          eraseShape();

          do{

            currentPosition.y ++;

          }while(!isCrash());

          currentPosition.y --;

          drawCurrentShape();

          playSound('drop');

          var clearRowsCount = clearRows();

          if(clearRowsCount > 0){

            addScore(clearRowsCount);

            playSound('clear');

          }

          getNewShape();

        }

      }

      //方块变型

      function transform(){

        if(isGameGoing){

          eraseShape();

          var transforms = shapes[currentShapeIndex];

          var newShapeTransformIndex = ++currentShapeTransformIndex;

          currentShape = transforms[newShapeTransformIndex % transforms.length];

          if(isCrash()){

            newShapeTransformIndex = --currentShapeTransformIndex;

            currentShape = transforms[newShapeTransformIndex % transforms.length];

          }

          drawCurrentShape();

        }

      }

      //结束游戏

      function gameOver(){

        isGameGoing = false;

        if(delay){clearTimeout(delay);}

        delay = null;

        

        gameCtx.save();

        var msg = 'Game Over';

        gameCtx.font = "45px Helvetica Neue, Helvetica, Arial, sans-serif;";

        gameCtx.fillStyle = "#333";

        gameCtx.fillText(msg, gameArea.width/2-msg.length*12, gameArea.height/2-msg.length/2);


        if(score > $('#lowscore').attr('value'))
        {
          $('#myModal').modal('show');
        }
        gameCtx.restore();


        
      }

      function gameOverwithN(){

        isGameGoing = false;

        if(delay){clearTimeout(delay);}

        delay = null;

        

        gameCtx.save();

        var msg = 'Game Over';

        gameCtx.font = "45px Helvetica Neue, Helvetica, Arial, sans-serif;";

        gameCtx.fillStyle = "#333";

        gameCtx.fillText(msg, gameArea.width/2-msg.length*12, gameArea.height/2-msg.length/2);

        gameCtx.restore();


        
      }

      //切换当前游戏暂停/继续

      function toggleGameGoing(){

        var pauseBtn = document.getElementById('pause_btn');

        if(isGameGoing == true){

          isGameGoing = false;

          if(pauseBtn){

            pauseBtn.innerHTML = '继续';

          }

        }else{

          isGameGoing = true;

          if(pauseBtn){

            pauseBtn.innerHTML = '暂停';

          }

        }

      }

      //加分，传入1个参数为消除的行数

      function addScore(row){

        if(typeof row == 'number'){

          switch(row){

            case 1:

              score += 10;

              break;

            case 2:

              score += 30;

              break;

            case 3:

              score += 60;

              break;

            case 4:

              score += 100;

              break;
              
                        default:
                
              score +=35*row;
              
              break;
          }

          showScore();

          showLevel();
          
          showScoreBar();

        }

      }

      //将分数展示在页面上

      function showScore(){

        var scoreArea = document.getElementById('score_area');

        if(scoreArea){

          scoreArea.innerHTML = score;

        }
        

      }
      
      function showScoreBar(){
        var scoreBar = document.getElementById('progressbar');
        var total=scoreToUpgrade[level];
        scoreBar.style.cssText="width: "+score/total*100+"%;";
      }
      //根据分数更新等级，并将登记展示在页面上

      function showLevel(){

        var newLevel;

        for(var i = 0, iLen = scoreToUpgrade.length; i < iLen; i++){

          if(score < scoreToUpgrade[i]){

            newLevel = i;

            break;

          }

        }

        if(typeof newLevel == 'undefined'){

          //分数高于最高分

          newLevel = scoreToUpgrade.length;

        }

        var levelArea = document.getElementById('level_area');
                var levelScore = document.getElementById('levelscore');
        if(levelArea){

          levelArea.innerHTML = newLevel;
                    levelScore.innerHTML = scoreToUpgrade[newLevel];
        }
                 
        if(newLevel != level){

          level = newLevel;

          setColor();

          setSpeed();
          
          var scoreBar = document.getElementById('cxprogressbar');
          scoreBar.className=barstyle[(level-1)%barstyle.length];

        }

      }

      //根据当前等级设置绘图颜色，并重绘

      function setColor(){

        var index = (level-1) % colors.length;
        var gradient = gameCtx.createLinearGradient(0,0,0, space);

        /*gradient.addColorStop(0, '#ECE6E6');
        */

        gradient.addColorStop(1, colors[index]);

        gameCtx.fillStyle = gradient;

        previewCtx.fillStyle = gradient;

        refreshGameArea();

      }

      //根据当前等级设置速度

      function setSpeed(){

        var index = level - 1;

        if(index < gameSpeedRange.length && index >= 0){

          gameSpeed = gameSpeedRange[index];

          normalGameSpeed = gameSpeed;

        }

      }

      //播放指定音效

      function playSound(sound){

        try{

          if(isSoundPermit == true){

            var elem;

            if(sound == 'drop'){

              //下落的音效

              elem = document.getElementById('drop_sound');

            }else if(sound == 'clear'){

              //清行的音效

              elem = document.getElementById('clear_sound');

            }else if(sound == 'gameover'){
            
              elem = document.getElementById('gameover_sound');
              
            }
            
            else if(sound =='clap'){
              elem = document.getElementById('clap_sound');             
            }
            if(elem){

              if(elem.currentTime){

                //从头播放

                elem.currentTime = 0;

              }

              elem.play();

            }

          }

        }catch(ex){}

      }

      //切换音效开关

      function toggleSoundPermit(){

        if(isSoundPermit == true){

          isSoundPermit = false;

        }else{

          isSoundPermit = true;
        }
      }
      
      return{

        start: start

      };
      
    })();

  //跨浏览器事件处理单元

  var EventUtil = {

    addHandler: function(element, type, handler){

      if (element.addEventListener){

        element.addEventListener(type, handler, false);

      } else if (element.attachEvent){

        element.attachEvent('on' + type, handler);

      } else {

        element['on' + type] = handler;

      }

    },

    removeHandler: function(element, type, handler) {

      if (element.removeEventListener){

        element.removeEventListener(type, handler, false);

      } else if (element.detachEvent){

        element.detachEvent('on' + type, handler);

      } else {

        element['on' + type] = null;

      }

    },

    getEvent: function(event){

      return event ? event : window.event;

    },

    getCharCode: function(event){

      return event.keyCode || event.which || event.charCode;

    },

    preventDefault: function(event){

      if (event.preventDefault){

        event.preventDefault();

      } else {

        event.returnValue = false;

      }

    }



  };
  Tetris.start();





</script>