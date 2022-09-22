<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\Master_model;
use App\Models\Setting_model;

class Settings extends AdminController
{

    var $global;
    function __construct()
    {
        parent::__construct();
        $this->data['menu'] = 'settings';
    }

    public function index()
    {
        $setting = new Setting_model();
        $this->data['main'] = 'settings/theme-options';
        $this->data['options'] = $setting->all_options();
        if ($this->request->getPost('submit')) {
            $fields = $this->request->getPost('fields');
            $arr_fields = explode(',', $fields);
            if (is_array($arr_fields) and count($arr_fields) > 0) {
                foreach ($arr_fields as $fname) {
                    $fname = trim($fname);
                    $s['option_name'] = $fname;
                    $s['option_value'] = $this->request->getPost($fname);
                    $setting->save_option($s);
                }
                session()->setFlashdata('success', 'Settings updated successfully');
            }
            return redirect()->to(admin_url('settings'));
        } else {
            return view(admin_view('default'), $this->data);
        }
    }

    function restore()
    {
        $builder = $this->db->table("options");
        $builder->truncate();
        session()->setFlashdata('success', 'Global Setting reset to Default');
        return redirect()->to(admin_url('settings'));
    }

    function sql()
    {
        $this->data['main'] = 'settings/sql';
        if ($this->input->post('sql')) {
            $sql = $this->input->post('sql');
            $this->db->query($sql);
            $this->session->set_flashdata("success", "SQL Executed");
            redirect(admin_url('settings/sql'));
        }
        $this->load->view('default', $this->data);
    }

    public function changepass()
    {
        $session = session();
        $builder = $this->db->table("admin");

        $this->activeTabs = 'Change Pasasword';
        $this->data['main'] = "users/changepwd";

        $id = $session->get('userid');
        $this->data['users'] = $admin = $builder->getWhere(array('id' => $id))->getRow();
        if ($this->request->getPost('submit')) {
            $rules = [
                'old_pass' => [
                    'label' => 'Old Password',
                    'rules' => 'required'
                ],
                'new_pass' => [
                    'label' => 'New Password',
                    'rules' => 'required'
                ],
                'cnf_pass' => [
                    'label' => 'Confirm Password',
                    'rules' => 'required'
                ]
            ];
            if ($this->validate($rules)) {
                $old_pass = $this->request->getPost("old_pass");
                $new_pass = $this->request->getPost("new_pass");
                if ($admin->password == md5($old_pass)) {
                    $s = array();
                    $s['id'] = $admin->id;
                    $s['password'] = md5($new_pass);
                    $builder->update($s, ['id' => $id]);
                    session()->setFlashdata('success', 'Your Password Changed Successfully');
                } else {
                    session()->setFlashdata('error', 'Old Password is mis-matched!!');
                }
                return redirect()->to(admin_url("settings/changepass"));
            } else {
                return view(admin_view("default"), $this->data);
            }
        } else {
            return view(admin_view("default"), $this->data);
        }
    }

    function edit_profile()
    {
        $session = session();
        $userid = $session->get('userid');
        $this->data['main'] = 'settings/edit-profile';
        $builder = $this->db->table("admin");
        $this->data['user'] = $builder->getWhere(['id' => $userid])->getRow();
        $rules = [
            'form.first_name' => [
                'label' => 'Full name',
                'rules' => 'required'
            ]
        ];
        if ($this->request->getPost('submit')) {
            if ($this->validate($rules)) {
                $form = $this->request->getPost("form");


                $avatar = $this->request->getFile('avatar');
                if ($avatar->isValid() && !$avatar->hasMoved()) {
                    $fname = $avatar->getRandomName();
                    $avatar->move(upload_dir(), $fname);
                    $form['avatar'] = $fname;
                }
                $form['updated'] = date("Y-m-d H:i:s");
                $builder->update($form, ['id' => $userid]);
                $session->setFlashdata('success', 'Profile updated');

                return redirect()->to(admin_url('settings/edit-profile'));
            } else {
                return view(admin_view('default'), $this->data);
            }
        } else {
            return view(admin_view('default'), $this->data);
        }
    }

    function cron_jobs()
    {
        $this->data['main'] = 'settings/cron-jobs';
        return view(admin_view('default'), $this->data);
    }


    function database()
    {
        $this->data['main'] = 'settings/database';
        return view(admin_view('default'), $this->data);
    }

    public function smtp_settings()
    {
        $setting = new Setting_model();
        $this->data['main'] = 'settings/smtp-settings';
        $this->data['options'] = $setting->all_options('smtp');
        if ($this->request->getPost('submit')) {
            $fields = $this->request->getPost('fields');
            $arr_fields = explode(',', $fields);
            if (is_array($arr_fields) and count($arr_fields) > 0) {
                foreach ($arr_fields as $fname) {
                    $fname = trim($fname);
                    $s['option_name'] = $fname;
                    $s['option_value'] = $this->request->getPost($fname);
                    $s['group_name'] = 'smtp';
                    $setting->save_option($s);
                }
                session()->setFlashdata('success', 'Settings updated successfully');
            }
            return redirect()->to(admin_url('settings/smtp-settings'));
        } else {
            return view(admin_view('default'), $this->data);
        }
        return view(admin_view('default'), $this->data);
    }

    public function withdraw_settings()
    {
        $setting = new Setting_model();
        $this->data['main'] = 'settings/withdraw-settings';
        $this->data['options'] = $setting->all_options('withdraw');
        if ($this->request->getPost('submit')) {
            $fields = $this->request->getPost('fields');
            $arr_fields = explode(',', $fields);
            if (is_array($arr_fields) and count($arr_fields) > 0) {
                foreach ($arr_fields as $fname) {
                    $fname = trim($fname);
                    $s['option_name'] = $fname;
                    $s['option_value'] = $this->request->getPost($fname);
                    $s['group_name'] = 'withdraw';
                    $setting->save_option($s);
                }
                session()->setFlashdata('success', 'Settings updated successfully');
            }
            return redirect()->to(admin_url('settings/withdraw-settings'));
        } else {
            return view(admin_view('default'), $this->data);
        }
        return view(admin_view('default'), $this->data);
    }

    public function payout_settings()
    {
        $setting = new Setting_model();
        $this->data['main'] = 'settings/payout-settings';
        $this->data['options'] = $setting->all_options('payout');
        if ($this->request->getPost('submit')) {
            $fields = $this->request->getPost('days');
            if (is_array($fields)) {
                $days = json_encode($fields);
            } else {
                $days = '[]';
            }
            $s['option_name'] = 'payout_days';
            $s['option_value'] = $days;
            $s['group_name'] = 'payout';
            $setting->save_option($s);
            session()->setFlashdata('success', 'Settings updated successfully');
            return redirect()->to(admin_url('settings/payout-settings'));
        } else {
            return view(admin_view('default'), $this->data);
        }
        return view(admin_view('default'), $this->data);
    }

    function ranks()
    {
        $this->data['main'] = 'settings/ranks';
        $builder = $this->db->table("ranks");
        $this->data['items'] = $builder->orderBy('rank_order', 'ASC')->get()->getResult();
        return view(admin_view('default'), $this->data);
    }

    function add_rank($id = false)
    {
        $master = new Master_model();
        $builder = $this->db->table("ranks");
        $this->data['main'] = 'settings/add-rank';
        $this->data['rank'] = $master->getNew('ranks');
        if ($id) {
            $this->data['rank'] = $master->getRow($id, 'ranks');
        }
        $rules = [
            'form.rank_title' => [
                'label' => 'Rank title',
                'rules' => 'required'
            ]
        ];
        if ($this->request->getPost('button') && $this->validate($rules)) {
            $form = $this->request->getPost("form");
            if ($id) {
                $builder->update($form, ['id' => $id]);
            } else {
                $builder->insert($form);
            }
            session()->setFlashdata('success', 'Rank saved successfully');
            return redirect()->to(admin_url('settings/ranks'));
        } else {
            return view(admin_view('default'), $this->data);
        }
    }

    function delete_rank($id = false)
    {
        if ($id) {
            $builder = $this->db->table("ranks");
            $builder->delete(['id' => $id]);

            session()->setFlashdata('success', 'Rank delete successfully');
        }
        return redirect()->to(admin_url('settings/ranks'));
    }

    function backup_new()
    {
        echo "We are working";
        return;
    }

    function rewards()
    {
        $this->data['main'] = 'settings/rewards';
        $builder = $this->db->table("reward_master");
        $this->data['items'] = $builder->orderBy('reward_order', 'ASC')->get()->getResult();
        return view(admin_view('default'), $this->data);
    }

    function add_reward($id = false)
    {
        $master = new Master_model();
        $builder = $this->db->table("reward_master");
        $this->data['main'] = 'settings/add-reward';
        $this->data['rank'] = $master->getNew('reward_master');
        if ($id) {
            $this->data['rank'] = $master->getRow($id, 'reward_master');
        }
        $rules = [
            'form.reward_title' => [
                'label' => 'Reward title',
                'rules' => 'required'
            ]
        ];
        if ($this->request->getPost('button') && $this->validate($rules)) {
            $form = $this->request->getPost("form");
            if ($id) {
                $builder->update($form, ['id' => $id]);
            } else {
                $builder->insert($form);
            }
            session()->setFlashdata('success', 'Rewards saved successfully');
            return redirect()->to(admin_url('settings/rewards'));
        } else {
            return view(admin_view('default'), $this->data);
        }
    }

    function delete_reward($id = false)
    {
        if ($id) {
            $builder = $this->db->table("reward_master");
            $builder->delete(['id' => $id]);

            session()->setFlashdata('success', 'Rewards delete successfully');
        }
        return redirect()->to(admin_url('settings/rewards'));
    }

    function email_templates()
    {
        $this->data['main'] = 'settings/email-templates';
        $builder = $this->db->table("emails");
        $this->data['items'] = $builder->get()->getResult();
        return view(admin_view('default'), $this->data);
    }

    function edit_template($id)
    {
        $this->data['main'] = 'settings/edit-template';
        $builder = $this->db->table("emails");
        $this->data['item'] = $builder->getWhere(['id' => $id])->getRow();

        if ($this->request->getPost("submit")) {
            $rules = [
                'form.email_subject' => [
                    'label' => 'Email Subject',
                    'rules' => 'required'
                ]
            ];
            if ($this->validate($rules)) {
                $form = $this->request->getPost("form");
                $builder->update($form, ['id' => $id]);
                session()->setFlashdata('success', 'Email templated updated');
                return redirect()->to(admin_url('settings/email-templates'));
            }
        }
        return view(admin_view('default'), $this->data);
    }
}
