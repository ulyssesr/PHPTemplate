<?php

// Table class

class Table
{
	// declare variables
	var $tablevars;

	function Table($name,$attribute=NULL)
	{
		// Sets table name and attribute string
		// Ex. class="tableclass" border="1″
		if ($attribute=="") {
			$this->tablevars['name'] = 'name="'.$name.'" id="'.$name.'"';
		} else {
			$this->tablevars['name'] = 'name="'.$name.'" id="'.$name.'" '.$attribute;
		}
	}

	function setHead($name=NULL,$col)
	{
		// Sets table header name and corresponding column title
		$name = (is_null($name))? : 'id="'.$name.'"';

		// stores table header row in $this->tablevars['head']
		$this->tablevars['head'] = '<tr '.$name.'>';

		// run through $col if it is a valid array variable
		if(is_array($col)){
			foreach($col as $value){
				$this->tablevars['head'].= '<th>'.$value.'</th>';
			}
		}
		$this->tablevars['head'].= '</tr>'."\n";
	}

	function setData($data)
	{
		$this->tablevars['data']="";

		if(is_array($data)){
			foreach($data as $row){
				$this->tablevars['data'].= '<tr>';

				if(is_array($row)){
					foreach($row as $key=>$value){
						$this->tablevars['data'].='<td>'.$value.'</td>';
					}
				}
				$this->tablevars['data'].='</tr>'."\n";
			}
		}
	}

	function setAlternate($data)
	{

		$rowCount = 0;
		$rowOdd = 'class="odd"';
		$rowEven= 'class="even"';

		$this->tablevars['data']="";

		if(is_array($data)){
			foreach($data as $key=>$row){
				$rowCount++;
				$checkOddEven = $rowCount %2;
				$displayRowID = ($checkOddEven==0)? $rowOdd:$rowEven;
				$this->tablevars['data'].= '<tr '.$displayRowID.'>';

				if(is_array($row)){
					foreach($row as $key=>$value){
						$this->tablevars['data'].= '<td>'.$value.'</td>';
					}
				}
				$this->tablevars['data'].= '</tr>'."\n";
			}
		}
	}
	
	function displayTable()
	{

		$this->tablevars['table'] = '<table '.$this->tablevars['name'].'>'."\n";
		$this->tablevars['table'].= $this->tablevars['head'];
		$this->tablevars['table'].= $this->tablevars['data'];
		$this->tablevars['table'].= '</table>'."\n";
		echo $this->tablevars['table'];
	}

}

/*

Usage:

include 'class.table.php';

$data = array();
$data[]= array('2′,'Johnny');
$data[]= array('4′,'Kathy');
$data[]= array('5′,'McCain');

$table = new Table('resultTable', 'class="newTable"');
$table->setHead('head',array('ID','Name'));
$table->setData($data);
$table->setAlternate($data);
echo $table->displayTable();

*/

/* end of table.class.php */