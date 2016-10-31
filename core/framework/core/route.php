<?php
/**
 * 路由
 *
 */
defined('InSystem') or exit('Access Invalid!');

class Route {

    /**
     * PATH_INFO 分隔符
     *
     * @var string
     */
    private $_pathinfo_split = '-';

    /**
     * 系统配置信息
     *
     * @var array
     */

    private $_config = array();

    /**
     * PATH_INFO内容分隔正则
     *
     * @var string
     */
    private $_pathinfo_pattern = '';

    /**
     * 伪静态文件扩展名
     *
     * @var string
     */
    private $_rewrite_extname = '.html';

    /**
     * 构造方法
     *
     */
    public function __construct($config = array()) {
        $this->_config = $config;
        $this->_pathinfo_pattern = "/{$this->_pathinfo_split}/";
        $this->parseRule();
    }

    /**
     * 路由解析
     *
     */
    public function parseRule() {
        if ($this->_config['url_model']) {
            $this->_parseRuleRewrite();
        } else {
            $this->_parseRuleNormal();
        }
    }

    /**
     * 默认URL模式
     *
     */
    private function _parseRuleNormal() {
        //不进行任何处理
    }

    /**
     * 伪静态模式
     *
     */
    private function _parseRuleRewrite() {
        $path_info = $_SERVER['REQUEST_URI'];
        $path_info = substr($path_info,strrpos($path_info,'/')+1);
        if(strpos($path_info, '?')) {
            $path_info = substr($path_info, 0, (int) strpos($path_info, '?'));
        }
        if (!empty($path_info) && $path_info != 'index.php' && strpos($path_info, $this->_rewrite_extname)){
            //去掉伪静态扩展名
            $path_info = substr($path_info,0,-strlen($this->_rewrite_extname));

            //根据不同APP匹配url规则
            $path_info_function = '_' . APP_ID . 'PathInfo';
            if (!method_exists($this,$path_info_function)) {
                return ;
            }
            $path_info = $this->$path_info_function($path_info);

            $split_array = preg_split($this->_pathinfo_pattern,$path_info);
            //act,op强制赋值
            $_GET['act'] = isset($split_array[0]) ? $split_array[0] : 'index';
            $_GET['op'] = isset($split_array[1]) ? $split_array[1] : 'index';
            unset($split_array[0]);
            unset($split_array[1]);

            //其它参数也放入$_GET
            while (current($split_array) !== false) {
                $name = current($split_array);
                $value = next($split_array);
                $_GET[$name] = $value;
                if (next($split_array) === false){
                    break;
                }
            }
        } else {
            $_GET['act'] = $_GET['op'] = 'index';
        }
    }

}
