<?php
/**
 *  访问控制
 * @user 罗仕辉
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
            MasterLogic::instance()->checkLogin($this->inputData);
        }else{
            if(time()>$_SESSION['Master']['Timeout']){
                unset($_SESSION['Master']);
                throw new \Exception('等待超时，请重新登录！',EXIT_ERROR);
            }
            $_SESSION['Master']['Timeout']=time()+OPERAT_WAIT_TIME;
        }
        $this->master=$_SESSION['Master'];
        unset($_SESSION['redirect']);
        // 验证菜单
        $this->_getMenu();
        if(empty($this->menu['Id'])){
            throw new \Exception('无法访问',EXIT_ERROR);
        }
        if(STATE_OFF == $this->menu['State']){
            throw new \Exception('功能已禁用',EXIT_ERROR);
        }
        if(ADMIN_NO == $this->master['IsAdmin']
            && STATE_ON == $this->menu['Ctrl']
            && !in_array($this->menu['Id'],$this->master['MenuIds'])){

            throw new \Exception('未授权',EXIT_ERROR);
        }
    }
}