<?php
namespace App\Controller\Component;



use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;






class MyComponent extends Component
{


    function random_password( $length = 8 ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = substr( str_shuffle( $chars ), 0, $length );
        return $password;
    }

    function uploadfile($fileData,$folder){

		$tmpFile = $fileData['tmp_name'];
        $fileName = rand()."_".str_replace(" ", "_",$fileData['name']);
        $filePath =  WWW_ROOT . 'uploads' . DS . $folder.DS.$fileName;
        $filePathThumb =  WWW_ROOT . 'uploads' . DS . $folder . DS ."thumb".DS;
        $this->createThumbnail($fileName,200,200,$tmpFile,$filePathThumb);
        move_uploaded_file($tmpFile, $filePath);
        return $fileName;
    }
	
	function uploadPdfFile($fileData,$folder){

        $tmpFile = $fileData['tmp_name'];
        $fileName = rand()."_".str_replace(" ", "_",$fileData['name']);
        $filePath =  WWW_ROOT . 'uploads' . DS . $folder.DS.$fileName;
        move_uploaded_file($tmpFile, $filePath);
        return $fileName;
    }

    function createThumbnail($image_name,$new_width,$new_height,$uploadDir,$moveToDir)
    {
        $path = $uploadDir;
        $mime = getimagesize($path);
		if(!empty($mime))
		{
			if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
			if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
			if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
			if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }

			$old_x          =   imageSX($src_img);
			$old_y          =   imageSY($src_img);

			if($old_x > $old_y)
			{
				$thumb_w    =   $new_width;
				$thumb_h    =   $old_y*($new_height/$old_x);
			}

			if($old_x < $old_y)
			{
				$thumb_w    =   $old_x*($new_width/$old_y);
				$thumb_h    =   $new_height;
			}

			if($old_x == $old_y)
			{
				$thumb_w    =   $new_width;
				$thumb_h    =   $new_height;
			}

			$dst_img        =   ImageCreateTrueColor($thumb_w,$thumb_h);

			imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);


			// New save location
			$new_thumb_loc = $moveToDir . $image_name;

			if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,8); }
			if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
			if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
			if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

			imagedestroy($dst_img);
			imagedestroy($src_img);

			return $result;
		}
    }

    
}