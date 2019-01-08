<?php

namespace Lib\Parser;

class Parser
{
    const ANTI_PATTERN = '~(\S[^/)/(])~';
    const PATTERN = "~[()/s]~";
    const DIR_PATH = 'test.work/';

    private $options = 'p:';
    private $arguments = [];

    public function __construct()
    {
        $this->arguments = getopt($this->options);
    }

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

    /**
     * @param string|null $string
     *
     * @return bool|null
     */
    public function run(?string $string = ''): ?bool
    {
        if (!empty($this->arguments)) {
            if (array_key_exists('p', $this->arguments)) {
                $filePath = sprintf('%s%s', self::DIR_PATH, $this->arguments['p']);
                return $this->runFileParsing($filePath);
            }
        } else {
            if (!empty($string)) {
                return $this->runParsing($string);
            }
        }
        return false;
    }

    /**
     * @param string $filePath
     *
     * @return bool|null
     */
    public function runFileParsing(string $filePath): ?bool
    {
        $string = file_get_contents($filePath);
        return $this->runParsing($string);
    }

    /**
     * @param string $string
     *
     * @return bool|null
     */
    private function runParsing(string $string): ?bool
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