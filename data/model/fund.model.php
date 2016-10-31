<?php
/**
 * 合粉公益
 *
 * 
 *
 *
 */
defined('InSystem') or exit('Access Invalid!');

class fundModel{
	/**
	 * 查询所有系统文章
	 */
	public function getList(){
		$param	= array(
			'table'	=> 'fund'
		);
		return Db::select($param);
	}

    /**
     * 根据编号查询一条
     * @param $id
     * @return array
     */
	public function getOneById($id){
		$param	= array(
			'table'	=> 'fund',
			'field'	=> 'fund_id',
			'value'	=> $id
		);
		return Db::getRow($param);
	}

    /**
     *
     * 更新
     *
     * @param $param
     * @return bool
     */
	public function update($param){
		return Db::update('fund',$param,"fund_id='{$param['fund_id']}'");
	}
}