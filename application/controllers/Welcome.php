<?php
require_once APPPATH.'controllers/Base.php';

class Welcome extends Base
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        echo 'welcome';
    }
}