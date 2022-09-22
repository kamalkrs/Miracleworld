<?php

namespace App\Models;

use CodeIgniter\Model;

class Dashboard_model extends Model
{

    const INCOME_LEVEL = 'level';
    const INCOME_ROI = 'roi';
    const INCOME_FUND_TRANSER = 'fund_transfer';
    const INCOME_FUND_CREDIT = 'add-fund';
    const INCOME_REWARD = 'reward';
    const INCOME_MATCHING = 'match';
    const CHARGE_TDS = 'tds';
    const CHARGE_ADMIN = 'admin';
    const CHARGE_UPGRADE = 'upgrade';
    const CHARGE_ACTIVATE_ACCOUNT = 'topup';
    const CHARGE_RETOPUP = 're-topup';
    const INCOME_SPONSOR = 'sponsor';
    const INCOME_WALLET = "wallet";
    const WITHDRAW = "withdraw";
    const PAYOUT = 'payout';
    const INCOME_CLUB = 'club';
    const INCOME_POOL = 'pool';
    const INCOME_REBIRTH = 'rebirth';

    const WITHDRAW_APPROVED = 1;
    const WITHDRAW_DECLINED = 2;
    const WITHDRAW_CANCELLED = 3;
    const WITHDRAW_PENDING = 0;
    function __construct()
    {
        parent::__construct();
    }

    function create_withdraw_request($user_id, $amount, $type, $adrs)
    {

        $db = db_connect();
        $builder = $db->table("withdraw");
        $data = array();
        $data['user_id'] = $user_id;
        $data['amount'] = $amount;
        $data['created'] = date("Y-m-d H:i:s");
        $data['updated'] = date("Y-m-d H:i:s");
        $data['status'] = 0;
        $data['comments'] = null;
        $data['wallet_type'] = $type;
        $data['wallet_adrs'] = $adrs;
        $builder->insert($data);

        $us = new User_model($user_id);
        $us->debit($amount, Dashboard_model::WITHDRAW, "Withdraw by user", $db->insertID());
    }

    function update_withdraw_request($req_id, $action = 1)
    {
        $db = db_connect();
        $builder = $db->table('withdraw');
        $order  = $builder->getWhere(['id' => $req_id])->getRow();

        $user = new User_model($order->user_id);
        switch ($action) {
            case '1':
                $builder->update(array('status' => self::WITHDRAW_APPROVED, 'updated' => date("Y-m-d H:i")), array('id' => $req_id));
                break;
            case '2':
                $builder->update(array('status' => self::WITHDRAW_DECLINED, 'updated' => date("Y-m-d H:i"), 'comments' => 'Rejected by admin'), array('id' => $req_id));

                $user->credit($order->amount, Dashboard_model::WITHDRAW, 'Withdraw Declined by admin', $req_id);
                break;
            case '3':
                $builder = $db->table('withdraw');
                $builder->update(array('status' => self::WITHDRAW_CANCELLED, 'updated' => date("Y-m-d H:i"), 'comments' => 'Cancelled by user'), array('id' => $req_id));

                $user->credit($order->amount, Dashboard_model::WITHDRAW, 'Withdraw Cancelled by user', $req_id);
                break;

            case '0':
                $builder = $db->table('withdraw');
                $builder->update(array('status' => self::WITHDRAW_PENDING, 'updated' => date("Y-m-d H:i")), array('id' => $req_id));
                break;
        }
    }
}
