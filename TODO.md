# TODO

Change AuthService->setStorage() to use PredisService.
Find all AuthService->getStorage() calls that used to return MyAuthStorage objects
    make the code return (Un)Serializable MyAuthStorage objects.

?? Configure SessionStorage with custom SessionStorage