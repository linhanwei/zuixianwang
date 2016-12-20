<?php
/**
 * 会员投诉建议
 *
 ***/

defined('InSystem') or exit('Access Invalid!');

class complain_suggestControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

    //新的投诉建议订单
    public function indexOp(){
        $this->get_listOp('index');
    }

    //已经处理的投诉建议
    public function handldOp(){
        $this->get_listOp('handld');
    }

    public function get_listOp($op){
        $page = new Page();
        $page->setEachNum(10) ;
        $page->setStyle('admin') ;
        $model_complain_suggest = Model('complain_suggest') ;
        $mobile = trim($_GET['mobile']);
        $member_name = trim($_GET['member_name']);

        //搜索条件
        $condition = array();
        if($mobile) $condition['cs_mobile'] = $mobile;
        if($member_name) $condition['cs_member_name'] = $member_name;

        if($op == 'index'){
            $list = $model_complain_suggest->getNewList($condition,$page) ;
        }else{
            $list = $model_complain_suggest->getHandleList($condition,$page) ;
        }

        Tpl::output('op',$op);
        Tpl::output('list', $list) ;
        Tpl::output('show_page',$page->show()) ;

        Tpl::showpage('complain_suggest.list');
    }

    /**
     * 详情
     */
    public function cs_infoOp(){
        $model_complain_suggest = Model('complain_suggest');


        $id = $_GET['id'];
        $info = $model_complain_suggest->getInfo(array('id'=>$id));

        Tpl::output('info',$info);
        Tpl::showpage('complain_suggest.detail');
    }

    /**
     * ajax操作
     */
    public function ajaxOp(){
        $cs_id = $_POST['cs_id'];
        $info = false;

        switch ($_POST['handle']){
            case 'handle_complete':
                $info = Model('complain_suggest')->handleComplete($cs_id);
                break;
            case 'del':
                $info = Model('complain_suggest')->delData($cs_id);
                break;
        }

        if ($info){
            echo(1);
        }else {
            echo(0);
        }
        die;
    }

}
