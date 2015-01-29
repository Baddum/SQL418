SQL418
==============
[![Latest Stable Version](https://poser.pugx.org/baddum/sql418/v/stable.svg)](https://github.com/Baddum/SQL418)
[![Build Status](https://travis-ci.org/Baddum/SQL418.png?branch=master)](https://travis-ci.org/Baddum/SQL418)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Baddum/SQL418/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Baddum/SQL418/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Baddum/SQL418/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Baddum/SQL418/?branch=master)
[![Total Downloads](https://poser.pugx.org/baddum/sql418/downloads.svg)](https://packagist.org/packages/baddum/sql418)
[![License](https://poser.pugx.org/baddum/sql418/license.svg)](http://opensource.org/licenses/MIT)


`SQL418` is a PHP library that allows you to modify a SQL requests by extending it.

1. [Features](#features)
2. [Use case](#use-cases)
3. [How to Install](#how-to-install)
4. [How to Contribute](#how-to-contribute)
5. [Author & Community](#author--community)



Features
--------------

Use the `extend()` method to complete a SQL request.
For example, you can add a `WHERE` clause to a `SELECT` request:

```php
$request = new Baddum\SQL418\Request('SELECT * from table');
echo $request;
// SELECT * FROM table;

echo $request->extend('WHERE id = 39');
// SELECT * FROM table WHERE id = 39;
```

You can override a defined part of a SQL request, like changing the selected fields:

```php
echo $request->extend('SELECT name');
// SELECT name FROM table WHERE id = 39;
```

Use the `&` keyword to extend a part of a SQL request.
For example, you can add a field to select:

```php
echo $request->extend('SELECT &, id');
// SELECT name, id FROM table WHERE id = 39;
```

You can change the type a SQL request, like changing a `SELECT` request to a `DELETE` one:

```php
echo $request->extend('DELETE');
// DELETE table WHERE id = 39;
```

You can also use all the features together:

```php
$sql->extend('UPDATE SET name = "Albert" WHERE & AND right <> admin"');
echo $sql;
// UPDATE table SET name = "Albert" WHERE id = 39 AND right <> admin;
```



Use cases
--------------

### Use case: DRYer requests 

In the following example, the `fetchById` and `deleteById` requests share a common pattern:

```php
class UserModel {
  protected $SQLFetchById = 'SELECT * from user WHERE user.id=?';
  protected $SQLDeleteById = '';
  public function __construct() {
    $request = new Request($this->SQLFetchById);
    $this->SQLDeleteById = $request->extend('DELETE');
  }
}
```

### Use case: extensible applications
In the following example, we extend the `UserModel` to do a soft delete:

```php
class UserModelSoftDelete extends UserModel {
  public function __construct() {
    $request = new Request($this->SQLFetchById);
    $this->SQLFetchById = $request->extend('WHERE & AND user.deleted = 0');
    $this->SQLDeleteById = $request->extend('UPDATE & SET user.deleted = 1');
  }
}
```


How to Install
--------

This library package requires `PHP 5.4` or later.<br>
Install [Composer](http://getcomposer.org/doc/01-basic-usage.md#installation) and run the following command to get the latest version:

```sh
composer require baddum/sql418:~1.1
```



How to Contribute
--------

1. [Star](https://github.com/Baddum/SQL418/stargazers) the project!
2. Tweet and blog about SQL418 and [Let me know](https://twitter.com/iamtzi) about it.
3. [Report a bug](https://github.com/Baddum/SQL418/issues/new) that you find
4. Pull requests are highly appreciated. Please review the [guidelines for contributing](https://github.com/Baddum/SQL418/blob/master/CONTRIBUTING.md) to go further.



Author & Community
--------

SQL418 is under [MIT License](http://opensource.org/licenses/MIT).<br>
It was created & is maintained by [Thomas ZILLIOX](http://tzi.fr).