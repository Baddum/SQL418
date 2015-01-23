SQL418
==============

SQL418 is a PHP library that allow you to extend the SQL requests.



Let's code
--------------

```php
use Elephant418\SQL418\Request;
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

### Get involved

1. [Star](https://github.com/elephant418/model418/stargazers) the project!
2. [Report a bug](https://github.com/elephant418/model418/issues/new) that you find
3. Tweet and blog about Model418 and [Let me know](https://twitter.com/iamtzi) about it.

### Pull Requests

Pull requests are highly appreciated.<br>
Please review the [guidelines for contributing](https://github.com/Elephant418/Model418/blob/master/CONTRIBUTING.md) to go further.



Author & Community
--------

SQL418 is under [MIT License](http://opensource.org/licenses/MIT).<br>
It was created & is maintained by [Thomas ZILLIOX](http://tzi.fr).