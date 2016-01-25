<?php

/* * *******************************************************************************
 * The content of this file is subject to the MYC Vtiger Customer Portal license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is Proseguo s.l. - MakeYourCloud
 * Portions created by Proseguo s.l. - MakeYourCloud are Copyright(C) Proseguo s.l. - MakeYourCloud
 * All Rights Reserved.
 * ****************************************************************************** */
 
 ?>
	   		
	   		<?php
	   		
				$result = array(); 
				$themesdir="../themes";
				$cdir = scandir($themesdir); 
				foreach ($cdir as $key => $value) 
					if (!in_array($value,array(".","..")) && is_dir($themesdir."/".$value)){
				    	$selected=false;
							if($value == $config['portal_theme']) $selected=true; 
							?>
							
							
								   		<div class="col-md-4 text-center">
									   		<div class="panel panel-conf <?php if($selected) echo "panel-conf"; ?>">
											  <div class="panel-heading">
											    <h3 class="panel-title"><?php echo ucfirst($value); ?></h3>
											  </div>
											  <div class="panel-body text-center">
											  <?php if(file_exists("../themes/".$value."/preview.png")): ?>
											    <img style="max-width:100%;" src="../themes/<?php echo $value; ?>/preview.png">
											  <?php else: ?>
											  	<img style="max-width:100%;" src="views/assets/logo_myc_bsr.png">
											  	<h4><small> No preview available</small></h4>
											  <?php endif; ?>  
											  <?php if(!$selected): ?>
											  <br><br>
											  <a class="btn btn-warning" href="../index.php?theme=<?php echo $value; ?>" target="_blank">Preview&nbsp;<i class="fa fa-eye"></i></a>
											  <a class="btn btn-primary" href="?action=themes&deftheme=<?php echo $value; ?>">Set as Default&nbsp;<i class="fa fa-star-o"></i></a> 
											  <?php else: ?>
											  <br><br>
											  <a class="btn btn-default disabled">Current Default Theme&nbsp;<i class="fa fa-star"></i></a>
											  <?php endif; ?>
											  </div>
											</div>
								   		</div>
	   		
							
							<?php
					}
						    
			?>

										<div class="col-md-4 text-center">
									   		<div class="panel panel-success panel-conf panel-conf-orange">
											  <div class="panel-heading">
											    <h3 class="panel-title">Theme Upload</h3>
											  </div>											  
											  <div class="panel-body text-center" style="min-height:300px;">
											  <br><i onclick="$('.upload').trigger('click');" class="fa fa-plus" style="font-size:10em;color:grey;cursor:pointer;"></i><br>
											    <form enctype="multipart/form-data" method="post">
												    <div class="text-center">
													    <label>Upload New Theme Zip or Theme Upgrade</label>
													    <p class="help-block">A theme Upgrade will replace all your current modification for the selected theme</p>
													    <input name="theme_zip" type="file" class="form-control" accept="application/zip" >
													    <br>
													    <input type="submit" class="btn btn-success" value="Upload and Refresh">
													</div>
												</form>
											  </div>
											</div>
								   		</div>
								   		
								
		
		   		
	   	</div>
   	</div>

 
