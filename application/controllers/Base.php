<?php
/**
 *  基础
 * @user 罗仕辉
 * @create 2018-08-19
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Base extends CI_Controller
{
    protected $inputData=array();   // 请求输入数据
    protected $outputData=array();  // 响应输出数据
    protected $requestUrl;  // 请求URL

    public function __construct()
    {
        parent::__construct();

        // 记录异常
        if(ENVIRONMENT == 'development'){
            set_error_handler(array($this,'_recordException'));
        }
        register_shutdown_function(array($this,'_handleError'));
        set_exception_handler(array($this,'_handleException'));

        // 获取请求数据
        $this->inputData=$this->_getInputXssCleanData();

        // 加载辅助函数
        $this->load->helper('common');
        $this->load->helper('url');
        $this->load->library('session');

        // 获取请求 URL
        $url=uri_string();
        $index=strpos($url,'/');
        if(FALSE === $index || $index > 0){
            $url = '/'.$url;
        }
        $this->requestUrl=$url;

        // 自动加载模型、验证器、等（命名空间）
        spl_autoload_register(function ($class){
            $file=APPPATH.str_replace('\\','/',$class).'.php';
            if(file_exists($file)){
                require_once $file;
            }
        });
    }


    /**  获取请求输入数据
     * @return array
     */
    public function _getInputData()
    {
        // 获取 get 输入
        $get=$this->input->get();
        // 获取 post 输入
        $post=$this->input->post();
        // 获取 stream 输入
        $stream=(array)json_decode(urldecode(file_get_contents('php://input')),true);
        // 数据合并
        $input=array_merge($get,$post,$stream);
        return $input;
    }

    /** 获取 XSS 安全过滤后的请求输入数据
     * @return array
     */
    public function _getInputXssCleanData()
    {
        $data=$this->_getInputData();
        $data=$this->security->xss_clean($data);
        return $data;
    }

    /**  文件上传
     * @return array|bool|int
     * @throws Exception
     */
    public function _upload()
    {
        // 上传配置
        $config=array();
        $config['allowed_types'] = '*';
        // 保存目录
        if(empty($this->inputData['SavePath'])){
            $savePath= '/uploads/'.date('Ymd');
        }else{
            $savePath='/uploads/'.$this->inputData['SavePath'];
        }
        $savePath=str_replace(' ','_',trim($savePath));
        $path='.'.$savePath;
        if(!file_exists($path)){
            mkdir($path,DIR_WRITE_MODE,true);
        }
        $config['upload_path'] = $path.'/';

        // 保存文件名
        if(empty($this->inputData['SaveName'])){
            $saveName=time();
            $config['encrypt_name']=true;
        }else{
            $saveName=$this->inputData['SaveName'];
            $saveName=str_replace(' ','_',trim($saveName));
            $config['file_name']=$saveName;
        }

        // 是否覆盖
        $overwrite=false;
        if(!empty($this->inputData['Overwrite'])){
            $overwrite=true;
        }
        $config['overwrite']=$overwrite;

        // 上传文件键名
        if(empty($this->inputData['UploadName'])){
            $uploadName= 'UploadFile';
        }else{
            $uploadName=trim($this->inputData['UploadName']);
        }

        // 表单上传
        $this->load->library('upload', $config);
        $result=$this->upload->do_upload($uploadName);
        if ( false !== $result )
        {
            $infos = $this->upload->data();
            $savePath=stristr($infos['file_path'],$savePath);
            $saveName=$infos['file_name'];
            $fileUrl=stristr($infos['full_path'],$savePath);
            goto result;
        }
        // base64 上传
        if(empty($this->inputData[$uploadName])){
            throw new Exception('输入文件',EXIT_USER_INPUT);
        }
        $stream = base64_decode($this->inputData[$uploadName]);
        $result = file_put_contents($path.'/'.$saveName, $stream);
        if(false !== $result){
            $fileUrl=$savePath.'/'.$saveName;
            goto result;
        }
        throw new Exception('上传失败',EXIT_ERROR);

        result:

        $protocol='http';
        if(is_https()){
            $protocol ='https';
        }
        $result=array(
            'FilePath'=>$savePath,
            'FileName'=>$saveName,
            'FileUrl'=>$protocol.'://'.$_SERVER['HTTP_HOST'].$fileUrl,
        );

        return $result;
    }

    /**  响应输出
     * @param array $data 响应数据
     * @param int $code   响应代码
     * @param string $msg 提示信息
     * @param string $url 重定向地址
     * @param array $tpls 响应模板
     */
    public function _response(array $data=array(),$code=EXIT_SUCCESS,$msg='请求成功',$url='', $tpls=array()){}

    /**  记录异常
     * @param $type
     * @param $msg
     * @param $file
     * @param $line
     */
    public function _recordException($type, $msg, $file, $line)
    {
        $datetime=date('Y-m-d H:i:s');
        $url=current_url();
        $ip=$this->input->ip_address();
        $get=json_encode($this->input->get());
        $post=json_encode($this->input->post());
        $stream=urldecode($this->input->raw_input_stream);
        $output=json_encode($this->outputData);

        $record=<<<"EEE"

[$url][$ip][$datetime][$type][$msg][$file][$line]
[Get:]$get
[Post:]$post
[Stream:]$stream
[OutputData:]$output
-------------------------------------------------

EEE;
        log_message('error',$record);

        $data=array();
        $code=EXIT_ERROR;
        $msg='';
        $url='';
        $tpls=array();
        // 缓存重定向 URL
        if(isset($_SESSION['redirect'])){
            $url=$_SESSION['redirect'];
        }

        $args=func_get_args();
        if(isset($args[5])){
            $code=$args[5];
            $msg=$args[4];
        }

        $this->_response($data,$code,$msg,$url,$tpls);
        exit;
    }

    /**
     *  处理致命错误（Fatal Error、Parse Error等）
     */
    public function _handleError()
    {
        $error=error_get_last();
        if(!empty($error)){
            $this->_recordException($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    /**  处理异常（没有 try/catch 捕获的代码块异常）
     * @param Exception $e
     */
    public function _handleException(Exception $e)
    {
        $type = $e->getCode();
        $msg  = $e->getMessage();
        $file = $e->getFile();
        $line = $e->getLine();
        $this->_recordException($type, $msg, $file, $line,$msg,$type);
    }
}