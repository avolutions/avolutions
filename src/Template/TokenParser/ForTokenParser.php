<?php

namespace Avolutions\Template\TokenParser;

use Avolutions\Template\Token;

class ForTokenParser implements ITokenParser, IEndTokenParser
{
    public function parse(Token $Token)
    {
        $VariableTokenParser = new VariableTokenParser();

        if (preg_match('@for\s(' . $VariableTokenParser->validVariableCharacters . '*)\sin\s(' . $VariableTokenParser->getVariableRegex(false) . ')@x', $Token->value, $matches)) {
            $variable = $VariableTokenParser->todoVariable($matches[2], false);

            $forLoop = 'if (isset(' . $variable . ')) { ' . PHP_EOL;
            $forLoop .= 'if (isset($loop)) {' . PHP_EOL;
            $forLoop .= '$loop["parent"] = $loop;' . PHP_EOL;
            $forLoop .= '}' . PHP_EOL;
            $forLoop .= '$loop["count"] = count(' . $variable . ');' . PHP_EOL;
            $forLoop .= '$loop["index"] = 1;' . PHP_EOL;
            $forLoop .= '$loop["first"] = true;' . PHP_EOL;
            $forLoop .= '$loop["last"] = false;' . PHP_EOL;
            $forLoop .= 'foreach (';
            $forLoop .= $variable;
            $forLoop .= ' as ';
            $forLoop .= '$loop["key"] => ';
            $forLoop .= $VariableTokenParser->todoVariable($matches[1], false);
            $forLoop .= ') {'.PHP_EOL;
            $forLoop .= '$loop["last"] = $loop["index"] == $loop["count"];' . PHP_EOL;
            $forLoop .= '$loop["even"] = $loop["index"] % 2 == 0;' . PHP_EOL;
            $forLoop .= '$loop["odd"] = !$loop["even"];' . PHP_EOL;

            return $forLoop;
        } else {
            // throw Exception
        }
    }

    public function parseEnd(Token $Token)
    {
        $end = '$loop["index"]++;' . PHP_EOL;
        $end .= '$loop["first"] = false;' . PHP_EOL;
        $end .= '}'.PHP_EOL;
        $end .= 'if (isset($loop["parent"])) {' . PHP_EOL;
        $end .= "\t" . '$loop = $loop["parent"];' . PHP_EOL;
        $end .= '}' . PHP_EOL;
        $end .= '}' .PHP_EOL;
        return $end;
    }
}