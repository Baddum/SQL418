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


    /* GETTER & SETTER METHODS
     *************************************************************************/
    public function get($keyword)
    {
        if (isset($this->tokenMap[$keyword])) {
            return $this->tokenMap[$keyword];
        }
        return false;
    }
    
    public function set($keyword, $value)
    {
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

    protected function replaceEscapedTag($subject, $search, $replace)
    {
        $subjectPartList = explode('\\\\', $subject);
        foreach ($subjectPartList as $key => $subjectPart) {
            $subSubjectPartList = explode('\\'.$search, $subjectPart);
            foreach ($subSubjectPartList as $subKey => $subSubjectPart) {
                $subSubjectPartList[$subKey] = str_replace($search, $replace, $subSubjectPart);
            }
            $subjectPartList[$key] = implode($search, $subSubjectPartList);
        }
        return implode('\\'.$search, $subjectPartList);
    }
}