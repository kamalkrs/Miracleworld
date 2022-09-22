<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use app\Models\Category_model;
use App\Models\Email_model;
use App\Models\Product_model;
use stdClass;

class Products extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['menu'] = "catalog";
        model('Product_model');
        model('Category_model');
    }


    function index()
    {
        $cm = new Category_model();
        $this->data['category']     = $cm->category_dropdown();
        $this->data['main']  = 'products/index';
        $rule = array();
        $this->data['filter_status'] = 'all';
        $this->data['q'] = null;
        $this->data['paginate'] = null;
        $builder = $this->db->table("products");
        $this->data['products'] = $builder->get()->getResult();
        return view(admin_view('default'), $this->data);
    }

    function stock()
    {
        $builder = $this->db->table('products');
        $this->data['main']  = 'products/stock-index';
        $this->data['designs'] = $builder->getWhere(['status' => 1])->getResult();
        return view(admin_view('default'), $this->data);
    }

    function add($id = false)
    {
        $cm = new Category_model();
        $pm = new Product_model();
        $db = db_connect();
        $this->data['main']  = 'products/add';
        $this->data['dashboard_title'] = ($id == false) ? "Add Products" : "Edit Products";
        $this->data['categories']        = $cm->get_categories_tierd();
        $this->data['category']     = $cm->category_dropdown();

        $builder = $db->table('products');
        if ($id) {
            $m = $builder->getWhere(['id' => $id])->getRow();
        } else {
            $fields = $db->getFieldNames('products');
            $m = new stdClass();
            foreach ($fields as $key) {
                $m->$key = null;
            }
        }
        $this->data['p'] = $m;

        $rules = [
            'frm.ptitle' => [
                'label' => 'Product Title',
                'rules' => 'required'
            ]
        ];

        if ($this->request->getPost('button')) {
            if ($this->validate($rules)) {
                $p = $this->request->getPost('frm');
                $p['id'] = $id;
                $p['gallery'] = '';
                $p['discount'] = $this->request->getPost('frm[discount]') ? 1 : 0;

                $upload = $this->request->getFile('cover_image');
                if ($upload->isValid() && !$upload->hasMoved()) {
                    $nm = $upload->getRandomName();
                    $upload->move(upload_dir(), $nm);
                    $p['image'] = $nm;
                }

                $slug = $p['ptitle'];
                $slug    = strtolower(url_title($slug));
                $p['slug'] = $slug;

                $builder = $db->table('products');
                if ($id) {
                    $builder->update($p, ['id' => $id]);
                } else {
                    $builder->insert($p);
                }
                session()->setFlashdata("success", "Product saved successfully");
                return redirect()->to(admin_url('products/add/' . $id));
            }
        }

        return view(admin_view('default'), $this->data);
    }

    function activate($id = false)
    {
        $builder = $this->db->table('products');
        $redirect = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : admin_url('products');
        if ($id) {
            $builder->update(['status' => 1], ['id' => $id]);
            session()->setFlashdata('success', 'Product Activated successfully');
        }
        return redirect()->to($redirect);
    }

    function deactivate($id = false)
    {
        $builder = $this->db->table('products');
        $redirect = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : admin_url('products');
        if ($id) {
            $builder->update(['status' => 0], ['id' => $id]);
            session()->setFlashdata('success', 'Product deactivated successfully');
        }
        return redirect()->to($redirect);
    }

    public function delete($id = false)
    {
        $builder = $this->db->table('products');
        if ($id) {
            $builder->delete(['id' => $id]);
            session()->setFlashdata('success', 'Product deleted successfully');
        }
        return redirect()->to('admin/products');
    }

    function orders()
    {
        $this->data['main'] = 'products/product-orders';
        $this->data['orders'] = $this->db->table("product_orders")->orderBy('id', 'DESC')->get()->getResult();

        return view(admin_view('default'), $this->data);
    }

    function orders1()
    {
        $builder = $this->db->table("stock_order");
        $this->data['main'] = 'products/new-stock-orders';
        $builder->select("stock_order.*, users.first_name, users.last_name, users.username, users.mobile");
        $builder->join("users", "users.id = stock_order.user_id");
        $builder->orderBy("stock_order.id", "DESC");
        $this->data['products'] = $builder->get()->getResult();
        return view(admin_view("default"), $this->data);
    }

    function order_status()
    {
        if (isset($_GET['act'], $_GET['id'])) {
            $id = $_GET['id'];
            $status = $_GET['act'];
            if ($status == 1) {
                $row = $this->db->get_where("stock_order", array("id" => $id))->row();
                $items = json_decode($row->order_items);
                foreach ($items as $item) {
                    $chkOb = $this->db->get_where("stock", array("product_id" => $item->id, 'user_id' => $row->user_id))->row();
                    if (is_object($chkOb)) {
                        $qty = $chkOb->qty + $item->qty;
                        $this->db->update("stock", array('qty' => $qty), array("id" => $chkOb->id));
                    } else {
                        $sb = array();
                        $sb['product_id'] = $item->id;
                        $sb['user_id'] = $row->user_id;
                        $sb['qty'] = $item->qty;
                        $this->db->insert("stock", $sb);
                    }
                }
                $this->db->update("stock_order", array("updated" => date("Y-m-d H:i:s"), "order_status" => 1), array("id" => $id));
                $this->session->set_flashdata("success", "Stock has been completed");
            } else if ($status == 2) {
                $this->db->update("stock_order", array("updated" => date("Y-m-d H:i:s"), "order_status" => 2), array("id" => $id));
                $this->session->set_flashdata("error", "Order has been cancelled");
            }
            redirect(admin_url('products/orders'));
        }
        redirect(admin_url());
    }

    function repurchase()
    {
        $this->data['main'] = "products/repurchase-index";
        return view(admin_view('default'), $this->data);
    }

    function gen_report()
    {
        if ($this->request->getPost("btngen")) {
            $m = $_POST['month'];
            $y = $_POST['year'];
            $builder = $this->db->table('monthly_purchase');
            $chkRpt = $builder->getWhere(array("month_val" => $m, "year_val" => $y))->getRow();
            if (is_object($chkRpt)) {
                $link = admin_url('products/report-view/' . $chkRpt->id);
            } else {
                $this->data['m'] = $m;
                $this->data['y'] = $y;
                $this->data['main'] = "products/repurchase-list";
            }
            return view(admin_view("default"), $this->data);
        }
    }

    function order_view($order)
    {
        $this->data['main'] = 'products/order-view';
        $this->data['order'] = $p = $this->db->table("product_orders")->getWhere(['id' => $order])->getRow();
        $this->data['product'] = $this->db->table("products")->getWhere(['id' => $p->product_id])->getRow();

        if ($this->request->getPost('button')) {
            $sb = $this->request->getPost('form');
            $sb['updated'] = date("Y-m-d H:i:s");
            $this->db->table("product_orders")->update($sb, ['id' => $order]);

            session()->setFlashdata('success', 'Order updated');
            if ($this->request->getPost('send_email')) {
                model('Email_model');
                $em = new Email_model();
                $em->orderUpdateEmail($order)->sendEmail($p->email_id);
                session()->setFlashdata('success', 'Order updated and Customer notified by Email');
            }
            return redirect()->to(admin_url('products/orders'));
        }
        return view(admin_view('default'), $this->data);
    }
}
