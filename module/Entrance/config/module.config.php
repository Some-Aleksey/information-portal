<?php
namespace Entrance;
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
                        'controller' => Controller\EntranceController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'entrance' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/entrance[/:action]',
                    'defaults' => [
                        'controller' => Controller\EntranceController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\EntranceController::class => LazyControllerAbstractFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layoutEntrance.phtml',
            'entrance/index/index'    => __DIR__ . '/../view/entrance/entrance/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
