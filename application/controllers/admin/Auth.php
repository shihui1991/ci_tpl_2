<?php
/**
 *  访问控制
 * @author 罗仕辉
 * @create 2018-09-08
 */

require_once APPPATH.'controllers/admin/Init.php';

use models\logic\MasterLogic;

class Auth extends Init
{
    protected $master;


    /**
     * Auth constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        // 验证登录
        $_SESSION['redirect']='/admin';
        if(empty($_SESSION['Master'])){
            if(empty($this->inputData['Token']) || empty($this->inputData['Id'])){
                throw new \Exception('请登录！',EXIT_USER_INPUT);
            }
            MasterLogic::instance()->checkLogin($this->inputData);
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