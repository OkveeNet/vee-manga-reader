<?php
/* 
 * author: mr.v automatic
 * copy from http://glennpratama.wordpress.com/2009/10/20/multi-level-subfolder-for-controller-in-codeigniter/
 */

class MY_Router extends CI_Router {

	function  __construct() {
		parent::CI_Router();
	}// __construct

	// --------------------------------------------------------------------

	/**
	 * Validates the supplied segments.  Attempts to determine the path to
	 * the controller.
	 *
	 * @access	private
	 * @param	array
	 * @return	array
	 */
	function _validate_request($segments)
	{
		// Does the requested controller exist in the root folder?
		if (file_exists(APPPATH.'controllers/'.$segments[0].EXT))
		{
			return $segments;
		}

		// Is the controller in a sub-folder?
		if (is_dir(APPPATH.'controllers/'.$segments[0]))
		{
			// Set the directory and remove it from the segment array
			$this->set_directory($segments[0]);
			$segments = array_slice($segments, 1);

			/* ----------- ADDED CODE ------------ */

			while(count($segments) > 0 && is_dir(APPPATH.'controllers/'.$this->directory.$segments[0]))
			{
				// Set the directory and remove it from the segment array
    		$this->set_directory($this->directory . $segments[0]);
    		$segments = array_slice($segments, 1);
			}

			/* ----------- END ------------ */

			if (count($segments) > 0)
			{
				// Does the requested controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].EXT))
				{
					show_404($this->fetch_directory().$segments[0]);
				}
			}
			else
			{
				$this->set_class($this->default_controller);
				$this->set_method('index');

				// Does the default controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.EXT))
				{
					$this->directory = '';
					return array();
				}

			}

			return $segments;
		}

		// Can't find the requested controller...
		show_404($segments[0]);
	}// _validate_request

}

/* end of file */