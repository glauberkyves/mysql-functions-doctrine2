<?php
namespace GlauberKyves\MysqlDoctrineFunctions\DQL;

use \Doctrine\ORM\Query\AST\Functions\FunctionNode;
use \Doctrine\ORM\Query\Lexer;
use \Doctrine\ORM\Query\SqlWalker;
use \Doctrine\ORM\Query\Parser;

/**
 * MysqlYear
 *
 * @uses FunctionNode
 * @author Fellipe Vasconcelos <fellipe.fvs@gmail.com>
 */
class MysqlYear extends FunctionNode
{
    public $date;

    /**
     * @param SqlWalker $sqlWalker
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return "YEAR(" . $sqlWalker->walkArithmeticPrimary($this->date) . ")";
    }

    /**
     * @param Parser $parser
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->date = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}