<?php
/**
 * 生成地区json
 *
 *
 *
 *
 */

defined('InSystem') or exit('Access Invalid!');

class areaControl {


    public function jsonOp(){
        $areaList = array();
        $model_area = Model('area');
        $fields = 'area_id,area_name';
        $areaList = $model_area->getAreaList(array('area_deep'=>1),$fields);
        foreach($areaList as $key=>$val){
            $areaList[$key]['city'] = $model_area->getAreaList(array('area_parent_id'=>$val['area_id']),$fields);

            foreach($areaList[$key]['city'] as $k=>$v){
                $areaList[$key]['city'][$k]['region'] = $model_area->getAreaList(array('area_parent_id'=>$v['area_id']),$fields);
            }
        }

        echo json_encode($areaList);
    }
}