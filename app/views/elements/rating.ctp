<?php
	
	if (!isset($nameStar)) {
		$nameStar = 'data[Space][rating]';
	}
		
	$disabled = '';
	if ( isset($isDisabled) && ($isDisabled == true)) {
		$disabled = 'disabled="disabled"';
	}

	$checked = ((isset($this->data['Space']['rating'])) && $this->data['Space']['rating'] == 0.5) ? 'checked="checked"':  '';
	echo '<input name="'.$nameStar.'" type="radio" class="star {split:2}" value="0.5" '.$checked.' '.$disabled.' />';
	$checked = ((isset($this->data['Space']['rating'])) && $this->data['Space']['rating'] == 1) ? 'checked="checked"':  '';
    echo '<input name="'.$nameStar.'" type="radio" class="star {split:2}" value="1" '.$checked. $disabled.'/>';
	$checked = ((isset($this->data['Space']['rating'])) && $this->data['Space']['rating'] == 1.5) ? 'checked="checked"':  '';
   	echo '<input name="'.$nameStar.'" type="radio" class="star {split:2}" value="1.5" '.$checked. $disabled.' />';
	$checked = ((isset($this->data['Space']['rating'])) && $this->data['Space']['rating'] == 2) ? 'checked="checked"':  '';
   	echo '<input name="'.$nameStar.'" type="radio" class="star {split:2}" value="2" '.$checked. $disabled.' />';
	$checked = ((isset($this->data['Space']['rating'])) && $this->data['Space']['rating'] == 2.5) ? 'checked="checked"':  '';
   	echo '<input name="'.$nameStar.'" type="radio" class="star {split:2}" value="2.5" '.$checked. $disabled.' />';
	$checked = ((isset($this->data['Space']['rating'])) && $this->data['Space']['rating'] == 3) ? 'checked="checked"':  '';
   	echo '<input name="'.$nameStar.'" type="radio" class="star {split:2}" value="3" '.$checked. $disabled.' />';
	$checked = ((isset($this->data['Space']['rating'])) && $this->data['Space']['rating'] == 3.5) ? 'checked="checked"':  '';
   	echo '<input name="'.$nameStar.'" type="radio" class="star {split:2}" value="3.5" '.$checked. $disabled.' />';
	$checked = ((isset($this->data['Space']['rating'])) && $this->data['Space']['rating'] == 4) ? 'checked="checked"':  '';
   	echo '<input name="'.$nameStar.'" type="radio" class="star {split:2}" value="4" '.$checked. $disabled.' />';
	$checked = ((isset($this->data['Space']['rating'])) && $this->data['Space']['rating'] == 4.5) ? 'checked="checked"':  '';
   	echo '<input name="'.$nameStar.'" type="radio" class="star {split:2}" value="4.5" '.$checked. $disabled.' />';
	$checked = ((isset($this->data['Space']['rating'])) && $this->data['Space']['rating'] == 5) ? 'checked="checked"':  '';
   	echo '<input name="'.$nameStar.'" type="radio" class="star {split:2}" value="5" '.$checked. $disabled.' />';
	
 
?>
