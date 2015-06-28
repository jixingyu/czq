<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Download Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/download_helper.html
 */

// ------------------------------------------------------------------------

if ( ! function_exists('go_dashboard'))
{
	function go_dashboard()
	{
	    redirect('admin/dashboard');
	}
}

function output_csv($data, $filename = 'output.csv') {
	if (!empty($data)) {
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename={$filename}");
		header("Pragma: no-cache");
		header("Expires: 0");
		$output = fopen("php://output", "w");
		foreach ($data as $row) {
			fputcsv($output, $row);
		}
		fclose($output);
	}
}
