<?php
require_once("Figure.php");

class FigureZ extends Figure
{
	function __construct()
	{
		$this->CreateFigure();
		parent::__construct();
	}
	private function CreateFigure()
	{
		$this->bodyFigure[0] = array(1, 0, 1, 1, 2, 1, 2, 2);
		$this->bodyFigure[1] = array(0, 2, 1, 1, 1, 2, 2, 1);
	}
	public function TypeFigure()
	{
		return "Z";
	}
}
?>