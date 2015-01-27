<?php

namespace Baddum\SQL418\Test;

use Baddum\SQL418\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    
    public function testExtendWithoutParentInheritance()
    {
        $sql = (new Request)->init('SELECT * from table ');
        $this->assertEquals('SELECT * FROM table;', $sql->output());
        $sql->extend('WHERE table.id = 39');
        $this->assertEquals('SELECT * FROM table WHERE table.id = 39;', $sql->output());
        $sql->extend('SELECT table.name');
        $this->assertEquals('SELECT table.name FROM table WHERE table.id = 39;', $sql->output());
    }

    public function testExtend()
    {
        $sql = (new Request)->init('SELECT table.column from table ');
        $this->assertEquals('SELECT table.column FROM table;', $sql->output());
        $sql->extend('SELECT & FROM & WHERE table.id = 39');
        $this->assertEquals('SELECT table.column FROM table WHERE table.id = 39;', $sql->output());
        $sql->extend('SELECT &, table.name FROM & LEFT JOIN user WHERE & AND table.is_active = 1');
        $this->assertEquals('SELECT table.column, table.name FROM table LEFT JOIN user WHERE table.id = 39 AND table.is_active = 1;', $sql->output());
        $sql->extend('From table WHERE table.name = "\&"');
        $this->assertEquals('SELECT table.column, table.name FROM table WHERE table.name = "&";', $sql->output());
    }
}