<?
    return array(
        'controllers' => array(
            'invokables' => array(
                'Reviews\Controller\Reviews' => 'Reviews\Controller\ReviewsController',
            ),
        ),
        'router' => array(
            'routes' => array(
                'reviews' => array(
                    'type' => 'segment',
                    'options' => array(
                        'route' => '/reviews[/:action][/:id]',
                        'constraints' => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults' => array(
                            'controller' => 'Reviews\Controller\Reviews',
                            'action'     => 'index',
                        ),
                    ),
                ),
            ),
        ),
        'view_manager' => array(
            'template_path_stack' => array(
                'reviews' => __DIR__ . '/../view',
            ),
            'strategies' => array(
                'ViewJsonStrategy',
            ),
        ),
    );
