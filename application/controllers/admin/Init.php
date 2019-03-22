<?php
/**
 *  初始化
 * @author 罗仕辉
 * @create 2018-09-08
 */

require_once APPPATH.'controllers/Base.php';


class Init extends Base
{
    protected $logicModel;
    protected $menu;
    protected $master;

    public function __construct()
    {
        parent::__construct();

    }

    /**  响应输出
     * @param array $data 响应数据
     * @param int $code   响应代码
     * @param string $msg 提示信息
     * @param string $url 重定向地址
     * @param array $tpls 响应模板
     */
    public function _response($data=array(),$code=EXIT_SUCCESS,$msg='请求成功',$url='', $tpls=array())
    {
        // 响应数据
        parent::_response($data,$code,$msg,$url,$tpls);
        $resp=json_encode($this->outputData,JSON_UNESCAPED_UNICODE);

        // AJAX
        if($this->input->is_ajax_request()){
            echo $resp;
        }
        else{
            if(empty($url)){
                if(empty($tpls)){
                    echo $resp;
                }else{
                    $this->outputData['data']['CurMenu'] = $this->menu;

                    $this->load->view('layout/head',$this->outputData);

                    if(is_string($tpls)){
                        $this->load->view($tpls,$this->outputData);
                    }else{
                        foreach($tpls as $tpl){
                            $this->load->view($tpl,$this->outputData);
                        }
                    }
                    $this->load->view('layout/foot',$this->outputData);
                }
            }else{
                echo '<script>alert("'.$msg.'");location.href="'.$url.'"</script>';
            }
        }
    }

    /** 生成分页条
     * @param $baseUrl
     * @param $total
     * @param $perPage
     * @param int $linksNum
     * @return mixed
     */
    protected function _makePageLinks($baseUrl, $total, $perPage, $linksNum=DEFAULT_PAGEBAR_NUM)
    {
        // 生成分页
        $this->load->library('pagination');
        $pageConfig=array(
            'base_url'=>$baseUrl,
            'total_rows'=>$total,
            'per_page'=>$perPage,
            'num_links'=>$linksNum,
            'use_page_numbers'=>TRUE,
            'page_query_string'=>TRUE,
            'reuse_query_string'=>TRUE,
            'query_string_segment'=>'Page',
            'full_tag_open'=>'<div class="layui-box layui-laypage layui-laypage-default">',
            'full_tag_close'=>'</div>',
            'first_link'=>'首页',
            'last_link'=>'末页',
            'next_link'=>'<i class="layui-icon layui-icon-next"></i>',
            'prev_link'=>'<i class="layui-icon layui-icon-prev"></i>',
            'cur_tag_open'=>'<span class="layui-laypage-curr"><em class="layui-laypage-em"></em><em>',
            'cur_tag_close'=>'</em></span>',
        );
        $this->pagination->initialize($pageConfig);
        $links=$this->pagination->create_links();

        return $links;
    }

    /** 获取当前菜单
     * @return array
     */
    protected function _getMenu()
    {
        $menu = \models\logic\MenuLogic::instance()->getRowByUrl($this->requestUrl);
        if(empty($menu['Id'])){
            return array();
        }
        $this->menu = $menu;

        return $this->menu;
    }

    /** 检查登录
     * @throws Exception
     */
    protected function _checkLogin()
    {
        // 验证登录
        $_SESSION['redirect']='/admin';
        if(empty($_SESSION['Master'])){
            if(empty($this->inputData['Token']) || empty($this->inputData['Id'])){
                throw new \Exception('请登录！',EXIT_USER_INPUT);
            }
            \models\logic\MasterLogic::instance()->checkLogin($this->inputData);
        }else{
            if(time()>$_SESSION['Master']['Timeout']){
                unset($_SESSION['Master']);
                throw new \Exception('等待超时，请重新登录！',EXIT_CONFIG);
            }
            $_SESSION['Master']['Timeout']=time()+(int)OPERAT_WAIT_TIME;
        }
        $this->master=$_SESSION['Master'];
        unset($_SESSION['redirect']);
        // 验证菜单
        $this->_getMenu();
        if(empty($this->menu['Id'])){
            throw new \Exception('无法访问',EXIT_CONFIG);
        }
        if(STATE_OFF == $this->menu['State']){
            throw new \Exception('功能已禁用',EXIT_CONFIG);
        }
        if(ADMIN_NO == $this->master['IsAdmin']
            && YES == $this->menu['Ctrl']
            && !in_array($this->menu['Id'],$this->master['MenuIds'])){

            throw new \Exception('未授权',EXIT_CONFIG);
        }
    }
}