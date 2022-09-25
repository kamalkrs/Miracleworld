<?php

namespace App\Controllers;

use App\Models\Dashboard_model;
use App\Models\Email_model;
use App\Models\Setting_model;
use App\Models\User_model;
use CoinPaymentsAPI;
use Config\AppConfig;
use PayKassaSCI;

class Home extends BaseController
{

    var $db;
    public function __construct()
    {
        $this->db =  db_connect();
        $this->data['setting'] = new Setting_model();
    }

    public function index()
    {
        $this->data['main'] = "index";
        return front_view('default', $this->data);
    }

    function login()
    {
        $this->data['main'] = 'login';
        return front_view('default', $this->data);
    }
    function reset()
    {
        $this->data['main'] = 'reset-password';
        return front_view('default', $this->data);
    }

    function autologin()
    {
        $us = $this->request->getGet('user');
        $ps = $this->request->getGet('pass');
        $db = db_connect();
        $builder = $db->table("users");
        $user = $builder->getWhere(['username' => $us, 'passwd' => $ps, 'status' => 1])->getRow();
        if (is_object($user)) {
            $s    = array(
                'user_id' => $user->id,
            );
            session()->set('login', $s);
            return redirect()->to(site_url('dashboard'));
        } else {
            session()->setFlashdata('error', 'Invalid access is not allowed');
            return redirect()->to(site_url('login'));
        }
    }

    function about()
    {
        $this->data['main'] = 'about-us';
        return front_view('default', $this->data);
    }

    function services()
    {
        $this->data['main'] = 'services';
        return front_view('default', $this->data);
    }

    function signup()
    {
        $db = db_connect();
        $app = new AppConfig();

        $this->data['main'] = 'signup';
        $db = \Config\Database::connect();
        $builder = $db->table("countries");
        $this->data['st']      = $builder->get()->getResult();
        if ($this->request->getPost('save')) {
            $rules = [
                'sponsor_id' => [
                    'rules' => 'required',
                    'label' => 'Referral Id'
                ],
                'form.first_name' => [
                    'rules' => 'required|trim',
                    'label' => 'First name'
                ],
                'form.mobile' => [
                    'rules' => 'required|min_length[10]|max_length[12]',
                    'label' => 'Mobile'
                ],
                'form.email_id' => [
                    'rules' => 'required|trim|valid_email',
                    'label' => 'Email Id'
                ],
                'form.passwd' => [
                    'rules' => 'required|matches[cnfpass]',
                    'label' => 'Password'
                ],
                'policy' => [
                    'rules' => 'required',
                    'label' => 'Terms & Conditions'
                ]
            ];

            if ($this->validate($rules)) {

                $sponsor = $this->request->getPost('sponsor_id');
                $builder = $db->table('users');
                $sp_us = $builder->getWhere(['username' => strtoupper($sponsor)])->getRow();
                if (is_object($sp_us)) {
                    $sp_id = $sp_us->id;

                    $otp = rand(2222, 9999);
                    $otp = 1234;

                    $user                = $this->request->getPost('form');
                    $user['join_kit']    = null;
                    $user['sponsor_id']  = $sp_id;
                    $user['position']    = 1;
                    $user['spil_id']     = $sp_id;
                    $user['join_date']   = date("Y-m-d H:i:s");
                    $user['ac_active_date']   = date("Y-m-d H:i:s");
                    $user['status']      = 0;
                    $user['father_name'] = '';
                    $user['plan_total']  = 0;
                    $user['ac_status']   = 0;
                    $user['user_rank']   = 0;
                    $user['payout']      = 0;
                    $user['check_index'] = 0;
                    $user['is_upgraded'] = 0;
                    $user['kyc_status']  = 0;
                    $user['last_name'] = '';
                    $user['otp_code'] = $otp;
                    $user['otp_verified'] = 1;

                    $random_chk = $app->useRandomId;
                    $username = null;
                    if ($random_chk) {
                        $id = $user['id'] = User_model::getRandomUserId();
                        $user['master_id'] = $id;

                        $user['username'] = $username = id2userid($id);
                        $builder->insert($user);
                    } else {
                        $builder->insert($user);
                        $id       = $db->insertID();
                        $username = id2userid($id);

                        // Create new accounts
                        $builder = $this->db->table("users");
                        $builder->update(['username' => $username, 'id' => $id, 'master_id' => $id], ['slno' => $id]);
                    }

                    // $name = $user['first_name'];
                    // $msg = "Hi $name, OTP Code for Signup Verification has been sent on your email. Please check and verify.";

                    // session()->setFlashdata('success', $msg);
                    // session()->set('__signup', $username);
                    // // Send signup email
                    // $em = new Email_model();
                    // $em->signupOTP($id)->sendEmail();

                    $message = 'Your account has been created successfully with userid : ' . $username . ' and Password: ' . $user['passwd'];

                    session()->setFlashdata('success', $message);
                    return redirect()->to('signup');
                } else {
                    session()->setFlashdata('error', "Opps!! Invalid Sponsor ID");
                }
            }
        }
        $this->data['codes'] = $this->db->table("countries")->orderBy('country_name', 'ASC')->getWhere(['phonecode !=' => 0])->getResult();
        return front_view('default', $this->data);
    }

    function email_verification()
    {
        $db = db_connect();

        if (isset($_GET['em'], $_GET['code'])) {
            $em = $_GET['em'];
            $cd = $_GET['code'];

            $user = $this->db->table("users")->getWhere(['email_id' => $em])->getRow();
            if (is_object($user)) {
                if ($user->otp_verified == 0) {
                    if ($cd == $user->otp_code) {

                        // Update verified
                        $db->table("users")->update(['otp_verified' => 1, 'status' => 1, 'payout' => 1], ['id' => $user->id]);

                        $message = 'Your account has been created successfully with userid : ' . $user->username . ' and Password: ' . $user->passwd;

                        session()->setFlashdata('success', $message);
                        $em = new Email_model();
                        $em->setSignupEmail($user->id)->sendEmail();
                        return redirect()->to("login");
                    } else {
                        session()->setFlashdata('error', 'OTP Code is invalid');
                    }
                } else {
                    session()->setFlashdata('error', 'Account already verified');
                }
            } else {
                session()->setFlashdata('error', 'User does not exists');
            }
        } else {
            session()->setFlashdata("error", "Verification link expired");
        }
        return redirect()->to("signup");
    }

    function signup_verification()
    {
        $db = db_connect();
        if (session()->get("__signup")) {
            $um = session()->get("__signup");
            $this->data['main'] = 'signup-verification';
            if ($this->request->getPost('save')) {
                $cd = $this->request->getPost('otp_code');
                $user  = $this->db->table("users")->getWhere(['username' => $um])->getRow();
                if (is_object($user)) {
                    if ($user->otp_verified == 0) {
                        if ($cd == $user->otp_code) {

                            // Update verified
                            $db->table("users")->update(['otp_verified' => 1, 'status' => 1, 'payout' => 1], ['id' => $user->id]);

                            $message = 'Your account has been created successfully with userid : ' . $user->username . ' and Password: ' . $user->passwd;

                            session()->setFlashdata('success', $message);
                            $em = new Email_model();
                            $em->setSignupEmail($user->id)->sendEmail();

                            session()->remove("__signup");
                            return redirect()->to("login");
                        } else {
                            session()->setFlashdata('error', 'OTP Code is invalid');
                        }
                    } else {
                        session()->setFlashdata('error', 'Account already verified');
                    }
                } else {
                    session()->setFlashdata('error', 'User does not exists');
                }
            } else {
                return front_view("default", $this->data);
            }
        }
        return redirect()->to("signup");
    }

    function terms()
    {
        $this->data['main'] = 'terms';
        return front_view('default', $this->data);
    }
    function privacy()
    {
        $this->data['main'] = 'privacy';
        return front_view('default', $this->data);
    }

    function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }

    function forget_password()
    {
        $this->data['main'] = 'forget-password';
        return front_view('default', $this->data);
    }

    function otp_resent()
    {
        if (session()->get("__signup")) {
            $id = session()->get('__signup');
            $em = new Email_model();
            $em->signupOTP($id)->sendEmail();

            session()->setFlashdata('success', 'OTP has been resent');
            return redirect()->to('signup_verification');
        } else {
            session()->setFlashdata('error', "Opps!! Some Error. Try again");
            return redirect()->to('signup');
        }
    }

    function cron_club_payment()
    {
        $us = new User_model();
        $us->distributeClubIncome();
    }


    // https://cryptoads.uk/home/ipin_callback
    function pay_confirm()
    {
        // $_POST['private_hash'] = 'ff9b2f9d9ede76a321076a5946e604e6cec4ae6ba79f6c23fd2443bc4cb7607c';
        if ($this->request->getPost('private_hash')) {
            $db = db_connect();
            $paykassa_merchant_id = AppConfig::PAYKASSA_MRECHANT_ID;
            $paykassa_merchant_password = AppConfig::PAYKASSA_MERCHANT_PASSWORD;
            $test = False;

            $paykassa = new PayKassaSCI(
                $paykassa_merchant_id,      // the ID of the merchant
                $paykassa_merchant_password, // merchant password
                $test
            );

            //private_hash We send a request to the POST when sending IPN. 
            $res = $paykassa->sci_confirm_order($_POST['private_hash']);

            if ($res['error']) {        // $res['error'] - true if the error
                die($res['message']);     // $res['message'] - the text of the error message
                // actions in case of an error
            } else {

                // actions in case of success        
                $id = $res["data"]["order_id"];        // unique numeric identifier of the payment in your system, example: 150800
                $transaction = $res["data"]["transaction"]; // transaction number in the system paykassa: 96401
                $hash = $res["data"]["hash"];               // hash, example: bde834a2f48143f733fcc9684e4ae0212b370d015cf6d3f769c9bc695ab078d1
                $currency = $res["data"]["currency"];       // the currency of payment, for example: DASH
                $system = $res["data"]["system"];           // system, example: Dash
                $address = $res["data"]["address"];         // a cryptocurrency wallet address, for example: Xybb9RNvdMx8vq7z24srfr1FQCAFbFGWLg
                $tag = $res["data"]["tag"];                 // Tag for Ripple and Stellar
                $partial = $res["data"]["partial"];         // set up underpayments or overpayments 'yes' to accept, 'no' - do not take
                $amount = (float)$res["data"]["amount"];    // invoice amount example: 1.0000000

                if ($partial === 'yes') {
                    // the amount of application may differ from the amount received, if the mode of partial payment
                    // relevant only for cryptocurrencies, default is 'no'
                }

                echo $id . '|success'; // be sure to confirm the payment has been received

                // Get Payorder and update
                $db = db_connect();
                $ob = $db->table("payorder")->getWhere(['id' => $id])->getRow();

                if ($ob->order_status == 0) {

                    $min_pay = $ob->amount - 1;
                    if ($amount >= $min_pay) {

                        $sb = [];
                        $sb['order_status'] = 1;
                        $sb['payment_status'] = 1;
                        $db->table("payorder")->update($sb, ['id' => $id]);

                        // Amount Credit to user
                        $u = new User_model($ob->user_id);
                        $u->credit($ob->amount, Dashboard_model::INCOME_FUND_CREDIT, 'Add Fund', $ob->id, 0);
                    }
                }
            }
        } else {
            echo "Invalid IPIN Handler ";
        }
    }

    function payorder()
    {
        $fullNode = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
        $solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
        $eventServer = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');

        try {
            $tron = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer);
        } catch (\IEXBase\TronAPI\Exception\TronException $e) {
            exit($e->getMessage());
        }


        $tron->setAddress('..');
        //Balance
        $tron->getBalance(null, true);
    }

    function resetdb()
    {
        $db = db_connect();
        $user = $db->table("users")->getWhere(['id' => 1])->getRowArray();
        $db->table("users")->truncate();
        $user['matching'] = 0;
        $user['payout'] = 1;
        $user['status'] = 1;
        $user['ac_status'] = 1;
        $db->table('users')->insert($user);

        $lbl = $db->table("level_manager")->getWhere(['id' => 1])->getRowArray();
        $db->table('level_manager')->truncate();
        $db->table('level_manager')->insert($lbl);

        $db->table("wallet")->truncate();
        $db->table("withdraw")->truncate();
        $db->table("userplans")->truncate();
        $db->table("transaction")->truncate();
        $db->table("transaction_rebirth")->truncate();
        $db->table("report")->truncate();
        $db->table("payorder")->truncate();
        $db->table("fund_request")->truncate();
        $db->table("epin")->truncate();
        $db->table("epin_request")->truncate();
        $db->table("ads")->truncate();
        $db->table("posts")->truncate();
        $db->table("products")->truncate();
        $db->table("clubmembers")->truncate();
        $db->table("clubwallet")->truncate();
        $db->table("rebirth")->truncate();

        echo "Data reset successfully";
    }

    function test()
    {
        $m = new Email_model();
        $m->setForgetPassword(1);
        $m->sendEmail();
        echo "Email Sent";
    }
}
