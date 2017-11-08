## This module is a wrapper for netcore/translations

Module allows you to manage user access right by giving them roles and access levels.

## Pre-installation
This package is part of Netcore CMS ecosystem and is only functional in a project that has following packages installed:

https://github.com/netcore/netcore

https://github.com/netcore/module-admin

https://github.com/netcore/module-user

https://github.com/nWidart/laravel-modules

## Installation
 
 Require this package with composer:
 ```$xslt
 composer require netcore/module-permission

```
 Publish config, assets, migrations. Migrate and seed:
 
 ```$xslt
 php artisan module:publish Permission
 php artisan module:publish-migration Permission
 php artisan migrate
 php artisan module:seed Permission
```

## Usage

Everything module related will be under `Permissions` section in acp.

Managing roles:
![Roles](https://node-eu.takescreen.io/media/c/c9fd2bafb1cf006b63edc80de1cc83f2.png)

Managing access levels:
![Levels](https://node-eu.takescreen.io/media/4/48b507bc6d16d85f134de45633069981.png)
