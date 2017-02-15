Notes:

Contexts and related functionality.

/sales      --> Sales\Controller\SalesController
This route only displays all salespeople. (MASTER)

/users      --> Sales\Controller\UsersController
This route displays all Users for a particular salesperson.

/items      --> Sales\Controller\ItemsController (extends AbstractController)
          
/ajax/items --> Ajax\Controller\Sales\ItemsController (extends AbstractRestfulController)
                Handles:
                    - switch based on query parameter "action"
                    - handles Remove row from /items route.
                    - handles overridePrice from /items route.
                    - handles ajax data-source calls for items datatable


