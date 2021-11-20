<?php
namespace Admin;
use Zend\Mvc\Controller\LazyControllerAbstractFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin[/:action][/:id][/:dopId]',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AdminController::class => LazyControllerAbstractFactory::class,
            ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'               => __DIR__ . '/../view/layout/layoutAdmin.phtml',
            'admin/admin/index'           => __DIR__ . '/../../User/view/user/user/index.phtml',
            'admin/admin/listmessages'    => __DIR__ . '/../../User/view/user/user/listmessages.phtml',
            'admin/admin/userinformation' => __DIR__ . '/../../User/view/user/user/userinformation.phtml' ,
            'admin/admin/messenger'       => __DIR__ . '/../../User/view/user/user/messenger.phtml' ,
            'admin/admin/changepassword'  => __DIR__ . '/../../User/view/user/user/changepassword.phtml' ,
            'error/404'                   => __DIR__ . '/../view/error/404.phtml',
            'error/index'                 => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
