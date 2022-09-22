<?php
class RestApi
{
    var $success = false;
    var $fields = null;
    var $message = null;
    var $data = null;

    function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
    }

    public function setOK($message = null)
    {
        $this->success = true;
        $this->message = $message;
    }

    public function setError($message = null)
    {
        $this->success = false;
        $this->message = $message;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setMessage($msg)
    {
        $this->message = $msg;
    }

    public function checkINPUT($keys, $data = [])
    {
        $this->fields = implode(',', $keys);
        $flag = true;
        if (count($keys) > 0) {
            foreach ($keys as $key) {
                if (!isset($data[$key])) {
                    $flag = false;
                }
            }
        }
        if ($flag == false) {
            $this->missing();
        }
        return $flag;
    }

    public function checkPOST($keys)
    {
        $this->fields = implode(',', $keys);
        $flag = true;
        if (count($keys) > 0) {
            foreach ($keys as $key) {
                if (!isset($_POST[$key])) {
                    $flag = false;
                }
            }
        }
        if ($flag == false) {
            $this->missing();
        }
        return $flag;
    }

    public function check($keys)
    {
        $this->fields = implode(',', $keys);
        $flag = true;
        if (count($keys) > 0) {
            foreach ($keys as $key) {
                if (!isset($_GET[$key])) {
                    $flag = false;
                }
            }
        }
        if ($flag == false) {
            $this->missing();
        }
        return $flag;
    }

    public function missing()
    {
        $this->setError("Required Parameter Missing !!");
    }

    public function render()
    {
        echo json_encode($this, JSON_PRETTY_PRINT);
    }
}
