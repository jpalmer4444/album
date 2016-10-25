<?php

return array(
        // added for Acl   ###################################
        'controller_plugins' => array(
            'factories' => array(
                'MyAclPlugin' => function($sm) {
                    $loggingService = $sm->get('LoggingService');
                    $authService = $sm->get('AuthService');
                    return new \MyAcl\Controller\Plugin\MyAclPlugin($loggingService, $authService);
                },
            ),
         ),
        // end: added for Acl   ###################################    
);
