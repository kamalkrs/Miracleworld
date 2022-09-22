<?php

namespace App\Models;

use CodeIgniter\Model;
use stdClass;

class Master_model extends Model
{
    var $table;
    var $db;
    function __construct($table = '')
    {
        $this->db = db_connect();
        $this->table = $table;
    }

    function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    function getNew($table = false)
    {
        if ($table) {
            $this->table = $table;
        }
        $f = $this->db->getFieldNames($this->table);
        $temp = new stdClass();
        $temp->id = false;
        foreach ($f as $fields) {
            $temp->$fields = '';
        }
        return $temp;
    }

    function getRow($id, $table = false)
    {

        if ($table) {
            $this->table = $table;
        }
        $builder = $this->db->table($this->table);
        return $builder->getWhere(['id' => $id])->getRow();
    }

    function getAll($limit = 40, $offset = 0, $table = false)
    {
        if ($table) {
            $this->table = $table;
        }
        $builder = $this->db->table($this->table);
        $builder->orderBy('id', 'DESC');
        $builder->limit($limit, $offset);
        $rest = $builder->get();
        $data['results'] = $rest->getResult();
        $data['total'] = $builder->countAll();
        return $data;
    }

    function getAllSearched($limit = 40, $offset = 0, $likes = array(), $table = false)
    {
        if ($table) {
            $this->table = $table;
        }
        $builder = $this->db->table($this->table);

        if (count($likes) > 0) {
            foreach ($likes as $key => $val) {
                $builder->orLike($key, $val);
            }
        }

        $builder->orderBy('id', 'DESC');
        $sql = $builder->getCompiledSelect($this->table, false);
        $builder->limit($limit, $offset);
        $rest = $builder->get();
        $data['results'] = $rest->getResult();
        $data['total'] = $this->db->query($sql)->getNumRows();
        return $data;
    }

    function getWhereRecords($limit = 40, $offset = 0, $rules = array(), $table = false)
    {
        if ($table) {
            $this->table = $table;
        }

        $builder = $this->db->table($this->table);
        $builder->orderBy('id', 'DESC');

        if (is_array($rules) && count($rules) > 0) {
            foreach ($rules as $key => $value) {
                $builder->where($key, $value);
            }
        }
        $sql = $builder->getCompiledSelect($this->table, false);
        $builder->limit($limit, $offset);
        $rest = $builder->get();
        $data['results'] = $rest->getResult();
        $data['total'] = $this->db->query($sql)->getNumRows();
        return $data;
    }

    function listAll($table = false)
    {
        if ($table) {
            $this->table = $table;
        }
        $builder = $this->db->table($this->table);
        return $builder->get()->getResult();
    }

    function listAllWhere($where, $table = false)
    {
        if ($table) {
            $this->table = $table;
        }
        $builder = $this->db->table($this->table);
        $builder->where($where);
        $rest = $builder->get($this->table);
        return $rest->getResult();
    }

    function get_unique_url($url, $id = false)
    {
        $builder = $this->db->table($this->table);
        $builder->select('slug, id');
        $builder->where('slug', $url);
        $rest = $builder->get()->getRow();
        if ($rest == null) {
            return $url;
        } else {
            if ($rest->id == $id) {
                return $url;
            } else {
                $url = $url . '1';
                return $this->get_unique_url($url, $id);
            }
        }
    }


    function getValue($colname, $rules = array(), $table = false)
    {
        if ($table) {
            $this->table = $table;
        }
        $builder = $this->db->table($this->table);
        $builder->where($rules);
        $row = $builder->get($this->table)->getRowArray();
        if (is_array($row) && isset($row[$colname])) {
            return $row[$colname];
        } else {
            return null;
        }
    }
}
