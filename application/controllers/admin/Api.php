<?php
/**
 *  Api
 * @user 罗仕辉
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
        $params=array();
        $orderBy=array();
        $args=array();
        // 查询参数
        // 名称
        $name='';
        if(!empty($this->inputData['Name'])){
            $name=(string)$this->inputData['Name'];
            $params[]=array('Name','like',$name);
        }
        $args['Name']=$name;
        // Url
        $url='';
        if(!empty($this->inputData['Url'])){
            $url=(string)$this->inputData['Url'];
            $params[]=array('Url','like',$url);
        }
        $args['Url']=$url;
        // 状态
        $state= -1;
        if(isset($this->inputData['State'])
            && is_numeric($this->inputData['State'])
            && in_array($this->inputData['State'],array(STATE_OFF,STATE_ON))){

            $state=(int)$this->inputData['State'];
            $params[]=array('State','eq',$state);
        }
        $args['State']=$state;

        // 排序方式
        $by=ORDER_BY_ASC;
        if(!empty($this->inputData['By']) && in_array($this->inputData['By'],array(ORDER_BY_ASC,ORDER_BY_DESC))){
            $by=(string)$this->inputData['By'];
        }
        $args['By']=$by;
        // 排序字段
        $order='';
        if(!empty($this->inputData['Order'])){
            $order=(string)$this->inputData['Order'];
            $orderBy[$order]=$by;
        }
        $args['Order']=$order;

        // 查询条数
        $total=$this->logicModel->getTotoal($params);
        // 获取列表
        $list=$this->logicModel->getListByPage($page, $perPage,$params,$orderBy);
        // 生成分页条
        $baseUrl='/admin/api';
        $links=$this->_makePageLinks($baseUrl,$total,$perPage);

        $data=array(
            'Page'=>$page,
            'PerPage'=>$perPage,
            'Total'=>$total,
            'List'=>$list,
            'Links'=>$links,
        );
        $data=array_merge($args,$data);

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