<?php
/**
 * 活动管理
 *
 *
 *
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');
class activity_projectModel extends Model {

    public function __construct() {
        parent::__construct('activity_project');
    }

	/**
	 * 查询列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 字段
	 * @param string $limit 取多少条
     * @return array
	 */
    public function getProjectList($condition, $page = null, $order = '', $field = '*', $limit = '') {
        $result = $this->field($field)->where($condition)->order($order)->limit($limit)->page($page)->select();
        return $result;
    }


    /**
     * 数量
     * @param array $condition
     * @return int
     */
    public function getProjectCount($condition) {
        return $this->where($condition)->count();
    }


    /**
	 * 查询信息
     *
	 * @param array $condition 查询条件
     * @return array
	 */
    public function getProjectInfo($condition) {
        return $this->where($condition)->find();
    }

    /**
	 * 通过编号查询信息
     *
	 * @param int $project_id 编号
     * @return array
	 */
    public function getProjectInfoByID($project_id) {
        $key = md5('activity_project_info_' . $project_id);
        $project_info = rkcache($key);
        if(empty($project_info)) {
            $project_info = $this->getProjectInfo(array('project_id' => $project_id));
            wkcache($key, $project_info, 60 * 24);
        }
        return $project_info;
    }

	/*
	 * 添加
     *
	 * @param array $param 信息
	 * @return bool
	 */
    public function addProject($param){
        return $this->insert($param);
    }

	/*
	 * 编辑
     *
	 * @param array $update 更新信息
	 * @param array $condition 条件
	 * @return bool
	 */
    public function editProject($update, $condition){
        //清空缓存
        $project_list = $this->getProjectList($condition);
        foreach ($project_list as $value) {
            $key = md5('activity_project_info_' . $value['project_id']);
            dkcache($key);
        }

        return $this->where($condition)->update($update);
    }

	/*
	 * 删除
     *
	 * @param array $condition 条件
	 * @return bool
	 */
    public function delProject($condition){
        $project_info = $this->getProjectInfo($condition);
        //删除相关图片
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY_PROJECT.DS.$project_info['project_pic']);
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY_PROJECT.DS.$project_info['project_pic_1']);
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY_PROJECT.DS.$project_info['project_pic_2']);
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY_PROJECT.DS.$project_info['project_pic_3']);
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY_PROJECT.DS.$project_info['project_pic_4']);
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY_PROJECT.DS.$project_info['project_pic_5']);

        //清空缓存
        $key = md5('activity_project_info_' . $project_info['project_id']);
        dkcache($key);

        return $this->where($condition)->delete();
    }
}
