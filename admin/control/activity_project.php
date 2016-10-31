<?php
/**
 * 活动服务管理
 *
 ***/

defined('InSystem') or exit('Access Invalid!');

class activity_projectControl extends SystemControl{
	const EXPORT_SIZE = 1000;
    private $project_type = '';
	public function __construct(){
		parent::__construct();
        $this->project_type = $_GET['project_type'];
	}

    /**
     * 车
     */
    public function cheOp(){
        $this->project_list('che');
    }

    /**
     * 房
     */
    public function fangOp(){
        $this->project_list('fang');
    }

    /**
     * 列表
     * @param $project_type
     */
	private function project_list($project_type){
        $this->project_type = $project_type;
        Tpl::output('project_type',$this->project_type);

		$model_project = Model('activity_project');

		if(trim($_GET['project_name']) != ''){
			$condition['project_name']	= array('like', '%'.trim($_GET['project_name']).'%');
			Tpl::output('project_name',$_GET['project_name']);
		}
        $condition['project_type'] = $this->project_type;

		//列表
		$project_list = $model_project->getprojectList($condition, 10,'project_id desc');
        if($project_list){
            $model_apply = Model('activity_apply');
            foreach($project_list as $key=>$val){
                $project_list[$key]['apply_count'] = $model_apply->getApplyCount(array('project_id'=>$val['project_id']));
            }
        }

		Tpl::output('project_list',$project_list);
		Tpl::output('page',$model_project->showpage());
		Tpl::showpage('activity_project.index');
	}
	/**
	 * 编辑
	 */
	public function project_editOp(){
        if (chksubmit())
        {
            $project_id = $_POST['project_id'];
            $model_project = model('activity_project');
            $saveArray = array();

            $saveArray['project_name'] = $_POST['project_name'];

            if($project_pic = $this->upload_image('project_pic')){
                $saveArray['project_pic'] = $project_pic;
            }

            if($project_pic_1 = $this->upload_image('project_pic_1')){
                $saveArray['project_pic_1'] = $project_pic_1;
            }

            if($project_pic_2 = $this->upload_image('project_pic_2')){
                $saveArray['project_pic_2'] = $project_pic_2;
            }
            if($project_pic_3 = $this->upload_image('project_pic_3')){
                $saveArray['project_pic_3'] = $project_pic_3;
            }
            if($project_pic_4 = $this->upload_image('project_pic_4')){
                $saveArray['project_pic_4'] = $project_pic_4;
            }
            if($project_pic_5 = $this->upload_image('project_pic_5')){
                $saveArray['project_pic_5'] = $project_pic_5;
            }


            $saveArray['project_desc'] = $_POST['project_desc'];
            $saveArray['project_price'] = $_POST['project_price'];
            $saveArray['project_phone'] = $_POST['project_phone'];
            $saveArray['project_desc'] = $_POST['project_desc'];
            $saveArray['project_address'] = $_POST['project_address'];
            $saveArray['project_content'] = $_POST['project_content'];
            $saveArray['project_state'] = 1;
            $saveArray['project_time']  = time();

            $model_project->editProject($saveArray,array('project_id'=>$project_id));


            $this->log("修改项目: {$saveArray['project_name']}");
            showMessage('操作成功', urlAdmin('activity_project', $_POST['project_type']));
            return;
        }

        $project_id = $_GET['project_id'];
        $project = Model('activity_project')->getprojectInfoByID($project_id);
        $_GET['project_type'] = $project['project_type'];
        Tpl::output('project',$project);
        Tpl::showpage('activity_project.add');
	}

    private function upload_image($file) {
        $pic_name = '';
        $upload = new UploadFile();
        $uploaddir =ATTACH_ACTIVITY_PROJECT.DS;
        $upload->set('default_dir',$uploaddir);
        $upload->set('allow_type',array('jpg','jpeg','gif','png'));
        if (!empty($_FILES[$file]['name'])){
            $result = $upload->upfile($file);
            if ($result){
                $pic_name = $upload->file_name;
                $upload->file_name = '';
            }
        }
        return $pic_name;
    }

    public function project_addOp()
    {
        if (chksubmit())
        {

            $model_project = model('activity_project');

            $saveArray = array();
            $saveArray['project_type'] = $_POST['project_type'];
            $saveArray['project_name'] = $_POST['project_name'];
            $saveArray['project_pic']   = $this->upload_image('project_pic');
            $saveArray['project_pic_1'] = $this->upload_image('project_pic_1');
            $saveArray['project_pic_2'] = $this->upload_image('project_pic_2');
            $saveArray['project_pic_3'] = $this->upload_image('project_pic_3');
            $saveArray['project_pic_4'] = $this->upload_image('project_pic_4');
            $saveArray['project_pic_5'] = $this->upload_image('project_pic_5');

            $saveArray['project_address'] = $_POST['project_address'];
            $saveArray['project_price'] = $_POST['project_price'];
            $saveArray['project_phone'] = $_POST['project_phone'];
            $saveArray['project_desc']      = $_POST['project_desc'];
            $saveArray['project_content']   = $_POST['project_content'];
            $saveArray['project_state']     = 1;
            $saveArray['project_time']      = time();

            $model_project->addProject($saveArray);


            $this->log("新增4S店铺: {$saveArray['project_name']}");
            showMessage('操作成功', urlAdmin('activity_project', $_POST['project_type']));
            return;
        }


        Tpl::showpage('activity_project.add');
    }
    


	    public function delOp()
    {
        $project_id = (int) $_GET['project_id'];
        $model_project = model('activity_project');

        $condition = array(
            'project_id' => $project_id,
        );

        $model_project->delProject($condition);
        $this->log("删除活动服务: {$project_id}");
        showMessage('操作成功', urlAdmin('activity_project', $_GET['project_type']));
    }

    /**
     * 验证名称是否存在
     */
    public function check_project_nameOp() {
        /**
         * 实例化卖家模型
         */
        $where = array();
        $where['project_name'] = $_GET['project_name'];
        if($_GET['project_id']) $where['project_id'] = array('neq', $_GET['project_id']);
        $project_info = Model('activity_project')->getProjectInfo($where);
        if(!empty($project_info['project_name'])) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /**
     * 报名列表
     */
    public function apply_listOp(){
        $model_project_apply = Model('activity_apply');

        $condition['project_id'] = $_GET['project_id'];
        if(trim($_GET['member_name']) != ''){
            $condition['member_name']	= array('like', '%'.trim($_GET['member_name']).'%');
        }

        //列表
        $apply_list = $model_project_apply->getApplyList($condition, 10,'apply_id desc');

        Tpl::output('apply_list',$apply_list);
        Tpl::output('page',$model_project_apply->showpage());
        Tpl::showpage('activity_apply.index');
    }
}
