<?php
/**
 * 投诉与建议
 *
 */
defined('InSystem') or exit('Access Invalid!');
class complain_suggestModel extends Model{
	const NEW_STATE = 1; //没有处理
	const EDIT_STATE = 2;//已经处理
	const DEL_STATE = 3;//删除
	
	public function __construct() {
		parent::__construct('complain_suggest');
	}

	//投诉建议图片保存位置
	private function img_dir(){
		$IMG_DIR = UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/complain_suggest/';
		return $IMG_DIR;
	}

    /**
     * 获取总数
     * @param unknown $condition
     */
    public function getCount($condition) {
        return $this->where($condition)->count();
    }

	/**
	 * 添加投诉建议
	 *
	 * @param
	 * @return int
	 */
	public function addData($data=array()) {
		$time = time();
		$data['cs_add_time'] = $time;
		$data['cs_edit_time'] = $time;

		$id = $this->insert($data);
		return $id;
	}


	/**
	 * 修改记录
	 *
	 * @param
	 * @return bool
	 */
	public function editData($condition, $data) {
		if (empty($condition)) {
			return false;
		}
		if (is_array($data)) {
			$time = time();
			$data['cs_handle_time'] = $time;
			$data['cs_edit_time'] = $time;
			$result = $this->where($condition)->update($data);
			return $result;
		} else {
			return false;
		}
	}

	/**
	 * 处理完成
	 * @param $id
	 * @return bool
	 */
	public function handleComplete($id){
		if(intval($id) <= 0){
			return false;
		}
		$condition['id'] = $id;
		$data['cs_state'] = self::EDIT_STATE;
		return $this->editData($condition, $data);
	}

	/**
	 * 删除  (不是真正的删除,只是改变状态值)
	 * @param $id
	 * @return bool
	 */
	public function delData($id){
		if(intval($id) <= 0){
			return false;
		}
		$condition['id'] = $id;
		$data['cs_state'] = self::DEL_STATE;
		return $this->editData($condition, $data);
	}

	/**
	 * 获取列表
	 *
	 * @param
	 * @return array
	 */
    public function getList($condition = array(), $page = '', $limit = '', $fields = '*') {
		$result = $this->field($fields)->where($condition)->page($page)->limit($limit)->order('cs_edit_time desc')->select();
		if($result){
			foreach($result as $k=>$v){
				if($v['cs_pic1']) $result[$k]['cs_pic1_url'] = $this->img_dir().$v['cs_pic1'];
				if($v['cs_pic2']) $result[$k]['cs_pic2_url'] = $this->img_dir().$v['cs_pic2'];
				if($v['cs_pic3']) $result[$k]['cs_pic3_url'] = $this->img_dir().$v['cs_pic3'];
			}
		}
		return $result;
    }


	/**
	 * 获取没有处理的投诉建议
	 * @param array $condition
	 * @param string $page
	 * @param string $limit
	 * @param string $fields
	 * @return array
	 */
	public function getNewList($condition = array(), $page = '', $limit = '', $fields = '*'){
		$condition['cs_state'] = self::NEW_STATE;
		return $this->getList($condition, $page, $limit, $fields);
	}

	/**
	 * 获取已经处理的投诉建议
	 * @param array $condition
	 * @param string $page
	 * @param string $limit
	 * @param string $fields
	 * @return array
	 */
	public function getHandleList($condition = array(), $page = '', $limit = '', $fields = '*'){
		$condition['cs_state'] = self::EDIT_STATE;
		return $this->getList($condition, $page, $limit, $fields);
	}

	/**
	 * 取一条记录
	 *
	 * @param
	 * @return array
	 */
	public function getInfo($condition = array(), $fields = '*') {
		$info = $this->where($condition)->field($fields)->find();
		if($info){
			if($info['cs_pic1']) $info['cs_pic1_url'] = $this->img_dir().$info['cs_pic1'];
			if($info['cs_pic2']) $info['cs_pic2_url'] = $this->img_dir().$info['cs_pic2'];
			if($info['cs_pic3']) $info['cs_pic3_url'] = $this->img_dir().$info['cs_pic3'];
		}
        return $info;
	}

}
