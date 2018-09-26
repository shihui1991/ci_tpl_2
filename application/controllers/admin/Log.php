<?php
/**
 *  Log
 * @user 罗仕辉
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
        $basename=basename($file);
//        $content=file_get_contents($file);

        Header( "Content-type: application/octet-stream ");
        Header( "Accept-Ranges: bytes ");
        header( "Content-Disposition: attachment; filename={$basename} ");
        header( "Expires: 0 ");
        header( "Cache-Control: must-revalidate, post-check=0, pre-check=0 ");
        header( "Pragma: public ");

        // 逐行输出
        $handle = fopen($file, "r") or exit("不能打开文件");
        while(!feof($handle))
        {
            echo fgets($handle);
        }
        fclose($handle);
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
        // 逐行读取
        $content='';
        $handle = fopen($file, "r") or exit("不能打开文件");
        while(!feof($handle))
        {
            $content .= fgets($handle);
        }
        fclose($handle);

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