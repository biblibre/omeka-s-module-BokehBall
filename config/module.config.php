<?php

namespace BokehBall;

return [
    'form_elements' => [
        'invokables' => [
            'BokehBall\Form\ConfigForm' => Form\ConfigForm::class,
            'BokahBall\Form\UpdatePropertyForm' => Form\UpdatePropertyForm::class,
        ],
    ],
    'resource_page_block_layouts' => [
        'invokables' => [
            Site\ResourcePageBlockLayout\BokehBallBounce::class => Site\ResourcePageBlockLayout\BokehBallBounce::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            'BokehBall\Controller\Admin\Index' => Service\Controller\Admin\IndexControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [
                    'bokeh-ball' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/bokeh-ball',
                            'defaults' => [
                                '__NAMESPACE__' => 'BokehBall\Controller\Admin',
                                'controller' => 'Index',
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'update' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/update',
                                    'defaults' => [
                                        '__NAMESPACE__' => 'BokehBall\Controller\Admin',
                                        'controller' => 'Index',
                                        'action' => 'update',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
        'AdminModule' => [
            [
                'label' => 'BokehBall',
                'route' => 'admin/bokeh-ball',
                'resource' => 'BokehBall\Controller\Admin\Index',
                'pages' => [
                    [
                        'label' => 'Update linked property', //@translate
                        'route' => 'admin/bokeh-ball',
                        'resource' => 'BokehBall\Controller\Admin\Index',
                    ],
                ],
            ],
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
];
