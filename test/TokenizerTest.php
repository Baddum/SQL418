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

        $actualTokenMap = (new Tokenizer)
            ->from($statement)
            ->with(array('SELECT', 'FROM', 'WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 'LIMIT'))
            ->tokenize();

        foreach ($expectedTokenList as $keyword => $value) {
            $this->assertEquals($value, $actualTokenMap[$keyword]);
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
                    ['WHERE' ,'table.id = " SELECT * FROM myTable WHERE id=3 "']
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
}