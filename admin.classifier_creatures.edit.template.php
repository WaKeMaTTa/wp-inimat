<?php

echo (isset($zf_error) ? $zf_error : (isset($error) ? $error : ''));

if (isset($author)) { ?>

	<div class="row even"><?php echo $label_author . $author . $author_other?></div>

<?php } ?>

<div class="row"><?php echo $label_name . $name . $note_name?></div>

<div class="row even">

    <div class="cell number"><?php echo $label_height . $height?></div>
    <div class="cell number"><?php echo $label_width . $width?></div>
    <div class="cell number"><?php echo $label_weight . $weight?></div>
    <br /><br /><br />
    <?php echo $note_height?>
    <div class="clear"></div>

</div>

<div class="row">

    <div class="cell">
    
		<?php echo $label_type?>
        
        <div class="cell"><?php echo $type_astral?></div>
        <div class="cell"><?php echo $label_type_astral?></div>
        <div class="clear"></div>
        
        <div class="cell"><?php echo $type_guardian?></div>
        <div class="cell"><?php echo $label_type_guardian?></div>
        <div class="clear"></div>
        
        <div class="cell"><?php echo $type_material?></div>
        <div class="cell"><?php echo $label_type_material?></div>
        <div class="clear"></div>
        
        <div class="cell"><?php echo $type_samus?></div>
        <div class="cell"><?php echo $label_type_samus?></div>
        <div class="clear"></div>
        
	</div>
    
    <div class="cell" style="margin-left: 20px">
    
		<?php echo $label_gender?>
        
        <div class="cell"><?php echo $gender_aquatic?></div>
        <div class="cell"><?php echo $label_gender_aquatic?></div>
        <div class="clear"></div>
        
        <div class="cell"><?php echo $gender_terrestrial?></div>
        <div class="cell"><?php echo $label_gender_terrestrial?></div>
        <div class="clear"></div>
        
        <div class="cell"><?php echo $gender_vegetable?></div>
        <div class="cell"><?php echo $label_gender_vegetable?></div>
        <div class="clear"></div>
        
        <div class="cell"><?php echo $gender_flying?></div>
        <div class="cell"><?php echo $label_gender_flying?></div>
        <div class="clear"></div>
        
	</div>
    
    <div class="clear"></div>
    
</div>

<div class="row">
	<?php
	echo $label_skills;
	
	if(isset($_POST["skill_total"])) {
		
		for($i=1; $i<=$_POST["skill_total"]; $i++) {
			
			if(isset($_POST["skill_".$i])) {
				
				echo '<input type="text" name="skill_'.$i.'" id="skill_'.$i.'" value="'.$_POST["skill_".$i].'" class="control text modifier-lowercase" style="display: inline; margin-bottom: 5px;"> ';
				
				echo '<select name="type_skill_'.$i.'" id="type_skill_'.$i.'" class="control" style="height: 28px; display: inline; margin-bottom: 5px;">';
				
				if($_POST["type_skill_".$i] == 'attack')  {
					
					echo '<option value="">- select -</option>';
					echo '<option value="attack" selected="selected">Attack</option>';
					echo '<option value="defense">Defense</option>';
					
				} elseif($_POST["type_skill_".$i] == 'defense') {
					
					echo '<option value="">- select -</option>';
					echo '<option value="attack">Attack</option>';
					echo '<option value="defense" selected="selected">Defense</option>';
					
				} else {
					
					echo '<option value="">- select -</option>';
					echo '<option value="attack">Attack</option>';
					echo '<option value="defense">Defense</option>';
					
				}
				
				echo '</select> <br/>';
				
				echo '<textarea name="desc_skill'.$i.'" id="desc_skill'.$i.'" rows="5" cols="80" class="control modifier-lowercase">'.$_POST["desc_skill".$i].'</textarea>';
					
			}
			
			$count = $i;
			
		}
		
		echo '<script type="text/javascript">$(document).ready(function(){ $("#add_skill").generaNuevosCampos("skill_", '.$count.'); }); </script>';	
		
		echo '<input type="hidden" name="skill_total" id="skill_total" value="'.$count.'" />';
		
		echo '<input type="button" name="add_skill" id="add_skill" value="'.__('Add Skill', 'wpinimat_languages').'" class="button">';
	
	} else {
		
		echo '<script type="text/javascript">$(document).ready(function(){ $("#add_skill").generaNuevosCampos("skill_", '.(COUNT_SKILLS + 1).'); }); </script>';
		
		for($i=1; $i<=COUNT_SKILLS; $i++) {
			
			$union_skill = 'skill_'.$i;
			$union_type_skill = 'type_skill_'.$i;
			$union_desc_skill = 'desc_skill_'.$i;
			
			$skill = $$union_skill;
			$type_skill = $$union_type_skill;
			$desc_skill = $$union_desc_skill;
			
			echo $skill . ' ' . $type_skill . $desc_skill . '<br />';
			
		}
		
		echo '<input type="hidden" name="skill_total" id="skill_total" value="'.COUNT_SKILLS.'" />';
		
		echo '<input type="button" name="add_skill" id="add_skill" value="'.__('Add Skill', 'wpinimat_languages').'" class="button">';
		
	} ?>
    
    <div class="clear"></div>
    
</div>

<div class="row"><?php echo $label_habitat . $habitat?></div>

<div class="row even"><?php echo $label_description . $description?></div>

<div class="row"><?php

	echo $label_imgSketch;
	
	echo '<table><tr>';
	
	if(defined('NAME_SKETCH') == TRUE) {
		
		echo '<td><img src="' . WPINIMAT_PLUGIN_URL . 'upload/th/' . NAME_SKETCH . '" width="100" height="100" /></td>';
		
	} else {
		
		echo '<td><img src="' . WPINIMAT_PLUGIN_URL . 'img/not-img.png" width="100" height="100" /></td>';
		
	}
	
	echo '<td style="vertical-align: inherit;">' . $imgSketch . $note_imgSketch . '</td></tr></table>';
	
?></div>

<div class="row even"><?php

	echo $label_imgModeled;
	
	echo '<table><tr>';
	
	if(defined('NAME_MODELED') == TRUE) {
		
		echo '<td><img src="' . WPINIMAT_PLUGIN_URL . 'upload/th/' . NAME_MODELED . '" width="100" height="100" /></td>';
		
	} else {
		
		echo '<td><img src="' . WPINIMAT_PLUGIN_URL . 'img/not-img.png" width="100" height="100" /></td>';
		
	}
	
	echo '<td style="vertical-align: inherit;">' . $imgModeled . $note_imgModeled . '</td></tr></table>';
	
?></div>

<div class="row"><?php

	echo $label_imgTextured;
	
	echo '<table><tr>';
	
	if(defined('NAME_TEXTURED') == TRUE) {
		
		echo '<td><img src="' . WPINIMAT_PLUGIN_URL . 'upload/th/' . NAME_TEXTURED . '" width="100" height="100" /></td>';
		
	} else {
		
		echo '<td><img src="' . WPINIMAT_PLUGIN_URL . 'img/not-img.png" width="100" height="100" /></td>';
		
	}
	
	echo '<td style="vertical-align: inherit;">' . $imgTextured . $note_imgTextured . '</td></tr></table>';
	
?></div>

<div class="row even"><?php

	echo $label_file;
	
	echo '<table><tr>';
	
	if(defined('NAME_FILE') == TRUE) {
		
		echo '<td><a href="' . WPINIMAT_PLUGIN_URL . '/upload/' . NAME_FILE . '"><img src="' . WPINIMAT_PLUGIN_URL . 'img/zip.png" width="48" height="48" /></a></td>';
		
	} else {
		
		echo '<td><img src="' . WPINIMAT_PLUGIN_URL . 'img/not-img.png" width="100" height="100" /></td>';
		
	}
	
	echo '<td style="vertical-align: inherit;">' . $file . $note_file . '</td></tr></table>';
	
?></div>

<div class="row">
	
	<div class="cell"><?php echo $license_1?></div>
	<div class="cell"><?php echo $label_license_1?></div>
    <div class="clear"></div>
    
</div>

<?php if (isset($finished_1)) { ?>

<div class="row even">

	<div class="cell"><?php echo $finished_1?></div>
	<div class="cell"><?php echo $label_finished_1?></div>
    <div class="clear"></div>
    
</div>

<?php } ?>

<div class="row last"><?php echo $btnsubmit?></div>