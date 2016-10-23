<?php

return array(
        // added for Acl   ###################################
        'controller_plugins' => array(
            'factories' => array(
                'MyAclPlugin' => function($sm) {
                    $loggingService = $sm->get('LoggingService');
                    
                    $config = $sm->get('config');
                    $session = $config['session'];

                    
                    return new \MyAcl\Controller\Plugin\MyAclPlugin($loggingService, $session);
                },
            ),
         ),
        // end: added for Acl   ###################################    
);
