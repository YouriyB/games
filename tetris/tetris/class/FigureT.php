<?php
require_once("Figure.php");

class FigureT extends Figure
{
	function __construct()
	{
		$this->CreateFigure();
		parent::__construct();
	}
	private function CreateFigure()
	{
		$this->bodyFigure[0] = array(0, 1, 1, 0, 1, 1, 1, 2);
		$this->bodyFigure[1] = array(0, 1, 1, 1, 1, 2, 2, 1);
		$this->bodyFigure[2] = array(1, 0, 1, 1, 1, 2, 2, 1);
		$this->bodyFigure[3] = array(0, 1, 1, 0, 1, 1, 2, 1);
	}
	public function TypeFigure()
	{
		return "T";
	}
}
?>