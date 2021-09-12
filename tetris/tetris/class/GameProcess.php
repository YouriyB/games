<?php
require("GameArea.php");
require("FigureI.php");
require("FigureJ.php");
require("FigureL.php");
require("FigureO.php");
require("FigureS.php");
require("FigureZ.php");
require("FigureT.php");

class GameProcess
{
	private $gameArea = null;//игровое поле
	private $currentFigure = null;//текущая фигура
	private $nextFigure = null;
	private $gamePlay = "game";//закончена ли игра
	private $fits = "";
	private $score = 0;
	private $maxCounterDropFigure = 10;//количество вызовов функции GameStep для падения фигуры
	private $currentCounterDropFigure = 0;//счётчик вызовов функции GameStep
	private $countNewFigures = 0;
	
	function __construct($widthArea, $heightArea)
	{
		$this->gameArea = new GameArea($widthArea, $heightArea);
		$createFigure = $this->GenerateNewFigure();
		$this->nextFigure = new $createFigure();
		$this->AddNewFigure();
		//$this->gamePlay = "game";
	}
	/*public function GetGameArea()///??????не нужна
	{
		return $this->gameArea->GetGameArea();
	}*/
	private function GenerateNewFigure()
	{
		$figures = array("I", "J", "L", "O", "S", "T", "Z");
		$index = rand(0, 6);
		return "Figure" . $figures[$index];
	}
	private function AddNewFigure()
	{
		$createFigure = $this->GenerateNewFigure();
		$this->currentFigure = $this->nextFigure;//new $createFigure();//
		$this->nextFigure = new $createFigure();
		$this->gamePlay = $this->gameArea->AddFigura($this->currentFigure->GetFigure());
		if($this->gamePlay == "gameover")
		{
			if($this->WorthyOfRecord() == true)
				$this->gamePlay = "givemename";
		}
		else
		{
			$this->countNewFigures++;
			if($this->maxCounterDropFigure > 2 && ($this->countNewFigures % 30 == 0))
			{
				$this->maxCounterDropFigure--;
			}
		}
		return $this->gamePlay;
	}
	public function DropFigure()
	{
		$ok = $this->gameArea->DropFigura($this->currentFigure->GetFigure());
		if($ok == "stop")
		{
			$ok = $this->gameArea->TestFillLine();
			if($ok != "fits")
				$ok = $this->AddNewFigure();
		}
		return $ok;
	}
	public function DeleteFillLine()
	{
		$this->score += pow($this->gameArea->GetFillLines(), 2);
		$this->gameArea->DeleteFillLine();
		return $this->AddNewFigure();
	}
	public function MoveLeftFigure()
	{
		return $this->gameArea->MoveLeftFigure($this->currentFigure->GetFigure());
	}
	public function MoveRightFigure()
	{
		return $this->gameArea->MoveRightFigure($this->currentFigure->GetFigure());
	}
	public function RotateClockWise()
	{
		$newFigure = clone($this->currentFigure);
		$newFigure->RotateFigureClockWise();
		$res = $this->gameArea->RotateFigure($this->currentFigure->GetFigure(), $newFigure->GetFigure());
		if($res == "rotateok")
			$this->currentFigure = $newFigure;
		return $res;
	}
	public function RotateCounterClockWise()
	{
		$newFigure = clone($this->currentFigure);
		$newFigure->RotateFigureCounterClockWise();
		$res = $this->gameArea->RotateFigure($this->currentFigure->GetFigure(), $newFigure->GetFigure());
		if($res == "rotateok")
			$this->currentFigure = $newFigure;
		return $res;
	}
	public function GameStep($data)
	{
		if($this->gamePlay == "gameover" || $this->gamePlay == "givemename")
			return $this->gamePlay;
		$keyData = json_decode($data);
		if(count($keyData) != 3)
			return "illegal data";
		
		if($this->fits == "fits")
		{
			if($this->DeleteFillLine() == "gameover")
			{
				return $this->gamePlay;
			}
		}
		$this->fits = "";
		
		$keyDo = array("do", "do", "do");
		
		if($keyData[0] == "down")
			$this->fits = $this->DropFigure();
		else
			$keyDo[0] = "none";
		
		if($keyData[1] == "left")
			$this->MoveLeftFigure();
		else if($keyData[1] == "right")
			$this->MoveRightFigure();
		else
			$keyDo[1] = "none";
		
		if($keyData[2] == "counterclock")
			$this->RotateCounterClockWise();
		else if($keyData[2] == "clock")
			$this->RotateClockWise();
		else
			$keyDo[2] = "none";
		
		//произвольное падение фигуры
		$this->currentCounterDropFigure++;
		if($this->currentCounterDropFigure >= $this->maxCounterDropFigure)
		{
			$this->currentCounterDropFigure = 0;
			if($this->fits != "fits" && $this->fits != "stop")
				$this->fits = $this->DropFigure();
		}
		$gameField = $this->gameArea->GetGameArea();
		$result = array($gameField, $keyDo, $this->score, $this->nextFigure->TypeFigure(), $this->nextFigure->GetFigure());
		
		return json_encode($result);
	}
	public function GetScore()
	{
		return $this->score;
	}
	public function SendName($name)
	{
		if(file_exists("../score.sc") == true)
		{
			$file = fopen("../score.sc", "r+");
			$score = json_decode(fgets($file), true);
			
			$score[9]["name"] = $name;
			$score[9]["score"] = $this->score;
			function CMP($a, $b)
			{
				if($a["score"] == $b["score"])
					return 1;
				return ($a["score"] < $b["score"]) ? 1 : -1;
			}
			usort($score, "CMP");
			
			ftruncate($file, 0);
			fseek($file, 0);
			fwrite($file, json_encode($score));
			fclose($file);
		}
	}
	private function WorthyOfRecord()
	{
		if(file_exists("../score.sc") == true)
		{
			$file = fopen("../score.sc", "r");
			$top = json_decode(fgets($file), true);
			fclose($file);
			if(is_array($top) == false)
				return false;
			if($top[9]["score"] < $this->score)
				return true;
		}
		return false;
	}
}
?>