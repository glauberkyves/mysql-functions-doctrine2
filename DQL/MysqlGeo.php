<?php
namespace GlauberKyves\MysqlDoctrineFunctions\DQL;

use \Doctrine\ORM\Query\AST\Functions\FunctionNode;
use \Doctrine\ORM\Query\Lexer;
use \Doctrine\ORM\Query\Parser;
use \Doctrine\ORM\Query\SqlWalker;

/**
 * MysqlGeo
 * Usage: GEO(latOrigin, lngOrigin, latDestination, lngDestination)
 * Returns: distance in km
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2015 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class MysqlGeo extends FunctionNode
{
    const EARTH_DIAMETER = 12742; // 2 * Earth's radius (6371 km)

    protected $latOrigin;
    protected $lngOrigin;
    protected $latDestination;
    protected $lngDestination;

    /**
     * @param SqlWalker $sqlWalker
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf(
            '%s * ASIN(SQRT(POWER(SIN((%s - %s) * PI()/360), 2) + COS(%s * PI()/180) * COS(%s * PI()/180) * POWER(SIN((%s - %s) *  PI()/360), 2)))',
            self::EARTH_DIAMETER,
            $sqlWalker->walkArithmeticPrimary($this->latOrigin),
            $sqlWalker->walkArithmeticPrimary($this->latDestination),
            $sqlWalker->walkArithmeticPrimary($this->latOrigin),
            $sqlWalker->walkArithmeticPrimary($this->latDestination),
            $sqlWalker->walkArithmeticPrimary($this->lngOrigin),
            $sqlWalker->walkArithmeticPrimary($this->lngDestination)
        );
    }

    /**
     * @param Parser $parser
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->latOrigin = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->lngOrigin = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->latDestination = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->lngDestination = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}