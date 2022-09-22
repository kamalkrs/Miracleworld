<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\Dashboard_model;
use App\Models\User_model;

class Payments extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['active_tabs'] = 'pin';
        $this->data['menu'] = "fund";
    }

    function index()
    {
        $builder  = $this->db->table("fund_request");
        $this->data['main'] = 'payments/fund-request';
        if (isset($_GET['type'])) {
            $builder->where('date(created) = CURDATE()');
        }
        $builder->orderBy("id", "DESC");
        $this->data['request'] = $builder->get()->getResult();
        return view(admin_view('default'), $this->data);
    }

    function fund_transfer()
    {
        $this->data['main'] = "payments/fund-transfer";
        $builder = $this->db->table("users");
        $this->data['users'] = $builder->orderBy("first_name", "ASC")->get()->getResult();
        $rules = [
            'amount' => [
                'label' => 'Amount',
                'rules' => 'required'
            ],
            'user_id' => [
                'label' => 'User',
                'rules' => 'required'
            ]
        ];
        if ($this->request->getPost('submit')) {
            if ($this->validate($rules)) {
                print_r($_POST);
                $to = $this->request->getPost("transferto");
                $save = array();
                $save['user_id'] = $_POST['user_id'];
                $save['amount']  = $_POST['amount'];
                $save['notes']   = "Admin Transfer";
                $save['cr_dr'] = "cr";
                $save['created'] = date("Y-m-d H:i");
                $save['ref_id'] = time();
                $save['comments'] = "Transfered by admin";

                $builder = $this->db->table("transaction");
                $builder->insert($save);
                session()->setFlashdata('success', 'Fund Transfer Completed');
                return redirect()->to(admin_url('dashboard'));
            } else {
                return view(admin_view('default'), $this->data);
            }
        } else {
            return view(admin_view('default'), $this->data);
        }
    }

    function transfer_report()
    {
        $this->data['main'] = 'payments/transfer-report';
        $builder = $this->db->table("transaction");
        $builder->whereIn('comments', ['Transfered by admin', 'Deducted by Admin', 'Credited by Admin']);
        $this->data['list'] = $builder->orderBy('id', 'DESC')->get()->getResult();
        return view(admin_view('default'), $this->data);
    }

    function income($type = '')
    {
        $this->data['menu'] = 'income';
        if (in_array($type, [Dashboard_model::INCOME_SPONSOR, Dashboard_model::INCOME_ROI, Dashboard_model::INCOME_REWARD, Dashboard_model::INCOME_MATCHING])) {
            $this->data['main'] = 'payments/income-report';
            $builder = $this->db->table("transaction");
            $result = $builder->orderBy('id', 'DESC')->getWhere(['notes' => $type])->getResult();
            $this->data['items'] = $result;
            $this->data['title'] = ucfirst($type) . ' Income Report';
            return view(admin_view('default'), $this->data);
        } else {
            return redirect()->to(admin_url());
        }
    }

    function decline($id = false)
    {
        $builder = $this->db->table('fund_request');
        if ($id) {
            $c['id'] = $id;
            $c['status'] = 2;
            $builder->update($c, ['id' => $id]);
            session()->setFlashdata("success", "Fund Request declined successfully");
        }
        return redirect()->to(admin_url('payments'));
    }

    function approved($id = false)
    {
        $builder = $this->db->table('fund_request');
        if ($id) {
            $pin = $builder->getWhere(['id' => $id])->getRow();
            $us = new User_model($pin->user_id);
            $us->credit($pin->amount, Dashboard_model::INCOME_FUND_TRANSER, "Fund Request", $id);

            session()->setFlashdata("success", "Fund request completed successfully");
            $builder = $this->db->table('fund_request');
            $c['id'] = $id;
            $c['status'] = 1;
            $builder->update($c, ['id' => $id]);
        }
        return redirect()->to(admin_url('payments'));
    }

    function payment_history()
    {
        $this->data['main'] = 'payments/payment-history';
        $this->data['title'] = 'Deposit History';
        $builder = $this->db->table("payorder");
        $builder->select("payorder.*");
        $builder->join("users", "users.id = payorder.user_id");

        if (isset($_GET['today']) && $_GET['today'] == 1) {
            $builder->where("DATE(created) = CURDATE()");
        } else if (isset($_GET['status'])) {
            $builder->where("payorder.order_status", 0);
            $this->data['title'] = 'Deposit Pending';
        } else {
            $builder->where("payorder.order_status", 1);
        }
        $builder->orderBy('payorder.id', 'DESC');
        $this->data['items'] = $builder->get()->getResult();
        return view('default', $this->data);
    }

    function markreceived($id)
    {
        $db = db_connect();
        $order = $db->table("payorder")->getWhere(['id' => $id])->getRow();
        if (is_object($order) && $order->order_status == 0) {

            $sb = [];
            $sb['order_status'] = 1;
            $sb['updated'] = date("Y-m-d H:i:s");
            $sb['pay_error'] = 'admin confirmed';
            $db->table("payorder")->update($sb, ['id' => $id]);

            $us = new User_model($order->user_id);
            $us->credit($order->amount, Dashboard_model::INCOME_FUND_CREDIT, 'Add Fund', $order->id);

            session()->setFlashdata('success', 'Deposit has been confirmed !!');
        }
        return redirect()->to(admin_url('payments/payment-history/?status=0'));
    }
}
