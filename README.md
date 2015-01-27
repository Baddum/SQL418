SQL418
==============
[![Latest Stable Version](https://poser.pugx.org/baddum/sql418/v/stable.svg)](https://github.com/Baddum/SQL418)
[![Build Status](https://travis-ci.org/Baddum/SQL418.png?branch=master)](https://travis-ci.org/Baddum/SQL418)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Baddum/SQL418/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Baddum/SQL418/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Baddum/SQL418/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Baddum/SQL418/?branch=master)
[![Total Downloads](https://poser.pugx.org/baddum/sql418/downloads.svg)](https://packagist.org/packages/baddum/sql418)
[![License](https://poser.pugx.org/baddum/sql418/license.svg)](http://opensource.org/licenses/MIT)


`SQL418` is a PHP library that allows you to modify a SQL requests by extending it.

1. [Let's code](#lets-code)
2. [How to Install](#how-to-install)
3. [How to Contribute](#how-to-contribute)
4. [Author & Community](#author--community)



Let's code
--------------

### Basics 

```php
$sql = new Baddum\SQL418\Request('SELECT * from table');
echo $sql;
// SELECT * FROM table;

$sql->extend('WHERE table.id = 39');
echo $sql;
// SELECT * FROM table WHERE table.id = 39;

$sql->extend('SELECT table.column');
echo $sql;
// SELECT table.column FROM table WHERE table.id = 39;

$sql->extend('UPDATE & SET table.column = "coco"');
echo $sql;
// UPDATE table SET table.column = "coco" WHERE table.id = 39;
```


### Use case: DRYer requests 

In the following example, the `fetchById` and `deleteById` requests share a common pattern:

```php
class UserModel {
  protected function getRequestFetchById() {
    return 'SELECT * from user WHERE user.id=?';
  }
  protected function getRequestDeleteById() {
    return new Request($this->getRequestFetchById())
      ->extend('DELETE');
  }
}
```

### Use case: extensible applications
In the following example, we extend the `UserModel` to do a soft delete:

```php
class UserModelSoftDelete extends UserModel {
  protected function getRequestFetchById() {
    return new Request(parent::getRequestFetchById())
      ->extend('WHERE & AND user.deleted = 0');
  }
  protected function getRequestDeleteById() {
    return new Request($this->getRequestFetchById())
      ->extend('UPDATE & SET user.deleted = 1');
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