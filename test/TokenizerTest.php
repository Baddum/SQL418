<?php

namespace Baddum\SQL418\Test;

use Baddum\SQL418\Tokenizer;

class TokenizerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider providerSQLToken
     */
    public function testSQLToken($expectedTokenList)
    {
        $statement = [];
        foreach ($expectedTokenList as $expectedToken) {
            list($keyword, $value) = $expectedToken;
            $statement[] = $keyword;
            $statement[] = $value;
        }
        $statement = implode(' ', $statement);

        $actualTokenList = (new Tokenizer)
            ->from($statement)
            ->with(array('SELECT', 'FROM', 'WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 'LIMIT'))
            ->tokenize();

        foreach ($expectedTokenList as $index => $token) {
            $this->assertEquals($token, $actualTokenList[$index]);
        }
    }

    public function providerSQLToken()
    {
        return [
            [
                [
                    ['SELECT', 'table.column'],
                    ['FROM', 'table'],
                    ['WHERE', 'table.id = 3'],
                    ['ORDER BY', 'table.name'],
                    ['LIMIT', '2']
                ]
            ],
            [
                [
                    ['SELECT', 'table.column'],
                    ['FROM', 'table'],
                    ['WHERE', 'table.id = " SELECT * FROM myTable WHERE id=3 "']
                ]
            ],
            [
                [
                    ['SELECT', 'table.column'],
                    ['FROM', 'table'],
                    ['WHERE', 'table.id = "SELECT * FROM myTable WHERE thing=\" SELECT \" LIMIT 3"'],
                    ['LIMIT', 1]
                ]
            ],
            [
                [
                    ['SELECT', 'tmp (SELECT * FROM table WHERE status=1)'],
                    ['FROM', 'table'],
                    ['WHERE', 'table.id = "SELECT * FROM myTable WHERE thing=\" SELECT \" LIMIT 3"'],
                    ['LIMIT', 1]
                ]
            ],
            [
                [
                    ['SELECT', 'tmp (SELECT * FROM table WHERE status="1")'],
                    ['FROM', 'table'],
                    ['WHERE', 'table.id = "SELECT * FROM myTable WHERE thing=\" SELECT \" LIMIT 3"'],
                    ['LIMIT', 1]
                ]
            ],
            [
                [
                    ['SELECT', 'tmp (SELECT * FROM table WHERE status=")")'],
                    ['FROM', 'table'],
                    ['WHERE', 'table.id = "SELECT * FROM myTable WHERE thing=\" SELECT \" LIMIT 3"'],
                    ['LIMIT', 1]
                ]
            ]
        ];
    }

    public function testListToken()
    {
        $statement = 't1 , t5 LEFT JOIN (t2, t3, t4) ON (t2.a=t1.a AND t3.b=t1.b AND t4.c=t1.c)';
        $actualTokenList = (new Tokenizer)
            ->from($statement)
            ->with(',')
            ->tokenize();
        
        $this->assertEquals(2, count($actualTokenList));
    }
}