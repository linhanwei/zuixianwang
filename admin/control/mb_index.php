<?php
/**
 * 手机首页
 *
 *
 *
 */



defined('InSystem') or exit('Access Invalid!');
class mb_indexControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

    /**
     * 首页项目列表
     */
    public function index_listOp() {
    }

	public function upload_apkOp(){
		if (chksubmit()) {
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input" => $_FILES['fileupload']['name'], "require" => "true", "message" => 'apk文件不能为空')
			);

			$error = $obj_validate->validate();
			if ($error != '') {
				showMessage($error);
			} else {
				$err_msg = '';
				$upload_file = $_FILES['fileupload'];

				if ($upload_file['tmp_name'] == ""){
					$err_msg = Language::get('cant_find_temporary_files');
				}

				$error = $upload_file['error'];
				$msg_result = $this->fileInputError($error);
				if($msg_result !== true){
					$err_msg = $msg_result;
				};

				$uploaddir = BASE_ROOT_PATH.DS. 'downapp' . DS;

				if(!is_dir($uploaddir)){
					if (!mkdir($uploaddir,0777,true)){
						$err_msg = '创建目录失败，请检查是否有写入权限';
					}
				}

				if ($err_msg) {
					showMessage($err_msg);
					exit;
				}

				$tmp_name = $upload_file["tmp_name"];
				$name=$uploaddir.'czb.apk';

				if(!move_uploaded_file($tmp_name,$name)){
					showMessage('上传失败');
				};

				$url = array(
					array(
						'url' => 'index.php?act=mb_index&op=upload_apk',
						'msg' => '返回apk上传'
					)

				);

				$this->log('上传apk文件', 1);
				showMessage('上传成功', $url);
			}
		}

		Tpl::showpage('mb_index.upload_apk');
	}

	/**

	 * 获取上传文件的错误信息

	 *

	 * @param string $field 上传文件数组键值

	 * @return string 返回字符串错误信息

	 */

	private function fileInputError($error){
		if(empty($error)){
			return false;
		}
		switch($error) {

			case 0:

				//文件上传成功

				return true;

				break;



			case 1:

				//上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值

				return Language::get('upload_file_size_over');

				break;



			case 2:

				//上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值

				return  Language::get('upload_file_size_over');

				break;



			case 3:

				//文件只有部分被上传

				return  Language::get('upload_file_is_not_complete');

				break;



			case 4:

				//没有文件被上传

				return Language::get('upload_file_is_not_uploaded');

				break;



			case 6:

				//找不到临时文件夹

				return Language::get('upload_dir_chmod');

				break;



			case 7:

				//文件写入失败

				return Language::get('upload_file_write_fail');

				break;



			default:

				return true;

		}

	}
}

