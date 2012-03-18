<?php
/* A Bison parser, made by GNU Bison 2.4.762-5220.  */

/* Skeleton implementation for Bison LALR(1) parsers in PHP
   
   Copyright (C) 2007-2011 Free Software Foundation, Inc.
   
   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.
   
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   
   You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.  */

/* As a special exception, you may create a larger work that contains
   part or all of the Bison parser skeleton and distribute that work
   under terms of your choice, so long as that work isn't itself a
   parser generator using the skeleton or a modified version thereof
   as a parser skeleton.  Alternatively, if you modify or redistribute
   the parser skeleton itself, you may (at your option) remove this
   special exception, which will cause the skeleton and the resulting
   Bison output files to be licensed under the GNU General Public
   License without this special exception.
   
   This special exception was added by the Free Software Foundation in
   version 2.2 of Bison.  */

/* First part of user declarations.  */

/* Line 40 of lalr1.php  */
/* Line 38 of "calc.php"  */

/* Line 41 of lalr1.php  */
/* Line 41 of "calc.php"  */

/**
 * A Bison parser, automatically generated from <tt>calc.y</tt>.
 *
 * @author LALR (1) parser skeleton written by Paolo Bonzini.
 */

  /**
   * Communication interface between the scanner and the Bison-generated
   * parser <tt>Calc</tt>.
   */
  interface LexerInterface {
    /** Token returned by the scanner to signal the end of its input.  */
    const EOF = 0;

/* Tokens.  */
    /** Token number, to be returned by the scanner.  */
    const NUM = 258;



    /**
     * Method to retrieve the beginning position of the last scanned token.
     * @return the position at which the last scanned token starts.  */
    function getStartPos ();

    /**
     * Method to retrieve the ending position of the last scanned token.
     * @return the first position beyond the last scanned token.  */
    function getEndPos ();

    /**
     * Method to retrieve the semantic value of the last scanned token.
     * @return the semantic value of the last scanned token.  */
    function getLVal ();

    /**
     * Entry point for the scanner.  Returns the token identifier corresponding
     * to the next token and prepares to return the semantic value
     * and beginning/ending positions of the token.
     * @return the token identifier corresponding to the next token. */
    function yylex ();

    /**
     * Entry point for error reporting.  Emits an error
     * referring to the given location in a user-defined way.
     *
     * @param $loc The location of the element to which the
     *                error message is related
     * @param $msg The string for the error message.  */
     function yyerror (Location $loc, $msg);
  }


  /**
   * A class defining a pair of positions.  Positions, defined by the
   * <code>Position</code> class, denote a point in the input.
   * Locations represent a part of the input through the beginning
   * and ending positions.  */
  class Location {
    /** The first, inclusive, position in the range.  */
    public $begin;

    /** The first position beyond the range.  */
    public $end;

    /**
     * Create a <code>Location</code> from the endpoints of the range.
     * @param $begin The position at which the range is anchored.
     * @param $end   The first position beyond the range.  Defaults to $begin. */
    public function __construct (Position $begin = NULL, Position $end = NULL) {
      $this->begin = $begin;
      $this->end = is_null ($end) ? $begin : $end;
    }

    /**
     * Print a representation of the location.  For this to be correct,
     * <code>Position</code> should override the <code>equals</code>
     * method.  */
    public function toString () {
      if ($this->begin->equals ($this->end))
        return $this->begin->toString ();
      else
        return $this->begin->toString () . "-" . $this->end->toString ();
    }
  }



class YYLexer implements LexerInterface {
/* "%code lexer" blocks.  */
/* Line 127 of lalr1.php  */
/* Line 31 of "calc.y"  */


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


/* Line 127 of lalr1.php  */
/* Line 200 of "calc.php"  */

  }

  

class YYStack {
    private $stateStack = array();
    private $locStack = array();
    private $valueStack = array();

    public $height = -1;

    public function push ($state, $value                            , Location $loc) {
      $this->height++;
      $this->stateStack[$this->height] = $state;
      $this->locStack[$this->height] = $loc;
      $this->valueStack[$this->height] = $value;
    }

    public function pop ($num = 1) {
      // Avoid memory leaks... garbage collection is a white lie!
      while ($num-- > 0) {
        unset ($this->valueStack [$this->height]);
        unset ($this->locStack   [$this->height]);
        $this->height--;
      }
    }

    public function stateAt ($i) {
      if (!array_key_exists ($this->height - $i, $this->stateStack))
        throw (new OutOfBoundsException ('YYStack::stateAt(' . $i . ') failed, $this->height = ' . $this->height));
      return $this->stateStack[$this->height - $i];
    }

    public function locationAt ($i) {
      return $this->locStack[$this->height - $i];
    }

    public function valueAt ($i) {
      return $this->valueStack[$this->height - $i];
    }

    // Print the state stack on the debug stream.
    public function printStack ($out)
    {
      fputs ($out, "Stack now");

      for ($i = 0; $i <= $this->height; $i++)
        fprintf ($out, " %d", $this->stateStack [$i]);
      fputs ($out, "\n");
    }
  }

class Calc
{
    /** Version number for the Bison executable that generated this parser.  */
  const bisonVersion = "2.4.762-5220";

  /** Name of the skeleton that generated this parser.  */
  const bisonSkeleton = "lalr1.php";


  /** True if verbose error messages are enabled.  */
  private $yyErrorVerbose = true;

  /**
   * Return whether verbose error messages are enabled.
   */
  public function getErrorVerbose() { return $this->yyErrorVerbose; }

  /**
   * Set the verbosity of error messages.
   * @param $verbose True to request verbose error messages.
   */
  public function setErrorVerbose($verbose)
  { $this->yyErrorVerbose = $verbose; }


  
  private function yylloc (YYStack $rhs, $n)
  {
    if ($n > 0)
      return new Location ($rhs->locationAt ($n-1)->begin, $rhs->locationAt (0)->end);
    else
      return new Location ($rhs->locationAt (0)->end);
  }
  /** The object doing lexical analysis for us.  */
  private $yylexer;
  
  


  /**
   * Instantiates the Bison-generated parser.
   */
  public function __construct ($is)
  {
    
    $this->yylexer = new YYLexer($is);
    
    $this->setDebugStream (defined ('STDERR') ? STDERR : fopen ('php://output', 'w'));
  }


  private $yyDebugStream;

  /**
   * Return the <tt>PrintStream</tt> on which the debugging output is
   * printed.
   */
  public function getDebugStream () { return $this->yyDebugStream; }

  /**
   * Set the <tt>PrintStream</tt> on which the debug output is printed.
   * @param $s The stream that is used for debugging output.
   */
  public function setDebugStream($s) { $this->yyDebugStream = $s; }

  private $yydebug = 0;

  /**
   * Answer the verbosity of the debugging output; 0 means that all kinds of
   * output from the parser are suppressed.
   */
  public function getDebugLevel() { return $this->yydebug; }

  /**
   * Set the verbosity of the debugging output; 0 means that all kinds of
   * output from the parser are suppressed.
   * @param $level The verbosity level for debugging output.
   */
  public function setDebugLevel($level) { $this->yydebug = $level; }


  /**
   * Print an error message via the lexer.
   * @param $locpos The location/position associated with the message.
   * @param $msg The error message.
   */
  public function yyerror ($locpos, $msg = null)
  {
    if (is_null ($msg))
      $this->yylexer->yyerror (null, $locpos);
    else
      $this->yylexer->yyerror ($locpos instanceof Location ? $locpos : new Location ($locpos), $msg);
  }

  protected function yycdebug ($s) {
    if ($this->yydebug > 0)
      fprintf ($this->yyDebugStream, "%s\n", $s);
  }

  /**
   * Returned by a Bison action in order to stop the parsing process and
   * return success (<tt>true</tt>).  */
  const YYACCEPT = 0;

  /**
   * Returned by a Bison action in order to stop the parsing process and
   * return failure (<tt>false</tt>).  */
  const YYABORT = 1;

  /**
   * Returned by a Bison action in order to start error recovery without
   * printing an error message.  */
  const YYERROR = 2;

  // Internal return codes that are not supported for user semantic
  // actions.
  const YYERRLAB = 3;
  const YYNEWSTATE = 4;
  const YYDEFAULT = 5;
  const YYREDUCE = 6;
  const YYERRLAB1 = 7;
  const YYRETURN = 8;

  private $yyerrstatus_ = 0;

  /**
   * Return whether error recovery is being done.  In this state, the parser
   * reads token until it reaches a known state, and then restarts normal
   * operation.  */
  public function recovering ()
  {
    return $this->yyerrstatus_ == 0;
  }

  private function yyaction ($yyn, YYStack $yystack, $yylen)
  {
    $yyloc = self::yylloc ($yystack, $yylen);

    /* If YYLEN is nonzero, implement the default value of the action:
       "$$ = $1".  Otherwise, use the top of the stack.

       Otherwise, the following line sets YYVAL to garbage.
       This behavior is undocumented and Bison
       users should not rely upon it.  */
    if ($yylen > 0)
      $yyval = $yystack->valueAt ($yylen - 1);
    else
      $yyval = $yystack->valueAt (0);

    self::yy_reduce_print ($yyn, $yystack);

    switch ($yyn)
      {
          case 5:
  /* Line 354 of lalr1.php  */
/* Line 20 of "calc.y"  */
    { printf ("\t%.10g\n", ($yystack->valueAt (2-(1)))); };
  break;
    

  case 6:
  /* Line 354 of lalr1.php  */
/* Line 23 of "calc.y"  */
    { $yyval = ($yystack->valueAt (1-(1)));           };
  break;
    

  case 7:
  /* Line 354 of lalr1.php  */
/* Line 24 of "calc.y"  */
    { $yyval = ($yystack->valueAt (3-(1))) + ($yystack->valueAt (3-(3)));      };
  break;
    

  case 8:
  /* Line 354 of lalr1.php  */
/* Line 25 of "calc.y"  */
    { $yyval = ($yystack->valueAt (3-(1))) - ($yystack->valueAt (3-(3)));      };
  break;
    

  case 9:
  /* Line 354 of lalr1.php  */
/* Line 26 of "calc.y"  */
    { $yyval = ($yystack->valueAt (3-(1))) * ($yystack->valueAt (3-(3)));      };
  break;
    

  case 10:
  /* Line 354 of lalr1.php  */
/* Line 27 of "calc.y"  */
    { $yyval = ($yystack->valueAt (3-(1))) / ($yystack->valueAt (3-(3)));      };
  break;
    

  case 11:
  /* Line 354 of lalr1.php  */
/* Line 28 of "calc.y"  */
    { $yyval = pow (($yystack->valueAt (3-(1))), ($yystack->valueAt (3-(3)))); };
  break;
    

  case 12:
  /* Line 354 of lalr1.php  */
/* Line 29 of "calc.y"  */
    { $yyval = ($yystack->valueAt (3-(2)));           };
  break;
    


/* Line 354 of lalr1.php  */
/* Line 465 of "calc.php"  */
        default: break;
      }

    self::yy_symbol_print ("-> \$\$ =", $this->yyr1_[$yyn], $yyval, $yyloc);

    $yystack->pop ($yylen);
    $yylen = 0;

    /* Shift the result of the reduction.  */
    $yyn = $this->yyr1_[$yyn];
    $yystate = $this->yypgoto_[$yyn - self::yyntokens_] + $yystack->stateAt (0);
    if (0 <= $yystate && $yystate <= self::yylast_
        && $this->yycheck_[$yystate] == $yystack->stateAt (0))
      $yystate = $this->yytable_[$yystate];
    else
      $yystate = $this->yydefgoto_[$yyn - self::yyntokens_];

    $yystack->push ($yystate, $yyval, $yyloc);
    return self::YYNEWSTATE;
  }


  /* Return YYSTR after stripping away unnecessary quotes and
     backslashes, so that it's suitable for yyerror.  The heuristic is
     that double-quoting is unnecessary unless the string contains an
     apostrophe, a comma, or backslash (other than backslash-backslash).
     YYSTR is taken from yytname.  */
  private function yytnamerr_ ($yystr)
  {
    if (substr ($yystr, 0, 1) == '"')
      {
        $yyr = "";
        for ($i = 1; $i < strlen ($yystr); $i++)
          switch (substr ($yystr, $i, 1))
            {
            case "'":
            case ',':
              break 2;

            case "\\":
              if (substr ($yystr, ++$i, 1) != "\\")
                break 2;
              /* Fall through.  */
            default:
              $yyr .= substr ($yystr, $i, 1);
              break;

            case '"':
              return $yyr;
            }
      }
    else if ($yystr == "\$end")
      return "end of input";

    return $yystr;
  }


  /*--------------------------------.
  | Print this symbol on YYOUTPUT.  |
  `--------------------------------*/

  private function yy_symbol_print ($s, $yytype,
                                 $yyvaluep                                 , $yylocationp)
  {
    if ($this->yydebug > 0)
    self::yycdebug ($s . ($yytype < self::yyntokens_ ? " token " : " nterm ")
              . $this->yytname_[$yytype] . " ("
              . $yylocationp->toString () . ": "
              . ($yyvaluep == null ? "(null)" : $yyvaluep) . ")");
  }

  /**
   * Parse input from the scanner that was specified at object construction
   * time.  Return whether the end of the input was reached successfully.
   *
   * @return <tt>true</tt> if the parsing succeeds.  Note that this does not
   *          imply that there were no syntax errors.
   */
  public function parse ()
  {
    /// Lookahead and lookahead in internal form.
    $yychar = self::yyempty_;
    $yytoken = 0;

    /* State.  */
    $yyn = 0;
    $yylen = 0;
    $yystate = 0;

    $yystack = new YYStack ();

    /* Error handling.  */
    $yynerrs_ = 0;
    /// The location where the error started.
    $yyerrloc = null;

    /// Location of the lookahead.
    $yylloc = new Location (null, null);

    /// Semantic value of the lookahead.
    $yylval = null;

    self::yycdebug ("Starting parse\n");
    $this->yyerrstatus_ = 0;


    /* Initialize the stack.  */
    $yystack->push ($yystate, $yylval, $yylloc);

    $label = self::YYNEWSTATE;
    for (;;)
      switch ($label)
      {
        /* New state.  Unlike in the C/C++ skeletons, the state is already
           pushed when we come here.  */
      case self::YYNEWSTATE:
        self::yycdebug ("Entering state " . $yystate . "\n");
        if ($this->yydebug > 0)
          $yystack->printStack ($this->yyDebugStream);

        /* Accept?  */
        if ($yystate == self::yyfinal_)
          return true;

        /* Take a decision.  First try without lookahead.  */
        $yyn = $this->yypact_[$yystate];
        if (self::yy_pact_value_is_default_ ($yyn))
          {
            $label = self::YYDEFAULT;
            break;
          }

        /* Read a lookahead token.  */
        if ($yychar == self::yyempty_)
          {
            self::yycdebug ("Reading a token: ");
            $yychar = $this->yylexer->yylex ();
            if (is_string ($yychar))
              $yychar = ord ($yychar);
            
            $yylloc = new Location($this->yylexer->getStartPos (),
                            $this->yylexer->getEndPos ());
            $yylval = $this->yylexer->getLVal ();
          }

        /* Convert token to internal form.  */
        if ($yychar <= LexerInterface::EOF)
          {
            $yychar = $yytoken = LexerInterface::EOF;
            self::yycdebug ("Now at end of input.\n");
          }
        else
          {
            $yytoken = self::yytranslate_ ($yychar);
            self::yy_symbol_print ("Next token is", $yytoken,
                             $yylval, $yylloc);
          }

        /* If the proper action on seeing token YYTOKEN is to reduce or to
           detect an error, take that action.  */
        $yyn += $yytoken;
        if ($yyn < 0 || self::yylast_ < $yyn || $this->yycheck_[$yyn] != $yytoken)
          $label = self::YYDEFAULT;

        /* <= 0 means reduce or error.  */
        else if (($yyn = $this->yytable_[$yyn]) <= 0)
          {
            if (self::yy_table_value_is_error_ ($yyn))
              $label = self::YYERRLAB;
            else
              {
                $yyn = -$yyn;
                $label = self::YYREDUCE;
              }
          }

        else
          {
            /* Shift the lookahead token.  */
            self::yy_symbol_print ("Shifting", $yytoken,
                             $yylval, $yylloc);

            /* Discard the token being shifted.  */
            $yychar = self::yyempty_;

            /* Count tokens shifted since error; after three, turn off error
               status.  */
            if ($this->yyerrstatus_ > 0)
              --$this->yyerrstatus_;

            $yystate = $yyn;
            $yystack->push ($yystate, $yylval, $yylloc);
            $label = self::YYNEWSTATE;
          }
        break;

      /*-----------------------------------------------------------.
      | yydefault -- do the default action for the current state.  |
      `-----------------------------------------------------------*/
      case self::YYDEFAULT:
        $yyn = $this->yydefact_[$yystate];
        if ($yyn == 0)
          $label = self::YYERRLAB;
        else
          $label = self::YYREDUCE;
        break;

      /*-----------------------------.
      | yyreduce -- Do a reduction.  |
      `-----------------------------*/
      case self::YYREDUCE:
        $yylen = $this->yyr2_[$yyn];
        $label = self::yyaction ($yyn, $yystack, $yylen);
        $yystate = $yystack->stateAt (0);
        break;

      /*------------------------------------.
      | yyerrlab -- here on detecting error |
      `------------------------------------*/
      case self::YYERRLAB:
        /* If not already recovering from an error, report this error.  */
        if ($this->yyerrstatus_ == 0)
          {
            ++$yynerrs_;
            if ($yychar == self::yyempty_)
              $yytoken = self::yyempty_;
            self::yyerror ($yylloc, self::yysyntax_error ($yystate, $yytoken));
          }

        $yyerrloc = $yylloc;
        if ($this->yyerrstatus_ == 3)
          {
        /* If just tried and failed to reuse lookahead token after an
         error, discard it.  */

        if ($yychar <= LexerInterface::EOF)
          {
          /* Return failure if at end of input.  */
          if ($yychar == LexerInterface::EOF)
            return false;
          }
        else
              $yychar = self::yyempty_;
          }

        /* Else will try to reuse lookahead token after shifting the error
           token.  */
        $label = self::YYERRLAB1;
        break;

      /*---------------------------------------------------.
      | errorlab -- error raised explicitly by YYERROR.  |
      `---------------------------------------------------*/
      case self::YYERROR:

        $yyerrloc = $yystack->locationAt ($yylen - 1);
        /* Do not reclaim the symbols of the rule which action triggered
           this YYERROR.  */
        $yystack->pop ($yylen);
        $yylen = 0;
        $yystate = $yystack->stateAt (0);
        $label = self::YYERRLAB1;
        break;

      /*-------------------------------------------------------------.
      | yyerrlab1 -- common code for both syntax error and YYERROR.  |
      `-------------------------------------------------------------*/
      case self::YYERRLAB1:
        $this->yyerrstatus_ = 3;       /* Each real token shifted decrements this.  */

        for (;;)
          {
            $yyn = $this->yypact_[$yystate];
            if (!self::yy_pact_value_is_default_ ($yyn))
              {
                $yyn += self::yyterror_;
                if (0 <= $yyn && $yyn <= self::yylast_ && $this->yycheck_[$yyn] == self::yyterror_)
                  {
                    $yyn = $this->yytable_[$yyn];
                    if (0 < $yyn)
                      break;
                  }
              }

            /* Pop the current state because it cannot handle the error token.  */
            if ($yystack->height == 0)
              return false;

            $yyerrloc = $yystack->locationAt (0);
            $yystack->pop ();
            $yystate = $yystack->stateAt (0);
            if ($this->yydebug > 0)
              $yystack->printStack ($this->yyDebugStream);
          }

        
        /* Muck with the stack to setup for yylloc.  */
        $yystack->push (0, null, $yylloc);
        $yystack->push (0, null, $yyerrloc);
        $yyloc = self::yylloc ($yystack, 2);
        $yystack->pop (2);

        /* Shift the error token.  */
        self::yy_symbol_print ("Shifting", $this->yystos_[$yyn],
                         $yylval, $yyloc);

        $yystate = $yyn;
        $yystack->push ($yyn, $yylval, $yyloc);
        $label = self::YYNEWSTATE;
        break;

        /* Accept.  */
      case self::YYACCEPT:
        return true;

        /* Abort.  */
      case self::YYABORT:
        return false;
      }
  }

  // Generate an error message.
  private function yysyntax_error ($yystate, $tok)
  {
    if ($this->yyErrorVerbose)
      {
        /* There are many possibilities here to consider:
           - Assume YYFAIL is not used.  It's too flawed to consider.
             See
             <http://lists.gnu.org/archive/html/bison-patches/2009-12/msg00024.html>
             for details.  YYERROR is fine as it does not invoke this
             function.
           - If this state is a consistent state with a default action,
             then the only way this function was invoked is if the
             default action is an error action.  In that case, don't
             check for expected tokens because there are none.
           - The only way there can be no lookahead present (in tok) is
             if this state is a consistent state with a default action.
             Thus, detecting the absence of a lookahead is sufficient to
             determine that there is no unexpected or expected token to
             report.  In that case, just report a simple "syntax error".
           - Don't assume there isn't a lookahead just because this
             state is a consistent state with a default action.  There
             might have been a previous inconsistent state, consistent
             state with a non-default action, or user semantic action
             that manipulated yychar.  (However, yychar is currently out
             of scope during semantic actions.)
           - Of course, the expected token list depends on states to
             have correct lookahead information, and it depends on the
             parser not to perform extra reductions after fetching a
             lookahead from the scanner and before detecting a syntax
             error.  Thus, state merging (from LALR or IELR) and default
             reductions corrupt the expected token list.  However, the
             list is correct for canonical LR with one exception: it
             will still contain any token that will not be accepted due
             to an error action in a later state.
        */
        if ($tok != self::yyempty_)
          {
            // FIXME: This method of building the message is not compatible
            // with internationalization.
            $res =
              "syntax error, unexpected ";
            $res .= self::yytnamerr_ ($this->yytname_[$tok]);
            $yyn = $this->yypact_[$yystate];
            if (!self::yy_pact_value_is_default_ ($yyn))
              {
                /* Start YYX at -YYN if negative to avoid negative
                   indexes in YYCHECK.  In other words, skip the first
                   -YYN actions for this state because they are default
                   actions.  */
                $yyxbegin = $yyn < 0 ? -$yyn : 0;
                /* Stay within bounds of both yycheck and yytname.  */
                $yychecklim = self::yylast_ - $yyn + 1;
                $yyxend = $yychecklim < self::yyntokens_ ? $yychecklim : self::yyntokens_;
                $count = 0;
                for ($x = $yyxbegin; $x < $yyxend; ++$x)
                  if ($this->yycheck_[$x + $yyn] == $x && $x != self::yyterror_
                      && !self::yy_table_value_is_error_ ($this->yytable_[$x + $yyn]))
                    ++$count;
                if ($count < 5)
                  {
                    $count = 0;
                    for ($x = $yyxbegin; $x < $yyxend; ++$x)
                      if ($this->yycheck_[$x + $yyn] == $x && $x != self::yyterror_
                          && !self::yy_table_value_is_error_ ($this->yytable_[$x + $yyn]))
                        {
                          $res .= $count++ == 0 ? ", expecting " : " or ";
                          $res .= self::yytnamerr_ ($this->yytname_[$x]);
                        }
                  }
              }
            return $res;
          }
      }

    return "syntax error";
  }

  /**
   * Whether the given <code>yypact_</code> value indicates a defaulted state.
   * @param $yyvalue   the value to check
   */
  private function yy_pact_value_is_default_ ($yyvalue)
  {
    return $yyvalue == self::yypact_ninf_;
  }

  /**
   * Whether the given <code>yytable_</code> value indicates a syntax error.
   * @param $yyvalue   the value to check
   */
  private function yy_table_value_is_error_ ($yyvalue)
  {
    return $yyvalue == self::yytable_ninf_;
  }

  const yypact_ninf_ = -7;
  const yytable_ninf_ = -1;

  /* YYPACT[STATE-NUM] -- Index in YYTABLE of the portion describing
   STATE-NUM.  */
  private $yypact_ = array(
      -7,     0,    -7,    -7,    -7,    -2,    -7,    20,    12,    -2,
      -2,    -2,    -2,    -2,    -7,    -7,    24,    24,    -6,    -6,
      -6
    );


/* YYDEFACT[S] -- default reduction number in state S.  Performed when
   YYTABLE does not specify something else to do.  Zero means the default
   is an error.  */
  private $yydefact_ = array(
       2,     0,     1,     6,     4,     0,     3,     0,     0,     0,
       0,     0,     0,     0,     5,    12,     8,     7,     9,    10,
      11
    );


/* YYPGOTO[NTERM-NUM].  */
  private $yypgoto_ = array(
      -7,    -7,    -7,     2
    );


/* YYDEFGOTO[NTERM-NUM].  */
  private $yydefgoto_ = array(
      -1,     1,     6,     7
    );


/* YYTABLE[YYPACT[STATE-NUM]].  What to do in state STATE-NUM.  If
   positive, shift that token.  If negative, reduce the rule which
   number is the opposite.  If YYTABLE_NINF, syntax error.  */
  private $yytable_ = array(
       2,     3,    13,     3,     0,     0,     0,     8,     5,     4,
       5,    16,    17,    18,    19,    20,     9,    10,    11,    12,
      13,     0,     0,    15,     9,    10,    11,    12,    13,    14,
      11,    12,    13
    );


private $yycheck_ = array(
       0,     3,     8,     3,    -1,    -1,    -1,     5,    10,     9,
      10,     9,    10,    11,    12,    13,     4,     5,     6,     7,
       8,    -1,    -1,    11,     4,     5,     6,     7,     8,     9,
       6,     7,     8
    );


/* STOS_[STATE-NUM] -- The (internal number of the) accessing
   symbol of state STATE-NUM.  */
  private $yystos_ = array(
       0,    13,     0,     3,     9,    10,    14,    15,    15,     4,
       5,     6,     7,     8,     9,    11,    15,    15,    15,    15,
      15
    );


/* YYR1[YYN] -- Symbol number of symbol that rule YYN derives.  */
  private $yyr1_ = array(
       0,    12,    13,    13,    14,    14,    15,    15,    15,    15,
      15,    15,    15
    );


/* YYR2[YYN] -- Number of symbols composing right hand side of rule YYN.  */
  private $yyr2_ = array(
       0,     2,     0,     2,     1,     2,     1,     3,     3,     3,
       3,     3,     3
    );


/* TOKEN_NUMBER_[YYLEX-NUM] -- Internal symbol number corresponding
   to YYLEX-NUM.  */
  private $yytoken_number_ = array(
       0,   256,   257,   258,    45,    43,    42,    47,    94,    10,
      40,    41
    );


/* YYTNAME[SYMBOL-NUM] -- String name of the symbol SYMBOL-NUM.
   First, the terminals, then, starting at \a yyntokens_, nonterminals.  */
  private $yytname_ = array(
  "\$end", "error", "\$undefined", "NUM", "'-'", "'+'", "'*'", "'/'",
  "'^'", "'\\n'", "'('", "')'", "\$accept", "input", "line", "exp", null
    );


/* YYRLINE[YYN] -- Source line where rule number YYN was defined.  */
  private $yyrline_ = array(
       0,    15,    15,    16,    19,    20,    23,    24,    25,    26,
      27,    28,    29
    );


  // Report on the debug stream that the rule yyrule is going to be reduced.
  private function yy_reduce_print ($yyrule, YYStack $yystack)
  {
    if ($this->yydebug == 0)
      return;

    $yylno = $this->yyrline_[$yyrule];
    $yynrhs = $this->yyr2_[$yyrule];
    /* Print the symbols being reduced, and their result.  */
    self::yycdebug ("Reducing stack by rule " . ($yyrule - 1)
              . " (line " . $yylno . "), ");

    /* The symbols being reduced.  */
    for ($yyi = 0; $yyi < $yynrhs; $yyi++)
      self::yy_symbol_print ("   \$" . ($yyi + 1) . " =",
                       $this->yystos_[$yystack->stateAt($yynrhs - ($yyi + 1))],
                       ($yystack->valueAt ($yynrhs-($yyi + 1))),
                       $yystack->locationAt ($yynrhs-($yyi + 1)));
  }

  /* YYTRANSLATE(YYLEX) -- Bison symbol number corresponding to YYLEX.  */
  private $yytranslate_table_ = array(
       0,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       9,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
      10,    11,     6,     5,     2,     4,     2,     7,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     8,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
       2,     2,     2,     2,     2,     2,     1,     2,     3
    );


  private function yytranslate_ ($t)
  {
    if ($t >= 0 && $t <= self::yyuser_token_number_max_)
      return $this->yytranslate_table_[$t];
    else
      return self::yyundef_token_;
  }

  const yylast_ = 32;
  const yynnts_ = 4;
  const yyempty_ = -2;
  const yyfinal_ = 2;
  const yyterror_ = 1;
  const yyerrcode_ = 256;
  const yyntokens_ = 12;

  const yyuser_token_number_max_ = 258;
  const yyundef_token_ = 2;

/* User implementation code.  */

}

/* Line 843 of lalr1.php  */
/* Line 94 of "calc.y"  */

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

$p = new Calc (STDIN, new Exception ());
$p->parse ();
