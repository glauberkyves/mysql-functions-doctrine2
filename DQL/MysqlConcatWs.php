<?php
namespace GlauberKyves\MysqlDoctrineFunctions\DQL;

use \Doctrine\ORM\Query\AST\Functions\FunctionNode;
use \Doctrine\ORM\Query\Lexer;
use \Doctrine\ORM\Query\SqlWalker;
use \Doctrine\ORM\Query\Parser;

/**
 * MysqlConcatWs
 *
 * @uses FunctionNode
 * @author Glauber Kyves <glauberkyves@gmail.com>
 */
class MysqlConcatWs extends FunctionNode
{

    public $firstStringPrimary;

    public $secondStringPrimary;

    public $concatExpressions = array();

    /**
     * @param SqlWalker $sqlWalker
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        $args = array();
        foreach ($this->concatExpressions as $expression) {
            $args[] = $sqlWalker->walkStringPrimary($expression);
        }

        return 'CONCAT_WS(' . join(', ', (array)$args) . ')';
    }

    /**
     * @param Parser $parser
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->firstStringPrimary  = $parser->StringPrimary();
        $this->concatExpressions[] = $this->firstStringPrimary;

        $parser->match(Lexer::T_COMMA);

        $this->secondStringPrimary = $parser->StringPrimary();
        $this->concatExpressions[] = $this->secondStringPrimary;

        while ($parser->getLexer()->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->concatExpressions[] = $parser->StringPrimary();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
