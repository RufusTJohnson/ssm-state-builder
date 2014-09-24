<?php 
// *****************************************************************************
// *****************************************************************************
// copyright 2008 SamSu, all rights reserved
//
// File:             	misc_routines.php
// Date:            	5 March 2009
// Description:      	Miscellaneous Routines
// *****************************************************************************
// *****************************************************************************

// ************************************************************************* 
// Logger Class
// *************************************************************************
class ssm_cLogger 
{    
    // ********************************************************************* 
	// Text contained in this class
    // ********************************************************************* 
    var $txt = "";
	
    // ********************************************************************* 
	// Line Termination Character
    // ********************************************************************* 
    var $lineTerm= "\r\n";
	
    // ********************************************************************* 
	// Space Character
    // ********************************************************************* 
    var $spaceChar= " ";
	
	// ********************************************************************* 
	// Indent Level
    // ********************************************************************* 
    var $indentLevel = 0;
	
	// ********************************************************************* 
	// Spaces to indent per Indent Level
    // ********************************************************************* 
    var $indentSpaces = 3;

	// ********************************************************************* 
	// Constructor
    // ********************************************************************* 
	function __construct()
	{    
		$this->indentSpaces = 3;
		$this->indentLevel 	= 0;
		$this->text_setup;
	}

	// **********************************************************************
	// Insert string into another string
	// **********************************************************************
	public static function insertString($parentString,$childString,$start)
	{
		$beg = substr($parentString,0,$start);
		$mid = $childString;
		$end = substr($parentString,$start+strlen($mid));
		return $beg.$mid.$end;
	}




	// ********************************************************************* 
	// Set up for HTML display
    // ********************************************************************* 
    function html_setup()
    {
		$this->lineTerm		= "<br/>";
		$this->spaceChar	= "&nbsp;";	  
    }

	// ********************************************************************* 
	// Set up to Write to a Text File
    // ********************************************************************* 
    function text_setup()
    {    
		$this->lineTerm		= "\r\n";
		$this->spaceChar	= " ";		
    }

    // ********************************************************************* 
	// Create indent string
    // ********************************************************************* 
    function indentString()
    {
		return str_repeat($this->spaceChar,$this->indentSize());
    }
	
    // ********************************************************************* 
	// Create indent string
    // ********************************************************************* 
    function indentSize()
    {
		return($this->indentSpaces*$this->indentLevel);
    }
	
    // ********************************************************************* 
	// increase the indent level
    // ********************************************************************* 
    function incLevel()
    {
		$this->indentLevel++;
    }
	
    // ********************************************************************* 
	// decrease the indent level
    // ********************************************************************* 
    function decLevel()
    {
		if ($this->indentLevel>0) $this->indentLevel--;
    }
	
	// ********************************************************************* 
	// multiple carriage returns
    // ********************************************************************* 
    function cr($number_of_cr=1)
    {
		return str_repeat($this->lineTerm,$number_of_cr);
    }

	
	// ********************************************************************
	// Append
	// ********************************************************************
	function append($str)
	{
		$this->txt = $this->txt.$str;  
	}
	

	
	// ********************************************************************
	// AppendCR
	// ********************************************************************
	function appendCR($str="")
	{
		$this->txt = $this->txt.$str.$this->lineTerm; 
	}
	
	// ********************************************************************
	// Append Indent
	// ********************************************************************
	function appendIndent($str="")
	{
		$this->txt =  $this->txt.$this->indentString().$str;  
	}
	
	// ********************************************************************
	// Append Indent CR
	// ********************************************************************
	function appendIndentCR($str="",$cr_after=1,$cr_before=0)
	{
		$this->txt .=  $this->cr($cr_before).$this->indentString().$str.$this->cr($cr_after);
	}	
	
	
	// ********************************************************************
	// Append Indent CR THE CALLING FUNCTION
	// ********************************************************************
	function appendIndentCR_THIS_FUNCTION($str="",$cr_after=1,$cr_before=0)
	{
		$vDebug = debug_backtrace();
		$callingFunction = $vDebug[1]['function']."()";
		$this->txt .=  $this->cr($cr_before).$this->indentString()."Enter Function:".$callingFunction.$str.$this->cr($cr_after);
	}	
	

	// ********************************************************************
	// Banner
	// ********************************************************************
	function banner($banner_text)
	{

		$banner =  $this->lineTerm.str_repeat("*",80).$this->lineTerm."* ".$banner_text.$this->lineTerm.str_repeat("*",80).$this->lineTerm;
		$this->txt.=$banner;	
	}	
	
	// ********************************************************************
	// Indented Banner
	// ********************************************************************
	function indentedBanner($banner_text)
	{
		$iVal 	= $this->indentSize();
		$frame 	= "// ".str_repeat("*",80-$iVal-3);

		$this->appendIndentCR($frame );
		$this->appendIndentCR("// ".$banner_text);
		$this->appendIndentCR($frame );
	}	
	

	
	// ********************************************************************
	// Append all text to file
	// ********************************************************************
	function appendToFile($filename)
	{
		$fh = fopen($filename, 'a') or die("can't open file");
		fwrite($fh, $this->txt);
		fclose($fh);
	}
	
}    



?>