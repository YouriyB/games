<?php
class GameArea
{
	private $gameArea = array(array());
	private $sizeWidth = 0;//ширина поля
	private $sizeHeight = 0;//высота поля
	private $positionX = 0;//прозиция Х фигуры
	private $positionY = 0;//позиция Y фигуры
	private $fillLines = array();
	
	function __construct($sWidth, $sHeight)
	{
		$this->sizeWidth = $sWidth + 2;
		$this->sizeHeight = $sHeight + 1;
		$this->CreateGameArea();
	}
	private function CreateGameArea()
	{
		for($i = 0; $i < $this->sizeHeight; $i++)
		{
			for($j = 0; $j < $this->sizeWidth; $j++)
			{
				if($i == $this->sizeHeight - 1 || $j == 0 || $j == $this->sizeWidth - 1)
					$this->gameArea[$i][$j] = 1;
				else
					$this->gameArea[$i][$j] = 0;
			}
		}
	}
	public function GetGameArea()
	{
		return $this->gameArea;
	}
	public function GetFillLines()
	{
		return count($this->fillLines);
	}
	public function TestFillLine()
	{
		$lineFits = "stop";
		for($i = $this->sizeHeight - 2; $i >= 0; $i--)
		{
			for($j = 1; $j < $this->sizeWidth - 1; $j++)
			{
				if($this->gameArea[$i][$j] != 1)
					break;
				else if($j == $this->sizeWidth - 2)
				{
					$this->fillLines []= $i;
					$lineFits = "fits";
					for($f = 1; $f < $this->sizeWidth - 1; $f++)
					{
						$this->gameArea[$i][$f] = 4;
					}
					break;
				}
			}
		}
		return $lineFits;
	}
	public function DeleteFillLine()
	{
		for($i = count($this->fillLines) - 1; $i >= 0; $i--)
		{
			for($j = $this->fillLines[$i]; $j >= 0; $j--)
			{
				if($j == 0)
				{
					$this->gameArea[0] = array_fill(0, $this->sizeWidth, 0);
					$this->gameArea[0][0] = 1;
					$this->gameArea[0][$this->sizeWidth - 1] = 1;
				}
				else
				{
					$this->gameArea[$j] = $this->gameArea[$j - 1];
				}
			}
		}
		$this->fillLines = array();
	}
	public function AddFigura($figure)
	{
		$ok = "stop";
		$this->positionY = 0;
		$this->positionX = $this->sizeWidth / 2 - 2;
		for($i = 0; $i < count($figure); $i += 2)
		{
			$this->gameArea[$this->positionY + $figure[$i]][$this->positionX + $figure[$i + 1]] += 2;
			if($this->gameArea[$this->positionY + $figure[$i]][$this->positionX + $figure[$i + 1]] == 3)
				$ok = "gameover";
		}
		
		return $ok;
	}
	public function DropFigura($figure)
	{
		$ok = "ok";
		$this->positionY++;
		for($i = 0; $i < count($figure); $i += 2)
		{
			$this->gameArea[$this->positionY + $figure[$i]][$this->positionX + $figure[$i + 1]] += 2;
			if($this->gameArea[$this->positionY + $figure[$i]][$this->positionX + $figure[$i + 1]] == 3)
				$ok = "stop";
		}
		if($ok == "stop")
		{
			for($i = 0; $i < count($figure); $i += 2)
			{
				$this->gameArea[$this->positionY + $figure[$i]][$this->positionX + $figure[$i + 1]] -= 2;
			}
			$this->positionY--;
			for($i = 0; $i < count($figure); $i += 2)
			{
				$this->gameArea[$this->positionY + $figure[$i]][$this->positionX + $figure[$i + 1]] = 1;
			}
		}
		else
		{
			for($i = 0; $i < count($figure); $i += 2)
			{
				$this->gameArea[$this->positionY - 1 + $figure[$i]][$this->positionX + $figure[$i + 1]] -= 2;
			}
		}
		return $ok;
	}
	public function MoveLeftFigure($figure)
	{
		$ok = true;
		if($this->positionX < 0)
			return false;
		$this->positionX--;
		for($i = 0; $i < count($figure); $i += 2)
		{
			$this->gameArea[$this->positionY + $figure[$i]][$this->positionX + $figure[$i + 1]] += 2;
			if($this->gameArea[$this->positionY + $figure[$i]][$this->positionX + $figure[$i + 1]] == 3)
				$ok = false;
		}
		if($ok == false)
		{
			for($i = 0; $i < count($figure); $i += 2)
			{
				$this->gameArea[$this->positionY + $figure[$i]][$this->positionX + $figure[$i + 1]] -= 2;
			}
			$this->positionX++;
		}
		else
		{
			for($i = 0; $i < count($figure); $i += 2)
			{
				$this->gameArea[$this->positionY + $figure[$i]][$this->positionX + 1 + $figure[$i + 1]] -= 2;
			}
		}
		
		return $ok;
	}
	public function MoveRightFigure($figure)
	{
		$ok = true;
		if($this->positionX > $this->sizeWidth - 2)
			return false;
		$this->positionX++;
		for($i = 0; $i < count($figure); $i += 2)
		{
			$this->gameArea[$this->positionY + $figure[$i]][$this->positionX + $figure[$i + 1]] += 2;
			if($this->gameArea[$this->positionY + $figure[$i]][$this->positionX + $figure[$i + 1]] == 3)
				$ok = false;
		}
		if($ok == false)
		{
			for($i = 0; $i < count($figure); $i += 2)
			{
				$this->gameArea[$this->positionY + $figure[$i]][$this->positionX + $figure[$i + 1]] -= 2;
			}
			$this->positionX--;
		}
		else
		{
			for($i = 0; $i < count($figure); $i += 2)
			{
				$this->gameArea[$this->positionY + $figure[$i]][$this->positionX - 1 + $figure[$i + 1]] -= 2;
			}
		}
		
		return $ok;
	}
	function RotateFigure($currentFigure, $newFigure)
	{
		$ok = "rotateok";
		for($i = 0; $i < count($newFigure); $i += 2)
		{
			if($newFigure[$i] + $this->positionY < 0 || $newFigure[$i] + $this->positionY > $this->sizeHeight - 1 || $newFigure[$i + 1] + $this->positionX < 0 || $newFigure[$i + 1] + $this->positionX > $this->sizeWidth - 1)
			{
				$ok = "rotateno";
				continue;
			}
			$this->gameArea[$this->positionY + $newFigure[$i]][$this->positionX + $newFigure[$i + 1]] += 2;
			if($this->gameArea[$this->positionY + $newFigure[$i]][$this->positionX + $newFigure[$i + 1]] == 3)
				$ok = "rotateno";
		}
		if($ok == "rotateok")
		{
			for($i = 0; $i < count($currentFigure); $i += 2)
			{
				$this->gameArea[$this->positionY + $currentFigure[$i]][$this->positionX + $currentFigure[$i + 1]] -= 2;
			}
		}
		else
		{
			for($i = 0; $i < count($newFigure); $i += 2)
			{
				if($newFigure[$i] + $this->positionY < 0 || $newFigure[$i] + $this->positionY > $this->sizeHeight - 1 || $newFigure[$i + 1] + $this->positionX < 0 || $newFigure[$i + 1] + $this->positionX > $this->sizeWidth - 1)
					continue;
				$this->gameArea[$this->positionY + $newFigure[$i]][$this->positionX + $newFigure[$i + 1]] -= 2;
			}
		}
		return $ok;
	}
}
?>