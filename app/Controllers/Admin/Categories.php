<?php

namespace app\Controllers\Admin;

use App\Controllers\AdminController;
use app\Models\Category_model;
use stdClass;

class Categories extends AdminController
{

    function __construct()
    {
        parent::__construct();
        $this->data['menu'] = "catalog";
        model('Category_model');
    }

    function index($page = 1)
    {
        $cat_m = new Category_model();
        $this->data['dashboard_title'] = "Manage Category";
        $this->data['main'] = 'category/index';

        $builder = $this->db->table("categories");
        $this->data['categories'] = $builder->get()->getResult();
        return view(admin_view('default'), $this->data);
    }

    function add($id = false)
    {
        $this->data['main'] = 'category/add';
        $db = db_connect();
        $builder = $db->table('categories');
        if ($id) {
            $m = $builder->getWhere(['id' => $id])->getRow();
        } else {
            $fields = $db->getFieldNames('categories');
            $m = new stdClass();
            foreach ($fields as $key) {
                $m->$key = null;
            }
        }
        $this->data['cat'] = $m;
        $ctob = new Category_model();
        $this->data['categories'] = $ctob->category_dropdown();

        $rules = [
            'cat.name' => [
                'label' => 'Name',
                'rules' => 'required'
            ]
        ];

        if ($this->request->getPost('button')) {
            if ($this->validate($rules)) {
                $catdata = $this->request->getPost('cat');
                $catdata['id'] = $id;
                $catdata['sequence'] = 0;
                $builder = $this->db->table('categories');
                if ($id) {
                    $builder->update($catdata, ['id' => $id]);
                } else {
                    $builder->insert($catdata);
                }

                session()->setFlashdata('success', "Category details saved");
                return redirect()->to(admin_url('categories'));
            }
        }
        return view(admin_view('default'), $this->data);
    }

    function activate($id = false)
    {
        $builder = $this->db->table("categories");
        $redirect = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : admin_url('categories');
        if ($id) {
            $builder->update(['status' => 1], ['id' => $id]);
            session()->setFlashdata('success', 'Category enabled');
        }
        return redirect()->to($redirect);
    }

    function deactivate($id = false)
    {
        $builder = $this->db->table("categories");
        $redirect = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : admin_url('categories');
        if ($id) {
            $builder->update(['status' => 0], ['id' => $id]);
            session()->setFlashdata('success', 'Category disabled');
        }
        return redirect()->to($redirect);
    }

    public function delete($id)
    {
        $builder = $this->db->table("categories");
        if ($id > 0) {
            $builder->delete(['id' => $id]);
            session()->setFlashdata('success', 'Category deleted');
        }
        return redirect()->to(admin_url('categories'));
    }
}
