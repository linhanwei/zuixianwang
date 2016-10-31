<?php

defined('InSystem') or exit('Access Invalid!');
class loginControl  extends ClientControl{

	public function __construct() {
        parent::__construct(false);

        $token = parent::get_token();
        Tpl::output('t',$token[0]);
        Tpl::output('token',$token[1]);
    }
	public function indexOp(){
		Tpl::showpage('login');
	}

    public function protocolOp(){
        Tpl::showpage('protocol');
    }

    public function get_passwordOp(){
        Tpl::output('pass_type',$_GET['pass_type']);
        Tpl::showpage('get_password');
    }

}
