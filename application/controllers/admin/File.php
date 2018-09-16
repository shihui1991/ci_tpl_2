<?php
/**
 *  File
 * @user 罗仕辉
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
        // 上传目录
        $list[]=array(
            'File'=>UPLOAD_DIR,
            'IsDir'=>1,
            'Dir'=>'./',
            'Path'=>UPLOAD_DIR,
            'RealDir'=>realpath('./'),
            'RealPath'=>realpath(UPLOAD_DIR),
            'Updated'=>date('Y-m-d H:i:s',filemtime(realpath(UPLOAD_DIR))),
        );
        $upload = getDirAllDirOrFile(UPLOAD_DIR);
        $list=array_merge($list,$upload);
        // 下载目录
        $list[]=array(
            'File'=>DOWNLOAD_DIR,
            'IsDir'=>1,
            'Dir'=>'./',
            'Path'=>DOWNLOAD_DIR,
            'RealDir'=>realpath('./'),
            'RealPath'=>realpath(DOWNLOAD_DIR),
            'Updated'=>date('Y-m-d H:i:s',filemtime(realpath(DOWNLOAD_DIR))),
        );
        $download = getDirAllDirOrFile(DOWNLOAD_DIR);
        $list=array_merge($list,$download);

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
            $result = exec('rm -rf '.$file);
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