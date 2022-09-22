<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Gallery extends BaseController
{

    public function __construct()
    {
        $this->load->model('Gallery_model');
        $this->data['menu'] = 'cms';

        $config = array();
        $config['upload_path'] = upload_dir();
        $config['allowed_types'] = '*';
        $config['max_size'] = '0';
        $this->load->library('upload', $config);
    }

    public function index()
    {
        $this->data['main'] = 'gallery/index';
        $this->data['gallery_list'] = $this->Gallery_model->getGalleries();
        $this->load->view('default', $this->data);
    }

    public function create($id = false)
    {
        $this->data['main'] = 'gallery/add';
        $this->data['gallery'] = $this->Gallery_model->getNew('gallery');

        if ($id) {
            $this->data['gallery'] = $this->Gallery_model->getRow($id);
        }
        $this->form_validation->set_rules('gal[gallery_name]', 'Gallery Name', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('default', $this->data);
        } else {
            $save = $this->input->post('gal');
            $save['id'] = $id;
            $this->Gallery_model->save($save);
            $this->session->set_flashdata("success", 'Gallery Created Successfully');
            redirect(admin_url('gallery'));
        }
    }

    public function multiple($id = false)
    {
        $this->data['main'] = 'gallery/multiple-upload';
        $this->data['id'] = $id;
        $err_str = '';
        if ($this->input->post('submit')) {
            $total = count($_FILES['filesToUpload']['name']);
            $files = $_FILES;
            $save['gallery_id'] = $id;
            $save['title'] = $this->input->post('title');
            for ($i = 0; $i < $total; $i++) {
                $_FILES['filesToUpload']['name'] = $files['filesToUpload']['name'][$i];
                $_FILES['filesToUpload']['type'] = $files['filesToUpload']['type'][$i];
                $_FILES['filesToUpload']['tmp_name'] = $files['filesToUpload']['tmp_name'][$i];
                $_FILES['filesToUpload']['error'] = $files['filesToUpload']['error'][$i];
                $_FILES['filesToUpload']['size'] = $files['filesToUpload']['size'][$i];

                if ($this->upload->do_upload('filesToUpload') == False) {
                    $err_str .= $this->upload->display_errors();
                } else {
                    $imgdata = $this->upload->data();
                    $save['image'] = $imgdata['file_name'];
                    $this->Master_model->save($save, "gallery_img");
                }
            }
            $this->session->set_flashdata('success', 'Gallery saved successfully');
            redirect(admin_url('gallery'));
        }
        $this->load->view('default', $this->data);
    }

    public function view_photos($id)
    {
        $this->data['main'] = 'gallery/view-images';
        $this->data['id'] = $id;
        $this->data['image_list'] = $this->Gallery_model->getImages($id);
        $gallery = $this->Gallery_model->getRow($id);
        $this->data['gallery_name'] = $gallery->gallery_name;
        $this->load->view('default', $this->data);
    }

    public function delete($id = false)
    {
        if ($id) {
            $this->Gallery_model->delete($id);
            $this->session->set_flashdata('success', 'Gallery Deleted Successfully');
            redirect(admin_url('gallery'));
        }
    }

    public function delete_image($gallery_id, $id = false)
    {
        if ($id) {
            $this->Gallery_model->delete($id, 'gallery_img');
            $this->session->set_flashdata('success', 'Image Deleted Successfully');
        }
        redirect(admin_url('gallery/view-photos/' . $gallery_id));
    }

    public function edit_image($id = false)
    {
        $this->data['main'] = admin_view('gallery/edit-image');
        $this->data['id'] = $id;
        $cat_id = false;
        if ($id) {
            $image = $this->Master_model->getRow($id, "gallery_img");
            $this->data['image'] = $image;
            $cat_id = $image->gallery_id;
        }
        if ($this->input->post('submit')) {
            $save = $this->input->post('im');
            $save['id'] = $id;
            $save['new_tab'] = $this->input->post('new_tab') ? 1 : 0;
            $this->Gallery_model->save($save, "gallery_img");
            $this->session->set_flashdata('success', "Gallery Image Saved");
            redirect(admin_url('gallery/view-photos/' . $cat_id));
        }
        $this->load->view(admin_view('default'), $this->data);
    }

    function ajaxlist()
    {
        $rest = $this->db->order_by("gallery_name", "ASC")->get("gallery")->result();
        foreach ($rest as $ob) {
            echo '<option value="' . $ob->id . '">' . $ob->gallery_name . '</option>';
        }
    }
}
