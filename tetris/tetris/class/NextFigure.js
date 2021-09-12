class NextFigure
{
	constructor()
	{
		this.type = "";
		this.body = null;
		this.CreateField();
		this.CreateTable();
	}
	CreateField()
	{
		this.fieldFigure = new Array();
		for(let i = 0; i < 4; i++)
		{
			this.fieldFigure[i] = new Array(0, 0, 0, 0);
		}
	}
	Create(type, body)
	{
		this.type = type;
		this.body = body;
		this.CreateField();
		this.FillField();
		this.Repaint();
	}
	FillField()
	{
		for(let i = 0; i < this.body.length; i += 2)
		{
			this.fieldFigure[this.body[i]][this.body[i+1]] = 1;
		}
	}
	CreateTable()
	{
		this.tableField = document.createElement("table");
		this.tableField.className = "game_field";

		let tr = document.createElement("tr");
		let td = document.createElement("td");
		for(let j = 0; j < this.fieldFigure[0].length; j++)
		{
			td.className = "empty_cell";
			tr.appendChild(td.cloneNode(true));
		}

		for(let i = 0; i < this.fieldFigure.length; i++)
		{
			this.tableField.appendChild(tr.cloneNode(true));
		}
	}
	get Type()
	{
		return this.type;
	}
	get Field()
	{
		return this.tableField;
	}
	Repaint()
	{
		for(let i = 0; i < this.fieldFigure.length; i++)
		{
			for(let j = 0; j < this.fieldFigure[i].length; j++)
			{
				if(this.fieldFigure[i][j] == 0)
					this.tableField.rows[i].cells[j].className = "empty_cell";
				else
					this.tableField.rows[i].cells[j].className = "figure_cell";				
			}
		}
	}
}