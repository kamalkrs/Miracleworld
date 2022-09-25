<?php

namespace App\Controllers;

use App\Models\Dashboard_model;
use App\Models\Setting_model;
use App\Models\User_model;
use App\Models\Email_model;
use CodeIgniter\RESTful\ResourceController;
use Config\AppConfig;
use PayKassaSCI;
use RestApi;

class Api extends ResourceController
{
    protected $format    = 'json';
    function __construct()
    {
        helper('restapi');
        helper('origin');
    }

    function site($m = null)
    {
        $app = new AppConfig();
        $db = db_connect();
        $session = session();
        $api = new RestApi();
        $user = new User_model();

        switch ($m) {
            case 'login':
                $data = $this->request->getJSON(true);
                $api->setData($data);
                if ($api->checkINPUT(array('user', 'pass'), $data)) {
                    $us = $data['user'];
                    $ps = $data['pass'];
                    $builder = $db->table("users");
                    $rule = "(username = '$us' OR email_id = '$us') AND passwd = '$ps'";
                    $builder->where($rule);
                    $user = $builder->get()->getRow();
                    if (is_object($user)) {
                        $s    = array(
                            'user_id' => $user->id,
                        );
                        $session->set('login', $s);
                        $api->setOK();
                        $api->setMessage('Login Successful. Redirecting....');
                    } else {
                        $api->setError();
                        $api->setMessage('Invalid Userid or Password');
                    }
                } else {
                    $api->missing();
                }
                break;

            case 'reset':
                $builder = $db->table('users');
                $m = $_GET['username'];
                $builder->where("username = '$m' OR email_id = '$m'");
                $us = $builder->get()->getRow();
                if (is_object($us)) {

                    $api->setOK();
                    $api->setMessage("Password has been sent on your email");
                } else {
                    $api->setError();
                    $api->setMessage('Userid/Email Id does not exist!!');
                }
                $api->setData($m);
                break;

            case 'users':
                $builder = $db->table("users");
                $list = $builder->orderBy('slno', 'DESC')->limit(200)->get()->getResult();
                return json_encode($list);
                break;
            case 'userinfo':
                if ($api->check(array('username'))) {
                    $username = $_GET['username'];
                    $builder = $db->table('users');
                    $us = $builder->getWhere(array('username' => $username))->getRow();
                    if (is_object($us)) {
                        $api->setOK();
                        $api->setData($us);
                    } else {
                        $api->setError();
                        $api->setMessage("Opps!! Invalid userid");
                    }
                } else {
                    $api->missing();
                }
                break;

            case 'default':
        }
        return $this->respond($api);
    }

    function call($m = null)
    {
        $session = session();
        $app = new AppConfig();
        $db = db_connect();
        $api = new RestApi();
        if ($session->login == null) {
            $api->setError("You must login to view the page");
            return $this->respond($api);
        }

        $user = new User_model(user_id());
        $setting = new Setting_model();

        switch ($m) {
            case 'change-password':
                $data = $this->request->getJSON(true);
                $api->setData($data);
                if ($api->checkINPUT(['new_pass', 'old_pass', 'user_id'], $data)) {
                    $old = $data['old_pass'];
                    $new = $data['new_pass'];
                    $user_id = $data['user_id'];
                    $me = $db->table("users")->getWhere(['id' => $user_id])->getRow();
                    if ($me->passwd == $old) {
                        $builder = $db->table("users");
                        $builder->update(['passwd' => $new], ['id' => user_id()]);
                        $api->setOK('Password has been changed successfully');
                    } else {
                        $api->setError('Old Password not matching');
                    }
                }
                break;

            case 'dashboard':
                $app = new AppConfig();
                $data = [];
                $data['company'] = $app->siteName;
                $api->setOK();
                $api->setData($data);
                break;

            case 'userinfo':
                if ($api->check(array('username'))) {
                    $username = $_GET['username'];
                    $builder = $db->table('users');
                    $us = $builder->getWhere(array('username' => $username))->getRow();
                    if (is_object($us)) {
                        $api->setOK();
                        $api->setData($us);
                    } else {
                        $api->setError();
                        $api->setMessage("Opps!! Invalid userid");
                    }
                } else {
                    $api->missing();
                }
                break;

            case 'activate':
                $data = $this->request->getJSON(true);
                // $user->checkAndAddToClubMembers(1);

                if ($api->checkINPUT(['username'], $data)) {
                    $from_id = user_id();
                    $username = $data['username'];
                    $wallet_id = $data['wallet'];

                    $from = new User_model($from_id);

                    $bal = 0;
                    if ($wallet_id == FUND_BALANCE) {
                        $bal = $from->getFundBalance();
                    } else {
                        $bal = $from->getWalletBalance();
                    }

                    $jamt = $app->joiningAmount;

                    if ($jamt <= $bal) {
                        $tr = $db->table("users")->getWhere(['username' => $username])->getRow();
                        if (is_object($tr) && $tr->ac_status == 0) {

                            // Activate account
                            $update = [];
                            $update['ac_status'] = 1;
                            $update['ac_active_date'] = date("Y-m-d H:i:s");
                            $update['payout'] = 1;
                            $db->table("users")->update($update, ['id' => $tr->id]);

                            // Debit amount from account
                            $msg = "Activation of " . $tr->id;
                            if ($wallet_id == FUND_BALANCE)
                                $from->debit($jamt, Dashboard_model::CHARGE_ACTIVATE_ACCOUNT, $msg, $tr->id);
                            else
                                $from->debitWallet($jamt, Dashboard_model::CHARGE_ACTIVATE_ACCOUNT, $msg, $tr->id);

                            // Send Activation Email
                            $em = new Email_model();
                            $em->setAccountActivationEmail($tr->id);
                            $em->sendEmail();

                            // Setup in Auto Pool Tree
                            $user = new User_model();
                            $user->creditToclub($tr->id); // Pass newly generated id
                            $user->sendToAutoPool($tr->id); // Auto pool setup
                            $api->setOK("Account activated successfully");

                            // Credit direct income
                            $sp = new User_model($tr->spil_id);
                            $sp->credit($app->directIncome, Dashboard_model::INCOME_SPONSOR, 'Account Topup', $tr->id);

                            // Check and add to club members
                            $user->checkAndAddToClubMembers($tr->spil_id);
                        } else {
                            $api->setError("Member account not found or already activated");
                        }
                    } else {
                        $api->setError("You have insufficient funds");
                    }
                } else {
                    $api->missing();
                }
                break;

            case 'max-pool-purchase':
                $data = $this->request->getJSON(true);
                if ($api->checkINPUT(['user_id'], $data)) {
                    $user_id = $data['user_id'];
                    $today = (int)$db->table("topup")->select("sum(qty) as qty")->where('DATE(created) = CURDATE()')->getWhere(['user_id' => $user_id])->getRow()->qty;
                    $maxAllowed = $user->user_rank * 5;
                    $m = $maxAllowed - $today;
                    $api->setData($m);
                    $api->setOK();
                }
                break;
            case 'pool-purchase':
                $data = $this->request->getJSON(true);
                $today = (int)$db->table("topup")->select("sum(qty) as qty")->where('DATE(created) = CURDATE()')->getWhere(['user_id' => user_id()])->getRow()->qty;
                $maxAllowed = $user->user_rank * 5;
                $us = new User_model(user_id());

                if ($api->checkINPUT(['qty', 'amount'], $data)) {
                    $qty = $data['qty'];
                    $amt = $data['amount'];
                    $wallet_id = $data['wallet'];

                    $bal = 0;
                    if ($wallet_id == FUND_BALANCE) {
                        $bal = $us->getFundBalance();
                    } else {
                        $bal = $us->getWalletBalance();
                    }

                    if ($bal >= $amt) {
                        // Saving today topup
                        $sb = [];
                        $sb['user_id'] = user_id();
                        $sb['qty'] = $qty;
                        $sb['toup_for'] = user_id();
                        $sb['created'] = date("Y-m-d H:i:s");
                        $db->table("topup")->insert($sb);

                        if ($qty <= $maxAllowed) {

                            if ($wallet_id == FUND_BALANCE) {
                                $user->debit($amt, Dashboard_model::CHARGE_RETOPUP, "$qty Pool Purchase");
                            } else {
                                $user->debitWallet($amt, Dashboard_model::CHARGE_RETOPUP, "$qty Pool Purchase");
                            }

                            for ($i = 1; $i <= $qty; $i++) {
                                $user->sendToAutoPool(user_id());
                            }

                            $api->setOK("Topup done successfully");
                        } else {
                            $api->setError("Max allowed exceed than your limit");
                        }
                    } else {
                        $api->setError('You have In-sufficient funds');
                    }
                }
                break;
            case 'retopup-others':
                $data = $this->request->getJSON(true);
                $us = new User_model(user_id());
                $app = new AppConfig();
                if ($api->checkINPUT(['qty', 'amount', 'username'], $data)) {
                    $qty = $data['qty'];
                    $amt = $data['amount'];
                    $nm  = $data['username'];
                    $wallet_id = $data['wallet'];

                    $bal = 0;
                    if ($wallet_id == FUND_BALANCE) {
                        $bal = $us->getFundBalance();
                    } else if ($wallet_id == WALLET_BALANCE) {
                        $bal = $us->getWalletBalance();
                    }

                    $other = $db->table('users')->getWhere(['username' => $nm])->getRow();
                    $jamt = $app->joiningAmount;

                    if ($bal >= $amt) {
                        for ($i = 1; $i <= $qty; $i++) {
                            $newId = $us->createDuplicateId($other->id);

                            $msg = "Activation of " . $newId;
                            if ($wallet_id == FUND_BALANCE)
                                $us->debit($jamt, Dashboard_model::CHARGE_ACTIVATE_ACCOUNT, $msg, $newId);
                            else
                                $us->debitWallet($jamt, Dashboard_model::CHARGE_ACTIVATE_ACCOUNT, $msg, $newId);

                            $user = new User_model();
                            $user->sendToAutoPool($newId); // Auto pool setup

                            // Credit direct income
                            $sp = new User_model($other->spil_id);
                            $sp->credit($app->directIncome, Dashboard_model::INCOME_SPONSOR, 'Account Topup', $newId);

                            // Check and add to club members
                            $user->checkAndAddToClubMembers($other->spil_id);
                        }
                        $api->setOK("Account activated successfully");
                    } else {
                        $api->setError('You have In-sufficient funds');
                    }
                }
                break;

            case 'retopup':
                $data = $this->request->getJSON(true);
                $us = new User_model(user_id());

                $app = new AppConfig();
                if ($api->checkINPUT(['qty', 'amount'], $data)) {
                    $qty = $data['qty'];
                    $amt = $data['amount'];
                    $wallet_id = $data['wallet'];
                    $jamt = $app->joiningAmount;
                    $bal = 0;
                    if ($wallet_id == FUND_BALANCE) {
                        $bal = $us->getFundBalance();
                    } else if ($wallet_id == WALLET_BALANCE) {
                        $bal = $us->getWalletBalance();
                    }

                    if ($bal >= $amt) {
                        for ($i = 1; $i <= $qty; $i++) {
                            $newId = $us->createDuplicateId(user_id());

                            $msg = "Activation of " . $newId;
                            if ($wallet_id == FUND_BALANCE)
                                $us->debit($jamt, Dashboard_model::CHARGE_ACTIVATE_ACCOUNT, $msg, $newId);
                            else
                                $us->debitWallet($jamt, Dashboard_model::CHARGE_ACTIVATE_ACCOUNT, $msg, $newId);

                            $user = new User_model();
                            $user->sendToAutoPool($newId); // Auto pool setup

                            // Credit direct income
                            $me = User_model::create(user_id());
                            $sp = new User_model($me->spil_id);
                            $sp->credit($app->directIncome, Dashboard_model::INCOME_SPONSOR, 'Account Topup', $newId);

                            // Check and add to club members
                            $user->checkAndAddToClubMembers($me->spil_id);
                        }
                        $api->setOK("Account activated successfully");
                    } else {
                        $api->setError('You have In-sufficient funds');
                    }
                }
                break;

            case 'remove-withdraw':
                $dashboard = new Dashboard_model();
                $data = $this->request->getJSON(true);
                if ($api->checkINPUT(['req_id'], $data)) {
                    $id = $data['req_id'];
                    $dashboard->update_withdraw_request($id, Dashboard_model::WITHDRAW_CANCELLED);
                    $api->setOK("Request deleted successfully");
                }
                break;
            case 'know-withdraw-limit':
                $data = $this->request->getJSON(true);
                if ($api->checkINPUT(['user_id'], $data)) {
                    $user_id = $data['user_id'];
                    $limit = $user->getWithdrawLimit($user_id);
                    $api->setData($limit);
                    $api->setOK();
                }
                break;
            case 'withdraw':
                $dashboard = new Dashboard_model();
                $min_limit = $app->minWithdrawLimit;
                $data = $this->request->getJSON(true);
                // $data = [
                //     'user_id' => 2,
                //     'amount' => 20,
                //     'wallet_adrs' => 'kk'
                // ];
                if ($api->checkINPUT(['user_id', 'amount'], $data)) {
                    $user_id = $data['user_id'];
                    $amount = $data['amount'];
                    $balance = $user->getFundBalance($user_id);
                    if ($amount <= $balance) {
                        if ($amount < $min_limit) {
                            $api->setError("Min withdrawal is " . $min_limit);
                        } else {
                            // $dashboard->create_withdraw_request($user_id, $amount, $type, $adrs);
                            $api->setOK("Withdrawal request submitted successfully");
                        }
                    } else {
                        $api->setError("You do not have sufficient balance.");
                    }
                }
                break;

            case 'add-funds':
                $data = $this->request->getJSON(true);
                if ($api->checkINPUT(['amount', 'user_id'], $data)) {

                    $amount = $data['amount'];
                    $user_id = $data['user_id'];

                    $sv = [];
                    $sv['amount'] = $amount;
                    $sv['user_id'] = $user_id;
                    $sv['order_status'] = 0;
                    $sv['payment_status'] = 0;
                    $sv['confirmation'] = 0;
                    $sv['pay_type'] = 'USDT';
                    $sv['created'] = date("Y-m-d H:i:s");
                    $sv['updated'] = date("Y-m-d H:i:s");
                    $sv['txnid'] = '';
                    $sv['amount_sendto'] = '';
                    $sv['status_url'] = '';
                    $sv['payment_address'] = '';
                    $sv['qrcode_url'] = '';
                    $sv['pay_error'] = '';
                    $db->table('payorder')->insert($sv);
                    $orderid = $db->insertID();


                    $system = 'tron_trc20';
                    $currency = 'USDT';
                    $comment = 'Membership Free';

                    $paykassa = new PayKassaSCI(
                        AppConfig::PAYKASSA_MRECHANT_ID,
                        AppConfig::PAYKASSA_MERCHANT_PASSWORD,
                        FALSE
                    );

                    $system_id = [
                        "tron_trc20" => 30,
                        "binancesmartchain_bep20" => 31,
                        "ethereum_erc20" => 32,
                    ];

                    $res = $paykassa->sci_create_order_get_data(
                        $amount,    // required parameter the payment amount example: 1.0433
                        $currency,  // mandatory parameter, currency, example: BTC
                        $orderid,  // mandatory parameter, the unique numeric identifier of the payment in your system, example: 150800
                        $comment,   // mandatory parameter, text commentary of payment example: service Order #150800
                        $system_id[$system] // a required parameter, for example: 12 - Ethereum
                    );

                    $msg = "You IP is not whitelisted. Please fix and try again.";

                    if ($res['error']) {        // $res['error'] - true if the error
                        $msg = $res['message'];   // $res['message'] - the text of the error message
                        // actions in case of an error

                        $sb = [];
                        $sb['pay_error'] = $msg;
                        $db->table("payorder")->update($sb, ['id' => $orderid]);
                        $api->setError($msg);
                    } else {
                        $invoice = $res['data']['invoice'];     // The operation number in the system Paykassa.pro
                        $order_id = $res['data']['order_id'];   // The order in the merchant
                        $wallet = $res['data']['wallet'];       // Address for payment
                        $amount = $res['data']['amount'];       // The amount to payment may change if the commission is transferred to the client
                        $system = $res['data']['system'];       // A system in which the billed
                        $url = $res['data']['url'];             // The link to proceed for payment
                        $tag = $res['data']['tag'];             // Tag to indicate the translation to ripple

                        $msg = 'Send funds to this address ' . $wallet . (!empty($tag) ? ' Tag: ' . $tag : '') . ' Balance will be updated automatically.';

                        $sb = [];
                        $sb['status_url'] = $url;
                        $sb['payment_address'] = $wallet;
                        $sb['txnid'] = $invoice;
                        $db->table("payorder")->update($sb, ['id' => $orderid]);
                        $api->setOK($msg);
                        $api->setData($res);
                    }
                }
                break;
            case 'topup-history':
                $data = $this->request->getJSON(true);
                if ($api->checkINPUT(['user_id'], $data)) {
                    $user_id = $data['user_id'];
                    $list = $db->table("transaction")->orderBy('id', 'DESC')->getWhere(['user_id' => $user_id, 'notes' => Dashboard_model::CHARGE_ACTIVATE_ACCOUNT, 'cr_dr' => 'dr'])->getResult();
                    $items = [];
                    foreach ($list as $item) {
                        $nm = $db->table("users")->getWhere(['id' => $item->ref_id])->getRow();
                        $item->name = $nm->first_name . ' ' . $nm->last_name;
                        $item->username = $nm->username;
                        $items[] = $item;
                    }
                    $api->setOK();
                    $api->setData($items);
                }
                break;
            case 'get-balance-info':
                $data = $this->request->getJSON(true);
                $api->setData($data);

                if ($api->checkINPUT(['user_id'], $data)) {
                    $ab = new User_model($data['user_id']);
                    $ob = [
                        'balance' => $ab->getFundBalance(),
                        'withdraw_limit' => $ab->getWithdrawLimit($data['user_id']),
                        'activation_wallet' => $ab->getWalletBalance()
                    ];
                    $api->setOK();
                    $api->setData($ob);
                }
                break;
            case 'default':
        }
        return $this->respond($api);
    }

    function admin($m = null)
    {
        $db = db_connect();
        $api = new RestApi();
        $session = session();
        if ($session->userid == null) {
            $api->setError("You must login to view the page");
            return $this->respond($api);
        }
        switch ($m) {
            case 'userinfo':
                if ($api->check(array('username'))) {
                    $username = $_GET['username'];
                    $builder = $db->table('users');
                    $us = $builder->getWhere(array('username' => $username))->getRow();
                    if (is_object($us)) {
                        $api->setOK();
                        $api->setData($us);
                    } else {
                        $api->setError();
                        $api->setMessage("Opps!! Invalid userid");
                    }
                } else {
                    $api->missing();
                }
                break;
            case 'drcr':
                if ($api->check(array('username', 'dr', 'amount'))) {
                    $username = $_GET['username'];
                    $dr = $_GET['dr'];
                    $amount = $_GET['amount'];
                    $msg = $_GET['msg'];
                    $user_id = userid2id($username);
                    $us = new User_model($user_id);
                    if ($dr == 'dr') {
                        $us->debit($amount, Dashboard_model::INCOME_FUND_CREDIT, $msg);
                    } else {
                        $us->credit($amount, Dashboard_model::INCOME_FUND_CREDIT, $msg);
                    }

                    $api->setOK();
                    $api->setMessage("Payment updated");
                } else {
                    $api->missing();
                }
                break;

            case 'default':
        }
        return $this->respond($api);
    }
}
