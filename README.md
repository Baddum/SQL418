SQL418
==============
[![Latest Stable Version](https://poser.pugx.org/baddum/sql418/v/stable.svg)](https://github.com/Baddum/SQL418)
[![Build Status](https://travis-ci.org/Baddum/SQL418.png?branch=master)](https://travis-ci.org/Baddum/SQL418)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Baddum/SQL418/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Baddum/SQL418/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Baddum/SQL418/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Baddum/SQL418/?branch=master)
[![Total Downloads](https://poser.pugx.org/baddum/sql418/downloads.svg)](https://packagist.org/packages/baddum/sql418)
[![License](https://poser.pugx.org/baddum/sql418/license.svg)](http://opensource.org/licenses/MIT)


`SQL418` is a PHP library that allows you to modify a SQL requests by extending it.



Let's code
--------------

```php
$sql = (new Baddum\SQL418\Request);
$sql->init('SELECT * from table');
echo $sql->output();
// SELECT * FROM table;

$sql->extend('WHERE table.id = 39');
echo $sql->output();
// SELECT * FROM table WHERE table.id = 39;

$sql->extend('SELECT table.column');
echo $sql->output();
// SELECT table.column FROM table WHERE table.id = 39;

$sql->extend('UPDATE & SET table.column = "coco"');
echo $sql->output();
// UPDATE table SET table.column = "coco" WHERE table.id = 39;
```



Use cases
--------------

`SQL418` allows to write DRYer SQL requests.
In the following example, the `fetchById` and `deleteById` requests share a common pattern:

```php
class UserModel {
  protected function getRequestBase() {
    return $sql->init('SELECT * from user JOIN user_credentials ON user_credentials.id = user.id');
  }
  protected function getRequestFetchById() {
    return $this->getRequestBase()->extend('WHERE &( AND) user.id=?');
  }
  protected function getRequestDeleteById() {
    return $this->getRequestFetchById()->extend('DELETE &');
  }
  
  public function fetchById($id) {
    $request = $this->getRequestFetchById();
    // ...
  }
  public function deleteById($id) {
    $request = $this->getRequestDeleteById();
    // ...
  }
}
```

Most of all, it allows you can to extends your own application.
In the following example, we extend the `UserModel` to do a soft delete:

```php
class UserModelSoftDelete extends UserModel {
  protected function getRequestBase() {
    return parent::getRequestBase()->extend('WHERE user.deleted = 0');
  }
  protected function getRequestDeleteById() {
    return $this->getRequestFetchById()->extend('UPDATE & SET user.deleted = 1');
  }
}
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