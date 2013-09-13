<?php
/**
 * Test module install config file for Popcorn.
 *
 * Allows for a quick scaffolding of a
 * module's folder structure and code.
 */
return array(
    'Test' => array(
        'databases'   => '.httest.sqlite',
        'controllers' => 'IndexController',
        'forms'       => array('Login', 'Page'),
        'tables'      => array('Users', 'Pages'),
        'models'      => array('User',  'Page')
    )
);
