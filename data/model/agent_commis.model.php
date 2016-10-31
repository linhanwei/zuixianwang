<?php
/**
 * 佣金记录
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');
class agent_commisModel extends Model
{
    public function __construct() {
        parent::__construct('agent_commis');
    }

    /**
     * 列表
     *
     * @return mixed
     */
    public function getAgentCommisList($condition = array(), $pagesize = '', $fields = '*', $order = '', $limit = '') {
        return $this->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }

    /**
     * 获取详情
     *
     * @return mixed
     */
    public function getAgentCommisInfo($condition = array(), $fileds = '*') {
        return $this->where($condition)->field($fileds)->find();
    }

    /**
     * 添加加盟商
     *
     * @param
     * @return int
     */
    public function addAgentCommis($data) {
        return $this->insert($data);
    }

    /**
     *
     * 修改
     *
     * @param $update
     * @param $condition
     * @return mixed
     */
    public function editAgentCommis($update,$condition){
        return $this->where($condition)->update($update);
    }

    /**
     *
     * 删除
     *
     * @param $id
     * @return mixed
     */
    public function delAgentCommis($id){
        if($id > 0){
            $condition['commis_id'] = $id;
            return $this->where($condition)->delete();
        }
    }

    /**
     *
     * 数量
     * @param $codition
     */
    public function getAgentCommisCount($codition){
        return $this->where($codition)->count();
    }

    /**
     * @param $change_type
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    function saveCommisLog($change_type,$data = array()){
        $data_log = array();
        $data_agent = array();

        $data_log['member_id'] = $data['member_id'];
        $data_log['member_name'] = $data['member_name'];
        $data_log['agent_id'] = $data['agent_id'];
        $data_log['commis_time'] = $data['commis_time'];
        $data_log['commis_type'] = $change_type;
        $data_log['commis_amount'] = $data['commis'];
        $data_log['commis_sn'] = $data['sn'];

        switch ($change_type){
            case 'upgrade':
                $data_log['commis_desc'] = '会员['.$data['member_name'].']升级，单号: '.$data['sn'];
                $data_agent['upgrade_commis'] = array('exp','upgrade_commis+'.$data['commis']);
                break;
            case 'sale':
                $data_log['commis_desc'] = '商家['.$data['member_name'].']销售，单号: '.$data['sn'];
                $data_agent['sale_commis'] = array('exp','sale_commis+'.$data['commis']);
                break;
            default:
                throw new Exception('参数错误');
                break;
        }

        $update = Model('agent')->editAgent($data_agent,array('agent_id'=>$data['agent_id']));
        if (!$update) {
            throw new Exception('操作失败');
        }
        $insert = $this->insert($data_log);
        if (!$insert) {
            throw new Exception('操作失败');
        }
        return $insert;

    }

    /**
     *
     * 获得会员升级代理佣金
     *
     * @param $agent_id
     * @param int $member_id
     *
     * @return array
     */
    public function getAgentCommissionByUpgrade($agent_id,$member_id = 0){
        if($member_id > 0){
            $upgrade_info = Model('upgrade')->getLastUpgrade($member_id);
            $condition['commis_sn'] = $upgrade_info['pu_sn'];
        }

        $condition['agent_id'] = $agent_id;
        $condition['commis_type'] = 'upgrade';
        list($commis_info) = Model('agent_commis')->getAgentCommisList($condition, 1,"commis_amount");

        if($commis_info){
            return $commis_info['commis_amount'];
        }else{
            return 0;
        }
    }

    /**
     *
     * 获得商家销售代理佣金
     * @param $agent_id
     * @param $member_id
     * @param $stime
     * @param $etime
     * @return mixed
     */
    public function getAgentCommissionBySale($agent_id,$member_id,$stime,$etime){
        $condition['agent_id'] = $agent_id;
        $condition['commis_type'] = 'sale';
        $condition['member_id'] = $member_id;
        $condition['commis_time'] = array('between',array($stime,$etime));
        return $this->where($condition)->sum('commis_amount');
    }

    /**
     *
     * 计算代理总佣金
     * @param $agent_id
     * @param int $start_date
     * @param int $end_date
     *
     * @return array
     */
    public function getAgentCommissionAmount($agent_id,$start_date,$end_date){
        $where = '';
        if($start_date && $end_date){
            $where = "AND T2.pu_payment_time between {$start_date} AND {$end_date}";
        }
        $sql = "SELECT sum(T1.commis_amount) as commission FROM czb.yunpay_agent_commis T1
                left join
                czb.yunpay_pd_upgrade T2
                on T1.commis_sn = T2.pu_sn
                WHERE T1.commis_type = 'upgrade' AND T2.pu_payment_state = '1' {$where}
                AND T1.agent_id = {$agent_id}";


        list($sum) = Db::getAll($sql);
        return $sum['commission'];
    }
}