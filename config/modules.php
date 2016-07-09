<?php

/**
 * Register application modules
 */
$application->registerModules(array(
    'frontend' => array(
        'className' => 'Corephalcon\Frontend\Module',
        'path' => __DIR__ . '/../apps/frontend/Module.php'
    ),
    'backend' => array(
        'className'=>'Corephalcon\Backend\Module',
        'path'=> __DIR__ . '/../apps/backend/Module.php'
    )
));
