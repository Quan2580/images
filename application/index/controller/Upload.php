<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Loader;
use app\home\model\User;
class Upload extends Controller
{
    public function index()
    {
        return $this->fetch();
    }


  public function up(){

    $file = request()->file('file');
    if($file){
      $filePaths = ROOT_PATH . 'public' . DS . 'uploads';
      if(!file_exists($filePaths)){
        mkdir($filePaths,0777,true);
      }

      $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
      
      if($info){
        // 替换为反斜杠
         $getSaveName=str_replace("\\","/",$info->getSaveName());
         // 原图路径
         $imgpath = str_replace("\\","/",'./'.'public' . DS .'uploads'.'/'.$getSaveName);
          // echo $imgpath;exit;  //public/uploads/20190516/1af088abd2bae6b71a50db7f1de1915b.jpg
          // 
          // echo '原图路径:'.$imgpath.'<br/>';
        $image = \think\Image::open($imgpath);
     
        $date_path = 'uploads/'.'thumb/'.date('Ymd');
        if(!file_exists($date_path)){
          mkdir($date_path,0777,true);
        }
        // 缩略图路径
        $thumb_path = $date_path.'/'.$info->getFilename();
          // echo '缩略图路径:'.$thumb_path.'<br/>';
        $image->thumb(200,200)->save($thumb_path);


        //水印图路径
      $datesy_path = 'uploads/'.'thumbsy/'.date('Ymd');
        if(!file_exists($datesy_path)){
          mkdir($datesy_path,0777,true);
        }

       $thusy_path = $datesy_path.'/'.$info->getFilename();
         if($image){
              // 水印路径
              $thum=str_replace("\\","/",$thumb_path);
            
            $images = \think\Image::open($thum);
            $images->text('quanquan.cn','./Alibaba-Light.ttf',20,'#ffffff')->save($thusy_path);
        
         }
       
        $data['img'] = $imgpath;   
        $data['thumb_img'] = $thumb_path;
        $data['thumsy_img'] = $thusy_path;
        echo json_encode($data);
      }else{
        // 上传失败获取错误信息
        return $file->getError();
      }
    }
}
      


}
