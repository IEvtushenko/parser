<?php

namespace Lib\Parser;

class Parser
{
    const ANTI_PATTERN = '~(\S[^/)/(])~';
    const PATTERN = "~[()/s]~";

    /**
     * @param string $string
     *
     * @return array|null
     */
    private function parser(string $string): ?array
    {
        $matches = [];
        if (preg_match_all(self::PATTERN, $string, $matches)) {
            return $matches;
        } else {
            return null;
        }

    }

    public function runFile(string $filePath)
    {
        //Получение файла вызов parser
    }

    public function runParsing(string $string)
    {
        $matches = null;
        if (!$this->findError($string)) {
            $matches = $this->parser($string);
        }

        if (!is_null($matches)) {
            return $this->countBrackets($matches[0]);
        }
    }

    /**
     * @param string $string
     *
     * @return bool|null
     */
    private function findError(string $string): ?bool
    {
        $matches = [];
        if ($result = preg_match(self::ANTI_PATTERN, $string, $matches)) {
            throw new \InvalidArgumentException('invalid symbols in string');
        } else {
            return false;
        }
    }

    /**
     * @param array $matches
     *
     * @return bool
     */
    private function countBrackets(array $matches): bool
    {
        $counter = 0;
        $openBrackets = array_filter($matches, function ($var) {
            return $var == "(";
        });
        $closeBrackets = array_filter($matches, function ($var) {
            return $var == ")";
        });

        if (count($openBrackets) == count($closeBrackets)) {
            return true;
        }

        return false;
    }
}