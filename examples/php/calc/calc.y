/* Infix notation calculator--calc */
%language "PHP"
%name-prefix "Calc"
%define parser_class_name "Calc"

%error-verbose %locations %lex-param { $is }

/* Grammar follows */
%token NUM
%left '-' '+'
%left '*' '/'
%right '^'    /* exponentiation */

%% /* The grammar follows.  */
input:    /* empty */
        | input line
;

line:     '\n'
        | exp '\n'  { printf ("\t%.10g\n", $1); }
;

exp:      NUM                { $$ = $1;           }
        | exp '+' exp        { $$ = $1 + $3;      }
        | exp '-' exp        { $$ = $1 - $3;      }
        | exp '*' exp        { $$ = $1 * $3;      }
        | exp '/' exp        { $$ = $1 / $3;      }
        | exp '^' exp        { $$ = pow ($1, $3); }
        | '(' exp ')'        { $$ = $2;           }
;
%code lexer {

  private $buffer;
  private $yypos;

  public function __construct ($is)
  {
    $this->buffer = stream_get_contents ($is);
    $this->yypos = new Position (1, 0);
  }

  public function getStartPos() {
    return $this->yypos;
  }

  public function getEndPos() {
    return $this->yypos;
  }

  public function yyerror (Location $l, $s)
  {
    if (is_null ($l))
      fprintf (STDERR, "%s\n", $s);
    else
      fprintf (STDERR, "%s: %s\n", $l->toString (), $s);
  }

  private $yylval;

  public function getLVal() {
    return $this->yylval;
  }

  public function yylex ()
    {
      $this->yypos = new Position ($this->yypos->lineno (), $this->yypos->token () + 1);

      $this->buffer = preg_replace ("/^[\\t ]+/", "", $this->buffer);

      if (strlen ($this->buffer) == 0)
        return Lexer::EOF;
      else
        if (substr ($this->buffer, 0, 1) == "\n")
          {
            $this->yypos = new Position ($this->yypos->lineno () + 1, 0);
            $this->buffer = substr ($this->buffer, 1);
            return ord ("\n");
          }
        else
          if (preg_match ("/^([0-9]+)/", $this->buffer, $matches))
            {
              $this->yylval = $matches [1];
              $this->buffer = substr ($this->buffer, strlen ($matches [1]));
              return self::NUM;
            }
          else
            {
              $tt = substr ($this->buffer, 0, 1);
              $this->buffer = substr ($this->buffer, 1);
              return ord ($tt);
            }
    }
};
%%
class Position {
  public $line;
  public $token;

  public function __construct ($l = 0, $t = 0)
  {
    $this->line = $l;
    $this->token = $t;
  }

  public function equals (Position $l)
  {
    return $l->line == $this->line && $l->token == $this->token;
  }

  public function toString ()
  {
    return $this->line . "." . $this->token;
  }

  public function lineno ()
  {
    return $this->line;
  }

  public function token ()
  {
    return $this->token;
  }
}

$p = new Calc (STDIN);
$p->parse ();
