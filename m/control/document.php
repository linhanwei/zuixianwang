<?php
defined('InSystem') or exit('Access Invalid!');
class documentControl extends mobileHomeControl{

	public function __construct() {
        parent::__construct();
    }


    private function displayContent($doc_id){
        $model_document = Model('document');

        $condition = array();
        $condition['doc_id'] = $doc_id;
        $document = $model_document->getOneById($doc_id);

        Tpl::output('html_title',$document['doc_title']);
        Tpl::output('document',$document);
        Tpl::showpage('document');
    }

    /**
     * 用户服务协议
     */
    public function indexOp(){
        $this->displayContent(1);
    }

    /**
     * 开店协议
     */
    public function storeOp(){
        $this->displayContent(4);
    }

    /**
     * VIP会员
     */
    public function vipOp(){
        $this->displayContent(5);
    }

    /**
     * 油卡
     */
    public function oilOp(){
        $this->displayContent(6);
    }

    /**
     * 中石化
     */
    public function oil_zshOp(){
        $this->displayContent(7);
    }

    /**
     * BP
     */
    public function oil_bpOp(){
        $this->displayContent(8);
    }


    /**
     * 中石油，广东
     */
    public function oil_gdOp(){
        $this->displayContent(9);
    }
}
