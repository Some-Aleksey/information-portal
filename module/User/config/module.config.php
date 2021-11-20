<?php
namespace User;
use Zend\Mvc\Controller\LazyControllerAbstractFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'user' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/user[/:action][/:id][/:dopId]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\UserController::class => LazyControllerAbstractFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'             => __DIR__ . '/../view/layout/layoutUser.phtml',
            'user/index/index'          => __DIR__ . '/../view/user/user/index.phtml',
            'user/user/listemails'      => __DIR__ . '/../../Admin/view/admin/admin/listemails.phtml',
            'user/user/addemail'        => __DIR__ . '/../../Admin/view/admin/admin/addemail.phtml',
            'user/user/deleteemail'     => __DIR__ . '/../../Admin/view/admin/admin/deleteemail.phtml',
            'user/user/listtelephones'  => __DIR__ . '/../../Admin/view/admin/admin/listtelephones.phtml',
            'user/user/addtelephone'    => __DIR__ . '/../../Admin/view/admin/admin/addtelephone.phtml',
            'user/user/deletetelephone' => __DIR__ . '/../../Admin/view/admin/admin/deletetelephone.phtml',
            'error/404'                 => __DIR__ . '/../view/error/404.phtml',
            'error/index'               => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
