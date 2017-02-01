# TODO

This is a TODO list for the feature/zend-mvc-v3-minimal branch.

##Redis Changes for Load-Balanced Web Servers Session Management
    MyAuthStorage needs to be serialized, but it extends Zend\Authentication\Storage\Session which presents several 
    problems in terms of serialization and deserialization. So the answer is to add a toArray() function that serializes
    instance state that is required to "de-serialize" a MyAuthStorage object manually.
    MyAuthStorage instance state pre-changes.
        - rememberMe
        - roles (UserRoleXref's)
        - user (User)
        - requestedRoute (string aka: login)
        - salespersoninplay (User)
  RedisService will serialize the array returned from MyAuthStorage->toArray() and save it by SESSIONID in Redis
  then RedisService will save seesionState and restoreSessionState by de-serializing the array and calling 
  MyAuthStorage::getInstance() to restore the MyAuthStorage instance.
