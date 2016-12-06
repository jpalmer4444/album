<?php

return array(
        // added for Acl   ###################################
        'controller_plugins' => array(
            'factories' => array(
                'MyAclPlugin' => 'MyAcl\Controller\Factory\MyAclPluginFactory',
            ),
         ),
        // end: added for Acl   ###################################    
);
