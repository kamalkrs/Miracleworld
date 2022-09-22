<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AdminController extends BaseController
{

    public $perPage;
    public $db;
    function __construct()
    {
        helper('origin');
        $this->perPage = 50;
        $this->db = db_connect();
        $session = session();
        if (!$session->get('userid')) {
            header("Location: " . admin_url('users/login'));
            exit();
        }
    }

    function initPagination()
    {
    }
}
