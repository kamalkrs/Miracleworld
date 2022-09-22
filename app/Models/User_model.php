<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\AppConfig;
use stdClass;

class User_model extends Model
{
    public $db;
    private $user_id;
    public $userData = null;
    public function __construct($user_id = null)
    {
        parent::__construct();
        $this->table   = 'users';
        $this->db = \Config\Database::connect();
        if ($user_id !== null) {
            $this->user_id = $user_id;
        }
    }

    public static function create($user_id)
    {
        $um = new User_model();
        $um->setUserdata($user_id);
        return $um;
    }

    function setUserdata($user_id)
    {
        $this->user_id = $user_id;
        $this->userData = $this->db->table("users")->getWhere(['id' => $user_id])->getRow();
    }

    function __get($key)
    {
        if ($this->userData == null)
            $this->setUserdata($this->user_id);

        return $this->userData->$key;
    }

    function get_user($id)
    {
        $us = $this->db->table("users")->getWhere(['id' => $id])->getRow();
        return $us;
    }

    public function state_dropdown()
    {
        $builder = $this->db->table('states');
        $builder->select("id, state_name");
        $builder->orderBy("state_name", "ASC");
        $rest = $builder->get()->getResult();
        $data = array();
        foreach ($rest as $ob) {
            $data[$ob->id] = $ob->state_name;
        }
        return $data;
    }

    public function get_members($sponsor_id, $members = array())
    {
        $db = $this->db->table('users');
        $db->getWhere('sponsor_id', $sponsor_id);
        $rest = $db->getWhere("users")->getResult();
        if (count($rest) > 0) {
            $ob = new stdClass();
            foreach ($rest as $r) {
                if ($r->position == 1) {
                    $ob->left = $r->user_id;
                } else {
                    $ob->right = $r->user_id;
                }
                $members[$sponsor_id] = $ob;
                $members              = $this->get_members($r->user_id, $members);
            }
        }
        return $members;
    }

    public function totalIncome()
    {
        $user_id = $this->user_id;
        $builder = $this->db->table('transaction');
        $builder->whereIn('notes', [Dashboard_model::INCOME_SPONSOR, Dashboard_model::INCOME_CLUB, Dashboard_model::INCOME_POOL, Dashboard_model::INCOME_REWARD]);
        $cr = $builder->select('sum(amount) as c')->where(array('cr_dr' => 'cr', 'user_id' => $user_id))->get()->getRow()->c;
        return usd_rs($cr);
    }

    public function totalPaid()
    {
        $user_id = $this->user_id;
        $builder = $this->db->table('transaction');
        $cr = $builder->select('sum(amount) as c')->where(['cr_dr' => 'dr', 'user_id' => $user_id, 'notes' => Dashboard_model::WITHDRAW])->get()->getRow()->c;
        return usd_rs($cr);
    }

    public function getIncomeByType($user_id, $type = Dashboard_model::INCOME_LEVEL)
    {
        $builder = $this->db->table('transaction');
        $cr = $builder->select('sum(amount) as c')->where(array('user_id' => $user_id, 'cr_dr' => 'cr', 'notes' => $type))->get()->getRow()->c;
        $dr = $builder->select('sum(amount) as c')->where(array('user_id' => $user_id, 'cr_dr' => 'dr', 'notes' => $type))->get()->getRow()->c;
        return usd_rs($cr - $dr);
    }

    function getLockedFund($user_id)
    {
        $builder = $this->db->table("withdraw");
        $row = $builder->getWhere(['status' => 0, 'user_id' => $user_id])->getRow();
        if (is_object($row)) {
            return $row->amount;
        } else {
            return 0;
        }
    }

    function getFundBalance()
    {
        $user_id = $this->user_id;
        $builder = $this->db->table('transaction');
        $cr = (float) $builder->select("SUM(amount) as c")->getWhere(array('user_id' => $user_id, 'cr_dr' => 'cr'))->getRow()->c;

        $dr = (float) $builder->select("SUM(amount) as c")->getWhere(array('user_id' => $user_id, 'cr_dr' => 'dr'))->getRow()->c;
        $sum = $cr  - $dr;
        return usd_rs($sum);
    }

    function getWalletBalance()
    {
        $user_id = $this->user_id;
        $builder = $this->db->table('wallet');
        $cr = (float) $builder->select("SUM(amount) as c")->getWhere(array('user_id' => $user_id, 'cr_dr' => 'cr'))->getRow()->c;

        $dr = (float) $builder->select("SUM(amount) as c")->getWhere(array('user_id' => $user_id, 'cr_dr' => 'dr'))->getRow()->c;
        $sum = $cr  - $dr;
        if ($sum < 0) {
            $sum = 0;
        }
        return usd_rs($sum);
    }

    public function getBalance($type = Dashboard_model::INCOME_LEVEL, $user_id = null)
    {
        $user_id = $user_id == null ? $this->user_id : $user_id;

        $builder = $this->db->table('transaction');
        $cr = $builder->select('sum(amount) as c')->where(array('user_id' => $this->user_id, 'cr_dr' => 'cr', 'notes' => $type))->get()->getRow()->c;
        $dr = $builder->select('sum(amount) as c')->where(array('user_id' => $this->user_id, 'cr_dr' => 'dr', 'notes' => $type))->get()->getRow()->c;
        return usd_rs($cr - $dr);
    }

    public function currentIncome()
    {
        $user_id = $this->user_id;
        $bl = $this->totalIncome($user_id);
        $lp = $this->totalPaid($user_id);
        $sum = $bl - $lp;

        return $sum;
    }

    public function getDownloadLineIds($user_id, $level_id = 1, $ids = array())
    {
        $builder = $this->db->table('users');
        $rest = $builder->select('id')->getWhere(array('spil_id' => $user_id))->getResult();
        foreach ($rest as $obr) {
            $ids[] = $obr->id;
            $ids   = $this->getDownloadLineIds($obr->id, $level_id, $ids);
        }
        return $ids;
    }

    public function getAutoSponsorExtremeLeftRight($sponsor_id, $position = 1)
    {
        $builder = $this->db->table("level_manager");
        $sp = $builder->getWhere(array('sponsor_id' => $sponsor_id, 'position' => $position, 'level_id' => 1))->getRow();
        $ob             = new stdClass();
        $ob->position   = $position;
        $ob->sponsor_id = 0;
        if (is_object($sp)) {
            $ob = $this->getAutoSponsorExtremeLeftRight($sp->user_id, $position);
        } else {
            $ob->sponsor_id = $sponsor_id;
        }
        return $ob;
    }

    public function getDirectChilds($user_id)
    {

        $builder = $this->db->table('users');
        $builder->select('id');
        $rest     = $builder->getWhere(['spil_id' => $user_id])->getResult();
        $ids = [];
        foreach ($rest as $row) {
            $ids[] = $row->id;
        }
        return $ids;
    }

    function getMatrixDirectChilds($parent_id, $level_id = 1)
    {
        $db = db_connect();
        $builder = $db->table("level_manager");
        if ($parent_id == 0) return array();
        $childs = $builder->getWhere(['sponsor_id' => $parent_id, 'level_id' => $level_id])->getResult();
        $ids = array();
        $ids[0] = null;
        $ids[1] = null;
        foreach ($childs as $ob) {
            if ($ob->position == 1) {
                $ids[0] = $ob->user_id;
            } else {
                $ids[1] = $ob->user_id;
            }
        }
        return $ids;
    }


    function level_members($user_id, $depth = 1, $level_id = 1)
    {
        $data = [];
        for ($i = 0; $i <= 15; $i++) {
            $data[] = [];
        }
        $data[0] = [$user_id];
        for ($lvl = 1; $lvl <= 15; $lvl++) {
            $ar = [];
            foreach ($data[$lvl - 1] as $id) {
                if ($id == 0 || $id == null) continue;
                $ids = $this->getMatrixDirectChilds($id, $level_id);
                $newids = [];
                foreach ($ids as $tid) {
                    if ($tid == null) continue;
                    $newids[] = $tid;
                }
                $ar = array_merge($ar, $newids);
            }
            $data[$lvl] = $ar;
            $ar = [];
        }
        return $data[$depth];
    }

    function getMatrixDownlineIds($parent_id, $level_id = 1)
    {
        $data = array();
        $ids = $this->getMatrixDirectChilds($parent_id, $level_id);
        for ($i = 0; $i < 15; $i++) {
            $data[] = null;
        }
        $data[0] = $parent_id;
        $data[1] = isset($ids[0]) ? $ids[0] : null;
        $data[2] = isset($ids[1]) ? $ids[1] : null;

        if (isset($data[1])) {
            $ids = $this->getMatrixDirectChilds($data[1], $level_id);
            $data[3] = isset($ids[0]) ? $ids[0] : null;
            $data[4] = isset($ids[1]) ? $ids[1] : null;
        }
        if (isset($data[2])) {
            $ids = $this->getMatrixDirectChilds($data[2], $level_id);
            $data[5] = isset($ids[0]) ? $ids[0] : null;
            $data[6] = isset($ids[1]) ? $ids[1] : null;
        }
        if (isset($data[3])) {
            $ids = $this->getMatrixDirectChilds($data[3], $level_id);
            $data[7] = isset($ids[0]) ? $ids[0] : null;
            $data[8] = isset($ids[1]) ? $ids[1] : null;
        }
        if (isset($data[4])) {
            $ids = $this->getMatrixDirectChilds($data[4], $level_id);
            $data[9] = isset($ids[0]) ? $ids[0] : null;
            $data[10] = isset($ids[1]) ? $ids[1] : null;
        }
        if (isset($data[5])) {
            $ids = $this->getMatrixDirectChilds($data[5], $level_id);
            $data[11] = isset($ids[0]) ? $ids[0] : null;
            $data[12] = isset($ids[1]) ? $ids[1] : null;
        }
        if (isset($data[6])) {
            $ids = $this->getMatrixDirectChilds($data[6], $level_id);
            $data[13] = isset($ids[0]) ? $ids[0] : null;
            $data[14] = isset($ids[1]) ? $ids[1] : null;
        }
        return $data;
    }

    public static function  getRandomUserId()
    {
        $db = db_connect();
        $builder = $db->table('users');
        $id = rand(100000, 999999);
        $chk = $builder->getWhere(array('id' => $id))->getRow();
        if (!is_object($chk)) {
            return $id;
        } else {
            return User_model::getRandomUserId();
        }
    }

    // Newly reg id
    function doAllPaymentCalculations($new_id)
    {
        $this->setSponsorIncome($new_id);
    }

    public function setSponsorIncome($user_id)
    {
        $builder = $this->db->table('users');
        $user = $builder->getWhere(['id' => $user_id])->getRow();
        $app = new AppConfig();

        if ($user->spil_id > 0) {
            $sp = $builder->getWhere(['id' => $user->spil_id])->getRow();
            if ($sp->ac_status == 1) {
                $sp_amount = $app->directIncome;
                if ($sp_amount > 0) {
                    $us = new User_model($user->spil_id);
                    $us->credit($sp_amount, Dashboard_model::INCOME_SPONSOR, "Plan activation of " . $user_id, $user_id);
                }
            }
        }
    }

    public function setLevelIncome($user_id)
    {
        $app = new AppConfig();
        $arr_ids = $this->getSpilTree($user_id);
        $ids = array_splice($arr_ids, 0, 5);

        $us = $this->get_user($user_id);
        $rates = $app->levelIncomes;

        $builder = $this->db->table("users");
        foreach ($ids as $index => $id) {
            $childs = (float)$builder->select("COUNT(*) as c")->getWhere(['spil_id' => $id])->getRow()->c;
            $needed = ($index + 1);
            if ($childs >= $needed) {
                $p = new User_model($id);
                if ($p->ac_status == 1) {
                    $amt = $us->plan_total * $rates[$index] / 100;
                    $p->credit($amt, Dashboard_model::INCOME_LEVEL, "Account Created", $user_id);
                }
            }
        }
    }

    function getSponsorTree($user_id, $ids = array(), $level_id = 1)
    {
        $builder = $this->db->table("level_manager");
        $us = $builder->getWhere(['user_id' => $user_id, 'level_id' => $level_id])->getRow();
        if (!is_object($us)) {
            return $ids;
        } else {
            if ($us->sponsor_id == 0) {
                return $ids;
            } else {
                $ids[] = $us->sponsor_id;
                return $this->getSponsorTree($us->sponsor_id, $ids, $level_id);
            }
        }
    }

    function getSpilTree($user_id, $ids = [])
    {
        $us = $this->get_user($user_id);
        if (!is_object($us)) {
            return $ids;
        } else {
            if ($us->spil_id == 0) {
                return $ids;
            } else {
                $ids[] = $us->spil_id;
                return $this->getSpilTree($us->spil_id, $ids);
            }
        }
    }

    function saveUserdata($user_id, $meta_name, $meta_value)
    {
        $builder = $this->db->table("users_meta");
        $chk = $builder->limit(1)->getWhere(array('meta_name' => $meta_name, 'user_id' => $user_id))->getRow();
        if (is_object($chk)) {
            $sv = [];
            $sv['meta_value'] = $meta_value;
            $builder->update($sv, array('user_id' => $user_id, 'meta_name' => $meta_name));
        } else {
            $sv = [];
            $sv['user_id'] = $user_id;
            $sv['meta_name'] = $meta_name;
            $sv['meta_value'] = $meta_value;
            $builder->insert($sv);
        }
    }

    function getUserdata($user_id, $meta_name)
    {
        $builder = $this->db->table("users_meta");
        $chk = $builder->limit(1)->getWhere(array('meta_name' => $meta_name, 'user_id' => $user_id))->getRow();
        if (is_object($chk)) {
            return $chk->meta_value;
        } else {
            return NULL;
        }
    }

    function todayEarned()
    {
        $builder = $this->db->table("transaction");
        $builder->select("SUM(amount) as c");
        $builder->whereIn('notes', [Dashboard_model::INCOME_CLUB, Dashboard_model::INCOME_SPONSOR, Dashboard_model::INCOME_POOL]);
        $builder->where("DATE(created) = CURDATE()");
        $bal = (float)$builder->where('user_id', $this->user_id)->get()->getRow()->c;
        return $bal;
    }

    function credit($amount, $notes, $comments, $ref_id = 0, $level = 0)
    {
        $builder = $this->db->table("transaction");
        $bal = $this->getFundBalance($this->user_id);
        $m = array();
        $m['user_id'] = $this->user_id;
        $m['cr_dr'] = 'cr';
        $m['amount'] = $amount;
        $m['notes'] = $notes;
        $m['created'] = date("Y-m-d H:i:s");
        $m['ref_id'] = $ref_id;
        $m['paylevel'] = $level;
        $m['comments'] = $comments;
        $m['total_bal'] = $bal + $amount;
        $builder->insert($m);
    }

    function debit($amount, $notes, $comments, $ref_id = 0)
    {
        $builder = $this->db->table("transaction");
        $bal = $this->getFundBalance($this->user_id);
        $m = array();
        $m['user_id'] = $this->user_id;
        $m['cr_dr'] = 'dr';
        $m['amount'] = $amount;
        $m['notes'] = $notes;
        $m['created'] = date("Y-m-d H:i:s");
        $m['ref_id'] = $ref_id;
        $m['paylevel'] = 0;
        $m['comments'] = $comments;
        $m['total_bal'] = $bal - $amount;
        $builder->insert($m);
    }

    function getTree($user_id)
    {
        $builder = $this->db->table("users");

        $ids = array();
        $ob = new stdClass();
        $ob->id = null;
        $ob->name = '-';
        $ob->image = 'd1.png';
        $ob->username = '-';
        $ob->mobile = '-';
        $ob->designation = "-";
        $ob->doj = "-";
        $ob->dot = "-";
        $ob->sponsor_id = '-';
        $ob->sponsor_name = '-';
        $ob->plan = '-';
        $ob->matching = '-';
        $ob->placement_id = '-';

        $data = array();

        $ids = $this->getMatrixDownlineIds($user_id);
        foreach ($ids as $id) {
            if ($id == null) {
                $data[] = $ob;
            } else {
                $u = $builder->getWhere(['id' => $id], 1)->getRow();
                $su = $builder->getWhere(["id" => $u->spil_id], 1)->getRow();
                if ($u->sponsor_id == 0) {
                    $sname = null;
                } else {
                    $sname = $su->first_name . ' ' . $su->last_name;
                }
                $p = new stdClass();
                $p->id = $id;
                $p->name = $u->first_name . ' ' . $u->last_name;
                $p->username = $u->username;
                $p->mobile = $u->mobile;
                $p->designation = "Distributor";
                $p->doj = date("d M, Y h:i:s A", strtotime($u->join_date));
                $p->image = "user_inactive.png";
                if ($u->ac_status == 1) {
                    $p->image = 'user_active.png';
                }
                if ($u->ac_status == 1) {
                    $p->dot = date("d M, Y h:i:s A", strtotime($u->ac_active_date));
                } else {
                    $p->dot = '-';
                }
                $p->sponsor_id = id2userid($u->spil_id);
                $p->sponsor_name = $sname;
                $p->plan = $u->plan_total;
                $p->matching = '-';
                $p->placement_id = id2userid($u->sponsor_id);
                $data[] = $p;
            }
        }
        return $data;
    }

    function doMatchingCalculations($calDate, $slot = 1)
    {
        $users = $this->db->table('users');
        $users->select("id, plan_total as plan, payout");
        $users->where("DATE(ac_active_date) = '$calDate'");
        $users->where(array("ac_status" => 1));
        $users->orderBy("ac_active_date", "ASC");
        $list = $users->get()->getResult();

        foreach ($list as $us) {
            $ptr = $this->getSponsorTree($us->id);
            foreach ($ptr as $user_id) {
                set_time_limit(120);
                $me = $users->limit(1)->getWhere(['id' => $user_id])->getRow();

                $ob = $this->getPairMatching($user_id, $calDate, $slot);
                if ($ob->left == 0 && $ob->right == 0) continue;

                // Last day carry report
                $report = $this->db->table("report");
                $lcover = 0;
                $rcover = 0;
                $lastDayReport = $report->orderBy('id', 'DESC')->limit(1)->where(['user_id' => $user_id])->get()->getRow();

                if (is_object($lastDayReport)) {
                    $lcover = $lastDayReport->left_carry;
                    $rcover = $lastDayReport->right_carry;
                }

                $lc = $ob->left + $lcover;
                $rc = $ob->right + $rcover;

                // get first matching
                $level_manager = $this->db->table('level_manager');
                $lm = $level_manager->getWhere(['user_id' => $user_id])->getRow();

                $report = $this->db->table("report");
                $chkFlag = $report->orderBy('id', 'DESC')->getWhere(['user_id' => $user_id, 'matching >' => 0])->getRow();
                if (!is_object($chkFlag)) {
                    if ($lm->first == 1) $lc = $lc - 0;
                    if ($lm->first == 2) $rc = $rc - 0;
                    if ($lm->first == 0) $me->payout = 0; // No payout
                }

                $lcr = $rcr = 0;

                $save = array();
                $save['user_id'] = $user_id;
                $save['left_count'] = $ob->left;
                $save['right_count'] = $ob->right;
                $save['left_carry'] = $lc;
                $save['right_carry'] = $rc;
                $save['matching'] = 0;
                $save['laps'] = 0;
                $save['plan_total'] = 0;
                // $save['created'] = date("Y-m-d H:i:s");
                $save['created'] = $calDate;
                $save['slot'] = $slot;

                if ($lc >= $rc) {
                    $rcr = 0;
                    $lcr = $lc - $rc;
                    $matching = $rc;
                } else {
                    $lcr = 0;
                    $rcr = $rc - $lc;
                    $matching = $lc;
                }

                $save['left_carry'] = $lcr;
                $save['right_carry'] = $rcr;

                $laps = 0;
                $save['matching'] = $matching;
                $save['laps'] = $laps;

                $report->orderBy("id", "DESC");
                $dpChk = $report->where(array('user_id' => $user_id, 'DATE(created)' => $calDate, 'slot' => $slot))->get()->getRow();
                if (!is_object($dpChk)) {
                    $report->insert($save);

                    $total = $matching * 0.05;
                    if ($total == 0) continue;

                    if ($total > $me->plan_total) {
                        $total = $me->plan_total;
                    }

                    // Update matching to users
                    $nm = new User_model($user_id);
                    $nm->credit($total, Dashboard_model::INCOME_MATCHING, 0, 0);
                }
            }
        }
    }

    function autoLeftRightSponsor()
    {
        $ob = new stdClass();
        $ob->sponsor_id = 0;
        $ob->position = 1;
        $builder = $this->db->table("level_manager");
        $ids = $builder->select('user_id')->orderBy('id', 'ASC')->get()->getResult();
        if (count($ids) > 0) {
            foreach ($ids as $u) {
                $childs = $builder->select('count(*) as c')->getWhere(['sponsor_id' => $u->user_id])->getRow()->c;
                if ($childs < 2) {
                    $ob->sponsor_id = $u->user_id;
                    $ob->position = $childs + 1;
                    break;
                }
            }
        } else {
            $ob->sponsor_id = '0-0';
            $ob->position = 1;
        }
        return $ob;
    }

    function retopupAccount($user_id)
    {
        $row = $this->db->table("level_manager")->limit(1)->orderBy('id', 'DESC')->getWhere(['master_id' => $user_id])->getRow();
        $this->sendToAutoPool($row->user_id);
    }

    public function sendToAutoPool($newId, $level = 0)
    {
        $us = $this->db->table("level_manager")->limit(1)->getWhere(['master_id' => $newId])->getRow();
        $pos = $this->autoLeftRightSponsor();

        if (!is_object($us)) {
            $sb = [];
            $sb['user_id'] = $newId . '-' . $level;
            $sb['master_id'] = $newId;
            $sb['level_id'] = $level;
            $sb['sponsor_id'] = $pos->sponsor_id;
            $sb['position'] = $pos->position;
            $sb['created'] = date("Y-m-d H:i:s");
            $sb['ac_active_date'] = date("Y-m-d H:i:s");
            $this->db->table("level_manager")->insert($sb);

            if ($pos->position == 2) {
                // check matching
                $master = $this->db->table("level_manager")->limit(1)->getWhere(['user_id' => $pos->sponsor_id])->getRow()->master_id;
                $us = User_model::create($master);
                $matching = $us->matching + 1;
                if ($matching % 2 == 0) {
                    $this->db->table("users")->update(['matching' => $matching], ['id' => $master]);
                    $this->sendToAutoPool($pos->sponsor_id);
                    $this->sendToAutoPool($pos->sponsor_id);
                } else {
                    $us->credit(10, Dashboard_model::INCOME_REBIRTH, "Joinig of " . $newId, $sb['sponsor_id']);
                    $this->db->table("users")->update(['matching' => $matching], ['id' => $master]);
                    $this->sendToAutoPool($pos->sponsor_id);
                }
            }
        } else {
            $level = $this->db->table("level_manager")->select("count(*) as c")->getWhere(['master_id' => $newId])->getRow()->c;

            $sb = [];
            $sb['user_id'] = $us->master_id . '-' . $level;
            $sb['master_id'] = $newId;
            $sb['level_id'] = $level;
            $sb['sponsor_id'] = $pos->sponsor_id;
            $sb['position'] = $pos->position;
            $sb['created'] = date("Y-m-d H:i:s");
            $sb['ac_active_date'] = date("Y-m-d H:i:s");
            $this->db->table("level_manager")->insert($sb);

            if ($pos->position == 2) {
                // check matching
                $master = $this->db->table("level_manager")->limit(1)->getWhere(['user_id' => $pos->sponsor_id])->getRow()->master_id;
                $us = User_model::create($master);
                $matching = $us->matching + 1;
                if ($matching % 2 == 0) {
                    $this->db->table("users")->update(['matching' => $matching], ['id' => $master]);
                    $this->sendToAutoPool($pos->sponsor_id);
                    $this->sendToAutoPool($pos->sponsor_id);
                } else {
                    $us->credit(10, Dashboard_model::INCOME_REBIRTH, "Rebirth of " . $newId, $sb['sponsor_id']);
                    $this->db->table("users")->update(['matching' => $matching], ['id' => $master]);
                    $this->sendToAutoPool($pos->sponsor_id);
                }
            }
        }
    }

    public function checkAndAddToClubMembers($spil_id)
    {
        $ob = $this->db->table("clubmembers")->getWhere(['user_id' => $spil_id, 'club_id' => 1])->getRow();
        if ($ob == null) {
            $users = $this->db->table("users")->getWhere(['spil_id' => $spil_id, 'ac_status' => 1])->getNumRows();
            if ($users >= 3) {
                $sb = [];
                $sb['user_id'] = $spil_id;
                $sb['club_id'] = 1;
                $sb['created'] = date("Y-m-d H:i:s");
                $this->db->table("clubmembers")->insert($sb);

                // Update user rank 
                $this->db->table("users")->update(['user_rank' => 1], ['id' => $spil_id]);

                $parent = $this->db->table("users")->limit(1)->getWhere(['id' => $spil_id])->getRow()->spil_id;
                $this->checkGoldClub($parent);
            }
        }
    }

    public function checkGoldClub($spil_id)
    {
        $ob = $this->db->table("clubmembers")->getWhere(['user_id' => $spil_id, 'club_id' => 2])->getRow();
        if ($ob == null) {
            $users = $this->db->table("users")->getWhere(['spil_id' => $spil_id, 'ac_status' => 1])->getNumRows();
            $ids = [];
            foreach ($users as $us) {
                $ids[] = $us->id;
            }
            if (count($ids) >= 6) {
                $silvers = $this->db->table("clubmembers")->whereIn('user_id', $ids)->get()->getResult();
                if ($silvers >= 3) {
                    $sb = [];
                    $sb['user_id'] = $spil_id;
                    $sb['club_id'] = 2;
                    $sb['created'] = date("Y-m-d H:i:s");
                    $this->db->table("clubmembers")->insert($sb);

                    // Update user rank 
                    $this->db->table("users")->update(['user_rank' => 2], ['id' => $spil_id]);

                    $parent = $this->db->table("users")->limit(1)->getWhere(['id' => $spil_id])->getRow()->spil_id;
                    $this->checkPlatinumClub($parent);
                }
            }
        }
    }

    public function checkPlatinumClub($spil_id)
    {
        $ob = $this->db->table("clubmembers")->getWhere(['user_id' => $spil_id, 'club_id' => 3])->getRow();
        if ($ob == null) {
            $users = $this->db->table("users")->getWhere(['spil_id' => $spil_id, 'ac_status' => 1])->getNumRows();
            $ids = [];
            foreach ($users as $us) {
                $ids[] = $us->id;
            }
            if (count($ids) >= 10) {
                $silvers = $this->db->table("clubmembers")->whereIn('user_id', $ids)->get()->getNumRows();
                if ($silvers >= 4) {
                    $sb = [];
                    $sb['user_id'] = $spil_id;
                    $sb['club_id'] = 3;
                    $sb['created'] = date("Y-m-d H:i:s");
                    $this->db->table("clubmembers")->insert($sb);

                    // Update user rank 
                    $this->db->table("users")->update(['user_rank' => 3], ['id' => $spil_id]);

                    $parent = $this->db->table("users")->limit(1)->getWhere(['id' => $spil_id])->getRow()->spil_id;
                    $this->checkEmraldClub($parent);
                }
            }
        }
    }

    public function checkEmraldClub($spil_id)
    {
        $ob = $this->db->table("clubmembers")->getWhere(['user_id' => $spil_id, 'club_id' => 4])->getRow();
        if ($ob == null) {
            $users = $this->db->table("users")->getWhere(['spil_id' => $spil_id, 'ac_status' => 1])->getNumRows();
            $ids = [];
            foreach ($users as $us) {
                $ids[] = $us->id;
            }
            if (count($ids) >= 15) {
                $silvers = $this->db->table("clubmembers")->whereIn('user_id', $ids)->get()->getNumRows();
                if ($silvers >= 5) {
                    $sb = [];
                    $sb['user_id'] = $spil_id;
                    $sb['club_id'] = 4;
                    $sb['created'] = date("Y-m-d H:i:s");
                    $this->db->table("clubmembers")->insert($sb);

                    // Update user rank 
                    $this->db->table("users")->update(['user_rank' => 4], ['id' => $spil_id]);

                    $parent = $this->db->table("users")->limit(1)->getWhere(['id' => $spil_id])->getRow()->spil_id;
                    $this->checkDiamondClub($parent);
                }
            }
        }
    }
    public function checkDiamondClub($spil_id)
    {
        $ob = $this->db->table("clubmembers")->getWhere(['user_id' => $spil_id, 'club_id' => 5])->getRow();
        if ($ob == null) {
            $users = $this->db->table("users")->getWhere(['spil_id' => $spil_id, 'ac_status' => 1])->getNumRows();
            $ids = [];
            foreach ($users as $us) {
                $ids[] = $us->id;
            }
            if (count($ids) >= 20) {
                $silvers = $this->db->table("clubmembers")->whereIn('user_id', $ids)->get()->getNumRows();
                if ($silvers >= 5) {
                    $sb = [];
                    $sb['user_id'] = $spil_id;
                    $sb['club_id'] = 5;
                    $sb['created'] = date("Y-m-d H:i:s");
                    $this->db->table("clubmembers")->insert($sb);

                    // Update user rank 
                    $this->db->table("users")->update(['user_rank' => 5], ['id' => $spil_id]);
                }
            }
        }
    }

    public function creditToclub($user_id)
    {
        for ($club = 1; $club <= 5; $club++) {
            $sb = [];
            $sb['user_id'] = $user_id;
            $sb['club_id'] = $club;
            $sb['amount'] = 2;
            $sb['created'] = date("Y-m-d H:i:s");
            $sb['cr_dr'] = 'cr';
            $this->db->table("clubwallet")->insert($sb);
        }
    }

    function getClubBalance($club = 1)
    {
        $builder = $this->db->table("clubwallet");
        $credit = (float)$builder->select("SUM(amount) as total")->where(['club_id' => $club, 'cr_dr' => 'cr'])->get()->getRow()->total;
        $debit = (float)$builder->select("SUM(amount) as total")->where(['club_id' => $club, 'cr_dr' => 'dr'])->get()->getRow()->total;

        return (float)($credit - $debit);
    }

    public function getClubMembers($club = 1)
    {
        $users = $this->db->table("users")->select("id")->getWhere(['user_rank <=' => $club, 'payout' => 1, 'user_rank >=' => "1"])->getResult();
        $ids = [];
        foreach ($users as $us) {
            $ids[] = $us->id;
        }
        return $ids;
    }

    public function distributeClubIncome()
    {
        $refIdA = date("Ymd") . $this->user_id . '1';
        $refIdB = date("Ymd") . $this->user_id . '2';
        $refIdC = date("Ymd") . $this->user_id . '3';
        $refIdD = date("Ymd") . $this->user_id . '4';
        $refIdE = date("Ymd") . $this->user_id . '5';

        $balClubA = $this->getClubBalance(1);
        $idsA = $this->getClubMembers(1);
        if ($balClubA > 0 && count($idsA) > 0) {
            $distValue = (float)$balClubA / count($idsA);
            foreach ($idsA as $id) {
                $u = new User_model($id);
                $u->credit($distValue, Dashboard_model::INCOME_CLUB, "Club Income", $refIdA, 1);
            }
        }
        $balClubB = $this->getClubBalance(2);
        $idsB = $this->getClubMembers(2);
        if ($balClubB > 0 && count($idsB) > 0) {
            $distValue = (float)$balClubB / count($idsB);
            foreach ($idsB as $id) {
                $u = new User_model($id);
                $u->credit($distValue, Dashboard_model::INCOME_CLUB, "Club Income", $refIdB, 2);
            }
        }
        $balClubC = $this->getClubBalance(3);
        $idsC = $this->getClubMembers(3);
        if ($balClubC > 0 && count($idsC) > 0) {
            $distValue = (float)$balClubC / count($idsC);
            foreach ($idsC as $id) {
                $u = new User_model($id);
                $u->credit($distValue, Dashboard_model::INCOME_CLUB, "Club Income", $refIdC, 3);
            }
        }
        $balClubD = $this->getClubBalance(4);
        $idsD = $this->getClubMembers(4);
        if ($balClubD > 0 && count($idsD) > 0) {
            $distValue = (float)$balClubD / count($idsD);
            foreach ($idsD as $id) {
                $u = new User_model($id);
                $u->credit($distValue, Dashboard_model::INCOME_CLUB, "Club Income", $refIdD, 4);
            }
        }
        $balClubE = $this->getClubBalance(5);
        $idsE = $this->getClubMembers(5);
        if ($balClubE > 0 && count($idsE) > 0) {
            $distValue = (float)$balClubE / count($idsE);
            foreach ($idsE as $id) {
                $u = new User_model($id);
                $u->credit($distValue, Dashboard_model::INCOME_CLUB, "Club Income", $refIdE, 5);
            }
        }
    }

    function getWithdrawLimit($user_id)
    {
        $amt = 10;

        return $amt;
    }
}
