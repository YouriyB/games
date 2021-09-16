<?php
require_once("Figure.php");

class FigureJ extends Figure
{
	function __construct()
	{
		$this->CreateFigure();
		parent::__construct();
	}
	private function CreateFigure()
	{
		$this->bodyFigure[0] = array(0, 2, 1, 2, 2, 1, 2, 2);
		$this->bodyFigure[1] = array(1, 1, 2, 1, 2, 2, 2, 3);
		$this->bodyFigure[2] = array(1, 1, 1, 2, 2, 1, 3, 1);
		$this->bodyFigure[3] = array(1, 0, 1, 1, 1, 2, 2, 2);
	}
	public function TypeFigure()
	{
		return "J";
	}
}
?>