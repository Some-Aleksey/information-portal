<?php
namespace Entrance;
use Entrance\Model\EntranceTable;
use Entrance\Model\Entrance;
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
                'Entrance\Model\EntranceTable' =>  function($sm) {
                    $entranceTableGateway = $sm->get('EntranceTableGateway');
                    $userTableGateway = $sm->get('UserTableGateway');
                    $emailTableGateway = $sm->get('EmailTableGateway');
                    $telephoneTableGateway = $sm->get('PhoneTableGateway');
                    return new EntranceTable($entranceTableGateway, $userTableGateway, $emailTableGateway, $telephoneTableGateway);
                },
                'EntranceTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Entrance);
                    return new TableGateway('entrance', $dbAdapter, null, $resultSetPrototype);
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Entrance);
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
                'EmailTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Entrance);
                    return new TableGateway('mail', $dbAdapter, null, $resultSetPrototype);
                },
                'PhoneTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Entrance);
                    return new TableGateway('telephone', $dbAdapter, null, $resultSetPrototype);
                },
            ]
        ];
    }
}
