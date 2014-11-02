<?php

/**

Plugin Name: State Builder
Plugin URI: http://www.sammela.com
Description: Does different things with states, such as a page with tables to link to individual state pages.
Version: 0.1
Author: Sam Mela
Author URI: http://www.sammela.com
Based on: http://www.mijnpress.nl/blog/plugin-framework/
*/



if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");

if(!class_exists('mijnpress_plugin_framework'))

{
	include('mijnpress_plugin_framework.php');
}
include_once('logger.php');

/**
* Support class for ssm state builder form
* @author Sam Mela, http://www.swammela.com.nl
*/
class ssm_state_builder_form_support
{
	var $errors;
	var $states;
	CONST PARENT_PAGE_TITLE = "Food By State";
	CONST ERROR					= "ERROR";
	CONST WARNING				= "WARNING";

	/**
	 * Contructor
	 */
	function __construct()
	{
			$this->errors = array();
			$this->build_states();
	}

	/**
	 * Build the states variable
	 */
	function build_states()
	{
		$this->states = 	array( "Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii",
								"Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota",
								"Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Carolina",
								"North Dakota","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah",
								"Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming");
	}

	/**
	 * Add Error
	 */
	function add_error($text) 
	{
		$this->errors[] = array(self::ERROR,$text);
	}

	/**
	 * Add Warning
	 */
	function add_warning($text) 
	{
		$this->errors[] = array(self::WARNING,$text);
	}

	/**
	 * Build error table
	 */
	function get_errors_1() 
	{
		$logger 	= new ssm_cLogger(); 
		$logger->incLevel();
		$error_logger		= new ssm_cLogger(); $error_logger->incLevel();

		$logger->appendIndentCR("<table class=\"widefat  ssm_error_table\">"); 
		//$logger->appendIndentCR("<table class=\"ssm_error_table\">"); 
		$logger->incLevel();
		
		foreach($this->errors as $row)
		{
			$even = !$even;
			$error_type = $row[0];
			$error 		= $row[1];
			$tr_class	=	$even ? "iedit" : "iedit alternate";
			$logger->appendIndentCR("<tr class=\"$tr_class\">");
			$logger->incLevel();
				$logger->appendIndentCR("<td class=\"ssm_error_type\">$error_type</td>");			
				$logger->appendIndentCR("<td class=\"ssm_error_text\">$error</td>");			
			$logger->decLevel();
			$logger->appendIndentCR("</tr>");			
		}
		$logger->decLevel();		
		$logger->appendIndentCR("</table>");
		//echo("<pre>".print_r($logger)."</pre>");
		return $logger->txt;
	}
	
	/**
	 * Build error list
	 */
	function get_errors() 
	{
		$logger 	= new ssm_cLogger(); 
		$logger->incLevel();
		$error_logger		= new ssm_cLogger(); $error_logger->incLevel();
		foreach($this->errors as $row)
		{
			$error_type = $row[0];
			$error 		= $row[1];
			$logger->appendIndentCR("<span class=\"ssm_error_type\">$error_type </span>"."<span class=\"ssm_error_text\">$error</span><br />");			
		}
		//echo("<pre>".print_r($logger)."</pre>");
		return $logger->txt;
	}

	/**
	 * Display errors
	 */
	function display_errors( ) 
	{
		$error_list = $this->get_errors();
		echo ("<div class=\"error\">$error_list</div>");
	}
	


	/**
	 * Build a page
	 */
	function create_page($post_data) 
	{
		wp_insert_post( $post_data,$wp_error);
	}
	
	/**
	 * Build a link
	 */
	function buildlink($url,$text,$title="")
	{
		return "<a href=\"$url\" title=\"$title\" >$text</a>";
	}

	/**
	 * Get state slug
	 */
	function get_state_slug($state)
	{
		return sanitize_title_with_dashes($state);
	}

	
	/**
	 * Build "Food By State" page content
	 */
	function build_food_by_state_page_content() 
	{
		$logger 		= new ssm_cLogger();
		$row_count	= ceil(count($this->states)/4.0);

		$logger->appendIndentCR("<table class=\"food-by-state\">"); 
		$logger->incLevel();
		for($row=0; $row<$row_count; $row++)
		{
			$logger->appendIndentCR("<tr>");
			$logger->incLevel();
			for($col=0; $col<4; $col++)
			{
				$state				=	$this->states[$row + $col*$row_count];
				$state_slug			=	$this->get_state_slug($state);
				$url 	= 	get_site_url()."/".$state_slug;
				$text	= 	$state;
				$link = 	$this->buildlink($url,$text,$state);
				$logger->appendIndentCR("<td>$link</td>");			
			}
			$logger->decLevel();
			$logger->appendIndentCR("<tr>");
		}
		$logger->decLevel();
		$logger->appendIndentCR("</table>");
		//echo("<pre>".print_r($logger)."</pre>");
		return $logger->txt;
	}
	

	
	/**
	 * Build "Food By State" page
	 */
	function build_food_by_state_page() 
	{
		$success			=	true;
		$page				=	get_page_by_title( self::PARENT_PAGE_TITLE);
		//echo("<pre>".print_r($page)."</pre>");		
		
		if (is_null($page))
		{
			$post_data = array();
			$post_content 						= 		$this->build_food_by_state_page_content();
			$post_data["post_title"]		= 		self::PARENT_PAGE_TITLE;
			$post_data["post_content"]		= 		$post_content;
			$post_data["post_status"]		=		"publish";
			$post_data["post_type"]			=		"page";
			$post_data["post_author"]		=		"admin";
			$result 								= 		wp_insert_post($post_data,$wp_error);
			if ($wp_error)
			{
				$this->add_error("Unable to create " . self::PARENT_PAGE_TITLE. ".");
				$success					=	false;
			};
		}
		else
		{
			$this->add_error(self::PARENT_PAGE_TITLE . " already exists.");
			$success					=	false;
		}
		
		return $success;
	}
	
	
	/**
	 * Build state pages
	 */
	function create_state_pages() 
	{
		
		$parent_page =	get_page_by_title( self::PARENT_PAGE_TITLE);
		if (is_null($parent_page))
		{
			$error = self::PARENT_PAGE_TITLE . " does not exists.  Cannot create state pages.";
			$this->add_error($error);
			throw new Exception($error);
		}
		
		
		$parent_id					= 	$parent_page->ID;
		
		$post_data = array();
		foreach($this->states as $state)
		{
			$child_page =	get_page_by_title($state);			
			if (!is_null($child_page))
			{
				$error = "$state already exists.";
				$this->add_error($error);
				throw new Exception($error);
			}
		
		
					
			$post_data["post_title"]		= 		$state;
			$post_data["post_content"]		= 		
				"<p><span style=\"color: #ff0000; font-size: 14pt;\">Welcome to the 18-Mealer $state page!</span></p>".
				"<p>Currently there are no meal providers signed up in $state, so this is a fantastic opportunity for you ".
				"to be our very first provider.</p><p>[sc:18MealerApplicationLink]</p>";
			$post_data["post_status"]		=		"publish";
			$post_data["post_parent"]		=		$parent_id;
			$post_data["post_type"]			=		"page";
			$post_data["post_author"]		=		"admin";
			$id 									= 		wp_insert_post( $post_data,true);
			
			if (is_wp_error($result)) 
			{
				$errors = $id->get_error_messages();
				foreach ($errors as $error) 
				{
					$this->add_error($error);
				}
				throw new Exception("Error creating $state page.");
			}
		}
	}


}








/**
 * If submiting the form
 */

//if (isset ($_POST['submitbutton']) && isset ($_POST['postorpage'])) {

//	if (!isset ($_POST['titles']) || !$_POST['titles']) {

//echo("<pre>".print_r($_POST)."</pre>");




class plugin_ssm_state_builder extends mijnpress_plugin_framework
{
	function __construct()
	{
		$this->showcredits = true;
		$this->showcredits_fordevelopers = true;
		$this->plugin_title = 'State Builder';
		$this->plugin_class = 'plugin_ssm_state_builder';
		$this->plugin_filename = 'ssm-state-builder/ssm-state-builder.php';
		$this->plugin_config_url = 'plugins.php?page='.$this->plugin_filename;
	}



	function plugin_ssm_state_builder()
	{
		$args= func_get_args();
		call_user_func_array
		(
		    array(&$this, '__construct'),
		    $args
		);
	}



	function addPluginSubMenu()

	{
		$plugin = new plugin_ssm_state_builder();
		parent::addPluginSubMenu($plugin->plugin_title,array($plugin->plugin_class, 'admin_menu'),__FILE__);
	}



	/**

	 * Additional links on the plugin page

	 */

	function addPluginContent($links, $file) {

		$plugin = new plugin_ssm_state_builder();

		$links = parent::addPluginContent($plugin->plugin_filename,$links,$file,$plugin->plugin_config_url);

		return $links;

	}



	/**

	 * Shows the admin plugin page

	 */

	public function admin_menu()

	{
		$plugin = new plugin_ssm_state_builder();		
		$plugin->content_start();		
		include('form.php');
		$plugin->content_end();
	}

}



// Admin only

if(mijnpress_plugin_framework::is_admin())
{
	add_action('admin_head',  'ssm_state_builder_create_pages');
	add_action('admin_menu',  array('plugin_ssm_state_builder', 'addPluginSubMenu'));
	add_filter('plugin_row_meta',array('plugin_ssm_state_builder', 'addPluginContent'), 10, 2);
	
	

	
}



function ssm_state_builder_create_pages()
{
	global $ssm_state_builder;
	
	if (isset ($_POST['submitbutton']) )
	{
		try 
		{
			$ssm_state_builder = new ssm_state_builder_form_support();
			//$ssm_state_builder->add_error("Some random error");
			//$ssm_state_builder->add_warning("Some warning");
			//$ssm_state_builder->create_state_pages();
			$ssm_state_builder->build_food_by_state_page();
			$ssm_state_builder->create_state_pages();
		} 
		catch (Exception $e)
		{
			//echo "Fatal error(s)<br />";
		}
		
		if (count($ssm_state_builder->errors) > 0)
		{
			add_action('admin_notices', array($ssm_state_builder,"display_errors"));
		}
		//echo $ssm_state_builder->get_errors();	
	}	
}
?>