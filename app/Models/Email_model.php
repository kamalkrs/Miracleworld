<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\AppConfig;

class Email_model extends Model
{
    var $db;

    public $emailSubject;
    public $emailBody;
    public $emailTo;

    function __construct()
    {
        $this->db = db_connect();
    }

    // Not is being used
    function withdrawEmailUser($user_id, $amount)
    {
        $cnf = new AppConfig();
        $builder = $this->db->table('users');
        $user = $builder->getWhere(['id' => $user_id])->getRow();
        $dtimg = date("d M Y h:i A");

        $html = "Dear " . $user->first_name . "<br />You have submitted a withdrawal request as per below details. <br /><b>Amount</b>: USD $amount <br /><b>Date & Time</b>: $dtimg <br />You will get the confirmation by email once we process your request. <br /> Regards<br />" . $cnf->siteName;

        return $html;
    }

    // Not in use
    function withdrawEmailAdmin($user_id, $amount)
    {
        $cnf = new AppConfig();
        $builder = $this->db->table('users');
        $user = $builder->getWhere(['id' => $user_id])->getRow();
        $dtimg = date("d M Y h:i A");

        $html = "Dear Admin <br />" . $user->first_name . "has submitted a withdrawal request as per below details. <br /><b>Amount</b>: USD $amount <br /><b>Date & Time</b>: $dtimg <br />Kindly process the payment. <br /> Regards<br />" . $cnf->siteName;

        return $html;
    }

    function setForgetPassword($user_id)
    {
        $cnf = new AppConfig();
        $builder = $this->db->table('users');
        $user = $builder->getWhere(['id' => $user_id])->getRow();

        $link = site_url('login');
        $un = $user->username;
        $ps = $user->passwd;

        $msg = "Dear " . $user->first_name . "<br />As per your request, we are sending you password for your account. <br /> Login at: $link <br />Username: $un <br />Password: $ps <br />Thanks<br />" . $cnf->siteName;

        $msg .= $this->signature();
        $this->emailBody  = $msg;
        $this->emailSubject = "Forget Password";
        $this->emailTo = $user->email_id;
        return $this;
    }

    function signupOTP($user_id)
    {
        $cnf = new AppConfig();
        $builder = $this->db->table('users');
        $user = $builder->getWhere(['id' => $user_id])->getRow();
        $link = site_url('email-verification/?em=' . $user->email_id . '&code=' . $user->otp_code);

        $link = anchor($link);

        $msg = "Dear " . $user->first_name . "<br /><br />Welcome to " . $cnf->siteName . " <br />";
        $msg .= "OTP code for email verification is";
        $msg .= "<h3>" . $user->otp_code . "</h3>";
        $msg .= "Alternatively, You can click on below link to verify your email. <br />$link <br />";
        $msg .= "<br />Regards<br />" . $cnf->siteName;

        $msg .= $this->signature();

        $this->emailBody  = $msg;
        $this->emailSubject = "Signup OTP :: " . $cnf->siteName;
        $this->emailTo = $user->email_id;
        return $this;
    }

    function setSignupEmail($user_id)
    {
        $cnf = new AppConfig();
        $builder = $this->db->table('users');
        $user = $builder->getWhere(['id' => $user_id])->getRow();

        $link = site_url('login');
        $un = $user->username;
        $ps = $user->passwd;

        $msg = "Dear " . $user->first_name . "<br /><br />Welcome to " . $cnf->siteName . " <br />";
        $msg .= "Your account has been created with following login details <br /><br /> Login: $link <br />Username: $un<br />Password: $ps";

        $msg .= $this->signature();
        $this->emailBody  = $msg;
        $this->emailSubject = "Signup Account Confirmation";
        $this->emailTo = $user->email_id;
        return $this;
    }

    function setWithdrawalOTPEmail($id)
    {
        $cnf = new AppConfig();
        $order = $this->db->table("withdraw")->getWhere(['id' => $id])->getRow();
        $user = $this->db->table('users')->getWhere(['id' => $order->user_id])->getRow();
        $fn = $user->first_name . ' ' . $user->last_name;

        $msg = 'Dear ' . $fn;
        $msg .= "<br />Your OTP for withdrawal of USD " . $order->amount . " is: <big><b>" . $order->otp_code . "</b></big>";

        $msg .= $this->signature();
        $this->emailBody  = $msg;
        $this->emailSubject = "Withdrawal OTP Verification";
        $this->emailTo = $user->email_id;
        return $this;
    }

    function setAccountActivationEmail($userId)
    {
        $cnf = new AppConfig();
        $user = $this->db->table('users')->getWhere(['id' => $userId])->getRow();
        $fn = $user->first_name . ' ' . $user->last_name;

        $msg = 'Dear ' . $fn;
        $msg .= "Congratulation!!, You account with " . $cnf->siteName . " has been activated with amount of " . $cnf->joiningAmount .  "<br />";

        $msg .= $this->signature();
        $this->emailBody  = $msg;
        $this->emailSubject = "Account Activated - " . $cnf->siteName;
        $this->emailTo = $user->email_id;
        return $this;
    }

    private function signature()
    {
        $cnf = new AppConfig();
        $html = "<br />Thanks & Regards <br /><br />" . $cnf->siteName;
        $html .= "<br />Support: " . $cnf->contactEmail;
        return $html;
    }

    public function sendEmail($emailTo = null)
    {
        if ($emailTo == null) {
            $emailTo  = $this->emailTo;
        }
        $cnf = new AppConfig();
        $mailConfig = [];
        $mailConfig['mailType'] = 'html';
        $email = \Config\Services::email();

        $email->initialize($mailConfig);
        $email->setFrom($cnf->emailFrom, $cnf->emailFromName);
        $email->setTo($emailTo);
        $email->setSubject($this->emailSubject);
        $email->setMessage($this->emailBody);
        $email->send();
    }
}
