<?php
require_once("Figure.php");

class FigureS extends Figure
{
	function __construct()
	{
		$this->CreateFigure();
		parent::__construct();
	}
	private function CreateFigure()
	{
		$this->bodyFigure[0] = array(1, 1, 1, 2, 2, 0, 2, 1);
		$this->bodyFigure[1] = array(0, 1, 1, 1, 1, 2, 2, 2);
	}
	public function TypeFigure()
	{
		return "S";
	}
}
?>