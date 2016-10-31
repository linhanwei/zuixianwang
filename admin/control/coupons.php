<?php
/**
 * 房车电子优惠券
 */

defined('InSystem') or exit('Access Invalid!');

class couponsControl extends SystemControl
{
    const EXPORT_SIZE = 100;

    public function __construct()
    {
        parent::__construct();
    }

    public function indexOp()
    {
        $model = Model('coupons');
        $condition = array();

        if (isset($_GET['form_submit'])) {
            $sn = trim((string) $_GET['sn']);
            $batchflag = trim((string) $_GET['batchflag']);
            $state = trim((string) $_GET['state']);
            $member_name = trim((string) $_GET['member_name']);

            if (strlen($sn) > 0) {
                $condition['sn'] = array('like', "%{$sn}%");
                Tpl::output('sn', $sn);
            }

            if (strlen($member_name) > 0) {
                $condition['member_name'] = $member_name;
                Tpl::output('member_name', $member_name);
            }

            if (strlen($batchflag) > 0) {
                $condition['batchflag'] = array('like', "%{$batchflag}%");
                Tpl::output('batchflag', $batchflag);
            }

            if ($state === '0' || $state === '1') {
                $condition['state'] = $state;
                Tpl::output('state', $state);
            }

            if ($condition) {
                Tpl::output('form_submit', 'ok');
            }
        }

        $cardList = $model->getCouponsList($condition, 20);

        Tpl::output('card_list', $cardList);
        Tpl::output('show_page', $model->showpage());

        Tpl::showpage('coupons.index');
    }

    public function addOp()
    {
        if (!chksubmit()) {
            Tpl::showpage('coupons.add');
            return;
        }

        $denomination = (float) $_POST['denomination'];
        if ($denomination < 0.01) {
            showMessage('面额不能小于0.01', '', 'html', 'error');
            return;
        }
        if ($denomination > 1000) {
            showMessage('面额不能大于1000', '', 'html', 'error');
            return;
        }

        $snKeys = array();

        switch ($_POST['type']) {
        case '0':
            $total = (int) $_POST['total'];
            if ($total < 1 || $total > 9999) {
                showMessage('总数只能是1~9999之间的整数', '', 'html', 'error');
                exit;
            }
            $prefix = (string) $_POST['prefix'];
            if (!preg_match('/^[0-9a-zA-Z]{0,16}$/', $prefix)) {
                showMessage('前缀只能是16字之内字母数字的组合', '', 'html', 'error');
                exit;
            }
            while (count($snKeys) < $total) {
                //$snKeys[$prefix . md5(uniqid(mt_rand(), true))] = null;
                $snKeys[$prefix . random(4) . '-' . random(4) . '-' . random(4) . '-' . random(4)] = null;
            }
            break;

        case '1':
            $f = $_FILES['_textfile'];
            if (!$f || $f['error'] != 0) {
                showMessage('文件上传失败', '', 'html', 'error');
                exit;
            }
            if (!is_uploaded_file($f['tmp_name'])) {
                showMessage('未找到已上传的文件', '', 'html', 'error');
                exit;
            }
            foreach (file($f['tmp_name']) as $sn) {
                $sn = trim($sn);
                if (preg_match('/^[0-9a-zA-Z]{1,50}$/', $sn))
                    $snKeys[$sn] = null;
            }
            break;

        case '2':
            foreach (explode("\n", (string) $_POST['manual']) as $sn) {
                $sn = trim($sn);
                if (preg_match('/^[0-9a-zA-Z]{1,50}$/', $sn))
                    $snKeys[$sn] = null;
            }
            break;

        default:
            showMessage('参数错误', '', 'html', 'error');
            exit;
        }

        $totalKeys = count($snKeys);
        if ($totalKeys < 1 || $totalKeys > 9999) {
            showMessage('只能在一次操作中增加1~9999个充值卡号', '', 'html', 'error');
            exit;
        }

        if (empty($snKeys)) {
            showMessage('请输入至少一个合法的电子券号', '', 'html', 'error');
            exit;
        }

        $snOccupied = 0;
        $model = Model('coupons');

        // chunk size = 50
        foreach (array_chunk(array_keys($snKeys), 50) as $snValues) {
            foreach ($model->getOccupiedCouponsSNsBySNs($snValues) as $sn) {
                $snOccupied++;
                unset($snKeys[$sn]);
            }
        }

        if (empty($snKeys)) {
            showMessage('操作失败，所有新增电子券与现有的电子券冲突', '', 'html', 'error');
            exit;
        }

        $batchflag = $_POST['batchflag'];
        $to_date = strtotime($_POST['to_date']?$_POST['to_date']:date('Y-m-d',strtotime("10 year")));
        $adminName = $this->admin_info['name'];

        $snToInsert = array();
        foreach (array_keys($snKeys) as $sn) {
            $snToInsert[] = array(
                'sn' => $sn,
                'denomination' => $denomination,
                'batchflag' => $batchflag,
                'admin_name' => $adminName,
                'to_date'=>$to_date,
                'create_at' => TIMESTAMP,
            );
        }

        if (!$model->insertAll($snToInsert)) {
            showMessage('操作失败', '', 'html', 'error');
            exit;
        }

        $countInsert = count($snToInsert);
        $this->log("新增{$countInsert}张电子券（面额￥{$denomination}，批次标识“{$batchflag}”）");

        $msg = '操作成功';
        if ($snOccupied > 0)
            $msg .= "有 {$snOccupied} 个电子券与已有的未使用电子券冲突";

        showMessage($msg, urlAdmin('coupons', 'index'));
    }


    public function useOp()
    {
        if (!chksubmit()) {
            Tpl::showpage('coupons.use');
            return;
        }
        $sn = $_POST['sn'];
        $model_coupons = Model('coupons');
        $coupons_info = $model_coupons->getCouponsBySN($sn);

        if(!$coupons_info){
            showMessage('电子消费券不存在', '', 'html', 'error');
            exit;
        }

        if($coupons_info['state'] != '0'){
            showMessage('电子消费券已使用', '', 'html', 'error');
            exit;
        }

        if($coupons_info['to_date']<strtotime(date('Y-m-d',time()))){
            showMessage('电子消费券已过有效期', '', 'html', 'error');
            exit;
        }


        $member_name = $_POST['member_name'];
        $member_info = Model('member')->getMemberInfo(array('member_name'=>$member_name));
        if(empty($member_info)){
            showMessage('会员不存在', '', 'html', 'error');
            exit;
        }

        if($coupons_info['member_id'] > 0  && ($coupons_info['member_id'] != $member_info['member_id'])){
            showMessage('此券不属于该会员', '', 'html', 'error');
            exit;
        }

        $model_coupons->setCouponsUsedById($coupons_info['id'],$member_info['member_id'],$member_info['member_name']);

        $this->log("{$member_name}领取电子券(#ID:{$coupons_info['id']})");

        $msg = '操作成功';
        showMessage($msg, urlAdmin('coupons', 'use'));
    }

    public function del_couponsOp()
    {
        if (empty($_GET['id'])) {
            showMessage('参数错误', '', 'html', 'error');
        }

        Model('coupons')->delCouponsById($_GET['id']);

        $this->log("删除电子券（#ID: {$_GET['id']}）");

        showMessage('操作成功', getReferer());
    }

    public function del_coupons_batchOp()
    {
        if (empty($_POST['ids']) || !is_array($_POST['ids'])) {
            showMessage('参数错误', '', 'html', 'error');
        }

        Model('coupons')->delCouponsById($_POST['ids']);

        $count = count($_POST['ids']);
        $this->log("删除{$count}张电子券");

        showMessage('操作成功', getReferer());
    }

    /**
     * 导出
     */
    public function export_step1Op()
    {
        $model = Model('coupons');
        $condition = array();

        if (isset($_GET['form_submit'])) {
            $sn = trim((string) $_GET['sn']);
            $batchflag = trim((string) $_GET['batchflag']);
            $state = trim((string) $_GET['state']);

            if (strlen($sn) > 0) {
                $condition['sn'] = array('like', "%{$sn}%");
                Tpl::output('sn', $sn);
            }

            if (strlen($batchflag) > 0) {
                $condition['batchflag'] = array('like', "%{$batchflag}%");
                Tpl::output('batchflag', $batchflag);
            }

            if ($state === '0' || $state === '1') {
                $condition['state'] = $state;
                Tpl::output('state', $state);
            }

            if ($condition) {
                Tpl::output('form_submit', 'ok');
            }
        }

        if (!is_numeric($_GET['curpage'])){
            $count = $model->getCouponsCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE ){	//显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=coupons&op=index');
                Tpl::showpage('export.excel');
                return;

            }else{	//如果数量小，直接下载
                $data = $model->getCouponsList($condition, self::EXPORT_SIZE);

                $this->createExcel($data);
            }
        }else{	//下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;

            $data = $model->getCouponsList($condition, 20, "{$limit1},{$limit2}");

            $this->createExcel($data);
        }
    }

    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'电子券号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'批次标识');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'面额(元)');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'发布管理员');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'发布时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'领取人');

        //data
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>"\t".$v['sn']);
            $tmp[] = array('data'=>"\t".$v['batchflag']);
            $tmp[] = array('data'=>"\t".$v['denomination']);
            $tmp[] = array('data'=>"\t".$v['admin_name']);
            $tmp[] = array('data'=>"\t".date('Y-m-d H:i:s', $v['create_at']));
            if ($v['state'] == 1 && $v['member_id'] > 0 && $v['use_at'] > 0) {
                $tmp[] = array('data'=>"\t".$v['member_name']);
            } else {
                $tmp[] = array('data'=>"\t-");
            }
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('电子消费券',CHARSET));
        $excel_obj->generateXML($excel_obj->charset('电子消费券',CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }

    /**
     * ajax操作
     */
    public function ajaxOp(){
        switch ($_GET['branch']){
            /**
             * 判断会员是否存在
             */
            case 'check_member_name':
                $member_name = $_GET['member_name'];
                $member_info = Model('member')->getMemberInfo(array('member_name'=>$member_name));

                if (empty($member_info)){
                    echo 'false';
                }else {
                    echo 'true';
                }

                exit;
                break;
            /**
             * 检查电子券
             */
            case 'check_coupons':
                $sn = $_GET['sn'];
                $model_coupons = Model('coupons');
                $coupons_info = $model_coupons->getCouponsBySN($sn);

                if(!$coupons_info){
                    echo 'false';
                    exit();
                }

                if($coupons_info['state'] != '0'){
                   echo 'false';exit();
                }
                echo 'true';
                exit();
                break;

        }
    }
}
