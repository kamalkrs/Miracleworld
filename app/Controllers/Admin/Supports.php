<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\Master_model;

class Supports extends AdminController
{

    function __construct()
    {
        parent::__construct();
        $this->data['menu'] = "supports";
    }

    function index()
    {
        $master = new Master_model();
        $this->data['main'] = "supports/index";
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $this->perPage;
        $result = $master->getAll($this->perPage, $offset, "supports");
        $this->data['datalist'] = $result['results'];
        $this->initPagination($result['total']);
        return view(admin_view("default"), $this->data);
    }

    function reply($id)
    {
        $this->data['main'] = "supports/send-reply";
        $this->data['m'] = $this->Master_model->getRow($id, "feedback");
        $this->load->view("default", $this->data);
    }

    function feedback()
    {
        $this->data['main'] = "supports/feedback";
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $this->per_page;
        if (isset($_GET['status'])) {
            $rule = array();
            $rule['status'] = $_GET['status'];
            $result = $this->Master_model->getWhereRecords($this->per_page, $offset, $rule, "feedback");
        } elseif (isset($_GET['q'])) {
            $q = $_GET['q'];
            $rules = array(
                'users.id' => $q,
                'users.email_id' => $q,
                'users.first_name' => $q
            );
            $result = $this->Master_model->getAllSearched($this->per_page, $offset, $rules, "feedback");
        } else {
            $result = $this->Master_model->getAll($this->per_page, $offset, "feedback");
        }
        $this->data['sl'] = $offset + 1;
        $this->data['datalist'] = $result['results'];
        $this->initPagination($result['total']);
        $this->load->view("default", $this->data);
    }

    function views($id)
    {
        $master = new Master_model();
        $this->data['main'] = "supports/view";
        $this->data['ticket'] = $t = $master->getRow($id, 'supports');
        if ($this->request->getPost("description")) {
            $s = array();
            $s['support_id'] = $id;
            $s['from_id'] = 0;
            $s['to_id'] = $t->user_id;
            $s['description'] = $this->request->getPost("description");
            $s['created'] = date("Y-m-d H:i");
            $builder = $this->db->table("supports_view");
            $builder->insert($s);

            $builder = $this->db->table("supports");
            $builder->update(['status' => 1, 'updated' => date('Y-m-d H:i:s')], ["id" => $id]);
            session()->setFlashdata('success', 'Reply updated');
            return redirect()->to(admin_url("supports/views/" . $id));
        }
        $builder = $this->db->table("supports_view");
        $this->data['views'] = $builder->orderBy('id', 'DESC')->getWhere(['support_id' => $id])->getResult();
        return view(admin_view("default"), $this->data);
    }

    function json()
    {
        $this->db->order_by("id", "DESC");
        $rest = $this->db->get("feedback")->result();
        header('Content-Type: application/json');
        $ob = new stdClass();
        $newarr = array();
        if (is_array($rest) && count($rest) > 0) {
            $sl = 1;
            foreach ($rest as $r) {
                $a = array($sl++, $r->name, $r->email, $r->subject, $r->description);
                $newarr[] = $a;
            }
        }
        $ob->data = $newarr;
        echo json_encode($ob, JSON_PRETTY_PRINT);
    }
}
