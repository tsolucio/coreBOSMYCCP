<?php
/* * *******************************************************************************
 * The content of this file is subject to the MYC Vtiger Customer Portal license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is Proseguo s.l. - MakeYourCloud
 * Portions created by Proseguo s.l. - MakeYourCloud are Copyright(C) Proseguo s.l. - MakeYourCloud
 * All Rights Reserved.
 * ****************************************************************************** */

class ApisalesorderPlugin {

	var $plugin_label="Sales Order";
	var $plugin_name="apisalesorder";
	var $plugin_description="This plugin allow you to enable the Sales Order module in your Portal and show the orders to your customers";
	var $affectedmodules=array("PortalBase","SalesOrderWS");
	var $pluginconfig;
	var $require_vtapi=true;

	function __construct(){

		if($this->require_vtapi && !Api::is_connected()) return false;
		$this->pluginconfig=require ROOT_PATH."/plugins/".$this->plugin_name."/config.php";

	}

	function settings(){


		if($this->require_vtapi && !Api::is_connected()) return false;
		global $mycwsapi;
		$sofields=$mycwsapi->getModuleFields("SalesOrder");
		$plugindata['salesordermoduleinfo']=$sofields;
		foreach($sofields['fields'] as $field){
			$plugindata['salesorderfields'][$field['name']]=$field['label'];
		}



		if(isset($_POST['settings'])){
			$newconfig = $_POST['settings'];
			$updconfig = $this->pluginconfig;

			$updconfig['SalesOrderWS']['list_fields']=$_POST['settings']['list_fields'];
			$updconfig['is_enabled']=$_POST['settings']['is_enabled'];


			$plugindata['altmess']=ConfigEditor::write(ROOT_PATH."/plugins/".$this->plugin_name."/config.php", $updconfig);
			$this->pluginconfig=$updconfig;
		}

		$plugindata['config']=$this->pluginconfig;
		$plugindata['affectedmodules']=$this->affectedmodules;

		return $plugindata;
	}

	function preTemplateLoad($modulename,$action,$data){

		$pluginconfig =  $this->pluginconfig;

		if(isset($pluginconfig['is_enabled']) && $pluginconfig['is_enabled']=="true"){

			if($modulename=="PortalBase"){
				$GLOBALS['api_modules']["SalesOrderWS"]=$pluginconfig['SalesOrderWS'];
				$GLOBALS['api_modules']["SalesOrderWS"]['relation_fields']=array("contact_id","account_id");
				$GLOBALS['avmod'][]="SalesOrderWS";
			}
			switch($action){
				case "base":{
					if(isset($_REQUEST['attachmentid'])){

						$doctarget=$_REQUEST['attachmentid'];
						$reldocs=$this->retrieveRelatedRecords($_REQUEST['id'],"Documents");

						if(isset($reldocs['result']) && count($reldocs['result'])>0){
							foreach($reldocs['result'] as $reldoc){
								if($reldoc['id']==$doctarget) $this->downloadAttachment($_REQUEST['attachmentid']);
							}
						}
						header("Location: index.php?module=SalesOrderWS&action=index");
					}
					break;
				}
				case "list_api":{

					for($r=0; $r<count($data['records']); $r++){
						$data['records'][$r]['contact_id']=$data['records'][$r]['contact_id']['firstname']." ".$data['records'][$r]['contact_id']['lastname'];
					  	$data['records'][$r]['account_id']=$data['records'][$r]['account_id']['accountname'];
					}
					break;
				}
				case "detail":
				case "detail_api": {
					if(isset($GLOBALS['api_client']) && $GLOBALS['api_client']!="NOT_CONFIGURED" && $GLOBALS['api_client']!="API_LOGIN_FAILED"){
					  	//$apidata = $GLOBALS['api_client']->doQuery("SELECT * FROM ");
					  	//if($this->endsWith($modulename,"WS")) $modulename=substr($modulename, 0, -2);
					  	$re = "/^[\\d]*x[\\d]*$/";
						if(preg_match($re,$data['targetid'])) $target=$data['targetid'];
						else{
							$modid=Api::getModuleId($modulename);
							$target=$modid."x".$data['targetid'];
						}
						$entdet=$GLOBALS['api_client']->doRetrieve($target);
						$reldocs=$this->retrieveRelatedRecords($target,"Documents");


					  	$data['plugin_data']['data']['apisalesorder']['relateddocuments']=$reldocs['result'];
					  	$data['record']['contact_id']=$data['record']['contact_id']['firstname']." ".$data['record']['contact_id']['lastname'];
					  	$data['record']['account_id']=$data['record']['account_id']['accountname'];
					}
					break;
				}
			}

			//array_unshift($data['plugin_data']['views']['blocks']["salesorder"], "ducumentsblock");

			$data['plugin_data']['views']['header']['apisalesorder']="";
			$data['plugin_data']['views']['blocks']['apisalesorder']="ducumentsblock";
			$data['plugin_data']['views']['footer']['apisalesorder']="";

		}

		return $data;

	}

	/*
	function postTemplateLoad($modulename,$action,$data){
		Template::displayPlugin('inventorylines',$data,'postload');
	}
	*/

	public function retrieveRelatedRecords($recordid,$relatedModule,$relationLabel=false){
		global $mycwsapi;

		if(!$relationLabel) $relationLabel=$relatedModule;

		$data = array('operation' => 'myc_retrieve_related', 'sessionName' => $mycwsapi->session_data->sessionName,'id'=>$recordid,'relatedType'=>$relatedModule, 'relatedLabel'=>$relationLabel);

		$result=WSRequest::get($mycwsapi->vtiger_ws_url,$data);
		if(!$result) return false;
		else return json_decode(json_encode($result),true);

	}

	public function downloadAttachment($recordid){
		global $mycwsapi;

		$all_ids=array($recordid);
		$docidsstring="";
		$c=0;
		foreach($all_ids as $docid){
			$c++;
			$docidsstring.="'$docid'";
			if($c<count($all_ids)) $docidsstring.=",";
		}

		$data = array('operation' => 'getpdfdata', 'sessionName' => $mycwsapi->session_data->sessionName,'id'=>$docidsstring);
		$result=WSRequest::get($mycwsapi->vtiger_ws_url,$data);

		if(!$result || !isset($result)) header("Location: index.php?module=SalesOrderWS&action=index");
		else {
			$result=json_decode(json_encode($result),true);
			header("Cache-Control: public");
			header("Content-Description: Attachment Download");
			header("Content-Length: ".$result['result'][$recordid]['filesize'].";");
			header("Content-Disposition: attachment; filename=".$result['result'][$recordid]['filename']);
			header("Content-Type: ".$result['result'][$recordid]['filetype']."; ");
			header("Content-Transfer-Encoding: binary");
			echo base64_decode($result['result'][$recordid]['attachment']);
			die();
		}

	}
}
?>
