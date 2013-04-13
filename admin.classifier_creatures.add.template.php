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
	<script type="text/javascript">
		$(document).ready(function(){
			$("#more_skill").generaNuevosCampos("skill_", 2);
		});
    </script>
    
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
				
				echo '<textarea name="desc_skill_'.$i.'" id="desc_skill_'.$i.'" rows="5" cols="80" class="control modifier-lowercase">'.$_POST["desc_skill_".$i].'</textarea>';
					
			}
			
			$count = $i;		
			
		}
		
		echo '<br /><input type="hidden" name="skill_total" id="skill_total" value="' . $count . '" />';
		
		echo '<input type="button" name="more_skill" id="more_skill" value="' . __('Add Skill', 'wpinimat_languages') . '" class="button">';
	
	} else {
		
		echo $skill_1 . ' ' . $type_skill_1 . $desc_skill_1 . '<br />' 
		. '<input type="hidden" name="skill_total" id="skill_total" value="1" />' 
		. '<input type="button" name="more_skill" id="more_skill" value="' . __('Add Skill', 'wpinimat_languages') . '" class="button">' ;
	} ?>
    
    <div class="clear"></div>
    
</div>

<div class="row"><?php echo $label_habitat . $habitat?></div>

<div class="row even"><?php echo $label_description . $description?></div>

<div class="row"><?php echo $label_imgSketch . $imgSketch . $note_imgSketch?></div>

<div class="row even"><?php echo $label_imgModeled . $imgModeled . $note_imgModeled?></div>

<div class="row"><?php echo $label_imgTextured . $imgTextured . $note_imgTextured?></div>

<div class="row even"><?php echo $label_file . $file . $note_file?></div>

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