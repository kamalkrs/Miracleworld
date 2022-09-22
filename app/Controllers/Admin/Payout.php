<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\Dashboard_model;
use App\Models\User_model;
use stdClass;

class Payout extends AdminController
{

    public $db;
    public function __construct()
    {
        parent::__construct();
        $this->data['main'] = 'payouts/tables';
        $this->data['menu'] = "payout";

        $this->db = db_connect();
    }

    function index()
    {
        $builder = $this->db->table("payout");
        $this->data['main'] = 'payouts/payout-reports';
        $this->data['list'] = $builder->orderBy('id', 'DESC')->get()->getResult();
        return view(admin_view('default'), $this->data);
    }

    function generate()
    {
        $builder = $this->db->table("transaction");
        $list  = $builder->select('distinct(user_id)')->get()->getResult();
        $users = array();
        $um = new User_model();
        foreach ($list as $ob) {
            $dbuser = $this->db->table("users");
            $user         = $dbuser->getWhere(array("id" => $ob->user_id))->getRow();
            $user->amount = $um->currentIncome($ob->user_id);
            $users[]      = $user;
        }
        $this->data['users'] = $users;
        $this->data['type'] = 'Payout';
        return view(admin_view('default'), $this->data);
    }

    function listview($id)
    {
        $builder = $this->db->table('payout');
        $row = $builder->getWhere(['id' => $id])->getRow();
        $this->data['main'] = 'payouts/listview';
        $this->data['payout'] = json_decode($row->details);
        return view(admin_view("default"), $this->data);
    }


    function payout_paid($pay_id, $user_id, $mode = "Bank")
    {
        $builder = $this->db->table("payout");
        $ob = $builder->getWhere(array('id' => $pay_id))->getRow();
        $details = json_decode($ob->details);
        $ars = array();
        foreach ($details as $ob) {
            if ($ob->id == $user_id) {
                $ob->is_paid = 1;
                $ob->note = $mode;

                $um = new User_model($ob->id);
                $um->debit($ob->total, Dashboard_model::PAYOUT, 'Payout by Admin');
            }
            $ars[] = $ob;
        }
        $builder = $this->db->table("payout");
        $builder->update(array('details' => json_encode($ars)), array('id' => $pay_id));
        session()->setFlashdata('success', 'Payment has been marked as paid');
        return redirect()->to(admin_url('payout/listview/' . $pay_id));
    }

    function payout_unpaid($id)
    {
        $builder = $this->db->table("payout");
        $ob = $builder->getWhere(array('id' => $id))->getRow();
        $details = json_decode($ob->details);
        $data = array();
        foreach ($details as $ob) {
            $ob->is_paid = 0;
            $data[] = $ob;
        }
        $builder->update(array('details' => json_encode($data)), array('id' => $id));
    }

    function withdrawal()
    {
        $builder = $this->db->table("withdraw");
        $this->data['main'] = 'payouts/withdrawal';
        if (isset($_GET['today']) && $_GET['today'] == 1) {
            $builder->where("DATE(created) = CURDATE()");
        } else if (isset($_GET['new'])) {
            $builder->where('status', 0);
        } else {
            $builder->where('status', 1);
        }
        $this->data['paylist'] = $builder->orderBy('id', 'DESC')->get()->getResult();
        return view('default', $this->data);
    }

    function withdrawal_rejected($id)
    {
        $dashboard = new Dashboard_model();
        $builder = $this->db->table("withdraw");
        $order = $builder->getWhere(['id' => $id])->getRow();
        if ($order->status == 0) {
            $sb  = [];
            $sb['status'] = "2";
            $sb['updated'] = date("Y-m-d H:i:s");
            $sb['comments'] = "Cancelled by Admin";
            $builder->update($sb, ['id' => $id]);

            $dashboard->update_withdraw_request($id, Dashboard_model::WITHDRAW_DECLINED);
            session()->setFlashdata('success', 'Withdrawal request rejected');
        } else {
            session()->setFlashdata('error', 'Opps!! Some error.');
        }
        return redirect()->to(admin_url('payout/withdrawal'));
    }

    function confirm_withdraw()
    {
        $builder = $this->db->table("withdraw");
        if ($this->request->getPost('order_id')) {
            $id = $this->request->getPost('order_id');
            $order = $builder->getWhere(['id' => $id])->getRow();
            if ($order->status == 0) {
                $sb = $this->request->getPost("form");
                $sb['updated'] = date("Y-m-d H:i:s");
                $sb['status'] = 1;
                $sb['comments'] = "Payment Confirmed";
                $sb['pay_method'] = "manual";
                $builder->update($sb, ['id' => $id]);
                session()->setFlashdata("success", "Withdrawal request confirmed");
            } else {
                session()->setFlashdata("error", "Opps!! Some error.");
            }
        }
        return redirect()->to(admin_url('payout/withdrawal'));
    }

    function manage()
    {
        $this->data['main'] = 'payouts/manage';
        $this->data['menu'] = "fund";
        return view('default', $this->data);
    }
    function payout_report()
    {
        $this->data['main'] = 'payouts/payout-report';
        return view('default', $this->data);
    }

    function drcr_report()
    {
        $this->data['main'] = 'payouts/drcr-report';
        $this->data['menu'] = "fund";
        $this->data['list'] = $this->db->table('transaction')->getWhere(['notes' => Dashboard_model::ADMIN_REPORT])->getResult();
        return view('default', $this->data);
    }

    function details($id)
    {
        $this->data['main'] = 'payouts/details';
        $builder = $this->db->table("withdraw");
        $this->data['order'] = $builder->getWhere(['id' => $id])->getRow();
        return view(admin_view('default'), $this->data);
    }

    function generatenow()
    {
        $builder = $this->db->table("transaction");
        $list  = $builder->select('distinct(user_id)')->where(['cr_dr' => 'cr'])->get()->getResult();
        $data = array();
        $um = new User_model();
        foreach ($list as $ob) {
            $amount = $um->currentIncome($ob->user_id);
            $payout = $amount - ($amount * 0.10);

            if ($amount >= 1) {
                $user = new stdClass();
                $user->id = $ob->user_id;
                $user->total = $amount;
                $user->paid  = $payout;
                $user->is_paid = 0;
                $data[] = $user;

                // save to transaction as debit account
                $usm = new User_model($ob->user_id);
                $usm->debit($amount, Dashboard_model::PAYOUT, 'Admin Payout', 0);
            }
        }
        $sum = 0;
        foreach ($data as $d) {
            $paid = $d->paid;
            $sum += $paid;
        }

        $builder = $this->db->table("payout");

        $save = array();
        $save['details'] = json_encode($data);
        $save['created'] = date("Y-m-d H:i");
        $save['paytype'] = 'Payout';
        $save['status']  = 0;
        $builder->insert($save);

        session()->setFlashdata("success", ' Payout generated successfully.');
        return redirect()->to(admin_url('payout'));
    }

    function withdrawal_update($id)
    {
        $d = new Dashboard_model();
        if (isset($_GET['status'])) {
            $d->update_withdraw_request($id, $_GET['status']);
            session()->setFlashdata('success', "Withdrawal request has been updated");
        }
        return redirect()->to(admin_url('payout/withdrawal'));
    }
}
