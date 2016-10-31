<?php
/**
 * 下维码生成器
 *
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');

class MyQRcode {
    static public function buildMember($value,$logo=''){
        $name = md5($value) . '.png';
         // 生成的文件名
        $filename = BASE_UPLOAD_PATH . DS . ATTACH_QRCODE . DS .$name;

        if(!file_exists($filename)){
            require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
            $PhpQRCode = new PhpQRCode();
            $PhpQRCode->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_QRCODE.DS);
            $PhpQRCode->set('data',$value);
            $PhpQRCode->set('pngTempName', $name);
            $PhpQRCode->init();

            if($logo){

            }
        }

        return ATTACH_QRCODE . DS .$name;

    }
}
