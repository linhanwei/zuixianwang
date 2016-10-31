<?php
/**
 * 父类
 *
 *
 *
 *
 *
 */

defined('InSystem') or exit('Access Invalid!');

class BaseCronControl {

    public function shutdown(){
        exit("success at ".date('Y-m-d H:i:s',TIMESTAMP)."\n");
    }

    public function __construct(){
        register_shutdown_function(array($this,"shutdown"));
    }

    /**
     * 记录日志
     * @param unknown $content 日志内容
     * @param boolean $if_sql 是否记录SQL
     */
    protected function log($content, $if_sql = true) {
        if ($if_sql) {
            $log = Log::read();
            if (!empty($log) && is_array($log)){
                $content .= end($log);
            }
        }
        Log::record('queue\\'.$content,Log::RUN);
    }
}