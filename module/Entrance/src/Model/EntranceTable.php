<?php
namespace Entrance\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Select as Select;

class EntranceTable
{
    protected $entranceTableGateway;
    protected $userTableGateway;
    protected $mailTableGateway;
    protected $telephoneTableGateway;

    public function __construct(TableGateway $entranceTableGateway, $userTableGateway, $mailTableGateway, $telephoneTableGateway)
    {
        $this->entranceTableGateway = $entranceTableGateway;
        $this->userTableGateway = $userTableGateway;
        $this->mailTableGateway = $mailTableGateway;
        $this->telephoneTableGateway = $telephoneTableGateway;
    }

    public function checkUser($login)
    {
        return $this->entranceTableGateway->select(['login' => $login]);
    }

    public function addNewUser($position)
    {
        $insert = new Insert();
        $insert->into('user')->columns(['firstname', 'lastname', 'patronymic', 'position_id', 'gender',
            'bithday', 'skype', 'photo'])->values([null,null,null,$position,null,null,null,null]);
        $this->userTableGateway->insertWith($insert);
    }

    public function addAllTheRest(Entrance $userInformation)
    {
        $insert = new Insert();
        $select = new Select();
        $insert->into('entrance')->columns(['login', 'password', 'admin', 'active'])->values([$userInformation->login,
            $userInformation->password,1,1]);
        $this->entranceTableGateway->insertWith($insert);
        $userId = $select->from('entrance')->columns(['user_id'])->where(['login' => $userInformation->login]);
        $insert->into('mail')->columns(['user_id', 'email'])->values([$userId,null]);
        $this->mailTableGateway->insertWith($insert);
        $insert->into('telephone')->columns(['user_id', 'phone'])->values([$userId,null]);
        $this->telephoneTableGateway->insertWith($insert);
    }
}