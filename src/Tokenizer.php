<?php

namespace Baddum\SQL418;

class Tokenizer
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $statement;
    protected $keywordList;
    protected $tokenMap;
    protected $currentBuffer = [];
    protected $currentKeyword;


    /* PUBLIC METHODS
     *************************************************************************/
    public function from($statement)
    {
        $this->statement = $statement;
        return $this;
    }

    public function with($keywordList)
    {
        $this->keywordList = $keywordList;
        return $this;
    }

    public function tokenize()
    {
        $statementPartList = $this->explodeWords($this->statement);

        $this->currentKeyword = null;
        $this->currentBuffer = [];
        foreach ($statementPartList as $statementPart) {
            if (!$this->hasBufferUnterminatedQuote()) {
                $buffer = $this->currentBuffer;
                $keyword = strtoupper($statementPart);
                do {
                    if (in_array($keyword, $this->keywordList)) {
                        $this->currentBuffer = $buffer;
                        $this->clearBuffer();
                        $this->currentKeyword = $keyword;
                        continue 2;
                    }
                    $continue = ! empty($buffer);
                    if ($continue) {
                        $keyword = strtoupper(array_pop($buffer)) . ' ' . $keyword;
                    }
                } while ($continue);
            }
            $this->currentBuffer[] = $statementPart;
        }
        $this->clearBuffer();

        return $this->tokenMap;
    }


    /* PROTECTED METHODS
     *************************************************************************/
    protected function clearBuffer()
    {
        if ($this->currentKeyword) {
            $this->tokenMap[$this->currentKeyword] = implode(' ', $this->currentBuffer);
            $this->currentBuffer = [];
            $this->currentKeyword = null;
        }
    }

    protected function hasBufferUnterminatedQuote()
    {
        $string = implode('', $this->currentBuffer);

        $quoteList = [
            ['open' => '"', 'close' => '"', 'nesting' => false],
            ['open' => "'", 'close' => "'", 'nesting' => false],
            ['open' => '(', 'close' => ')', 'nesting' => true]
        ];

        // Remove escaped characters
        $string = str_replace('\\\\', '', $string);
        foreach ($quoteList as $quote) {
            $quoteCharacterList = array_unique(array($quote['open'], $quote['close']));
            foreach ($quoteCharacterList as $quoteCharacter) {
                $string = str_replace('\\' . $quoteCharacter, '', $string);
            }
        }

        // Group starting quotes
        $startingQuoteList = [];
        foreach ($quoteList as $key => $quoteCouple) {
            $startingQuoteList[$key] = $quoteCouple['open'];
        }

        $openedQuoteList = [];
        $currentQuote = false;
        for ($i = 0; $i < strlen($string); $i++) {
            $char = $string[$i];

            // Catch opening quote
            if ($currentQuote === false || $currentQuote['nesting']) {
                $quoteIndex = array_search($char, $startingQuoteList);
                if ($quoteIndex !== false) {
                    $openedQuoteList[] = $quoteIndex;
                    $currentQuote = $quoteList[$quoteIndex];
                    continue;
                }
            }

            // Catch closing quote
            if ($currentQuote !== false && $char == $currentQuote['close']) {
                array_pop($openedQuoteList);
                $quoteIndex = end($openedQuoteList);
                if ($quoteIndex === false) {
                    $currentQuote = false;
                } else {
                    $currentQuote = $quoteList[$quoteIndex];
                }
            }
        }

        return !empty($openedQuoteList);
    }

    protected function explodeWords($string)
    {
        $partList = [];
        $string = trim($string);
        $spaceCharacterList = [' ', "\n", "\t"];
        $buffer = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = $string[$i];
            if (in_array($char, $spaceCharacterList)) {
                if (!empty($buffer)) {
                    $partList[] = $buffer;
                    $buffer = '';
                }
                continue;
            }
            $buffer .= $char;
        }
        $partList[] = $buffer;
        return $partList;
    }
}