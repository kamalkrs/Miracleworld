<?php

namespace App\Models;

use App\Models\Master_model;

class Post_model extends Master_model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'posts';
    }

    function pagesDropdown()
    {
        $builder = $this->db->table("posts");
        $data = array(0 => 'Main Page');
        $builder->select('id,post_title');
        $builder->orderBy('post_title', "ASC");
        $builder->where('parent_id', 0);
        $builder->where('post_type', 'page');
        $rest = $builder->get();
        foreach ($rest->getResult() as $r) {
            $tname = ucwords(strtolower($r->post_title));
            $data[$r->id] = $tname;
            $data = $this->sub_child($r->id, $tname, $data);
        }
        return $data;
    }

    function sub_child($parent_id, $name, $old_arr = array())
    {
        $builder = $this->db->table("posts");
        $builder->select('id, post_title');
        $builder->where('parent_id', $parent_id);
        $builder->where('post_type', 'page');
        $builder->orderBy('post_title', 'ASC');
        $rest = $builder->get();
        foreach ($rest->getResult() as $r) {
            $fname = $name . ' &#x021D2; ' . ucwords(strtolower($r->post_title));
            $old_arr[$r->id] = $fname;
            $old_arr = $this->sub_child($r->id, $fname, $old_arr);
        }

        return $old_arr;
    }
}
