<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;

class Dashboard extends AdminController
{
    function __construct()
    {
        parent::__construct();
        $this->data['menu'] = 'dashboard';
        helper('origin');
    }


    public function index()
    {
        $this->data['main'] = 'dashboard/index';
        $this->data['users'] = 0;
        $this->data['today_income'] = 0;
        $this->data['today_expense'] = 0;
        return view('default', $this->data);
    }
}
