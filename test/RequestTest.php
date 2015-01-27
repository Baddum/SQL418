<?php

namespace Baddum\SQL418\Test;

use Baddum\SQL418\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testFolderWithoutEndingSlash()
    {
        $sql = (new Request)->init('SELECT * from table ');
        $this->assertEquals('SELECT * FROM table;', $sql->output());
        $sql->extend('WHERE table.id = 39');
        $this->assertEquals('SELECT * FROM table WHERE table.id = 39;', $sql->output());
        $sql->extend('SELECT table.name');
        $this->assertEquals('SELECT table.name FROM table WHERE table.id = 39;', $sql->output());
    }
}