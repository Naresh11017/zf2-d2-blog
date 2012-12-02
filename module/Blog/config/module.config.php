<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog;

return array(
    'router' => array(
        'routes' => array(
            'blog' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller'    => 'blog',
                        'action'        => 'latest',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '(?!page)[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array(
                                'controller' => 'blog',
                                'action' => 'latest'
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => (array(
                            'query' => array(
                                'type' => 'Query',
                            ),
                        ))
                    ),
                    'search' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'blog/search',
                            'defaults' => array(
                                'controller' => 'search',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => (array(
                            'query' => array(
                                'type' => 'Query',
                            ),
                        )),
                    ),
                    'contact' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'contact-rob',
                            'defaults' => array(
                                'controller' => 'contact',
                                'action' => 'index'
                            ),
                        ),
                    ),
                    'about' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'about-this-website',
                            'defaults' => array(
                                'controller' => 'about',
                                'action' => 'index'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewFeedStrategy',
            'ViewJsonStrategy'
        ),
        'template_map' => include __DIR__  .'/../template_map.php',
        'template_path_stack' => array(
            'blog' => __DIR__ . '/../view',
        ),
        /*
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
        )
        */
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Model')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Model' => __NAMESPACE__ . '_driver'
                )
            )
        ),
    )
);