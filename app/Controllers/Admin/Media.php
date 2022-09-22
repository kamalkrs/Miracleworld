<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;

class Media extends AdminController
{

    var $submenu;

    function __construct()
    {
        parent::__construct();
        helper('origin');
        model('Media_model');
        $this->data['menu'] = 'cms';
    }

    public function index()
    {
        $builder = $this->db->table("media");
        $this->data['main'] = 'media/index';
        $this->data['medias'] = $builder->orderBy("id", "DESC")->get()->getResult();
        return view(admin_view('default'), $this->data);
    }

    public function add()
    {
        $this->data['main'] = 'media/add';
        $builder = $this->db->table("media");
        if ($this->request->getPost('submit')) {
            $isUploaded = false;
            if ($imagefile = $this->request->getFiles()) {
                foreach ($imagefile['filesToUpload'] as $img) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        $newName = $img->getRandomName();
                        $type = $img->getMimeType();
                        $img->move(upload_dir(), $newName);

                        $save = [];
                        $save['img_title'] = $this->request->getPost('title');
                        $save['file_name'] = $newName;
                        $save['id'] = false;
                        $save['created'] = date("Y-m-d H:i:s");
                        $save['file_type'] = $type;
                        $builder->insert($save);

                        $isUploaded = true;
                    }
                }
            }

            if ($isUploaded) {
                session()->setFlashdata('success', 'Media file uploaded');
            } else {
                session()->setFlashdata('error', 'Unable to upload files');
            }

            return redirect()->to(admin_url('media/add'));
        } else {
            return view(admin_view('default'), $this->data);
        }
    }

    public function delete($id = false)
    {
        $db = db_connect();
        $builder = $db->table("media");
        if ($id > 0) {
            $builder->delete(['id' => $id]);
            session()->setFlashdata('success', 'Media file deleted successfully');
        }
        return redirect()->to(admin_url('media'));
    }
}
