SQL418
==============
[![Latest Stable Version](https://poser.pugx.org/baddum/sql418/v/stable.svg)](https://github.com/Baddum/SQL418)
[![Build Status](https://travis-ci.org/Baddum/SQL418.png?branch=master)](https://travis-ci.org/Baddum/SQL418)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Baddum/SQL418/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Baddum/SQL418/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Baddum/SQL418/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Baddum/SQL418/?branch=master)
[![Total Downloads](https://poser.pugx.org/baddum/sql418/downloads.svg)](https://packagist.org/packages/baddum/sql418)
[![License](https://poser.pugx.org/baddum/sql418/license.svg)](http://opensource.org/licenses/MIT)


SQL418 is a PHP library that allow you to extend the SQL requests.



Let's code
--------------

```php
use Baddum\SQL418\Request;
$sql = (new Request)->init('SELECT * from table ');
echo $sql->output().PHP_EOL;
// SELECT * FROM table;
$sql->extend('WHERE table.id = 39');
echo $sql->output().PHP_EOL;
// SELECT * FROM table WHERE table.id = 39;
$sql->extend('SELECT table.name');
echo $sql->output().PHP_EOL;
// SELECT table.name FROM table WHERE table.id = 39;
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