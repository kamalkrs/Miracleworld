<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\Dashboard_model;
use App\Models\Setting_model;
use App\Models\User_model;

class Members extends AdminController
{

    var $db;

    public function __construct()
    {
        parent::__construct();
        $this->data['menu'] = "members";
        $this->db = db_connect();
    }

    public function index()
    {

        $this->data['main'] = 'members/index';
        $this->data['title'] = 'All Members';
        $db = db_connect();
        $users = $db->table('users');
        if (isset($_GET['payout'])) {
            $users->where(['payout' => $_GET['payout']]);
        }
        if (isset($_GET['status'])) {
            $users->where(['status' => $_GET['status']]);
        }
        if (isset($_GET['filter'])) {
            $users->where("DATE(join_date) = CURDATE()");
        }

        $this->data['members'] = $users->orderBy("slno", "DESC")->get()->getResult();
        return view(admin_view('default'), $this->data);
    }


    function kyc()
    {
        $this->data['main'] = 'members/kyc';
        $this->data['doc'] = $this->db->table("users")->getWhere(['kyc_updated' => 1, 'kyc_status' => 0])->getResult();
        return view(admin_view('default'), $this->data);
    }

    function edit_image($id = false)
    {
        $this->data['main'] = admin_view('members/image');
        $config['upload_path']      = upload_dir();
        $config['allowed_types']    = 'gif|jpg|png|jpeg|bmp';
        $config['max_size']         = '5000';
        $config['max_width']        = '3000';
        $config['max_height']       = '2000';
        $this->load->library('upload', $config);
        $this->data['title'] = "upload file";

        $this->data['doc'] = $this->db->get_where('users', array('id' => $id))->row();

        $i = $j = $k = $l = false;
        $s['pan_no'] = $this->input->post('pan_no');
        $kyc = $this->db->get_where('users', array('id' => $id))->row();
        $uploaded = $this->upload->do_upload('image');
        if ($uploaded) {
            $image     = $this->upload->data();
            $s['image'] = $image['file_name'];
            $this->session->set_flashdata("success", "Photo uploaded successfully.Please Upload Other remain document.");
            $this->db->where('id', $id);
            $this->db->update('users', $s);
            $i = true;
        }

        $uploaded = $this->upload->do_upload('pan');

        if ($uploaded) {
            $image    = $this->upload->data();
            $s['pan']   = $image['file_name'];
            $this->session->set_flashdata("success", "Photo uploaded successfully.Please Upload Other remain document.");
            $this->db->where('id', $id);
            $this->db->update('users', $s);
        }

        $uploaded = $this->upload->do_upload('aadharf');

        if ($uploaded) {

            $image    = $this->upload->data();
            $s['aadharf']   = $image['file_name'];
            $this->session->set_flashdata("success", "Photo uploaded successfully.Please Upload Other remain document.");
            $this->db->where('id', $id);
            $this->db->update('users', $s);
            $j = true;
        }
        $uploaded = $this->upload->do_upload('aadharb');

        if ($uploaded) {

            $image    = $this->upload->data();
            $s['aadharb']   = $image['file_name'];
            $this->session->set_flashdata("success", "Photo uploaded successfully.Please Upload Other remain document.");
            $this->db->where('id', $id);
            $this->db->update('users', $s);
            $k = true;
        }

        $uploaded = $this->upload->do_upload('account');

        if ($uploaded) {

            $image    = $this->upload->data();
            $s['passbook']   = $image['file_name'];
            $this->session->set_flashdata("success", "Photo uploaded successfully.Please Upload Other remain document.");
            $this->db->where('id', $id);
            $this->db->update('users', $s);
            $l = true;
        }


        if ($this->input->post('btn_reg')) {

            $s['kyc_status'] = $this->input->post('kyc_status');
            $this->db->where('id', $id);
            $this->db->update('users', $s);

            if ($s['kyc_status'] == 0) {
                $this->session->set_flashdata("error", "Kyc Disapproved");
            } else {
                $this->session->set_flashdata("success", "Kyc Approved.");
            }
            redirect(admin_url('members/edit_image/' . $id));
        }

        if ($this->input->post('bankd')) {
            $bank           = $this->input->post("bank");
            $this->db->update('users', $bank, array("id" => $id));
        }


        if ($kyc->image != '' && $kyc->pan != '' && $kyc->aadharf != '' && $kyc->aadharb != '' && $kyc->passbook != '') {
            $s['kyc_status'] = 1;
            $this->db->update('users', $s, array("id" => $id));
        }

        $this->load->view(admin_view('default'), $this->data);
    }

    public function edit($id)
    {
        $session = session();
        $this->data['main'] = 'members/edit';
        $this->data['m']    = $this->db->table('users')->getWhere(['id' => $id])->getRow();

        if ($this->request->getPost('submit')) {
            $user = $this->request->getPost('frm');
            $builder = $this->db->table('users');
            $builder->update($user, ['id' => $id]);
            $session->setFlashdata('success', 'Details updated Successfully');
            redirect()->to(admin_url('members/edit/' . $id));
        }

        return view(admin_view('default'), $this->data);
    }

    public function delete($id)
    {
        $builder = $this->db->table("users");
        $builder->delete(['id' => $id]);
        session()->setFlashdata('success', 'Account deleted Successfully');
        return redirect()->to(admin_url('members'));
    }

    function wallets()
    {
        $this->data['main'] = 'members/wallets';
        $this->data['menu'] = 'wallets';
        $builder = $this->db->table("users");
        $users = $builder->get()->getResult();
        $user_m = new User_model();
        $items = [];
        foreach ($users as $user) {
            $bal = $user_m->getFundBalance($user->id);
            $user->balance = $bal;
            $user->update = date("Y-m-d H:i:s");
            $items[] = $user;
        }
        $this->data['users'] = $items;
        return view(admin_view('default'), $this->data);
    }

    public function details($id)
    {
        $builder = $this->db->table("users");
        $this->data['main']     = 'members/details';
        $this->data['user']     = $builder->getWhere(['id' => $id])->getRow();
        $this->data['members'] = $builder->getWhere(['spil_id' => $id])->getResult();
        $this->data['current_income'] = 0;
        $this->data['total_income'] = 0;
        $this->data['downline'] = 0;
        return view(admin_view('default'), $this->data);
    }

    function kyc_pending()
    {
        $builder = $this->db->table("users");
        $this->data['main'] = 'members/kyc';
        $this->data['title'] = "Pending";
        $this->data['menu'] = 'kyc';
        $this->data['users'] = $builder->getWhere(['kyc_status' => 0])->getResult();
        return view(admin_view('default'), $this->data);
    }
    function kyc_verified()
    {
        $builder = $this->db->table("users");
        $this->data['main'] = 'members/kyc';
        $this->data['title'] = "Verified";
        $this->data['menu'] = 'kyc';
        $this->data['users'] = $builder->getWhere(['kyc_status' => 1])->getResult();
        return view(admin_view('default'), $this->data);
    }
    function kyc_rejected()
    {
        $builder = $this->db->table("users");
        $this->data['main'] = 'members/kyc';
        $this->data['title'] = "Rejected";
        $this->data['menu'] = 'kyc';
        $this->data['users'] = $builder->getWhere(['kyc_status' => 2])->getResult();
        return view(admin_view('default'), $this->data);
    }

    function wallet_options($user_id)
    {
        $this->data['main'] = 'members/wallet-options';
        $this->data['user_id'] = $user_id;
        $sm = new User_model();
        $this->data['bm'] = $sm->getUserdata($user_id, 'binary_matching');
        $this->data['rb'] = $sm->getUserdata($user_id, 'roi_bonus');
        $this->data['dw'] = $sm->getUserdata($user_id, 'disable_withdraw');
        $this->data['dt'] = $sm->getUserdata($user_id, 'disable_transfer');

        return view(admin_view('default'), $this->data);
    }

    function update_wallet_options()
    {
        $action = $this->request->getPost('action');
        $user_id = $this->request->getPost('user_id');
        $user = new User_model();
        switch ($action) {
            case 'add-fund':
                if ($this->request->getPost('amount')) {

                    $balance = $user->getFundBalance($user_id);
                    // Credit to other's wallet
                    $sm = array();
                    $sm['user_id'] = $user_id;
                    $sm['cr_dr'] = 'cr';
                    $sm['amount'] = $this->request->getPost('amount');
                    $sm['notes'] = Dashboard_model::INCOME_FUND_TRANSER;
                    $sm['created'] = date("Y-m-d H:i:s");
                    $sm['ref_id'] = time();
                    $sm['comments'] = "Credited by Admin";
                    $sm['total_bal'] = $balance + $sm['amount'];
                    $builder = $this->db->table('transaction');
                    $builder->insert($sm);

                    session()->setFlashdata('success', 'Amount credited to account');
                }
                break;
            case 'deduct-fund':
                if ($this->request->getPost('amount')) {
                    $balance = $user->getFundBalance($user_id);
                    // Credit to other's wallet
                    $sm = array();
                    $sm['user_id'] = $user_id;
                    $sm['cr_dr'] = 'dr';
                    $sm['amount'] = $this->request->getPost('amount');
                    $sm['notes'] = Dashboard_model::INCOME_FUND_TRANSER;
                    $sm['created'] = date("Y-m-d H:i:s");
                    $sm['ref_id'] = time();
                    $sm['comments'] = "Deducted by Admin";
                    $sm['total_bal'] = $balance - $sm['amount'];
                    $builder = $this->db->table('transaction');
                    $builder->insert($sm);

                    session()->setFlashdata('success', 'Amount debited from account');
                }
                break;
            case 'disable-bonus':
                $bm = $this->request->getPost('binary_matching') ? 1 : 0;
                $rb = $this->request->getPost('roi_bonus') ? 1 : 0;
                $sm = new User_model();
                $sm->saveUserdata($user_id, 'binary_matching', $bm);
                $sm->saveUserdata($user_id, 'roi_bonus', $rb);
                break;
            case 'disable-wallet':
                $bm = $this->request->getPost('disable_withdraw') ? 1 : 0;
                $rb = $this->request->getPost('disable_transfer') ? 1 : 0;
                $sm = new User_model();
                $sm->saveUserdata($user_id, 'disable_withdraw', $bm);
                $sm->saveUserdata($user_id, 'disable_transfer', $rb);
                break;
            default:
        }

        return redirect()->to(admin_url('members/wallet-options/' . $user_id));
    }

    function kyc_edit($user_id)
    {
        $builder = $this->db->table("users");
        $this->data['main'] = 'members/kyc-edit';
        $this->data['user'] = $builder->getWhere(['id' => $user_id])->getRow();

        print_r($_POST);
        if ($this->request->getPost('button')) {
            $idFile = $this->request->getFile('id_proof');
            $uploaded = false;
            if ($idFile->isValid() && !$idFile->hasMoved()) {

                $id_file_name = $idFile->getRandomName();
                $idFile->move(upload_dir(), $id_file_name);
                $sb = [];
                $sb['id_proof'] = $id_file_name;
                $builder->update($sb, ['id' => $user_id]);

                $uploaded = true;
            }

            $addFile = $this->request->getFile('address_proof');
            if ($addFile->isValid() && !$addFile->hasMoved()) {
                $add_file_name = $addFile->getRandomName();
                $addFile->move(upload_dir(), $add_file_name);

                $sb = [];
                $sb['address_proof'] = $add_file_name;
                $builder->update($sb, ['id' => $user_id]);

                $uploaded = true;
            }

            if ($uploaded) {
                session()->setFlashdata('success', 'KYC File uploaded');
            }
            return redirect()->to(admin_url('members/kyc-edit/' . $user_id));
        }

        return view(admin_view('default'), $this->data);
    }

    function change_status($id, $status)
    {
        $this->db->table("users")->update(['status' => $status], ['id' => $id]);
        session()->setFlashdata('success', 'Account Status Changed');
        return redirect()->to(admin_url('members'));
    }

    function change_payout($id, $value)
    {
        $this->db->table("users")->update(['payout' => $value], ['id' => $id]);
        session()->setFlashdata('success', 'Withdrawal Status Changed');
        return redirect()->to(admin_url('members'));
    }
}
