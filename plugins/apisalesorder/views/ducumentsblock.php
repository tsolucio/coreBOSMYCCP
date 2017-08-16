<?php
/* * *******************************************************************************
 * The content of this file is subject to the MYC Vtiger Customer Portal license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is Proseguo s.l. - MakeYourCloud
 * Portions created by Proseguo s.l. - MakeYourCloud are Copyright(C) Proseguo s.l. - MakeYourCloud
 * All Rights Reserved.
 * ****************************************************************************** */

  if(isset($data['plugin_data']['data']['apisalesorder']['relateddocuments']) && count($data['plugin_data']['data']['apisalesorder']['relateddocuments'])>0 && $data['plugin_data']['data']['apisalesorder']['relateddocuments']!=""){ ?>
            <div class="col-lg-6">
            
               
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php  echo Language::translate("Attachments"); ?>
                        </div>
                        
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                        <?php foreach($data['plugin_data']['data']['apisalesorder']['relateddocuments'] as $reldoc): ?>
                        <tr>
                        	<td><h5><?php echo $reldoc['notes_title']; ?> - <small><?php echo $reldoc['filename']; ?></small></h5></td>
							<td><a class="btn btn-success btn-sm" href="index.php?module=SalesOrderWS&action=index&id=<?php echo $_GET['id']; ?>&attachmentid=<?php echo $reldoc['id']; ?>"> <?php  echo Language::translate("Download"); ?></a></td>
                        </tr>
						<?php endforeach; ?> 
						</table>
						</div>
                            
                            
                    </div>
                    <!-- /.panel -->
               
               </div>
                <!-- /.col-lg-6 -->
                
                <?php  } ?>
