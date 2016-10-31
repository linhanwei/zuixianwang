<?php

/**
 * 系统banner管理
 *
 *
 * */
defined('InSystem') or exit('Access Invalid!');

class bannerControl extends SystemControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 列表
     */
    public function indexOp() {
       
        $model_banner = Model('banner');

        /**
         * 检索条件
         */
        if (!empty($_GET['b_title'])) {
            $condition['title'] = array('like', "%" . $_GET['b_title'] . "%");
        }

        $list = $model_banner->getList($condition, "*", 10, 'edit_time desc');

        Tpl::output('page', $model_banner->showpage());
        Tpl::output('list', $list);
        Tpl::output('b_title', trim($_GET['b_title']));

        Tpl::showpage('banner.index');
    }

    /**
     * 编辑
     */
    public function editOp() {
        $lang = Language::getLangContent();

        $id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
        $id = intval($id);

        if (empty($id)) {
            showMessage('请选择需要编辑的banner');
        }

        $model_banner = Model('banner');
        $info = $model_banner->getOneById($id);

        if (empty($info)) {
            showMessage('编辑的banner不存在');
        }

        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["b_title"], "require" => "true", "message" => '标题不能为空'),
                array("input" => $_POST["url"], "require" => "true", "message" => 'banner说明不能为空'),
//                array("input" => $_POST["content"], "require" => "true", "message" => 'banner说明不能为空')
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {

                $param = array();
                $param['id'] = $id;
                $param['title'] = trim($_POST['b_title']);
                $param['url'] = trim($_POST['url']);
                $param['content'] = trim($_POST['content']);
                
                if ($_FILES['fileupload']['name']) {
                    $param['image_name'] = $this->pic_uploadOp();
                    $this->del_image($info['image_name']);
                }

                $result = $model_banner->editData(array('id' => $id), $param);

                if ($result) {

                    $url = array(
                        array(
                            'url' => 'index.php?act=banner&op=index',
                            'msg' => '返回banner管理'
                        ),
                        array(
                            'url' => 'index.php?act=banner&op=edit&id=' . $id,
                            'msg' => '继续编辑banner'
                        ),
                    );
                    $this->log('编辑banner:' . '[ID:' . $id . ']', 1);
                    showMessage($lang['nc_common_save_succ'], $url);
                } else {
                    showMessage($lang['nc_common_save_fail']);
                }
            }
        }

        Tpl::output('PHPSESSID', session_id());
        Tpl::output('info', $info);
        Tpl::showpage('banner.edit');
    }

    /**
     * 添加
     */
    public function addOp() {
        $lang = Language::getLangContent();

        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["b_title"], "require" => "true", "message" => '标题不能为空'),
                array("input" => $_FILES['fileupload']['name'], "require" => "true", "message" => 'banner图片不能为空'),
                array("input" => $_POST["url"], "require" => "true", "message" => 'URL不能为空'),
//                array("input" => $_POST["content"], "require" => "true", "message" => 'banner说明不能为空')
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {

                $param = array();
                $param['title'] = trim($_POST['b_title']);
                $param['url'] = trim($_POST['url']);
                $param['image_name'] = $this->pic_uploadOp();
                $param['content'] = trim($_POST['content']);
                
                $model_banner = Model('banner');

                $result = $model_banner->addData($param);

                if ($result) {

                    $url = array(
                        array(
                            'url' => 'index.php?act=banner&op=index',
                            'msg' => '返回banner管理'
                        ),
                        array(
                            'url' => 'index.php?act=banner&op=add',
                            'msg' => '继续添加banner'
                        ),
                    );
                    $this->log('添加banner管理' . '[ID:' . $result . ']', 1);
                    showMessage($lang['nc_common_save_succ'], $url);
                } else {
                    showMessage($lang['nc_common_save_fail']);
                }
            }
        }

        Tpl::showpage('banner.add');
    }

    /**
     * 图片上传
     */
    public function pic_uploadOp() {

        $upload = new UploadFile();

        $uploaddir = ATTACH_PATH . DS . 'banner' . DS;
        $upload->set('default_dir', $uploaddir);
        $upload->set('max_size',300);

        $result = $upload->upfile('fileupload');
        if ($result) {
            return $upload->file_name;
        } else {
            showMessage($upload->error);
            exit;
        }
    }

    /**

     * 删除

     */
    public function delOp() {
        
        $rec_url =  array(
                        array(
                            'url' => 'index.php?act=banner&op=index',
                            'msg' => '返回banner管理'
                        )
                    );
        $id = $_GET['id'];

        if (empty($id)) {
            showMessage('请选择banner', $rec_url);
        }

        $where['id'] = $id;
        $model_banner = Model('banner');
        $info = $model_banner->getInfo($where);

        if (empty($info)) {
            showMessage('banner 不存在', $rec_url);
        }

        $result = $model_banner->delData($where);

        if ($result) {
           
            $this->del_image($info['image_name']);
            
            $this->log('删除:[ID:' . $id . ']', 1);
            showMessage('删除成功', $rec_url);
        } else {
            $this->log('删除:[ID:' . $id . ']', 0);
            showMessage('删除失败', $rec_url);
        }
    }
    
    /**
     * 删除图片
     * @param type $image_name
     */
    private function del_image($image_name) {
        if(empty($image_name)){
            return FALSE;
        }
        
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_PATH . DS . 'banner' . DS . $image_name);
            
    }

}
