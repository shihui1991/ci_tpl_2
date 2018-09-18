<?php
/**
 *  初始化
 * @user 罗仕辉
 * @create 2018-09-08
 */

require_once APPPATH.'controllers/Base.php';

use models\logic\MenuLogic;

class Init extends Base
{
    protected $logicModel;
    protected $menu;

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
    public function _response(array $data=array(),$code=EXIT_SUCCESS,$msg='请求成功',$url='', $tpls=array())
    {
        // 登录验证失败强制跳转
        if(isset($_SESSION['redirect'])){
            $url=$_SESSION['redirect'];
            unset($_SESSION['redirect']);
        }
        // 响应数据
        parent::_response($data,$code,$msg,$url,$tpls);
        $resp=json_encode($this->outputData);

        // AJAX
        if($this->input->is_ajax_request()){
            echo $resp;
        }
        else{
            if(empty($url)){
                if(empty($tpls)){
                    echo $resp;
                }else{
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
        $menu=MenuLogic::instance()->getRowByUrl($this->requestUrl);
        if(empty($menu['Id'])){
            return array();
        }
        $this->menu = $menu;

        return $this->menu;
    }
}