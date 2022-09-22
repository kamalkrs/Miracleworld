<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use stdClass;

class Plans extends AdminController
{

    function __construct()
    {
        parent::__construct();
        helper(['origin']);
        $session = session();
        if (!$session->has('userid')) {
            $session->setFlashdata('error', 'You must login to view page');
            return redirect()->to(admin_url('users/login'));
        }
        $this->data['menu'] = 'plans';
        helper(['form']);
    }

    function index()
    {
        $db = db_connect();
        $this->data['main'] = "plans/index";
        $this->data['menu'] = 'plans';

        $builder = $db->table('plans');
        $this->data['pack'] = $builder->orderBy('id', 'ASC')->get()->getResult();
        return view(admin_view('default'), $this->data);
    }

    public function add($id = false)
    {
        $this->data['main'] = 'plans/add';
        $db = db_connect();
        $builder = $db->table('plans');
        if ($id) {
            $m = $builder->getWhere(['id' => $id])->getRow();
        } else {
            $fields = $db->getFieldNames('plans');
            $m = new stdClass();
            foreach ($fields as $key) {
                $m->$key = null;
            }
        }
        $this->data['m'] = $m;
        if ($this->request->getPost('submit')) {
            $rules = [
                'frm.plan_title' => [
                    'label' => 'Plan title',
                    'rules' => 'required'
                ],
                'frm.amount' => [
                    'label' => 'Amount',
                    'rules' => 'required'
                ]
            ];

            if ($this->validate($rules)) {
                $sv = $this->request->getPost('frm');

                if ($id) {
                    $sv['updated'] = date("Y-m-d H:i:s");
                    $builder->update($sv, ['id' => $id]);
                } else {
                    $sv['created'] = date("Y-m-d H:i:s");
                    $sv['updated'] = date("Y-m-d H:i:s");
                    $builder->insert($sv);
                }
                session()->setFlashdata('success', 'Plan saved successfullly');
                return redirect()->to(admin_url('plans'));
            } else {
                return view(admin_view('default'), $this->data);
            }
        } else {
            return view(admin_view('default'), $this->data);
        }
    }

    function delete($id = false)
    {
        if ($id) {
            $builder = $this->db->table('plans');
            $builder->delete(['id' => $id]);
            session()->setFlashdata('success', 'Plan deleted successfully');
        }
        return redirect()->to(admin_url('plans'));
    }

    function deactivate($id)
    {
        $url = $this->request->getGet('redirect_to');
        if ($id) {
            $builder = $this->db->table('plans');
            $builder->update(['status' => 0], ['id' => $id]);
            session()->setFlashdata('success', 'Plan deactivated');
        }
        return redirect()->to($url);
    }

    function activate($id)
    {
        $url = $this->request->getGet('redirect_to');
        if ($id) {
            $builder = $this->db->table('plans');
            $builder->update(['status' => 1], ['id' => $id,]);
            session()->setFlashdata('success', 'Plan activated');
        }
        return redirect()->to($url);
    }
}
