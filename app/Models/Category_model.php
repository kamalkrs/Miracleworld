<?php

namespace app\Models;

use App\Models\Master_model;

class Category_model extends Master_model
{
    private $cat_id;
    public  $db;
    function __construct($id = false)
    {
        $this->table = 'categories';
        $this->cat_id = $id;
        $this->db = db_connect();
    }

    function get_categories_tierd($parent = 0)
    {
        $categories = array();
        $result = $this->categories($parent);
        foreach ($result as $category) {
            $categories[$category->id]['category'] = $category;
            $categories[$category->id]['children'] = $this->get_categories_tierd($category->id);
        }
        return $categories;
    }



    function categories($parent = 0)
    {

        $builder = $this->db->table('categories');
        return $builder->getWhere(['parent_id' => $parent])->getResult();
    }



    function hasChildren($parent_id)
    {
        $builder = $this->db->table('categories');
        $c = $builder->getWhere(['parent_id' => $parent_id])->getNumRows();
        return $c > 0;
    }



    function category_dropdown()
    {
        $builder = $this->db->table('categories');
        $data = array(
            0 => 'Select'
        );
        $rest = $builder->getWhere(['status' => 1])->getResult();

        foreach ($rest as $r) {
            $tname = ucwords(strtolower($r->name));
            $data[$r->id] = $tname;
            //$data = $this->sub_child($r->id, $tname, $data);
        }

        return $data;
    }
}
