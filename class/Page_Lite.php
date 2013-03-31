<?php

/*
 *******************************************************
 *   PageLite Pagiation System                         #
 *******************************************************
 *
 * PageLite is a extremely light weight, portable and simple to use
 * pagiation system.
 *
 * It is built using strict OOP technology and standards and runs
 * completely based off of PHP code, which means there is no
 * Database communication required.
 *
 * PageLite is compatiable with any and all Data storing method
 * wether it is a MySQL Server or a old-skool flat-file datastore
 * PageLite can built a pagination for it.
 *
 * Pagelite only requires 2 lines of code to run and will build and return
 * information that you can put into your information query retrieval method.
 *
 * The goal of PageLite is to provide a simple, easy to use and powerful
 * pagiation system for everyone and a flexiable and extendable system
 * for advanced users
 *
 * If you would like to thank me for pageLite shoot me an email.
 *
 * Have Fun and happy coding!
 *
 * @copyright Copyright (c) Nickolas Whiting, 2008
 * @license GNU/GPL
 * @author Nickolas Whiting <nwhiting@xstudiosinc.com>
 * @version 1.0
 *
 * @todo Intergrate Plugin System
 */

class Page_Lite
{
	/*
	 * Total Number of items that are being sorted by the SQL Query
	 *
	 * @access protected
	 */

	protected $_totalItems = null;

	/*
	 * Url Link that will be used for pagination
	 *
	 * @access protected
	 */

	protected $_pagiUrl = null;

	/*
	 * Total Number of items per page
	 *
	 * @access protected
	 */

	protected $_perPage = 10;

	/*
	 * Total Number of lines to display half on each side of current page
	 *
	 * @access protected
	 */

	protected $_range = 5;

	/*
	 * Use mod_rewrite style links
	 *
	 * @access protected
	 */

	protected $_rewrite = false;

	/*
	 * Use page Jumping links ( 3, 7, 13, 25 )
	 *
	 * @access protected
	 */

	protected $_pageJump = true;

	/*
	 * Total Number of jumps to perform per side
	 *
	 * @access protected
	 */

	protected $_jumpCount = 5;

	/*
	 * Calculation number for a jump link ( currentPageLink * number = jumpLink)
	 *
	 * Lower numbers are recommended for foward jumping Anything over 1.25 will cause the links to jump
	 * at a very high rate
	 *
	 * @access protected
	 */

	protected $_jumpCalcFoward = 1.15;

	/*
	 * Calculation number for a jump link ( currentPageLink * number = jumpLink)
	 *
	 * Lower numbers are not recommended for backwards jumping Anything under 1.25 will cause the links allmost to completely not jump
	 *
	 * @access protected
	 */

	protected $_jumpCalcBackward = 1.25;

	/*
	 * GET Variable that the current page number is stored in
	 *
	 * @access protected
	 */

	protected $_pageIdentifer = 'page';

	/*
	 * Automatically extract the page number based on the pageIdenitifer
	 *
	 * @access protected
	 */

	protected $_useAutoIdentifier = true;

	/*
	 * Total Number of pages based on totalItems / perPage
	 *
	 * @access public
	 */

	public $totalPages = null;

	/*
	 * Styleization Array
	 *
	 * @access public
	 * @static
	 */

	public static $styleArray = array(
									  'start' 		  => '',
									  'end'			  => '',
									  'first_start'   => '',
									  'first_end'     => '',
									  'last_start'    => '',
									  'last_end'      => '',
									  'current_start' => '',
									  'current_end'   => '',
									  'link_start'    => '',
									  'link_end'      => '',
									  'next_start' 	  => '',
									  'next_end'      => '',
									  'prev_start'    => '',
									  'prev_end'      => ''
									  );

	/*
	 * Current Page
	 *
	 * @access protected
	 */

	public $currentPage = null;

	/*
	 * Option to show the link to the first page results
	 *
	 * @access protected
	 */

	protected $_displayLinkFirst = true;

	/*
	 * Option to show link for the previous page results
	 *
	 * @access protected
	 */

	protected $_displayLinkPrev = true;

	/*
	 * Option to show the link to the last page results
	 *
	 * @access protected
	 */

	protected $_displayLinkLast = true;

	/*
	 * Option to show the link to the next page results
	 *
	 * @access protected
	 */

	protected $_displayLinkNext = true;

	/*
	 * Text to be displayed for page 1 link
	 *
	 * @access protected
	 */

	public $linkPageOne = '[&laquo;]';

	/*
	 * Text to be displayed for prev page link
	 *
	 * @access protected
	 */

	public $linkPagePrev = '&laquo;';

	/*
	 * Text to be displayed for Last page link
	 *
	 * @access protected
	 */

	public $linkPageLast = '[&raquo;]';

	/*
	 * Text to be displayed for next page link
	 *
	 * @access protected
	 */

	public $linkPageNext = '&raquo;';

	/*
	 * Constructor
	 *
	 * Set the total number of items and the pageURL, also check for pageIdentifier and try and get current page number
	 * if not set
	 *
	 * @access protected
	 */

	public function __construct($totalItems, $pagiUrl)
	{
		$this->setTotalItems($totalItems);

		$this->setPageUrl($pagiUrl);
	}

	/*
	 * Sets option to display 1st page link
	 */

	public function setDisplayLinkFirst($flag)
	{
		$this->_displayLinkFirst = $flag;
	}

	/*
	 * Sets option to display Prev Page link
	 */

	public function setDisplayLinkPrev($str)
	{
		$this->_displayLinkPrev = $str;
	}

	/*
	 * Sets option to display Last page link
	 */

	public function setDisplayLinkLast($flag)
	{
		$this->_displayLinkLast = $flag;
	}

	/*
	 * Sets option to display Next page link
	 */

	public function setDisplayLinkNext($flag)
	{
		$this->_displayLinkNext = $flag;
	}

	/*
	 * Sets text that will be displayed for 1st page link
	 */

	public function setLinkFirst($str)
	{
		$this->linkPageOne = $str;
	}

	/*
	 * Sets text that will be displayed for next page link
	 */

	public function setLinkNext($str)
	{
		$this->linkPageNext = $str;
	}

	/*
	 * Sets text that will be displayed for last page link
	 */

	public function setLinkLast($str)
	{
		$this->linkPageLast = $str;
	}

	/*
	 * Sets text that will be displayed for prev page link
	 */

	public function setLinkPrev($str)
	{
		$this->linkPagePrev = $str;
	}

	/*
	 * Sets the pageIdentifier
	 *
	 * @access protected
	 */

	public function setIdentifier($str)
	{
		$this->_pageIdentifer = $str;
	}

	/*
	 * Sets the current Page
	 *
	 * @access protected
	 */

	public function setCurrentPage($int)
	{
		$this->currentPage = $int;
	}

	/*
	 * Register the AutoIdentifier on|off
	 *
	 * @access protected
	 */

	public function setAutoIdentifier($flag)
	{
		$this->_useAutoIdentifier = $flag;
	}

	/*
	 * Sets the style that will be used to parse out the pagination
	 *
	 * @access public
	 */

	public function setStyleArray($style)
	{
		$this->styleArray = $style;
	}


	/*
	 * Checks and returns if the page number
	 *
	 * @access public
	 */

	public function getCurrentPage()
	{
		// Check if auto identify is disabled if so we dont worry if the page count is correct
		if ( $this->_useAutoIdentifier == false )
		{
			return $this->currentPage;
		}
		else
		{

			if (isset($_GET[$this->_pageIdentifer]))
			{
				$this->currentPage = (int) $_GET[$this->_pageIdentifer];

				return (int) $_GET[$this->_pageIdentifer];
			}
			else
			{
				$this->currentPage = 1;
				return 1;
			}
		}
	}

	/*
	 * Set the page range
	 * @access public
	 */

	public function setRange($count)
	{
		$this->_range = $count;
	}

	/*
	 * Sets the number of jump Links
	 * @access public
	 */

	public function setPageJumpCount($count)
	{
		$this->_jumpCount = $count;
	}

	/*
	 * Set page jumping on|off
	 * @access public
	 */

	public function setPageJump($flag)
	{
		$this->_pageJump = $flag;
	}

	/*
	 * Sets the use of mod_rewrite style links
	 * @access public
	 */

	public function setRewrite($flag)
	{
		$this->_rewrite = $flag;
	}

	/*
	 * Sets the total number of items
	 *
	 * @access public
	 */

	public function setTotalItems($int)
	{
		$this->_totalItems = $int;
	}

	/*
	 * Sets the Jump Forward Calculator
	 *
	 * @access public
	 */

	public function setJumpCalForward($int)
	{
		$this->_jumpCalcFoward = $int;
	}

	/*
	 * Sets the Jump Forward Calculator
	 *
	 * @access public
	 */

	public function setJumpCalBackward($int)
	{
		$this->_jumpCalcBackward = $int;
	}

	/*
	 * Sets the pageUrl
	 *
	 * @access public
	 */

	public function setPageUrl($url)
	{
		$this->_pagiUrl = $url;
	}

	/*
	 * Sets the number of items per page
	 *
	 * @access public
	 */

	public function setPerPage($int)
	{
		$this->_perPage = $int;
	}

	/*
	 * Returns the number of items that are displayed per page
	 *
	 * @access public
	 */

	public function getPerPage()
	{
		return $this->_perPage;
	}

	public function getStart()
	{
		return ($this->getCurrentPage() == 1) ? 0 : $this->getCurrentPage() * $this->getPerPage();
	}

	/*
	 * Calculates the total number of pages based on the totalItems and Perpage
	 *
	 * @access public
	 */

	public function calculatePageTotal()
	{
		return $this->totalPages = floor( ($this->_totalItems) / ($this->_perPage) );
	}

	/*
	 * Returns the total number of pages
	 *
	 * @access public
	 * @return int
	 */

	public function getTotalPages()
	{
		return $this->calculatePageTotal();
	}

	/*
	 * Build the pagination
	 *
	 * @access public
	 * @return string
	 */

	public function build()
	{

		$return = '';

		$back = '';

		// Figure out the total number of pages. Always round up :)>
		$totalPages = $this->getTotalPages();

		$range = ceil($this->_range / 2);

		$jumpRange = ceil($this->_jumpCount / 2);

		$return .= $this->styleArray['start'];

		$currentPage = $this->getCurrentPage();

		if ($this->_rewrite == true)
		{
			$append = '/';
		}
		elseif ( strpos($this->_pagiUrl, '?') === false )
		{
			$append = '?'.$this->_pageIdentifer.'=';
		}
		else
		{
			$append = '&'.$this->_pageIdentifer.'=';
		}

		if ($currentPage != 1 && $this->_displayLinkFirst == true)
		{
			$return .= $this->styleArray['first_start'].' <a href="'.$this->_pagiUrl.$append.'1" title="Go to page 1  of '.$totalPages.'">'.$this->linkPageOne.'</a>'.$this->styleArray['first_end'];
		}

		if ($currentPage > 1 && $this->_displayLinkPrev == true)
		{
			$prev = ($currentPage - 1);
			$return .= $this->styleArray['prev_start'].'  <a href="'.$this->_pagiUrl.$append.$prev.'" title="Go to page '.$prev.' of '.$totalPages.'">'.$this->linkPagePrev.'</a>  '.$this->styleArray['prev_end'];
		}

		// Build Backward Links
		for ($i = 1; $i <= $range; $i++)
		{
			$p = $currentPage - $i;
			if ($p > 0)
			{
				$back .= $p.',';
			}
		}

		$lastBack = $currentPage - $range - 1;

		// Backwards Jumps!

		// 1.2 Update
		// When jumping backwards we need to use floor....not ceil.....

		if ($lastBack > 2 && $this->_pageJump == true)
		{
			for ($i = 1; $i <= $jumpRange; $i++)
			{

				$lastBack = floor($lastBack / $this->_jumpCalcBackward);

				if ($lastBack >= 2)
				{
					$back .= $lastBack . ',';
				}
			}
		}

		$back = strrev($back);

		$backJump = explode(',', $back);


		foreach ($backJump as $rev)
		{
			if( $rev != null )
			{
				$rev = strrev($rev);
				$return .= $this->styleArray['link_start'].'  <a href="'.$this->_pagiUrl.$append.$rev.'" title="Go to page '.$rev.' of '.$totalPages.'">'.$rev.'</a> '.$this->styleArray['link_end'];
			}
		}

		$return .=  $this->styleArray['current_start'].$currentPage.'</span>'.$this->styleArray['current_end'];


		for ($i = 1; $i <= $range; $i++)
		{

			$p = $currentPage + $i;
			if($p <= $totalPages)
			{
				$return .= $this->styleArray['link_start'].' <a href="'.$this->_pagiUrl.$append.$p.'" title="Go to page '.$p.' of '.$totalPages.'">'.$p.'</a> '.$this->styleArray['link_end'];
			}
		}

		$lastPage = $currentPage + $range;

		if ($this->_pageJump == true)
		{
			for ($i = 1; $i <= $jumpRange; $i++)
			{
				$lastPage = ceil($lastPage * $this->_jumpCalcFoward);

				if ($lastPage <= $totalPages)
				{

					$return .= $this->styleArray['link_start'].' <a href="'.$this->_pagiUrl.$append.$lastPage.'" title="Go to page '.$lastPage.' of '.$totalPages.'">'.$lastPage.'</a> '.$this->styleArray['link_end'];
				}
			}
		}

		// Build Next Link
		if ($currentPage < $totalPages && $totalPages != '0' && $this->_displayLinkNext == true)
		{
			$next = $currentPage + 1;
		    $return .= $this->styleArray['next_start'].' <a href="'.$this->_pagiUrl.$append.$next.'" title="Go to page '.$next.' of '.$totalPages.'">'.$this->linkPageNext.'</a> '.$this->styleArray['next_end'];
		}

		if ($currentPage != $totalPages && $totalPages != '0' && $this->_displayLinkLast == true)
		{
			$return .= $this->styleArray['last_start'].'<a href="'.$this->_pagiUrl.$append.$totalPages.'" title="Go to page '.$totalPages.' of '.$totalPages.'">'.$this->linkPageLast.'</a>'.$this->styleArray['last_end'];
		}

		$return .= $this->styleArray['end'];

		return  $return;
	}
}


?>