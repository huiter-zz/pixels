<?php
	 //ini_set('memory_limit','50M'); 
	//load image
     $imageUrl =$_GET["url"];// "http://img.pconline.com.cn/images/upload/upc/tx/wallpaper/1210/16/c2/14456891_1350376154829_800x600.jpg";
	 $format=getimagesize($imageUrl);
	 $width = $format[0];
     $height = $format[1];
	 if($width==0){
		echo( 'PictureFail');
	 }
	 else if($format[2]!='2'&&$format[2]!='3'){
		echo 'FormatFail';
	 }
	 else{
		 switch($format[2]){
			/*case '1'://gif
				$image_p = imagecreatefromgif($imageUrl);
				break;*/
			case '2'://jpeg
				$image_p = imagecreatefromjpeg($imageUrl);
				break;
			case '3'://png
				$image_p = imagecreatefrompng($imageUrl);
				break;
			default:
				echo 'FormatFail';
		 } 
		 //echo imagedestroy($image_p);
		 //process image
		 $canvasw= $_GET["canvasWidth"];//�������
		 $canvash=  $_GET["canvasHeight"];//�����߶�

		$spiltx=ceil(($_GET["spiltx"])* $width);//��ͼλ��x y
		$spilty=ceil(($_GET["spilty"])*$height);
		$spiltw=ceil(($_GET["spiltw"])* $width);//��ͼͼ��w
		$spilth=ceil(($_GET["spilth"])*$height);//��ͼͼ��h
		$processway = $_GET["way"];//ͼƬ����ʽ��0����仭����1���ü�;2������������
		
		if($spiltw<$canvasw&&$spilth<$canvash){
			switch($processway){
				case'0':
					$width=$canvasw;
					$height=$canvash;
					break;
				case'1':
					$width=$spiltw;
					$height=$spilth;
					break;
				case'2':
					$flag=(($canvasw/$spiltw)*$spilth)>$canvash?1:0;
					if($flag==0){
						$width=$canvasw;
						$height=ceil(($canvasw/$spiltw)*$spilth);
					}
					else{
						$width=ceil(($canvash/$spilth)*$spiltw);
						$height=$canvash;
					}
					break;
				default:
					echo '��֧�����ִ���ʽ';
			 }
	/*
		echo $width.'<br />';
		echo $height.'<br />';
		echo $spiltx.'<br />';;
		echo $spilty.'<br />';;
		echo $spiltw.'<br />';;
		echo $spilth.'<br />';;
		echo $canvasw.'<br />';;
		echo $canvash.'<br />';;
	*/
			$image = imagecreatetruecolor($width,$height);
			imagecopyresampled($image,$image_p,0,0,$spiltx,$spilty,$width,$height,$spiltw,$spilth); 
		}//��С����
		else if($spiltw<$canvasw){
			switch($processway){
				case'0':
					$width=$canvasw;
					$height=$canvash;
					$image = imagecreatetruecolor($width,$height);
					imagecopyresampled($image,$image_p,0,0,$spiltx,$spilty,$width,$height,$spiltw,$spilth); 
					break;
				case'1':
					$width=$spiltw;
					$height=$canvash;
					$image = imagecreatetruecolor($width,$height);
					imagecopyresampled($image,$image_p,0,0,$spiltx,$spilty+($spilth-$canvash)/2,$width,$height,$width,$height);
					break;
				case'2':
					$width=ceil(($canvash/$spilth)*$spiltw);
					$height=$canvash;
					$image = imagecreatetruecolor($width,$height);
					imagecopyresampled($image,$image_p,0,0,$spiltx,$spilty,$width,$height,$spiltw,$spilth);
					break;
				
				default:
					echo '��֧�����ִ���ʽ';
			 }
		}//̫����
		else if($spilth<$canvash){
			switch($processway){
				case'0':
					$width=$canvasw;
					$height=$canvash;
					$image = imagecreatetruecolor($width,$height);
					imagecopyresampled($image,$image_p,0,0,$spiltx,$spilty,$width,$height,$spiltw,$spilth); 
					break;
				case'1':
					$width=$canvasw;
					$height=$spilth;
					$image = imagecreatetruecolor($width,$height);
					imagecopyresampled($image,$image_p,0,0,$spiltx+($spiltw-$canvasw)/2,$spilty,$width,$height,$width,$height);
					break;
				case'2':
					$width=$canvasw;
					$height=ceil(($canvasw/$spiltw)*$spilth);
					$image = imagecreatetruecolor($width,$height);
					imagecopyresampled($image,$image_p,0,0,$spiltx,$spilty,$width,$height,$spiltw,$spilth);
					break;
				default:
					echo '��֧�����ִ���ʽ';
			 }
		}//̫����
		else{
			switch($processway){
				case'0':
					$width=$canvasw;
					$height=$canvash;
					$image = imagecreatetruecolor($width,$height);
					imagecopyresampled($image,$image_p,0,0,$spiltx,$spilty,$width,$height,$spiltw,$spilth); 
					break;
				case'1':
					$width=$canvasw;
					$height=$canvash;
					$image = imagecreatetruecolor($width,$height);
					imagecopyresampled($image,$image_p,0,0,$spiltx+($spiltw-$canvasw)/2,$spilty+($spilth-$canvash)/2,$width,$height,$width,$height);
					break;
				case'2':
					$flag=(($canvasw/$spiltw)*$spilth)>$canvash?1:0;
					if($flag==0){
						$width=$canvasw;
						$height=ceil(($canvasw/$spiltw)*$spilth);
					}
					else{
						$width=ceil(($canvash/$spilth)*$spiltw);
						$height=$canvash;
					}
					$image = imagecreatetruecolor($width,$height);
					imagecopyresampled($image,$image_p,0,0,$spiltx,$spilty,$width,$height,$spiltw,$spilth);
					break;
				default:
					echo '��֧�����ִ���ʽ';
			 }
			
		}//�ֳ��ֿ�
		
	/*	
		 switch($processway){
			case'1':
				imagecopyresampled($image,$image_p,0,0,0,0,$canvasw,$canvash,$width,$height);
				$width=$canvasw;
				$height=$canvash;
				break;
			case'2':
				imagecopyresampled($image,$image_p,0,0,($width-$canvasw)/2,($height-$canvash)/2,$canvasw,$canvash,$canvasw,$canvash);
				$width=$canvasw;
				$height=$canvash;
				break;
			default:
				echo '��֧�����ִ���ʽ';
		 }
	*/
		
		   imagedestroy($image_p);//�������
		 //imagedestroy($image_p);
		$w = $_GET["pixelWidth"];//$_GET["width"];//ϣ���õ���ģ�����ݿ��
		$cw =$_GET["pixelWidth"];// $_GET["colorpxWidth"];//ϣ��ϸ��ͼ�������
		$color = $_GET["color"];//��ɫ���Ǻڰ� 1����ɫ 0���ڰ�
		$white = $_GET["white"]; //�Ƿ���� 1����Ҫ 0��Ҫ
		$resultJSON = array();
		//echo $width." ".$height."<br>";

		//foreach the image
		for($y=0; $y<$height;$y=$y+$cw){
			for($x=0;$x<$width;$x=$x+$cw){
				$pixel = new Pixel();
				$pixel->a = "a";
				if($color==1)
				{
					$pixel->c = hexcolor(imagecolorat($image, $x, $y));
				}
				else
				{
					$pixel->c = nocolor(imagecolorat($image, $x, $y));
				}
				$pixel->w = $w;
				$pixel->x = floor(($x - $width / 2)/$cw)*$w;
				$pixel->y = floor(($y - $height / 2)/$cw)*$w;
				if(($white!="1")||($pixel->c != "#ffffff"))           
				array_push($resultJSON, $pixel);
	  
			}
		}
		imagedestroy($image);//�������
		//echo $resultJSON;
		function cmp($p1, $p2)
		{
			$a = $p1;
			$b = $p2;
			if ($a == $b) {
				/*
				if(rand(0,9)>4)
				return -1;
				else
				return 1;
				*/
				return 0;
			}
			return ($a < $b) ? -1 : 1;
		}
		usort($resultJSON, "cmp");
		echo json_encode($resultJSON);
	}
    //console.log 'hello world';

    //change rgb to hex
    function hexcolor($c){
    
        $r = ($c >> 16) & 0xFF;
        $g = ($c >> 8) & 0xFF;
        $b = $c & 0xFF;
        return '#'.str_pad(dechex($r), 2, '0', STR_PAD_LEFT).str_pad(dechex($g), 2, '0', STR_PAD_LEFT).str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
    }
    function nocolor($c){
    
        $r = ($c >> 16) & 0xFF;
        $g = ($c >> 8) & 0xFF;
        $b = $c & 0xFF;

        $gray =  0.3*$r + 0.59*$g + 0.11*$b;
        $r = $gray;
        $g = $gray;
        $b = $gray;
        return '#'.str_pad(dechex($r), 2, '0', STR_PAD_LEFT).str_pad(dechex($g), 2, '0', STR_PAD_LEFT).str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
    }
    //class pixel
    class Pixel{
        var $a;
        var $c;
        var $x;
        var $y;
        var $w;
    }
?>
