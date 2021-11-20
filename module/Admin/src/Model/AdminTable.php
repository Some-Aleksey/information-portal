<?php
namespace Admin\Model;

use http\Exception\InvalidArgumentException;
use MongoDB\Driver\Query;
use mysql_xdevapi\Exception;
use mysql_xdevapi\TableSelect;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Select as Select;

class AdminTable
{
    protected $userTableGateway;
    protected $positionTableGateway;
    protected $telephoneTableGateway;
    protected $mailTableGateway;
    protected $entranceTableGateway;
    protected $messageTableGateway;

    public function __construct(TableGateway $userTableGateway, $positionTableGateway, $telephoneTableGateway, $mailTableGateway, $entranceTableGateway, $messageTableGateway)
    {
        $this->userTableGateway      = $userTableGateway;
        $this->positionTableGateway  = $positionTableGateway;
        $this->telephoneTableGateway = $telephoneTableGateway;
        $this->mailTableGateway      = $mailTableGateway;
        $this->entranceTableGateway  = $entranceTableGateway;
        $this->messageTableGateway   = $messageTableGateway;
    }

    public function fetchUser($id)
    {
        $select = new Select();
        $select->from('user')->join('position', 'position.position_id = user.position_id', ['job'])->
        join('telephone', 'telephone.user_id = user.user_id', ['phone' => new Expression('GROUP_CONCAT(DISTINCT phone)')])->group('user.user_id')->
        join('mail', 'mail.user_id = user.user_id', ['email' => new Expression('GROUP_CONCAT(DISTINCT email)')])->
        join('entrance', 'entrance.user_id = user.user_id', ['admin', 'active'])->where(['user.user_id' => $id]);
        return $this->userTableGateway->selectWith($select);
    }

    public function filter(Admin $filter)
    {
        $filter->moreInformation = str_ireplace("%40","@",$filter->moreInformation);
        $select = new Select();
        $bd = $select->from('user')->join('position','position.position_id = user.position_id', ['job'])->
        join('telephone','telephone.user_id = user.user_id', ['phone'=>new Expression('GROUP_CONCAT(DISTINCT phone)')])->
        group('user.user_id')->
        join('mail','mail.user_id = user.user_id', ['email' => new Expression('GROUP_CONCAT(DISTINCT email)')])->
        join('entrance','entrance.user_id = user.user_id', ['active','admin']);
        if ($filter->position != null) $bd->where(['job' => $filter->position]);
        if ($filter->gender != 0) $bd->where(['gender' => $filter->gender]);
        if ($filter->older != null) $bd->where('bithday <= date_sub(current_date,interval '.$filter->older.' year)');
        if ($filter->under != null) $bd->where('bithday >= date_sub(current_date,interval '.$filter->under.' year)');
        if ($filter->moreInformation != null) {
          $bd->where->like('firstname', $filter->moreInformation)->OR->like('lastname', $filter->moreInformation)
               ->OR->like('patronymic', $filter->moreInformation)->OR->like('phone', $filter->moreInformation)
               ->OR->like('email', $filter->moreInformation);
        }
        if ($filter->active != 0) $bd->where(['active' => $filter->active]);
        if ($filter->admin != 0) $bd->where(['admin' => $filter->admin]);
        return $this->userTableGateway->selectWith($select);
    }

    public function fetchPosition()
    {
        return $this->positionTableGateway->select();
    }

    public function createPosition($name, $id)
    {
        if ($id == 0) {
            $insert = new Insert();
            $insert->into('position')->columns(['job'])->values([$name]);
            $this->positionTableGateway->insertWith($insert);
        } else {
            $this->positionTableGateway->update(['job' => $name], ['position_id' => $id]);
        };
    }

    public function deletePosition($positionId)
    {
        return $this->positionTableGateway->delete(['position_id' => $positionId]);
    }

    public function fetchTelephones($userId)
    {
        return $this->telephoneTableGateway->select(['user_id' => $userId]);
    }

    public function saveTelephone($number, $userId)
    {
        $insert = new Insert();
        $insert->into('telephone')->columns(['user_id', 'phone'])->values([$userId,$number]);
        $this->telephoneTableGateway->insertWith($insert);
    }

    public function deleteTelephone($telephoneId)
    {
        return $this->telephoneTableGateway->delete(['telephone_id' => $telephoneId]);
    }

    public function changeUser(Admin $userInformation, $userId)
    {
        $this->userTableGateway->update(['lastname' => $userInformation->lastname, 'firstname' => $userInformation->firstname,
            'patronymic' => $userInformation->patronymic, 'position_id' => $userInformation->position,'gender' => $userInformation->gender, 'bithday' => $userInformation->bithday,
        'skype' => $userInformation->skype],['user_id' => $userId]);
        $this->entranceTableGateway->update(['admin' => $userInformation->admin, 'active' => $userInformation->active],
            ['user_id' => $userId]);
    }

    public function changePassword($password, $userId)
    {
        $this->entranceTableGateway->update(['password' => $password],['user_id' => $userId]);
    }

    public function deleteUser($id)
    {
        $this->entranceTableGateway->delete(['user_id' => $id]);
        $this->telephoneTableGateway->delete(['user_id' => $id]);
        $this->mailTableGateway->delete(['user_id' => $id]);
        $this->messageTableGateway->delete(['sender_id' => $id]);
        $this->messageTableGateway->delete(['recipient_id' => $id]);
        $this->userTableGateway->delete(['user_id' => $id]);
    }

    public function fetchEmails($userId)
    {
        return $this->mailTableGateway->select(['user_id' => $userId]);
    }

    public function saveEmail($email, $userId)
    {
        $insert = new Insert();
        $insert->into('mail')->columns(['user_id', 'email'])->values([$userId, $email]);
        $this->mailTableGateway->insertWith($insert);
    }

    public function deleteEmail($mailId)
    {
        $this->mailTableGateway->delete(['mail_id' => $mailId]);
    }

    public function message($senderId, $interlocutorId)
    {
        $select = new Select();
        $select->from('message')->columns(['sender_id','recipient_id','text','departure_time'])->where->
        like('sender_id',$senderId)->and->like('recipient_id',$interlocutorId)->OR->
        like('sender_id',$interlocutorId)->and->like('recipient_id',$senderId);
        return $this->messageTableGateway->selectWith($select);
    }

    public function addNewMessage($senderId, $interlocutorId, $text)
    {
        $insert = new Insert();
        $insert->into('message')->columns(['sender_id', 'recipient_id', 'text', 'departure_time', 'reading_time'])
            ->values([$senderId, $interlocutorId, $text, new Expression('now()'), new Expression('now()')]);
        $this->messageTableGateway->insertWith($insert);
    }
}