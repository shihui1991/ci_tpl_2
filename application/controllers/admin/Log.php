<?php
/**
 *  Log
 * @author 罗仕辉
 * @create 2018-09-16
 */

require_once APPPATH.'controllers/admin/Auth.php';

class Log extends Auth
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  日志
     */
    public function index()
    {
        $list = getDirAllDirOrFile(APPPATH.'logs');

        $data=array(
            'List'=>$list,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/log/index',
        );
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /** 删除文件
     * @throws Exception
     */
    public function del()
    {
        if(empty($this->inputData['File'])) {
            throw new Exception('请选择文件',EXIT_USER_INPUT);
        }
        $file = realpath($this->inputData['File']);
        if(false == $file){
            throw new Exception('文件不存在',EXIT_UNKNOWN_FILE);
        }
        if(is_dir($file)){
            exec('rm -rf '.$file);
            $result = true;
        }
        else{
            $result = unlink($file);
        }
        if(false == $result){
            throw new Exception('删除失败',EXIT_CONFIG);
        }

        $data=array();
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array();
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /** 下载
     * @throws Exception
     */
    public function download()
    {
        if(empty($this->inputData['File'])) {
            throw new Exception('请选择文件',EXIT_USER_INPUT);
        }
        $file = realpath($this->inputData['File']);
        if(false == $file){
            throw new Exception('文件不存在',EXIT_UNKNOWN_FILE);
        }
        // 文件输出
        outputHeaderForFile(basename($file),filesize($file));
        @readfile($file);
        exit;
    }

    /** 查看文件
     * @throws Exception
     */
    public function info()
    {
        if(empty($this->inputData['File'])) {
            throw new Exception('请选择文件',EXIT_USER_INPUT);
        }
        $file = realpath($this->inputData['File']);
        if(false == $file){
            throw new Exception('文件不存在',EXIT_UNKNOWN_FILE);
        }
        ini_set('memory_limit','512M');
        // 逐行读取
        $content = makeTextYield($file);

        $data=array(
            'File'=>$file,
            'Updated'=>filemtime($file),
            'Content'=>$content,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/log/info',
        );
        $this->_response($data,$code,$msg,$url,$tpls);
    }
}