<?php

defined('InSystem') or exit('Access Invalid!');
class registerControl  {

	public function __construct() {
		//parent::__construct();

    }
	public function indexOp(){
		Tpl::showpage('register');
	}

    public function protocolOp(){
        Tpl::showpage('protocol');
    }
}
