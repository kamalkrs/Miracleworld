<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Users extends BaseController
{

    public function index()
    {
        redirect(admin_url());
    }

    public function login()
    {
        $session = session();
        $data = [];
        helper(['form']);
        $validator = \Config\Services::validation();
        if ($this->request->getPost("submit")) {
            $rules = [
                'username' => [
                    'label' => 'Username',
                    'rules' => 'required'
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required'
                ]
            ];
            if ($this->validate($rules)) {
                $em = $this->request->getVar('username');
                $ps = $this->request->getVar('password');

                $db = db_connect();
                $builder = $db->table("admin");

                $user = $builder->getWhere(['username' => $em, 'password' => md5($ps)])->getRow();
                if (is_object($user)) {
                    $session->set('userid', $user->id);
                    return redirect()->to(admin_url('dashboard'));
                } else {
                    $session->markAsFlashdata('error', "Invalid Username / Password");
                    return redirect()->to(admin_url('users/login'));
                }
            } else {
                return view('users/login', $data);
            }
        } else {
            return view('users/login', $this->data);
        }
    }

    public function login1()
    {
        helper(['form', 'url']);
        //$validator = \Config\Services::validation();
        echo gettype($this->validator);
        print_r($this->validator);

        if (is_admin_login()) {
            redirect(admin_url());
        }
        if ($this->request->getPost('submit')) {
            $this->validator->setRule('username', 'Email Id', 'required');
            $this->validator->setRule('password', 'Password', 'required');
            if ($this->validate([])) {
                echo "Process Login";
            } else {
                $this->session->markAsFlashdata('error', "Invalid Userid / Password");
                redirect()->to(admin_url('users/login'));
            }
        } else {
            //return view(admin_view('users/login'), $this->data);
        }
    }

    public function forget()
    {
        $data['main'] = 'users/forget';
        if ($this->input->post('submit')) {
            $validate = array(array('field' => 'email_id', 'label' => 'Email ID', 'rules' => 'required|valid_email'));
            $this->form_validation->set_rules($validate);
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('users/forget', $data);
            } else {
                $email = $this->input->post('email_id');
                $user = $this->db->get_where('admin', array('email' => $email))->first_row();
                if ($user) {
                    $msg = 'Dear Admin';
                    $msg .= '<br />Here is your login details : ';
                    $msg .= '<br />User Name: ' . $user->username;
                    $msg .= '<br />Password : ' . $user->password;
                    $msg .= '<br /><br /> To login here. <a href="' . base_url($this->config->item('admin_folder') . 'users/login') . '">Login Now</a>';
                    $this->load->library('email');
                    $this->email->from('no-reply@domain.com', 'Web Admin');
                    $this->email->to($user->email);
                    $this->email->subject('Recover Password');
                    $this->email->message($msg);
                    $this->email->send();
                    $this->session->set_flashdata('error', 'Password has been sent on your email id');
                    redirect($this->config->item('admin_folder') . '/users/login');
                } else {
                    $this->session->set_flashdata('error', 'Sorry, Invalid Email ID');
                    $this->load->view('users/forget', $data);
                }
            }
        } else {
            $this->load->view('users/forget', $data);
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        $session->setFlashdata('success', 'You have successfully logged out');
        return redirect()->to(admin_url('users/login'));
    }

    function apiusers()
    {
        $db = db_connect();
        $builder = $db->table("users");
        $scan = isset($_GET['scan']) ? $_GET['scan'] : 1;
        if ($scan == 0) {
            $builder->where("DATE(join_date) = CURDATE()");
        }
        $list = $builder->get()->getResult();
        return $this->response->setJSON($list);
    }
}
