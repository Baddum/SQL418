<?php

namespace Baddum\SQL418\Test;

use Baddum\SQL418\Tokenizer;

class TokenizerTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @dataProvider providerSQLToken
     */
    public function testSQLToken($expectedTokenMap)
    {
        $statement = [];
        foreach ($expectedTokenMap as $keyword => $value) {
            $statement[] = $keyword;
            $statement[] = $value;
        }
        $statement = implode(' ', $statement);
        
        $actualTokenMap = (new Tokenizer)
            ->from($statement)
            ->with(array('SELECT', 'FROM', 'WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 'LIMIT'))
            ->tokenize();
        
        foreach ($expectedTokenMap as $keyword => $value) {
            $this->assertEquals($value, $actualTokenMap[$keyword]);
        }
    }

    public function providerSQLToken()
    {
        return [
            [
                [
                    'SELECT' => 'table.coumn',
                    'FROM' => 'table',
                    'WHERE' => 'table.id = " SELECT * FROM myTable WHERE id=3 "'
                ]
            ],
            [
                [
                    'SELECT' => 'table.coumn',
                    'FROM' => 'table',
                    'WHERE' => 'table.id = "SELECT * FROM myTable WHERE thing=\" SELECT \" LIMIT 3"',
                    'LIMIT' => 1
                ]
            ],
            [
                [
                    'SELECT' => 'tmp (SELECT * FROM table WHERE status=1)',
                    'FROM' => 'table',
                    'WHERE' => 'table.id = "SELECT * FROM myTable WHERE thing=\" SELECT \" LIMIT 3"',
                    'LIMIT' => 1
                ]
            ],
            [
                [
                    'SELECT' => 'tmp (SELECT * FROM table WHERE status="1")',
                    'FROM' => 'table',
                    'WHERE' => 'table.id = "SELECT * FROM myTable WHERE thing=\" SELECT \" LIMIT 3"',
                    'LIMIT' => 1
                ]
            ],
            [
                [
                    'SELECT' => 'tmp (SELECT * FROM table WHERE status=")")',
                    'FROM' => 'table',
                    'WHERE' => 'table.id = "SELECT * FROM myTable WHERE thing=\" SELECT \" LIMIT 3"',
                    'LIMIT' => 1
                ]
            ]
        ];
    }
}