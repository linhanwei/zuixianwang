<?php

defined('InSystem') or exit('Access Invalid!');
class indexControl extends ClientControl{

	public function __construct() {
		parent::__construct();
    }

	public function indexOp(){

		Tpl::showpage('index');
	}



}
