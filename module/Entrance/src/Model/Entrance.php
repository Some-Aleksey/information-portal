<?php
namespace Entrance\Model;

class Entrance
{
    public $id;
    public $login;
    public $password;
    public $position;
    public $email;
    public $admin;

    public function exchangeArray($data)
    {
        $this->id               = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->login            = (!empty($data['login'])) ? $data['login'] : null;
        $this->password         = (!empty($data['password'])) ? $data['password'] : null;
        $this->position         = (!empty($data['job'])) ? $data['job'] : null;
        $this->email            = (!empty($data['email'])) ? $data['email'] : null;
        $this->admin            = (!empty($data['admin'])) ? $data['admin'] : null;
    }
}