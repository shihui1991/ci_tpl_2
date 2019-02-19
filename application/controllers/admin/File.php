<?php
/**
 *  File
 * @author 罗仕辉
 * @create 2018-09-16
 */

require_once APPPATH.'controllers/admin/Auth.php';

class File extends Auth
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  上传目录 和 下载目录
     */
    public function index()
    {
        $dirs=array(
            UPLOAD_LINK_DIR,  // 上传目录
            DOWNLOAD_DIR,     // 下载目录
        );
        foreach($dirs as $dir){
            $list[]=array(
                'File'=>$dir,
                'IsDir'=>1,
                'Dir'=>'./',
                'Path'=>$dir,
                'RealDir'=>realpath('./'),
                'RealPath'=>realpath($dir),
                'Size'=>'0B',
                'Updated'=>date('Y-m-d H:i:s',filemtime(realpath($dir))),
            );
            $upload = getDirAllDirOrFile($dir);
            $list=array_merge($list,$upload);
        }

        $data=array(
            'List'=>$list,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/file/index',
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
}