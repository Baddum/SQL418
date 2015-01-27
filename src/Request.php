<?php

namespace Baddum\SQL418;

class Request
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $type;
    protected $tokenMap = array();
    protected $keywordMap = array(
        'SELECT' => ['SELECT', 'FROM', 'WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 'LIMIT'],
    );


    /* PUBLIC METHODS
     *************************************************************************/
    public function init($statement)
    {
        $this->type;
        $this->tokenMap = array();
        return $this->extend($statement);
    }

    public function extend($statement)
    {
        $statement = trim(rtrim(trim($statement), ';'));
        $type = $this->extractType($statement);
        if ($type !== false) {
            $this->type = $type;
        }
        $this->tokenMap = array_merge($this->tokenMap, $this->extractTokenMap($statement));
        return $this;
    }

    public function output()
    {
        $statement = ' ';
        if (!isset($this->keywordMap[$this->type])) {
            throw new \RuntimeException('Try to output an unknown SQL request type: ' . $this->type);
        }
        foreach ($this->keywordMap[$this->type] as $keyword) {
            if (isset($this->tokenMap[$keyword])) {
                $statement .= ' ' . $keyword . ' ' . $this->tokenMap[$keyword];
            }
        }
        return trim($statement) . ';';
    }


    /* PROTECTED METHODS
     *************************************************************************/
    protected function extractType($statement)
    {
        foreach (array_keys($this->keywordMap) as $type) {
            if (substr($statement, 0, strlen($type) + 1) == $type . ' ') {
                return $type;
            }
        }
        return false;
    }

    protected function extractTokenMap($statement)
    {
        $tokenMap = (new Tokenizer)
            ->from($statement)
            ->with($this->keywordMap[$this->type])
            ->tokenize();
        return $tokenMap;
    }
}