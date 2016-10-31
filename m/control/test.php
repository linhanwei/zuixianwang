<?php
/**
 * 测试实例
 *
 *
 *
 *
 *
 */


defined('InSystem') or exit('Access Invalid!');

class testControl extends mobileHomeControl {

	public function __construct(){
		parent::__construct();

        var_dump(getMobileArea($_POST['mobile_phone']));
	}

    public function testPoints(){

    }
}
