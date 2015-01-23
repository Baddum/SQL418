<?php

namespace Elephant418\SQL418;

class Request {


    /* ATTRIBUTES
     *************************************************************************/
    protected $type;
    protected $optionMap = array();
    protected $keywordMap = array(
        'SELECT' => array('SELECT', 'FROM', 'WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 'LIMIT')
    );


    /* PUBLIC METHODS
     *************************************************************************/
    public function init($sql) {
        $this->type;
        $this->optionMap = array();
        return $this->extend($sql);
    }
    
    public function extend($sql) {
        $sql = trim(rtrim(trim($sql), ';'));
        $type = $this->extractType($sql);
        if ($type !== false) {
            $this->type = $type;
        }
        foreach ($this->extractOptionMap($sql) as $keyword => $option) {
            $this->optionMap[$keyword] = $option;
        }
        return $this;
    }

    public function output() {
        $sql = ' ';
        if (! isset($this->keywordMap[$this->type])) {
            throw new \RuntimeException('Try to output an unknown SQL request type: '.$this->type);
        }
        foreach ($this->keywordMap[$this->type] as $keyword) {
            if (isset($this->optionMap[$keyword])) {
                $sql .= ' '.$keyword . ' ' . $this->optionMap[$keyword];
            }
        }
        return trim($sql).';';
    }


    /* PROTECTED METHODS
     *************************************************************************/
    protected function extractType($sql) {
        foreach (array_keys($this->keywordMap) as $type) {
            if (substr($sql, 0, strlen($type) + 1) == $type.' ') {
                return $type;
            }
        }
        return false;
    }

    protected function extractOptionMap($sql) {
        $keywordPattern = implode('|', $this->keywordMap[$this->type]);
        $optionMap = array();
        $matchList = array();
        $sql .= ' ';
        do {
            if ($matchList) {
                $optionMap[strtoupper($matchList[2])] = trim($matchList[3]);
                $sql = $matchList[1];
            }
            $match = preg_match('/^(.*)(?:('.$keywordPattern.') ([^"]*(?:"[^"]*")*[^"]*)) (?:'.$keywordPattern.'|$)/i', $sql, $matchList);
        } while ($match);
        return $optionMap;
    }
}

$sql = (new SQL418)->init('SELECT * from table ');
echo $sql->output().PHP_EOL;
$sql->extend('WHERE table.id = 39');
echo $sql->output().PHP_EOL;
$sql->extend('SELECT table.name');
echo $sql->output().PHP_EOL;