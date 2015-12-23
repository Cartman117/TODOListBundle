TODOListBundle
==============

1. What is it ?
---------------

This project intend to propose a TODOList using local storage or Google Api Task.

2. How to install it ?
----------------------

You need an Apache server and Symfony2. You'll also need a MySQL database and apply this script [schema.sql](https://github.com/Cartman117/TODOListBundle/blob/master/schema.sql)
to your mysql server, after creating your database.

For this you can use Vagrant [PHP-VM](https://github.com/willdurand-edu/php-vm) where the database, apache are already configured. But you will have to apply the SQL script.

Then you will have to configure Doctrine, to use your database in /app/config in Symfony2 :
<code>
    - parameters.yml
    - config.yml
</code>
Add the follwing code into your /app/config/routing.yml

```
    todo_list:
        resource: "@TODOListBundle/Resources/config/routing.yml"
        prefix:   /
```

This into you /app/config/config.yml

```
    happy_r_google_api:
        application_name:       "TODOListBundle"
        oauth2_client_id:       "XXXX"
        oauth2_client_secret:   "XXXX"
        oauth2_redirect_uri:    "XXXX/TODOList/oauth/callback"
        developer_key:          "XXXX"
        site_name:              "XXXX/TODOList"
    parameters:
        todolist_google_client:         HappyR\Google\ApiBundle\Services\GoogleClient
        todolist_access_denied_handler: TODOListBundle\Security\Authorization\AccessDeniedHandler
        todolist_authenticator:         TODOListBundle\Security\Authentication\Authenticator
    services:
        todolist_google_client:
            class:      %todolist_google_client%
            arguments:  [%happy_r_google_api%]
        todolist_access_denied_handler:
            class:      %todolist_access_denied_handler%
            arguments:  [@todolist_google_client, @router]
            tags:
                - { name: kernel.event_listener, event: kernel.exception, method: onKernelException }
        todolist_authenticator:
            class:      %todolist_authenticator%
```

And this into your /app/config/security.yml

```
    security:
        providers:
            in_memory:
                memory: ~
        firewalls:
            dev:
                pattern: ^/(_(profiler|wdt)|css|images|js)/
                security: false
            default:
                pattern:  ^/
                simple_preauth:
                    authenticator: todolist_authenticator
                access_denied_handler: todolist_access_denied_handler
        access_control:
            - { path: ^/$, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/TODOList$, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/TODOList/oauth/callback, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/TODOList/lists, role: IS_AUTHENTICATED_ANONYMOUSLY }
        access_denied_url: /TODOList/oauth/callback
```

You must include [HappyR - GoogleApiBundle](https://github.com/HappyR/GoogleApiBundle) and add our Bundle into Symfony2 and register them in your AppKernel.php

3. How does it work ?
---------------------

You will have several pages to manage your tasks or your tasklists to create, update, get or delete them.

4. Issues
---------

It's not possible to destroy token after getting Authenticated when clicking on Exit link.
