<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\Post_model;

class Posts extends AdminController
{
    var $db;
    public function __construct()
    {
        parent::__construct();
        $this->data['menu'] = 'cms';
        $this->db = db_connect();
        model('Post_model');
    }

    public function index($offset = 0)
    {
        $builder = $this->db->table('posts');
        $type = isset($_GET['type']) ? $_GET['type'] : 'post';
        $builder->orderBy("id", "DESC");
        $this->data['type'] = $type;
        $this->data['post_list'] = $builder->getWhere(array("post_type" => $type))->getResult();
        $this->data['main'] = 'posts/index';
        return view(admin_view('default'), $this->data);
    }


    public function pages()
    {
        $builder = $this->db->table("posts");
        $this->data['main'] = 'posts/page-index';
        $pages = $builder->orderBy('post_title', 'ASC')->getWhere(['post_type' => 'page'])->getResult();
        $this->data['post_list'] = $pages;
        return view(admin_view('default'), $this->data);
    }

    function edit_page($id)
    {
        $page = $this->db->get_where('posts', array('page_id' => $id))->row();
        if (is_object($page)) {
            redirect(admin_url('posts/add/' . $page->id));
        } else {
            $page = array();
            $page['post_title'] = "Untitled";
            $page['post_type'] = 'page';
            $page['page_id'] = $id;
            $page['status'] = 1;
            $this->db->insert('posts', $page);
            $id = $this->db->insert_id();
            redirect(admin_url('posts/add/' . $id));
        }
    }

    public function add_page($id = false)
    {
        $builder = $this->db->table("posts");
        $post = new Post_model();
        $this->data['post_type'] = 'page';
        $this->data['main'] = 'posts/add-page';
        $this->data['p'] = $post->getNew();
        $this->data['layouts'] = [];
        $this->data['parents'] = $post->pagesDropdown();
        if ($id) {
            $this->data['p'] = $post = $post->getRow($id);
        }

        if ($this->request->getPost('submit')) {
            $rules = [
                'form.post_title' => [
                    'label' => 'Title',
                    'rules' => 'required'
                ]
            ];
            if ($this->validate($rules)) {
                $sb = $this->request->getPost('form');

                $sb['post_type'] = 'page';
                if ($id) {
                    $builder->update($sb, ['id' => $id]);
                } else {
                    $builder->insert($sb);
                    $id = $this->db->insertID();
                }
                session()->setFlashdata('success', 'Page saved successfully');
                redirect()->to(admin_url('posts/add-page/' . $id));
            }
        }
        return view(admin_view('default'), $this->data);
    }

    public function add_post($id = false)
    {

        $post = new Post_model();

        $type = isset($_GET['type']) ? $_GET['type'] : 'post';
        $this->data['type'] = $type;
        $this->data['main'] = 'posts/add';
        $this->data['p'] = $post->getNew('posts');
        $this->data['layouts'] = [];
        $this->data['parents'] = [];
        if ($id) {
            $this->data['p'] = $post->getRow($id);
        }

        if ($this->request->getPost('submit')) {
            $rules = [
                'form.post_title' => [
                    'label' => 'Post Title',
                    'rules' => 'required'
                ]
            ];
            if ($this->validate($rules)) {
                $save = $this->request->getPost('form');
                if ($this->request->getPost('del_img')) {
                    $del_img = $this->request->getPost('hid_img');
                    $save['image'] = '';
                }
                $save['id'] = $id;
                $upload = $this->request->getFile('image');
                if ($upload->isValid() && !$upload->hasMoved()) {
                    $nm = $upload->getRandomName();
                    $upload->move(upload_dir(), $nm);
                    $save['image'] = $nm;
                }

                $slug  = $save['slug'];
                if (empty($slug) || $slug == '') {
                    $slug = $save['post_title'];
                }
                $slug    = strtolower(url_title($slug));
                $save['slug']    = $post->get_unique_url($slug, $id);
                if ($id) {
                    $save['updated'] = date('Y-m-d H:i:s');
                } else {
                    $save['created'] = date('Y-m-d H:i:s');
                    $save['updated'] = date('Y-m-d H:i:s');
                }
                if ($id) {
                    $this->db->table("posts")->update($save, ['id' => $id]);
                } else {
                    $save['post_type'] = $type;
                    $this->db->table("posts")->insert($save);
                }
                session()->setFlashdata('success', 'Data saved successfully');
                return redirect()->to(admin_url('posts/add-post/' . $id));
            }
        }

        return view(admin_view('default'), $this->data);
    }

    function activate($id = false)
    {
        $redirect = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : admin_url('posts');
        if ($id) {
            $c = [];
            $c['status'] = 1;
            $this->db->table("posts")->update($c, ['id' => $id]);
            session()->setFlashdata("success", "Posts is now active");
        }
        return redirect()->to($redirect);
    }

    function deactivate($id = false)
    {
        $redirect = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : admin_url('posts');
        if ($id) {
            $c = [];
            $c['status'] = 0;
            $this->db->table("posts")->update($c, ['id' => $id]);
            session()->setFlashdata("success", "Posts saved to draft");
        }
        return redirect()->to($redirect);
    }

    function delete($id = false)
    {
        $builder = $this->db->table("posts");
        if ($id) {
            $p = $builder->getWhere(['id' => $id])->getRow();
            $builder->delete(['id' => $id]);
            session()->setFlashdata("success", "Posts deleted successfully");
            if ($p->post_type == 'page') {
                return redirect()->to(admin_url('posts/pages'));
            }
        }
        return redirect()->to(admin_url('posts'));
    }




    function active($id = false)
    {
        $redirect = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : admin_url('posts/senior_post');
        if ($id) {
            $c['id'] = $id;
            $c['status'] = 1;
            $this->Master_model->save($c, 'senior_post');
            $this->session->set_flashdata("success", "Page published");
        }
        redirect($redirect);
    }

    function deactive($id = false)
    {
        $redirect = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : admin_url('posts/senior_post');
        if ($id) {
            $c['id'] = $id;
            $c['status'] = 0;
            $this->Master_model->save($c, 'senior_post');
            $this->session->set_flashdata("success", "Posts saved to draft");
        }
        redirect($redirect);
    }
    function deletep($id = false)
    {
        if ($id) {

            $this->Master_model->delete($id, 'senior_post');
            $this->session->set_flashdata("success", "Posts deleted successfully");

            redirect(admin_url('posts/senior_post'));
        }
        redirect(admin_url('posts'));
    }
}
