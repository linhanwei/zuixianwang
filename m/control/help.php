<?php
defined('InSystem') or exit('Access Invalid!');
class helpControl{

    public function __construct() {
    }


    public function contentOp(){
        $model_article = Model('article');
        $article_array = $model_article->getOneArticle(intval($_GET['id']));
        Tpl::output('html_title',$article_array['article_title']);
        Tpl::output('article',$article_array);
        Tpl::showpage('help_content');
    }

    /**
     * 用户帮助列表
     */
    public function indexOp(){
        $cate_list = Model('article_class')->getTreeClassList(2,2);
        if($cate_list){
            $model_article = Model('article');
            $condition['article_type'] = 2;
            foreach($cate_list as $k=>$v){
                $condition['ac_id'] = $v['ac_id'];
                $cate_list[$k]['article_list'] = $model_article->getArticleList($condition,20);
            }
        }

        Tpl::output('cate_list',$cate_list);
        Tpl::output('html_title','问题解答');
        Tpl::showpage('help_list');
    }

}
