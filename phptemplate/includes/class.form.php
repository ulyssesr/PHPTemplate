<?php

class Forms {

   // Example: openForm('submit.php', 'post', 'name1', 'id1', 'class1');
   
	function openForm($action, $method = 'post', $name, $id='', $class='', $additional = '', $fileUpload=false, $charset='', $target='' ) {
		$res = '';
		$res .= "<form action=\"$action\" method=\"$method\"";
		$res .= ($name == '' ? '' : " name=\"$name\"");
		$res .= ($id == '' ? '' : " id=\"$id\"");
		$res .= ($class == '' ? '' : " class=\"$class\"");			
		$res .= ($fileUpload == true ? " enctype=\"multipart/form-data\"" : '');
		$res .= ($charset == '' ? '' : " accept-charset=\"$charset\"");
		$res .= ($target == '' ? '' : " target=\"$target\"");
		$res .= ($additional ? " $additional" : '') . '>'."\n";
		echo $res;
	}

	function closeForm() {
		echo "</form>\n";
	}

	function openFieldset($legend) {
		echo "<fieldset>\n<legend>$legend</legend>\n";
	}

	function closeFieldset() {
		echo "</fieldset>\n";
	}

	function label($label='', $before='', $after='') {
		$res = '';
		$res .= ($before!=''?$before:'')."<label for name=\"$label\">".$label."</label>".($after!=''?$after:'')."\n";
		echo $res;
	}

	function hidden($name, $value='') {
		echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">\n";
	}

	function textField($name='', $value, $additional='', $before='', $after='', $hidden=false, $size =-1, $length =-1 ) {
		$res = '';
		static $count = 0;
		if ($name == '') {
			$name = 'textField' . ++$count;
		}
		$res .= ($before!=''?$before:'')."<input name=\"$name\" type=\"".($hidden?'password':'text')."\" value=\"$value\"";
		$res .= ($additional ? " $additional" : '');
		$res .= ($size != -1 ? " size=\"$size\"" : '');
		$res .= ($length != -1 ? " maxlength=\"$length\"" : '');
		$res .= "/>".($after!=''?$after:'')."\n";
		echo $res;
	}

	function fileField($name = '', $file = '', $additional='', $before='', $after='', $fileSize = 1000000, $size = -1, $accept="text/*" ) {
		static $count = 0;
		if ($name == '') {
			$name = 'fileField' . ++$count;
		}
		$res = '';
		$res .= ($before!=''?$before:'')."<input type=\"file\" name=\"$name\"";
		$res .= ($size != -1 ? " size=\"$size\"" : '');
		$res .= ($file != '' ? " value=\"$file\"" : '');
		$res .= " maxlength=\"$fileSize\" accept=\"$accept\"".($additional ? " $additional" : '').">".($after!=''?$after:'')."\n";
		echo $res;
	}

 	function textArea($name='', $value, $cols=-1, $rows=-1, $additional='', $before='', $after='', $wrap='soft' , $readOnly=false ) {
		$res = '';
		static $count = 0;
		if ($name == '') {
			$name = 'textArea' . ++$count;
		}
		$res .= ($before!=''?$before:'')."<textarea name=\"$name\" wrap=\"$wrap\"";
		$res .= ($rows != -1 ? "rows=\"$rows\"" : '');
		$res .= ($cols != -1 ? "cols=\"$cols\"" : '');
		$res .= ($readOnly ? " readonly=\"readonly\"" : '');
		$res .= ($additional ? " $additional" : '') . ">";
		$res .= $value;
		$res .= "</textarea>".($after!=''?$after:'')."\n";
		echo $res;
	}

	function checkBox($value, $group='', $selected=false, $additional='') {
		static $count = 0;
		if ($group == '') {
			$group = 'checkBox' . ++$count;
		}
		$res = '';
		$res .= "<input type=\"checkbox\" name=\"$group\" value=\"$value\"" . ($selected==false ? '' : " checked=\"checked\"") .  ($additional ? " $additional" : '') . ">"; 
		echo $res;
		}

	function checkBoxes($array, $group='', $selectArray, $additional='') {
		if (is_array($array) && is_array($selectArray)) {
			static $count = 0;
			if ($group == '') {
				$group = 'boxGroup' . ++$count;
        	}
			$res = array();
			$i = 0;
			foreach($array as $value) {
				$i++;
				$res[$i]= Forms::checkBox($value, $group, $selectArray[$i - 1], $additional);
				echo $value ."\n";
			}
		}    
	}

	function radioButton($value, $group='', $selected=false, $additional='') {
		static $count = 0;
		if ($group == '') {
			$group = 'radioButton' . ++$count;
		}
		$res = '';
		$res .= "<input type=\"radio\" name=\"$group\" value=\"$value\"" . ($selected==false ? '' : " checked=\"checked\"") . ($additional ? " $additional" : '') . ">"; 
		echo $res;
	}

	function radioButtons($array, $group='', $selectId=1, $additional = '') {
		static $count = 0;
		if ($group == '') {
			$group = 'radioGroup' . ++$count;
		}
		if (is_array($array)) {
			$res = array();
			$i = 0;
			foreach($array as $value) {
				$res[$i++]= Forms::radioButton($value, $group, (int)($selectId == $i), $additional);
				echo $value."\n";
			}
		}
	}

	function selectBox($array, $selected=0, $name='', $size=1, $multiple=false, $additional='') {
		$res = '';
		static $count = 0;
		if (is_array($array)) {
			if ($name == '') {
				$name = 'selectBox' . ++$count;
			}
			$res .= "<select name=\"$name\" size=\"$size\"" . ($multiple==false ? '' : " multiple=\"multiple\"") . ($additional ? " $additional" : '') . ">\n";
			$i = 0;
			foreach($array as $value) {
				$res .= "<option" . ($selected == ++$i ? " selected=\"selected\"" : '') . ">$value</option>\n";
			}
			$res .="</select>\n";
		}
		echo $res;
	}

	function submitButton($name, $title='', $additional='', $before='', $after='', $new=false) {
		$res = '';
		if ($new == true) {
			$res .= ($before!=''?$before:'')."<button name=\"$name\" type=\"submit\" $additional>$title</button>".($after!=''?$after:'')."\n";
		} else {
			$res .= ($before!=''?$before:'')."<input name=\"$name\" type=\"submit\"".($title==''?'':" value=\"$title\"").($additional ? " $additional" :'').">".($after!=''?$after:'')."\n";
		}
		echo $res;
	}

	function resetButton($name, $title='', $additional='', $before='', $after='', $new="false") {
		$res = '';
		if ($new == true) {
			$res .= ($before!=''?$before:'')."<button name=\"$name\" type=\"reset\" $additional>$title</button>".($after!=''?$after:'')."\n";
		} else {
			$res .= ($before!=''?$before:'')."<input name=\"$name\" type=\"reset\"" .($title==''?'':" value=\"$title\"").($additional?" $additional":'').">".($after!=''?$after:'')."\n";
		}
		echo $res;
	}

	function button($name, $title, $additional='', $before='', $after='', $new="false") {
		static $count = 0;
		if ($name == '') {
			$name = 'button' . ++$count;
		}
		$res = '';
		if ($new == true) {
			$res .= ($before!=''?$before:'')."<button name=\"$name\" type=\"button\" $additional>$title</button>".($after!=''?$after:'')."\n";
		} else {
			$res .= ($before!=''?$before:'')."<input name=\"$name\" type=\"button\" value=\"$title\"" .($additional ? " $additional" : '').">".($after!=''?$after:'')."\n";
		}
		echo $res;
	}

}

/* end of class.forms.php */