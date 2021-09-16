<?php
require_once("Figure.php");

class FigureO extends Figure
{
	function __construct()
	{
		$this->CreateFigure();
	}
	private function CreateFigure()
	{
		$this->bodyFigure[0] = array(1, 1, 1, 2, 2, 1, 2, 2);
	}
	public function TypeFigure()
	{
		return "O";
	}
}
?>