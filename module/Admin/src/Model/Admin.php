<?php
namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Admin
{
    public $id;
    public $firstname;
    public $lastname;
    public $patronymic;
    public $gender;
    public $position;
    public $positionId;
    public $bithday;
    public $telephone;
    public $telephoneId;
    public $emailId;
    public $email;
    public $active;
    public $admin;
    public $older;
    public $under;
    public $moreInformation;
    public $textMessage;
    public $senderId;
    public $recipientId;
    public $dateSend;
    public $skype;

    public function exchangeArray($data)
    {
        $this->id               = (!empty($data['user_id'])) ? $data['user_id'] : null;
        $this->firstname        = (!empty($data['firstname'])) ? $data['firstname'] : null;
        $this->lastname         = (!empty($data['lastname'])) ? $data['lastname'] : null;
        $this->patronymic       = (!empty($data['patronymic'])) ? $data['patronymic'] : null;
        $this->gender           = (!empty($data['gender'])) ? $data['gender'] : null;
        $this->position         = (!empty($data['job'])) ? $data['job'] : null;
        $this->positionId       = (!empty($data['position_id'])) ? $data['position_id'] : null;
        $this->bithday          = (!empty($data['bithday'])) ? $data['bithday'] : null;
        $this->telephone        = (!empty($data['phone'])) ? $data['phone'] : null;
        $this->telephoneId      = (!empty($data['telephone_id'])) ? $data['telephone_id'] : null;
        $this->email            = (!empty($data['email'])) ? $data['email'] : null;
        $this->emailId          = (!empty($data['mail_id'])) ? $data['mail_id'] : null;
        $this->active           = (!empty($data['active'])) ? $data['active'] : null;
        $this->admin            = (!empty($data['admin'])) ? $data['admin'] : null;
        $this->older            = (!empty($data['older'])) ? $data['older'] : null;
        $this->under            = (!empty($data['under'])) ? $data['under'] : null;
        $this->moreInformation  = (!empty($data['more_information'])) ? $data['more_information'] : null;
        $this->skype            = (!empty($data['skype'])) ? $data['skype'] : null;
        $this->textMessage      = (!empty($data['text'])) ? $data['text'] : null;
        $this->senderId         = (!empty($data['sender_id'])) ? $data['sender_id'] : null;
        $this->recipientId      = (!empty($data['recipient_id'])) ? $data['recipientd'] : null;
        $this->dateSend         = (!empty($data['departure_time'])) ? $data['departure_time'] : null;

    }
}