<?php
namespace Admin;

use Admin\Model\Admin;
use Admin\Model\AdminTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    const VERSION = '3.1.3';
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'Admin\Model\AdminTable' =>  function($sm) {
                    $userTableGateway = $sm->get('AdminTableGateway');
                    $positionTableGateway = $sm->get('PositionTableGateway');
                    $telephoneTableGateway = $sm->get('TelephoneTableGateway');
                    $mailTableGateway = $sm->get('MailTableGateway');
                    $entranceTableGateway = $sm->get('EntranceTableGateway');
                    $messageTableGateway = $sm->get('MessageTableGateway');
                    return new AdminTable($userTableGateway,$positionTableGateway,$telephoneTableGateway,$mailTableGateway, $entranceTableGateway, $messageTableGateway);
                },
                'AdminTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Admin);
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
                'PositionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Admin);
                    return new TableGateway('position', $dbAdapter, null, $resultSetPrototype);
                },
                'TelephoneTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Admin);
                    return new TableGateway('telephone', $dbAdapter, null, $resultSetPrototype);
                },
                'MailTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Admin);
                    return new TableGateway('mail', $dbAdapter, null, $resultSetPrototype);
                },
                'EntranceTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Admin);
                    return new TableGateway('entrance', $dbAdapter, null, $resultSetPrototype);
                },
                'MessageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Admin);
                    return new TableGateway('message', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
}
