<?php

namespace App\Models;

use App\Models\Master_model;

class Setting_model extends Master_model
{

    var $_global;

    function __construct()
    {
        parent::__construct();
        $this->_global = array();
    }

    function all_options($group = '')
    {
        $builder = $this->db->table("options");
        $data = array();
        if ($group) {
            $builder->where('group_name', $group);
        }
        $rest = $builder->get()->getResult();
        if (is_array($rest) and count($rest) > 0) {
            foreach ($rest as $row) {
                $data[$row->option_name] = $row->option_value;
            }
        }
        return $data;
    }

    function get_option($option_name)
    {
        $builder = $this->db->table("options");
        $d = $builder->getWhere(array('option_name' => $option_name))->getRow();
        if (is_object($d)) {
            return $d;
        } else {
            $m['option_name'] = $option_name;
            $m['option_value'] = null;
            return $m;
        }
    }

    function get_option_value($option_name)
    {
        $row = $this->get_option($option_name);
        return isset($row->option_value) ? $row->option_value : null;
    }

    function save_option($data)
    {
        $builder = $this->db->table("options");
        $rest = $builder->getWhere(array('option_name' => $data['option_name']))->getRow();
        if (is_object($rest)) {
            $builder->update($data, ['option_name' => $data['option_name']]);
        } else {
            $builder->insert($data);
        }
    }



    function export_database()
    {
        $exportFile = "database-" . date("dmYhis") . ".sql";
        $this->load->dbutil();

        $prefs = array(
            'ignore'        => array(),                     // List of tables to omit from the backup
            'format'        => 'txt',                       // gzip, zip, txt
            'filename'      => 'databse.sql',              // File name - NEEDED ONLY WITH ZIP FILES
            'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
            'newline'       => "\n"                         // Newline character used in backup file
        );

        // Backup your entire database and assign it to a variable
        $backup = $this->dbutil->backup($prefs);

        // Load the file helper and write the file to your server
        $this->load->helper('file');
        write_file(upload_dir($exportFile), $backup);

        // Load the download helper and send the file to your desktop
        $this->load->helper('download');
        force_download($exportFile, $backup);
    }
}
