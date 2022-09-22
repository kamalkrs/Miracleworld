<?php

namespace App\Controllers;

use App\Models\Dashboard_model;
use App\Models\Email_model;
use App\Models\User_model;
use Config\AppConfig;
use QRcode;
use PayKassaAPI;

class Dashboard extends BaseController
{
    var $data;
    var $db;
    var $user;
    public function __construct()
    {
        $this->data['menu'] = 'dashboard';
        helper(['origin']);
        model('Category_model');

        $session = session();
        if (!$session->get('login')) {
            header("Location: " . site_url('login'));
            exit();
        }


        $user_id = user_id();
        $db = db_connect();
        $builder = $db->table("users");
        $us = $builder->getWhere(['id' => $user_id])->getRow();

        if ($us->otp_verified == 0) {
            session()->set('__signup', $us->username);

            $rand = rand(1111, 9999);
            $db->table('users')->update(['otp_code' => $rand], ['id' => $user_id]);

            $em = new Email_model();
            $em->signupOTP($user_id)->sendEmail();

            session()->setFlashdata('success', 'Enter the OTP received on your email');
            header("Location: " . site_url('signup-verification'));
            exit();
        }

        $this->data['me'] = $us;
        $this->db = db_connect();

        $this->user = new User_model();
        $this->data['bmenu'] = '';
    }

    function index()
    {
        $this->data['bmenu'] = 'index';
        $user = new User_model(user_id());
        $this->data['balance'] = $user->getFundBalance();
        $this->data['main'] = 'index';
        $ids = $user->getDownloadLineIds(user_id());

        $this->data['total_team'] = count($ids);
        $builder = $this->db->table("users");
        $builder->select("count(*) as c");
        $builder->where("spil_id", user_id());
        $directs = (int)$builder->get()->getRow()->c;

        $this->data['direct'] = $directs;
        $this->data['main_balance'] = $user->getFundBalance();
        return front_view('dashboard/default', $this->data);
    }

    function add_new()
    {
        $user = new User_model();
        $this->data['main'] = 'add-new';
        $this->data['balance'] = $user->getFundBalance();
        $builder = $this->db->table("plans");
        $this->data['plans'] = $builder->get()->getResult();
        return front_view('dashboard/default', $this->data);
    }

    public function change_password()
    {
        $this->data['main'] = 'change-password';
        $this->data['menu'] = 'profile';
        return front_view('dashboard/default', $this->data);
    }

    public function edit_profile()
    {
        $session = \Config\Services::session();
        $builder = $this->db->table("countries");
        $this->data['menu'] = 'profile';
        $this->data['title']   = 'PROFILE';
        $this->data['main']    = 'edit-profile';
        $this->data['st']      = $builder->get()->getResult();
        $this->data['profile'] = $this->data['me'];
        $validator = \Config\Services::validation();

        $rules = [
            'form.first_name' => [
                'label' => 'Name',
                'rules' => 'required|trim'
            ],
            'form.trc20_adrs' => [
                'label' => 'USDT Address',
                'rules' => 'required|trim'
            ]
        ];

        if ($this->request->getPost('submit')) {
            if ($this->validate($rules)) {
                $builder = $this->db->table("users");
                $m        = $this->request->getPost("form");

                $em = $this->db->table("users")->limit(1)->getWhere(['email_id' => $m['email_id']])->getRow();
                if (is_object($em) && $em->id != user_id()) {
                    $session->setFlashdata('error', "Email id already exists");
                    return redirect()->to('dashboard/edit-profile');
                }
                $m['id']  = user_id();
                $builder->update($m, ['id' => user_id()]);
                $session->setFlashdata('success', 'Profile updated successfully');
                return redirect()->to('dashboard/edit-profile');
            } else {
                $data['form_error'] = $validator->listErrors();
                return front_view('dashboard/default', $this->data);
            }
        } else {
            return front_view('dashboard/default', $this->data);;
        }
    }

    public function kyc()
    {
        $builder = $this->db->table("users");
        $this->data['menu'] = 'profile';

        $this->data['title'] = "upload file";
        $this->data['main']  = 'kyc';
        $this->data['doc']  = $kyc  = $builder->getWhere(['id' => user_id()])->getRow();

        $photo = $this->request->getFile('id_proof');
        if ($photo != null) {
            if ($photo->isValid() && !$photo->hasMoved()) {
                $image      = $photo->getRandomName();
                $photo->move(WRITEPATH . 'uploads', $image);
                $s = [];
                $s['id_proof'] = $image;
                session()->setFlashdata("success", "ID Proof uploaded successfully.Please Upload Other remain document.");
                $builder->update($s, ['id' => user_id()]);
                redirect()->to(site_url('dashboard/kyc'));
            }
        }

        $photo = $this->request->getFile('address_proof');
        if ($photo != null) {
            if ($photo->isValid() && !$photo->hasMoved()) {
                $image      = $photo->getRandomName();
                $photo->move(WRITEPATH . 'uploads', $image);
                $s = [];
                $s['address_proof'] = $image;
                session()->setFlashdata("success", "Address Proof uploaded successfully.Please Upload Other remain document.");
                $builder->update($s, ['id' => user_id()]);
                redirect()->to(site_url('dashboard/kyc'));
            }
        }
        return front_view('dashboard/default', $this->data);;
    }

    function kyc_submitted()
    {
        $builder = $this->db->table('users');
        $session = session();
        $kyc = $builder->getWhere(['id' => user_id()])->getRow();
        if ($kyc->id_proof != '' && $kyc->address_proof != '') {
            $builder->update(['kyc_updated' => 1], ['id' => user_id()]);
            $session->setFlashdata('success', "Document updated successfully");
        } else {
            $session->setFlashdata('error', "You must upload all documents");
        }
        return redirect()->to('dashboard/kyc');
    }

    function remove_kyc()
    {
        $builder = $this->db->table("users");
        $session = session();
        if ($this->request->getGet('field')) {
            $builder->update([$_GET['field'] => '', 'kyc_updated' => 0], ['id' => user_id()]);
            $session->setFlashdata('success', 'KYC data reset successfully');
        }
        return redirect()->to('dashboard/kyc');
    }

    public function welcome()
    {
        $builder = $this->db->table("users");
        $this->data['title'] = 'WELCOME LETTER';
        $this->data['user']   = $builder->getWhere(['id' => user_id()])->getRow();
        return view('front/dashboard/welcome', $this->data);
    }

    function fund_transfer()
    {
        $user = new User_model(user_id());

        // Check permissoin 
        $ft = $user->getUserdata(user_id(), 'disable_transfer');
        if ($ft == 1) {
            session()->setFlashdata('error', 'Fund transfer has been disabled by Admin.');
            return redirect()->to('dashboard');
        }

        $this->data['menu'] = 'fund';
        $this->data['main'] = 'fund-transfer';
        $this->data['arorders'] = [];
        $this->data['balance'] = $bal = $user->getFundBalance();
        if ($this->request->getPost('save')) {
            $amount = $this->request->getPost('amount');
            $to = userid2id($this->request->getPost('to'));
            $builder = $this->db->table("users");
            $touser = $builder->getWhere(["id" => $to])->getRow();
            if (is_object($touser) && $amount <= $bal) {

                $us = new User_model($to);
                $us->credit($amount, Dashboard_model::INCOME_FUND_TRANSER, "Received from " . id2userid(user_id()), $to);

                $us1 = new User_model(user_id());
                $us1->debit($amount, Dashboard_model::INCOME_FUND_TRANSER, "Transfer to " . id2userid($to), user_id());

                session()->setFlashdata("success", "Fund transfer Completed");
            } else {
                session()->setFlashdata("error", "Opps!! In-sufficient Balance or Invalid user");
            }
            return redirect()->to('dashboard/fund-transfer');
        }
        return front_view('dashboard/default', $this->data);;
    }

    function fund_request()
    {
        $builder = $this->db->table("fund_request");
        $this->data['menu'] = 'fund';
        $this->data['main']     = 'fund-request';
        $this->data['arorders'] = $builder->orderBy('id', 'DESC')->where('user_id', user_id())->get()->getResult();
        $rules = [
            'form.amount' => [
                'label' => 'Amount',
                'rules' => 'required'
            ],
            'form.fdate' => [
                'label' => 'Date',
                'rules' => 'required'
            ]
        ];
        if ($this->request->getPost("submit")) {
            if ($this->validate($rules)) {
                $x            = $this->request->getPost("form");
                $x['id']      = false;
                $x['user_id'] = user_id();
                $x['created'] = date('Y-m-d H:i');
                $x['status']  = 0;

                $upload = $this->request->getFile('screenshot');
                if ($upload->isValid() && !$upload->hasMoved()) {
                    $nm = $upload->getRandomName();
                    $upload->move(upload_dir(), $nm);
                    $x['screenshot'] = $nm;
                } else {
                    session()->setFlashdata('error', "You must upload screenshot.");
                    return redirect()->to(site_url('dashboard/fund-request'));
                }
                $builder->insert($x);
                session()->setFlashdata('success', "Fund Request has been done successfully");
                return redirect()->to(site_url('dashboard/fund-request'));
            }
        }
        return front_view('dashboard/default', $this->data);;
    }

    public function transfer()
    {
        $this->data['menu'] = "pin";
        $this->data['main'] = 'dashboard/transfer';
        if ($this->input->post('pinids')) {
            $ids                 = $this->input->post('pinids');
            $this->data['epins'] = $ids;
            $this->load->front_view('dashboard/default', $this->data);
        } else {
            $this->session->set_flashdata('error', "You must select pin to transfer");
            redirect('dashboard/pin-transfer');
        }
    }

    function topup()
    {
        $this->data['main'] = 'topup-account';
        $this->data['menu'] = 'topup';
        $us = new User_model(user_id());
        $this->data['balance'] = $us->getFundBalance();
        return front_view('dashboard/default', $this->data);;
    }

    public function members()
    {
        $this->data['bmenu'] = 'teams';
        $builder = $this->db->table('users');
        $this->data['menu'] = 'members';
        $this->data['main'] = 'members';
        $builder->orderBy('join_date', 'DESC');
        $this->data['members'] = $builder->getWhere(['spil_id' => user_id()])->getResult();
        return front_view('dashboard/default', $this->data);;
    }

    public function downline_members()
    {
        $us = new User_model();
        $this->data['menu'] = 'members';
        $this->data['main'] = 'downline-members';
        $ids = $us->getDownloadLineIds(user_id());
        $ids = array_reverse($ids);
        if (count($ids) > 0) {
            $builder = $this->db->table("users");
            $builder->whereIn('id', $ids);
            $builder->orderBy('slno', 'DESC');
            $this->data['members'] = $builder->get()->getResult();
        } else {
            $this->data['members'] = [];
        }
        return front_view('dashboard/default', $this->data);;
    }

    public function payment_history()
    {
        $this->data['bmenu'] = 'history';
        $builder = $this->db->table("transaction");
        $this->data['menu'] = 'report';
        $this->data['main']    = 'payment-history';
        $this->data['title'] = "Payment History";
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'payment-history';
        $this->data['tab'] = $tab;
        $this->data['showButtons'] = true;

        $from = isset($_GET['from']) && $_GET['from'] != '' ? date("Y-m-d", strtotime($_GET['from'])) : null;
        $to = isset($_GET['to']) && $_GET['to'] != '' ? date("Y-m-d", strtotime($_GET['to'])) : null;
        if ($from != '' && $to == null) {
            $builder->where("DATE(created) > '$from'");
        } else if ($from == null && $to != '') {
            $builder->where("DATE(created) < '$to'");
        } else if ($from != '' && $to != '') {
            $builder->where("DATE(created) BETWEEN '$from' AND '$to'");
        }

        $this->data['arrdata'] = [];
        switch ($tab) {
            case 'ads':
                $this->data['arrdata'] = $builder->orderBy('id', 'DESC')->where('user_id', user_id())->get()->getResult();
                break;
            case 'payment-history':
                $this->data['arrdata'] = $builder->orderBy('id', 'DESC')->where('user_id', user_id())->get()->getResult();
                break;

            case 'matching':
                $this->data['title'] = "Matching Income History";
                $builder->where("notes", Dashboard_model::INCOME_MATCHING);
                $this->data['arrdata'] = $builder->orderBy('id', 'DESC')->where('user_id', user_id())->get()->getResult();
                break;

            case 'level':
                $this->data['title'] = "Level Income History";
                $builder->where("notes", Dashboard_model::INCOME_LEVEL);
                $this->data['arrdata'] = $builder->orderBy('id', 'DESC')->where('user_id', user_id())->get()->getResult();
                break;

            case 'roi':
                $this->data['title'] = "ROI Income History";
                $builder->where("notes", Dashboard_model::INCOME_ROI);
                $this->data['arrdata'] = $builder->orderBy('id', 'DESC')->where('user_id', user_id())->get()->getResult();
                break;

            case 'sponsor':
                $this->data['title'] = "Reward Income History";
                $builder->where("notes", Dashboard_model::INCOME_SPONSOR);
                $this->data['arrdata'] = $builder->orderBy('id', 'DESC')->where('user_id', user_id())->get()->getResult();
                break;

            case 'only-income':
                $builder->where("cr_dr", 'cr');
                $this->data['arrdata'] = $builder->orderBy('id', 'DESC')->where('user_id', user_id())->get()->getResult();
                break;
            case 'campaign':
                $this->data['title'] = "Campaign Income";
                break;
        }
        return front_view('dashboard/default', $this->data);;
    }

    public function wallet_history()
    {
        $builder = $this->db->table('wallet');

        $this->data['menu'] = 'report';
        $this->data['main']    = 'payment-history';
        $this->data['title'] = "Wallet History";
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'wallet-history';
        $this->data['tab'] = $tab;
        $this->data['showButtons'] = FALSE;
        $from = isset($_GET['from']) && $_GET['from'] != '' ? date("Y-m-d", strtotime($_GET['from'])) : null;
        $to = isset($_GET['to']) && $_GET['to'] != '' ? date("Y-m-d", strtotime($_GET['to'])) : null;
        if ($from != '' && $to == null) {
            $builder->where("DATE(created) > '$from'");
        } else if ($from == null && $to != '') {
            $builder->where("DATE(created) < '$to'");
        } else if ($from != '' && $to != '') {
            $builder->where("DATE(created) BETWEEN '$from' AND '$to'");
        }

        $this->data['arrdata'] = $builder->orderBy('id', 'DESC')->where('user_id', user_id())->get()->getResult();
        return front_view('dashboard/default', $this->data);;
    }

    public function fund_transfer_history()
    {
        $builder = $this->db->table("transaction");
        $this->data['menu'] = 'fund';
        $this->data['main']    = 'payment-history';
        $this->data['title'] = "Fund Transfer History";
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'fund-transfer-history';
        $this->data['tab'] = $tab;
        $this->data['showButtons'] = FALSE;
        $from = isset($_GET['from']) && $_GET['from'] != '' ? date("Y-m-d", strtotime($_GET['from'])) : null;
        $to = isset($_GET['to']) && $_GET['to'] != '' ? date("Y-m-d", strtotime($_GET['to'])) : null;
        if ($from != '' && $to == null) {
            $builder->where("DATE(created) > '$from'");
        } else if ($from == null && $to != '') {
            $builder->where("DATE(created) < '$to'");
        } else if ($from != '' && $to != '') {
            $builder->where("DATE(created) BETWEEN '$from' AND '$to'");
        }
        $builder->where('notes', Dashboard_model::INCOME_FUND_TRANSER);
        $this->data['arrdata'] = $builder->orderBy('id', 'DESC')->where('user_id', user_id())->get()->getResult();
        return front_view('dashboard/default', $this->data);;
    }

    public function topup_account()
    {
        $this->data['main'] = 'topup-account';
        $this->data['menu'] = 'topup';
        return front_view('dashboard/default', $this->data);;
    }

    function retopup_self()
    {
        $this->data['main'] = 'retopup-self';
        $this->data['menu'] = 'topup';
        $us = new User_model(user_id());
        $this->data['balance'] =  $us->getFundBalance();
        return front_view('dashboard/default', $this->data);
    }

    function auto_pool()
    {
        $this->data['main'] = 'auto-pool';
        $this->data['menu'] = 'topup';
        $us = new User_model(user_id());
        if ($us->user_rank == 0) {
            session()->setFlashdata('error', 'Your account must be in Silver Club to purchase auto pool');
            return redirect()->to('dashboard');
        }
        $this->data['balance'] =  $us->getFundBalance();
        return front_view('dashboard/default', $this->data);
    }

    function topup_history()
    {
        $this->data['main'] = 'topup-history';
        return front_view('dashboard/default', $this->data);
    }

    function add_funds()
    {
        $app = new AppConfig();
        $user = new User_model(user_id());
        $this->data['main'] = 'add-funds';
        $this->data['menu'] = 'fund';
        $this->data['minAmt'] = $app->minDeposit;
        $this->data['maxAmt'] = $app->maxDeposit;
        $this->data['balance'] = $user->getFundBalance(user_id());
        return front_view('dashboard/default', $this->data);;
    }

    function deposite_history()
    {
        $this->data['main'] = 'deposit-history';
        $this->data['menu'] = 'fund';
        $this->data['pending_list'] = $this->db->table("payorder")->orderBy('id', 'DESC')->getWhere(['user_id' => user_id()])->getResult();

        return front_view('dashboard/default', $this->data);;
    }

    function order_view($order_id)
    {
        $this->data['menu'] = 'fund';
        require APPPATH . "/ThirdParty/qrlib/qrlib.php";
        $db = db_connect();
        $builder = $db->table("payorder");
        $ob = $builder->getWhere(['id' => $order_id])->getRow();
        if (is_object($ob)) {

            // Generate qr code and save
            $qrfile = upload_dir("qr-" . $ob->id . ".png");
            QRcode::png($ob->payment_address, $qrfile);

            $this->data['main'] = 'qrcode';
            $this->data['data'] = $ob;
            return front_view('dashboard/default', $this->data);
        } else {
            session()->setFlashdata("error", "Opps!! Error generated");
            return redirect()->to("dashboard");
        }
        return front_view('dashboard/default', $this->data);
    }

    function matching_income()
    {
        $this->data['menu'] = 'report';
        $this->data['main'] = "matching-income";
        return front_view('dashboard/default', $this->data);;
    }

    function member_level()
    {
        $this->data['main'] = 'dashboard/member-level';
        $this->load->front_view('dashboard/default', $this->data);
    }

    function summary()
    {
        $this->data['main'] = 'summary';
        $this->data['menu'] = "orders";
        return front_view('dashboard/default', $this->data);;
    }

    function rewards()
    {
        $this->data['main'] = 'rewards';
        $this->data['menu'] = "report";
        return front_view('dashboard/default', $this->data);;
    }

    function withdraw()
    {
        $user = new User_model(user_id());
        $app = new AppConfig();
        $this->data['wallet_bal'] = $balance = $user->getFundBalance(user_id());

        if ($user->trc20_adrs == '') {
            session()->setFlashdata('success', 'Save USDT Address for Withdrawal Processing.');
            return redirect()->to('dashboard/edit-profile');
        }

        $rules = [
            'amount' => [
                'rules' => 'required',
                'label' => 'Amount'
            ]
        ];

        if ($this->request->getPost('btnsubmit')) {
            if ($this->validate($rules)) {
                $amt = $this->request->getPost('amount');
                if ($amt >= $app->minWithdrawLimit && $amt <= $balance) {

                    $code = rand(2222, 9999);

                    $us = new User_model(user_id());
                    $adrs = $us->trc20_adrs;

                    $sb = [];
                    $sb['user_id'] = user_id();
                    $sb['amount'] = $amt;
                    $sb['created'] = date("Y-m-d H:i:s");
                    $sb['updated'] = null;
                    $sb['status'] = 0;
                    $sb['comments'] = '';
                    $sb['txn_id'] = '';
                    $sb['pay_method'] = 'TRON_TRC20';
                    $sb['otp_code'] = $code;
                    $sb['otp_verified'] = 0;
                    $sb['wallet_adrs'] = $adrs;

                    $this->db->table("withdraw")->insert($sb);
                    $lastId = $this->db->insertID();

                    session()->set("withdraw_reqid", $lastId);
                    session()->setFlashdata("success", "OTP Shared on your email, Please verify to continue");

                    // Sending Email to User
                    $em = new Email_model();
                    $em->setWithdrawalOTPEmail($lastId);
                    $em->sendEmail();

                    return redirect()->to('dashboard/withdraw-otp');
                } else {
                    session()->setFlashdata('error', "Min withdrawal must be more than " . $app->minWithdrawLimit);
                }
            } else {
                return redirect()->to('dashboard/withdraw');
            }
        }

        $this->data['menu'] = 'withdraw';
        $this->data['main'] = 'withdraw';
        $this->data['withdraw_min'] = $app->minWithdrawLimit;
        return front_view('dashboard/default', $this->data);;
    }

    function withdraw_otp()
    {
        if (session()->has('withdraw_reqid')) {
            $this->data['main'] = 'withdraw-otp';
            $this->data['menu'] = 'withdraw';

            $lastId = session()->get('withdraw_reqid');
            $ob = $this->db->table("withdraw")->getWhere(['id' => $lastId])->getRow();
            $us = new User_model(user_id());

            if ($this->request->getPost('otp')) {
                $otp_code = $this->request->getPost('otp');
                if ($otp_code == $ob->otp_code) {

                    // Withdrawal Verified
                    $d = [];
                    $d['otp_verified'] = 1;
                    $this->db->table("withdraw")->update($d, ['id' => $lastId]);

                    // Debit from fund
                    session()->remove('withdraw_reqid');
                    $this->fund_withdraw($lastId);
                } else {
                    session()->setFlashdata('error', 'OTP not matching');
                    return redirect()->to('dashboard/withdraw-otp');
                }
                return redirect()->to('dashboard/withdraw');
            }

            return front_view('dashboard/default', $this->data);
        } else {
            session()->setFlashdata('error', 'Oppss !! Some error. Try again');
            return redirect()->to('dashboard');
        }
    }

    // Paykassa fund transfer api
    private function fund_withdraw($order_id)
    {
        $db = db_connect();
        $order = $db->table("withdraw")->getWhere(['id' => $order_id])->getRow();
        if (is_object($order) && $order->status == 0) {
            $me = new User_model(user_id());
            $amt = $order->amount * 0.96;
            $toAddress = $me->trc20_adrs;

            $pay = new PayKassaAPI(AppConfig::PAYKASSA_API_ID, AppConfig::PAYKASSA_API_PASSWORD, FALSE);
            $resp = $pay->api_payment(AppConfig::PAYKASSA_MRECHANT_ID, 30, $toAddress, $amt, 'USDT', "Payout Request #" . $order_id);

            if ($resp['error'] == '') {
                $sb = [];
                $sb['updated'] = date("Y-m-d H:i:s");
                $sb['status'] = 1;
                $sb['comments'] = $resp['data']['transaction'];
                $sb['txn_id'] = $resp['data']['txid'];
                $sb['paid_total'] = $amt;
                $db->table("withdraw")->update($sb, ['id' => $order_id]);
                $me->debit($order->amount, Dashboard_model::WITHDRAW, 'Withdraw by user', $order_id);
            } else {
                $sb = [];
                $sb['comments'] = $resp['message'];
                $db->table("withdraw")->update($sb, ['id' => $order_id]);
            }

            session()->setFlashdata('success', 'Fund transfer to your wallet is completed');
        } else {
            session()->setFlashdata('error', 'Unable to initiate Fund transfer');
        }
        return redirect()->to('dashboard/withdraw');
    }

    function withdraw_history()
    {
        $builder = $this->db->table("withdraw");
        $this->data['menu'] = 'withdraw';
        $this->data['main'] = 'withdraw-history';
        $this->data['reqlist'] = $builder->orderBy('id', 'DESC')->getWhere(array('user_id' => user_id(), 'otp_verified' => 1))->getResult();
        return front_view('dashboard/default', $this->data);;
    }

    function member_list()
    {
        $this->data['main'] = 'member-list';
        $this->data['menu'] = 'members';
        return front_view('dashboard/default', $this->data);;
    }

    function supports()
    {
        $builder = $this->db->table("supports");
        $this->data['main'] = 'supports';
        $this->data['slist'] = $builder->orderBy("updated", "DESC")->getWhere(['user_id' => user_id()])
            ->getResult();
        return front_view('dashboard/default', $this->data);;
    }

    function create_new()
    {
        $builder = $this->db->table("supports");
        $this->data['main'] = 'support-create-new';
        if ($this->request->getPost('btnsubmit')) {
            $frm = $this->request->getPost("frm");
            $config['upload_path']   = upload_dir();
            $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
            $config['max_size']      = 0;
            $config['max_width']     = 0;
            $config['max_height']    = 0;
            // $this->load->library('upload', $config);

            // $uploaded = $this->upload->do_upload('attach');
            // if ($uploaded) {
            //     $data = $this->upload->data();
            //     $frm['attachment'] = $data['file_name'];
            // }
            $frm['created'] = date("Y-m-d H:i");
            $frm['updated'] = date("Y-m-d H:i");
            $frm['user_id'] = user_id();
            $builder->insert($frm);
            session()->setFlashdata('success', 'Support Ticket Created');
            return redirect()->to('dashboard/supports');
        }
        return front_view('dashboard/default', $this->data);;
    }

    function support_view($id = false)
    {
        $builder = $this->db->table("supports");

        $this->data['main'] = "support-view";
        $this->data['ticket'] = $t = $builder->getWhere(['id' => $id])->getRow();
        if ($this->request->getPost("description")) {
            $s = array();
            $s['support_id'] = $id;
            $s['from_id'] = user_id();
            $s['to_id'] = 0;
            $s['description'] = $this->request->getPost("description");
            $s['created'] = date("Y-m-d H:i");
            $builder->update(['status' => 1, 'updated' => date('Y-m-d H:i:s')], ["id" => $id]);

            $builder = $this->db->table("supports_view");
            $builder->insert($s);
            session()->setFlashdata('success', 'Reply updated');
            return redirect()->to(site_url("dashboard/support-view/" . $id));
        }
        $builder = $this->db->table("supports_view");
        $this->data['views'] = $builder->getWhere(['support_id' => $id])->getResult();
        return front_view('dashboard/default', $this->data);;
    }

    function support_del($id = false)
    {
        $builder = $this->db->table("supports");
        if ($id) {
            $builder->delete(["id" => $id]);
            $builder = $this->db->table('supports_view');
            $builder->delete(["support_id" => $id]);
            session()->setFlashdata("success", "Deleted Successfully  !!");
        }
        return redirect()->to('dashboard/supports');
    }

    function support_close($id = false)
    {
        $builder = $this->db->table("supports");
        if ($id) {
            $builder->update(['status' => 0], ["id" => $id]);
            session()->setFlashdata("success", "Closed Successfully  !!");
        }
        return redirect()->to('dashboard/supports');
    }

    function upgrade()
    {
        $this->data['main'] = 'upgrade';
        $us = new User_model(user_id());
        $this->data['current_balance'] = $balance = $us->getFundBalance(user_id());

        if ($this->request->getPost('btn')) {
            $user_id = $this->request->getPost('user_id');
            $plan_total = $this->request->getPost('amount');

            if ($plan_total > $balance || $plan_total < 0) {
                session()->setFlashdata('error', "Opps!! In-sufficient Fund balance");
            } else {
                $builder = $this->db->table("users");
                $user = $builder->getWhere(['username' => strtoupper($user_id)])->getRow();
                if (is_object($user)) {
                    if ($user->ac_status == 1) {
                        session()->setFlashdata('error', "Account is already active");
                    } else {
                        $sb = [];
                        $sb['plan_total'] = $plan_total;
                        $sb['ac_status']  = 1;
                        $sb['payout'] = 1;
                        $sb['ac_active_date'] = date("Y-m-d H:i:s");
                        $builder->update($sb, ['id' => $user->id]);
                        session()->setFlashdata('success', 'Account activated successfully');

                        // Debit transaction
                        $us->debit($plan_total, Dashboard_model::CHARGE_UPGRADE, "Topup Account", $user->id);
                    }
                } else {
                    session()->setFlashdata('error', "Invalid Userid for activation up account");
                }
            }
            return redirect()->to(site_url('dashboard'));
        }
        $builder = $this->db->table("plans");
        $this->data['plans'] = $builder->get()->getResult();
        return front_view('dashboard/default', $this->data);;
    }

    function payment_report()
    {
        $user = new User_model(user_id());
        $this->data['main'] = 'payment-report';
        $this->data['menu'] = 'report';
        $this->data['total_income'] = $user->totalEarned();
        $this->data['total_withdrawal'] =  $user->totalPaid();
        $this->data['sponsor_income'] = $user->getIncomeByType(user_id(), Dashboard_model::INCOME_SPONSOR);
        $this->data['level_income'] = $user->getIncomeByType(user_id(), Dashboard_model::INCOME_LEVEL);
        return front_view('dashboard/default', $this->data);;
    }

    function accounts()
    {
        $user = new User_model();
        $this->data['main'] = 'accounts';
        $this->data['menu'] = 'accounts';
        $this->data['main_balance'] = $user->getFundBalance(user_id());
        return front_view('dashboard/default', $this->data);
    }

    function pages($page = 'about')
    {

        $this->data['menu'] = 'home';
        $this->data['main'] = $page;
        return front_view('dashboard/default', $this->data);
    }

    function view_profile()
    {
        $this->data['main'] = 'view-profile';
        return front_view('dashboard/default', $this->data);
    }

    function view_contact()
    {
        $this->data['main'] = 'view-contact';
        return front_view('dashboard/default', $this->data);
    }
}
