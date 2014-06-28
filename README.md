TODOListBundle
==============

1. What is it ?

This project intend to propose a TODOList using local storage or Google Api Task.
This project is realised by [Kevin PIGNOT](https://github.com/Cartman117) and [Olivier GOYON](https://github.com/ss-bb)

2. How to install it ?

You need an Apache server and Symfony2. You'll also need a MySQL database and apply this script [schema.sql](https://github.com/Cartman117/TODOListBundle/blob/master/schema.sql)
to your mysql server, after creating your database.

For this you can use Vagrant [PHP-VM](https://github.com/willdurand-edu/php-vm) where the database, apache are already configured. But you will have to apply the SQL script.

Then you will have to configure Doctrine, to use your database in /app/config in Symfony2 :
    - parameters.yml
    - config.yml

Add the follwing code into your /app/config/routing.yml
<code>
todo_list:
    resource: "@TODOListBundle/Resources/config/routing.yml"
    prefix:   /
</code>

You must include [HappyR - GoogleApiBundle](https://github.com/HappyR/GoogleApiBundle) and add our Bundle into Symfony2 and register them in your AppKernel.php

3. How does it work ?

You will have several pages to manage your tasks or your tasklists to create, update, get or delete them.

4. Issues

It's not possible to destroy token after getting Authenticated when clicking on Exit link.
Can't catch
