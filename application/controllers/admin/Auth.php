<?php
/**
 *  访问控制
 * @author 罗仕辉
 * @create 2018-09-08
 */

require_once APPPATH.'controllers/admin/Init.php';


class Auth extends Init
{

    /**
     * Auth constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->_checkLogin();
    }
}