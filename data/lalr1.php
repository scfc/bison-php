# PHP skeleton for Bison -*- autoconf -*-

# Copyright (C) 2007-2011 Free Software Foundation, Inc.

# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

m4_include(b4_pkgdatadir/[php.m4])

b4_defines_if([b4_fatal([%s: %%defines is not implemented for PHP],
              [b4_skeleton])])

# We don't depend on %debug in PHP, but pacify warnings about non-used flags.
b4_parse_trace_if([0], [0])

m4_define([b4_symbol_no_destructor_assert],
[b4_symbol_if([$1], [has_destructor],
              [b4_fatal([%s: %s: %%destructor does not make sense in PHP],
                        [b4_skeleton],
                        [b4_symbol_action_location([$1], [destructor])])])])
b4_symbol_foreach([b4_symbol_no_destructor_assert])

m4_divert_push(0)dnl
@output(b4_parser_file_name@)@
<?php
b4_copyright([Skeleton implementation for Bison LALR(1) parsers in PHP],
             [2007-2011])

b4_percent_define_ifdef([package], [package b4_percent_define_get([package]);
])[/* First part of user declarations.  */
]b4_user_pre_prologue
b4_user_post_prologue
b4_percent_code_get([[imports]])
[/**
 * A Bison parser, automatically generated from <tt>]m4_bpatsubst(b4_file_name, [^"\(.*\)"$], [\1])[</tt>.
 *
 * @@author LALR (1) parser skeleton written by Paolo Bonzini.
 */

  /**
   * Communication interface between the scanner and the Bison-generated
   * parser <tt>]b4_parser_class_name[</tt>.
   */
  interface Lexer {
    /** Token returned by the scanner to signal the end of its input.  */
    const EOF = 0;

]b4_token_enums(b4_tokens)[

    ]b4_locations_if([[/**
     * Method to retrieve the beginning position of the last scanned token.
     * @@return the position at which the last scanned token starts.  */
    function getStartPos ();

    /**
     * Method to retrieve the ending position of the last scanned token.
     * @@return the first position beyond the last scanned token.  */
    function getEndPos ();]])[

    /**
     * Method to retrieve the semantic value of the last scanned token.
     * @@return the semantic value of the last scanned token.  */
    function getLVal ();

    /**
     * Entry point for the scanner.  Returns the token identifier corresponding
     * to the next token and prepares to return the semantic value
     * ]b4_locations_if([and beginning/ending positions ])[of the token.
     * @@return the token identifier corresponding to the next token. */
    function yylex ();

    /**
     * Entry point for error reporting.  Emits an error
     * ]b4_locations_if([referring to the given location ])[in a user-defined way.
     *
     * ]b4_locations_if([[@@param $loc The location of the element to which the
     *                error message is related]])[
     * @@param $msg The string for the error message.  */
     function yyerror (]b4_locations_if([b4_location_type[ $loc, ]])[$msg);
  }
]b4_locations_if([[
  /**
   * A class defining a pair of positions.  Positions, defined by the
   * <code>]b4_position_type[</code> class, denote a point in the input.
   * Locations represent a part of the input through the beginning
   * and ending positions.  */
  class ]b4_location_type[ {
    /** The first, inclusive, position in the range.  */
    public $begin;

    /** The first position beyond the range.  */
    public $end;

    /**
     * Create a <code>]b4_location_type[</code> from the endpoints of the range.
     * @@param $begin The position at which the range is anchored.
     * @@param $end   The first position beyond the range.  Defaults to $begin. */
    public function __construct (]b4_position_type[ $begin = NULL, ]b4_position_type[ $end = NULL) {
      $this->begin = $begin;
      $this->end = is_null ($end) ? $begin : $end;
    }

    /**
     * Print a representation of the location.  For this to be correct,
     * <code>]b4_position_type[</code> should override the <code>equals</code>
     * method.  */
    public function toString () {
      if ($this->begin->equals ($this->end))
        return $this->begin->toString ();
      else
        return $this->begin->toString () . "-" . $this->end->toString ();
    }
  }

]])[
]b4_lexer_if([[class YYLexer implements Lexer {
]b4_percent_code_get([[lexer]])[
  }
]])[
class YYStack {
    private $stateStack = array();
    ]b4_locations_if([[private $locStack = array();]])[
    private $valueStack = array();

    public $height = -1;

    public function push ($state, $value]dnl
                            b4_locations_if([, ]b4_location_type[ $loc])[) {
      $this->height++;
      $this->stateStack[$this->height] = $state;
      ]b4_locations_if([[$this->locStack[$this->height] = $loc;]])[
      $this->valueStack[$this->height] = $value;
    }

    public function pop ($num = 1) {
      // Avoid memory leaks... garbage collection is a white lie!
      while ($num-- > 0) {
        unset ($this->valueStack [$this->height]);
]b4_locations_if([[        unset ($this->locStack   [$this->height]);]])[
        $this->height--;
      }
    }

    public function stateAt ($i) {
      return $this->stateStack[$this->height - $i];
    }

    ]b4_locations_if([[public function locationAt ($i) {
      return $this->locStack[$this->height - $i];
    }

    ]])[public function valueAt ($i) {
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

]b4_percent_define_get3([annotations], [], [ ])dnl
b4_abstract_if([abstract ])dnl
b4_final_if([final ])dnl
[class ]b4_parser_class_name[]dnl
b4_percent_define_get3([extends], [ extends ])dnl
b4_percent_define_get3([implements], [ implements ])[
{
  ]b4_identification[
]b4_error_verbose_if([[
  /** True if verbose error messages are enabled.  */
  private $yyErrorVerbose = true;

  /**
   * Return whether verbose error messages are enabled.
   */
  public function getErrorVerbose() { return $this->yyErrorVerbose; }

  /**
   * Set the verbosity of error messages.
   * @@param $verbose True to request verbose error messages.
   */
  public function setErrorVerbose($verbose)
  { $this->yyErrorVerbose = $verbose; }
]])

  b4_locations_if([[
  private function yylloc (YYStack $rhs, $n)
  {
    if ($n > 0)
      return new ]b4_location_type[ ($rhs->locationAt ($n-1)->begin, $rhs->locationAt (0)->end);
    else
      return new ]b4_location_type[ ($rhs->locationAt (0)->end);
  }]])[

  /** The object doing lexical analysis for us.  */
  private $yylexer;
  ]
  b4_parse_param_vars

b4_lexer_if([[
  /**
   * Instantiates the Bison-generated parser.
   */
  public function __construct (]b4_parse_param_decl([b4_lex_param_decl])[)
  {
    ]b4_percent_code_get([[init]])[
    $this->yylexer = new YYLexer(]b4_lex_param_call[);
    ]b4_parse_param_cons[
  }
]],[
  /**
   * Instantiates the Bison-generated parser.
   * @@param $yylexer The scanner that will supply tokens to the parser.
   */
  b4_lexer_if([[protected]], [[public]]) b4_parser_class_name[ (]b4_parse_param_decl([[Lexer $yylexer]])[)
  {
    ]b4_percent_code_get([[init]])[
    $this->yylexer = $yylexer;
    ]b4_parse_param_cons[
  }
]])[
  private $yyDebugStream = STDERR;

  /**
   * Return the <tt>PrintStream</tt> on which the debugging output is
   * printed.
   */
  public function getDebugStream () { return $this->yyDebugStream; }

  /**
   * Set the <tt>PrintStream</tt> on which the debug output is printed.
   * @@param $s The stream that is used for debugging output.
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
   * @@param $level The verbosity level for debugging output.
   */
  public function setDebugLevel($level) { $this->yydebug = $level; }
]b4_locations_if([[
  /**
   * Print an error message via the lexer.
   * @@param $locpos The location/position associated with the message.
   * @@param $msg The error message.
   */
  public function yyerror ($locpos, $msg = null)
  {
    if (is_null ($msg))
      $this->yylexer->yyerror (null, $locpos);
    else
      $this->yylexer->yyerror ($locpos instanceof ]b4_location_type[ ? $locpos : new ]b4_location_type[ ($locpos), $msg);
  }]],[[
  /**
   * Print an error message via the lexer.
   *]b4_locations_if([[ Use a <code>null</code> location.]])[
   * @@param $msg The error message.
   */
  public function yyerror ($msg)
  {
    $this->yylexer->yyerror (]b4_locations_if([[()null, ]])[$msg);
  }
]])

  [protected function yycdebug ($s) {
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
    ]b4_locations_if([[$yyloc = self::yylloc ($yystack, $yylen);]])[

    /* If YYLEN is nonzero, implement the default value of the action:
       `$$ = $1'.  Otherwise, use the top of the stack.

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
        ]b4_user_actions[
        default: break;
      }

    self::yy_symbol_print ("-> \$\$ =", $this->yyr1_[$yyn], $yyval]b4_locations_if([, $yyloc])[);

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

    $yystack->push ($yystate, $yyval]b4_locations_if([, $yyloc])[);
    return self::YYNEWSTATE;
  }

]b4_error_verbose_if([[
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
]])[

  /*--------------------------------.
  | Print this symbol on YYOUTPUT.  |
  `--------------------------------*/

  private function yy_symbol_print ($s, $yytype,
                                 $yyvaluep]dnl
                                 b4_locations_if([, $yylocationp])[)
  {
    if ($this->yydebug > 0)
    self::yycdebug ($s . ($yytype < self::yyntokens_ ? " token " : " nterm ")
              . $this->yytname_[$yytype] . " ("]b4_locations_if([
              . $yylocationp->toString () . ": "])[
              . ($yyvaluep == null ? "(null)" : $yyvaluep) . ")");
  }

  /**
   * Parse input from the scanner that was specified at object construction
   * time.  Return whether the end of the input was reached successfully.
   *
   * @@return <tt>true</tt> if the parsing succeeds.  Note that this does not
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
    ]b4_locations_if([/// The location where the error started.
    $yyerrloc = null;

    /// ]b4_location_type[ of the lookahead.
    $yylloc = new ]b4_location_type[ (null, null);

    /// Semantic value of the lookahead.
    [$yylval = null;

    self::yycdebug ("Starting parse\n");
    $this->yyerrstatus_ = 0;

]m4_ifdef([b4_initial_action], [
m4_pushdef([b4_at_dollar],     [yylloc])dnl
m4_pushdef([b4_dollar_dollar], [yylval])dnl
    /* User initialization code.  */
    b4_user_initial_action
m4_popdef([b4_dollar_dollar])dnl
m4_popdef([b4_at_dollar])])dnl

  [  /* Initialize the stack.  */
    $yystack->push ($yystate, $yylval]b4_locations_if([, $yylloc])[);

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
            $yychar = $this->yylexer->yylex ();]
            b4_locations_if([[
            $yylloc = new ]b4_location_type[($this->yylexer->getStartPos (),
                            $this->yylexer->getEndPos ());]])
            $yylval = $this->yylexer->getLVal ();[
          }

        /* Convert token to internal form.  */
        if ($yychar <= Lexer::EOF)
          {
            $yychar = $yytoken = Lexer::EOF;
            self::yycdebug ("Now at end of input.\n");
          }
        else
          {
            $yytoken = self::yytranslate_ ($yychar);
            self::yy_symbol_print ("Next token is", $yytoken,
                             $yylval]b4_locations_if([, $yylloc])[);
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
                             $yylval]b4_locations_if([, $yylloc])[);

            /* Discard the token being shifted.  */
            $yychar = self::yyempty_;

            /* Count tokens shifted since error; after three, turn off error
               status.  */
            if ($this->yyerrstatus_ > 0)
              --$this->yyerrstatus_;

            $yystate = $yyn;
            $yystack->push ($yystate, $yylval]b4_locations_if([, $yylloc])[);
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
            self::yyerror (]b4_locations_if([$yylloc, ])[self::yysyntax_error ($yystate, $yytoken));
          }

        ]b4_locations_if([$yyerrloc = $yylloc;])[
        if ($this->yyerrstatus_ == 3)
          {
        /* If just tried and failed to reuse lookahead token after an
         error, discard it.  */

        if ($yychar <= Lexer::EOF)
          {
          /* Return failure if at end of input.  */
          if ($yychar == Lexer::EOF)
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

        ]b4_locations_if([$yyerrloc = $yystack->locationAt ($yylen - 1);])[
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
            if ($yystack->height == 1)
              return false;

            ]b4_locations_if([$yyerrloc = $yystack->locationAt (0);])[
            $yystack->pop ();
            $yystate = $yystack->stateAt (0);
            if ($this->yydebug > 0)
              $yystack->printStack ($this->yyDebugStream);
          }

        ]b4_locations_if([
        /* Muck with the stack to setup for yylloc.  */
        $yystack->push (0, null, $yylloc);
        $yystack->push (0, null, $yyerrloc);
        $yyloc = self::yylloc ($yystack, 2);
        $yystack->pop (2);])[

        /* Shift the error token.  */
        self::yy_symbol_print ("Shifting", $this->yystos_[$yyn],
                         $yylval]b4_locations_if([, $yyloc])[);

        $yystate = $yyn;
        $yystack->push ($yyn, $yylval]b4_locations_if([, $yyloc])[);
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
  {]b4_error_verbose_if([[
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
]])[
    return "syntax error";
  }

  /**
   * Whether the given <code>yypact_</code> value indicates a defaulted state.
   * @@param $yyvalue   the value to check
   */
  private function yy_pact_value_is_default_ ($yyvalue)
  {
    return $yyvalue == self::yypact_ninf_;
  }

  /**
   * Whether the given <code>yytable_</code> value indicates a syntax error.
   * @@param $yyvalue   the value to check
   */
  private function yy_table_value_is_error_ ($yyvalue)
  {
    return $yyvalue == self::yytable_ninf_;
  }

  const yypact_ninf_ = ]b4_pact_ninf[;
  const yytable_ninf_ = ]b4_table_ninf[;

  ]b4_parser_tables_define[
  ]b4_integral_parser_table_define([token_number], [b4_toknum],
     [TOKEN_NUMBER_[YYLEX-NUM] -- Internal symbol number corresponding
     to YYLEX-NUM.])[

  /* YYTNAME[SYMBOL-NUM] -- String name of the symbol SYMBOL-NUM.
     First, the terminals, then, starting at \a yyntokens_, nonterminals.  */
  ]b4_typed_parser_table_define([String], [tname], [b4_tname])[

  ]b4_integral_parser_table_define([rline], [b4_rline],
  [YYRLINE[YYN] -- Source line where rule number YYN was defined.])[

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
                       $this->yystos_[$yystack->stateAt($yyi + 1 - $yynrhs)],
                       ]b4_rhs_value($yynrhs, $yyi + 1)b4_locations_if([,
                       b4_rhs_location($yynrhs, $yyi + 1)])[);
  }

  /* YYTRANSLATE(YYLEX) -- Bison symbol number corresponding to YYLEX.  */
  ]b4_integral_parser_table_define([translate_table], [b4_translate])[

  private function yytranslate_ ($t)
  {
    if ($t >= 0 && $t <= self::yyuser_token_number_max_)
      return $this->yytranslate_table_[$t];
    else
      return self::yyundef_token_;
  }

  const yylast_ = ]b4_last[;
  const yynnts_ = ]b4_nterms_number[;
  const yyempty_ = -2;
  const yyfinal_ = ]b4_final_state_number[;
  const yyterror_ = 1;
  const yyerrcode_ = 256;
  const yyntokens_ = ]b4_tokens_number[;

  const yyuser_token_number_max_ = ]b4_user_token_number_max[;
  const yyundef_token_ = ]b4_undef_token_number[;

]/* User implementation code.  */
b4_percent_code_get[]dnl

}

b4_epilogue[]dnl
m4_divert_pop(0)dnl
