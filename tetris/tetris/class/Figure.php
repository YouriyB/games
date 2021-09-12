<?php
abstract class Figure
{
	protected $bodyFigure = array();
	private $stateFigure = 0;
	
	function __construct()
	{
		$this->stateFigure = rand(0, count($this->bodyFigure) - 1);
	}
	public function RotateFigureClockWise()
	{
		$this->stateFigure++;
		if(count($this->bodyFigure) == $this->stateFigure)
			$this->stateFigure = 0;
	}
	public function RotateFigureCounterClockWise()
	{
		$this->stateFigure--;
		if($this->stateFigure < 0)
			$this->stateFigure = count($this->bodyFigure) - 1;
	}
	public function GetFigure()
	{
		return $this->bodyFigure[$this->stateFigure];
	}
}
?>