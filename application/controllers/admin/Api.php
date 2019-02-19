<?php
/**
 *  Api
 * @author 罗仕辉
 * @create 2018-09-10
 */

require_once APPPATH.'controllers/admin/Auth.php';

use models\logic\ApiLogic;

class Api extends Auth
{
    public function __construct()
    {
        parent::__construct();

        $this->logicModel = ApiLogic::instance();
    }

    /**
     *  列表
     */
    public function index()
    {
        $page = 1;
        if(!empty($this->inputData['Page']) && $this->inputData['Page'] > 1){
            $page=(int)$this->inputData['Page'];
        }
        $perPage=DEFAULT_PERPAGE;
        if(!empty($this->inputData['PerPage']) && $this->inputData['PerPage'] > 1){
            $perPage=(int)$this->inputData['PerPage'];
        }
        // 处理筛选
        $filter = $this->logicModel->handleFilter($this->inputData);
        $params=$filter['Params'];
        $orderBy=$filter['OrderBy'];

        // 查询条数
        $total=$this->logicModel->getTotal($params);
        // 获取列表
        $list=$this->logicModel->getListByPage($page, $perPage,$params,$orderBy);

        // 生成分页条
        $links=$this->_makePageLinks($this->requestUrl,$total,$perPage);

        $data=array(
            'Page'=>$page,
            'PerPage'=>$perPage,
            'Total'=>$total,
            'List'=>$list,
            'Links'=>$links,
            'FilterUrl'=>$this->requestUrl,
            'OtherBtns'=>array(),
        );
        $data = array_merge($data,$filter);

        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/api/index',
        );
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /**
     *  全部
     */
    public function all()
    {
        $list=$this->logicModel->getAll();

        $data=array(
            'List'=>$list,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array();
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /**
     *  新增
     */
    public function add()
    {
        // 添加页
        if('get' == $this->input->method()){

            $data=array();
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='';
            $tpls=array(
                'admin/api/add',
            );
            $this->_response($data,$code,$msg,$url,$tpls);
        }
        // 保存
        else{
            $newRow=$this->logicModel->add($this->inputData);

            $data=array(
                'List'=>$newRow,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='/admin/api';
            $tpls=array();
            $this->_response($data,$code,$msg,$url,$tpls);
        }
    }

    /** 信息
     * @throws Exception
     */
    public function info()
    {
        if(empty($this->inputData['Id'])){
            throw new Exception('请选择数据',EXIT_USER_INPUT);
        }
        $id=(int)$this->inputData['Id'];
        $row=$this->logicModel->getRowById($id);
        if(empty($row['Id'])){
            throw new Exception('数据不存在',EXIT_DATABASE);
        }

        $data=array(
            'Id'=>$id,
            'List'=>$row,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/api/info',
        );
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /**  修改
     * @throws Exception
     */
    public function edit()
    {
        // 修改页
        if('get' == $this->input->method()){

            if(empty($this->inputData['Id'])){
                throw new Exception('请选择数据',EXIT_USER_INPUT);
            }
            $id=(int)$this->inputData['Id'];
            $row=$this->logicModel->getRowById($id);
            if(empty($row['Id'])){
                throw new Exception('数据不存在',EXIT_DATABASE);
            }

            $data=array(
                'Id'=>$id,
                'List'=>$row,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='';
            $tpls=array(
                'admin/api/edit',
            );
            $this->_response($data,$code,$msg,$url,$tpls);
        }
        // 保存
        else{
            $updated=$this->logicModel->edit($this->inputData);

            $data=array(
                'List'=>$updated,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='/admin/api';
            $tpls=array();
            $this->_response($data,$code,$msg,$url,$tpls);
        }
    }
}