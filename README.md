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



Author & Community
--------

SQL418 is under [MIT License](http://opensource.org/licenses/MIT).<br>
It was created & is maintained by [Thomas ZILLIOX](http://tzi.fr).