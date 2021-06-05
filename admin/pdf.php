<?php

//Config Dompdf to support print pdf file

require_once 'dompdf/autoload.inc.php';

//Reference the Dompdf namespace 
use Dompdf\Dompdf;

class Pdf extends Dompdf
{
	// Constructor function
	public function __construct()
	{
		// Call all construct function of parent class(Dompdf)
		parent::__construct();
	}
}


?>