<?php

namespace Baddum\SQL418;

class Request
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $type;
    protected $tokenMap = array();
    protected $keywordAliasMap = [
        'FROM' => 'TABLE',
        'UPDATE' => 'TABLE',
        'INSERT INTO' => 'TABLE',
        'DELETE' => 'TABLE',
    ];
    protected $keywordMap = array(
        'SELECT' => ['SELECT', 'FROM', 'WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 'LIMIT'],
        'UPDATE' => ['UPDATE', 'SET', 'WHERE', 'ORDER BY', 'LIMIT'],
        'INSERT' => ['INSERT INTO', 'SET', 'VALUES'],
        'DELETE' => ['DELETE', 'WHERE', 'ORDER BY', 'LIMIT'],
    );


    /* GETTER & SETTER METHODS
     *************************************************************************/
    public function get($keyword)
    {
        if (isset($this->keywordAliasMap[$keyword])) {
            $keyword = $this->keywordAliasMap[$keyword];
        }
        if (isset($this->tokenMap[$keyword])) {
            return $this->tokenMap[$keyword];
        }
        return false;
    }

    public function set($keyword, $value)
    {
        if (isset($this->keywordAliasMap[$keyword])) {
            $keyword = $this->keywordAliasMap[$keyword];
        }
        $old = $this->get($keyword);
        $value = $this->replaceEscapedTag($value, '&', $old);
        $this->tokenMap[$keyword] = $value;
        return false;
    }


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
        $tokenMap = $this->extractTokenMap($statement);
        foreach ($tokenMap as $keyword => $value) {
            $this->set($keyword, $value);
        }
        return $this;
    }

    public function output()
    {
        $statementPartList = [];
        if (!isset($this->keywordMap[$this->type])) {
            throw new \RuntimeException('Try to output an unknown SQL request type: ' . $this->type);
        }
        foreach ($this->keywordMap[$this->type] as $keyword) {
            $value = $this->get($keyword);
            if ($value !== false) {
                $statementPartList[] = $keyword;
                $statementPartList[] = $value;
            }
        }
        return trim(implode(' ', $statementPartList)) . ';';
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

    protected function replaceEscapedTag($subject, $search, $replace)
    {
        $subjectPartList = explode('\\\\', $subject);
        foreach ($subjectPartList as $key => $subjectPart) {
            $subSubjectPartList = explode('\\' . $search, $subjectPart);
            foreach ($subSubjectPartList as $subKey => $subSubjectPart) {
                if (!empty($replace)) {
                    $replace .= '${2}';
                }
                $subSubjectPart = preg_replace('/&(\(([^)]*)\)|)/i', $replace, $subSubjectPart);
                $subSubjectPartList[$subKey] = trim($subSubjectPart);
            }
            $subjectPartList[$key] = implode($search, $subSubjectPartList);
        }
        return implode('\\' . $search, $subjectPartList);
    }
}