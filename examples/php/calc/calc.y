/* Infix notation calculator--calc */
%language "PHP"
%name-prefix "Calc"
%define parser_class_name "Calc"

%error-verbose %locations %lex-param { $is }

/* Bison Declarations */
%token <Integer> NUM "number"
%type  <Integer> exp

%nonassoc '=' /* comparison            */
%left '-' '+'
%left '*' '/'
%left NEG     /* negation--unary minus */
%right '^'    /* exponentiation        */

/* Grammar follows */
%%
input:
  line
| input line
;

line:
  '\n'
| exp '\n'
| error '\n'
;

exp:
  NUM                { $$ = $1;                                             }
| exp '=' exp
  {
    if ($1 != $3)
      self::yyerror (@$, "calc: error: " . $1 . " != " . $3);
  }
| exp '+' exp        { $$ = ($1 + $3);  }
| exp '-' exp        { $$ = ($1 - $3);  }
| exp '*' exp        { $$ = ($1 * $3);  }
| exp '/' exp        { $$ = ($1 / $3);  }
| '-' exp  %prec NEG { $$ = (-$2);                  }
| exp '^' exp        { $$ = ((int) pow ($1,
                                             $3));  }
| '(' exp ')'        { $$ = $2;                                             }
| '(' error ')'      { $$ = (1111);                             }
| '!'                { $$ = (0); return self::YYERROR;                }
| '-' error          { $$ = (0); return self::YYERROR;                }
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

$data = "1 + 2 = 3\n1 + 2 = 4\n";
$p = new Calc (fopen('data:text/plain,'.urlencode($data), 'rb'));
# $p = new Calc (STDIN);
// $p->setDebugLevel (255);
$p->parse ();
/* $l = new YYLexer (fopen('data:text/plain,'.urlencode($data), 'rb')); */
/* do */
/*   { */
/*       $Token = $l->yylex (); */
/*     $Value =$l->getLVal (); */
/*   var_dump ($Token, $Value); */
/* } while ($Token !== Lexer::EOF); */
/* exit; */

//* public static void main (String args[]) throws IOException */
  /* { */
  /*   new Calc (System.in).parse (); */
  /* } */

