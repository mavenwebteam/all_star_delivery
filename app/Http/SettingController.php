<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Input;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use App\Model\Setting;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
class SettingsController extends BaseController {
	/**
	* function for list all settings
	*
	* @param  null
	* 
	* @return view page
	*/
	public function listSetting(){	
		$DB				=	Setting::query();
		$searchVariable	=	array(); 
		$inputGet		=	Input::get();
		if ($inputGet && isset($inputGet['display'])) {
			$searchData	=	Input::get();
			unset($searchData['display']);
			unset($searchData['_token']);
			foreach($searchData as $fieldName => $fieldValue){
				if(!empty($fieldValue)){
					$DB->where("$fieldName",'like','%'.$fieldValue.'%');
					$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
				}
			}
		}
		$sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'id';
	    $order  = (Input::get('order')) ? Input::get('order')   : 'ASC';
		$result = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		return  View::make('admin.settings.index',compact('result','searchVariable','sortBy','order'));
	} // end listSetting()
	
	/**
	* prefix function
	*
	* @param $prefix as prefix
	* 
	* @return void
	*/

	public function prefix($prefix = null) { 
		$result = Setting::where('key', 'like', $prefix.'%')->orderBy('id', 'ASC')->get()->toArray();
		return  View::make('admin.settings.prefix', compact('result','prefix'));
	}// end prefix()
	
	/**
	* update prefix function
	*
	* @param $prefix as prefix
	* 
	* @return void
	*/

	public function updatePrefix($prefix = null){
		
		$thisData		= Input::all(); 
		Input::replace($this->arrayStripTags($thisData));
		$allData		= Input::all(); 
		
		if(!empty($allData)){
			if(!empty($allData['Setting'])){
				foreach($allData['Setting'] as $key => $value){
					if(!empty($value["'id'"]) && !empty($value["'key'"])){
						
						if($value["'type'"] == 'checkbox'){
							$val = (isset($value["'value'"])) ? 1 : 0;
						}else{
							$val = (isset($value["'value'"])) ? $value["'value'"] : '';
						}
						
						Setting::where('id', $value["'id'"])->update(array(
							'key'   	=>  $value["'key'"],
							'value' 	=>  $val
						)); 
					}
				}
			}
		}
		$this->settingFileWrite();
		Session::flash('flash_notice', 'Settings updated successfully.'); 
		return  Redirect::intended('admin/settings/prefix/'.$prefix);
	}


	/**
	* function add new settings view page
	*
	*@param null
	* @return void
	*/

	public function addSetting(){
		return  View::make('admin.settings.add');
	}//end addSetting()

	/**
	* function for save added new settings
	*
	*@param null
	*
	* @return void
	*/

	public function saveSetting(){

		$thisData	=	Input::all(); 
		Input::replace($this->arrayStripTags($thisData));
		$validator  = 	Validator::make(
			Input::all(),
			array(
				'title' 	 => 'required',
				'key' 		 => 'required',
				'value' 	 => 'required',
				'input_type' => 'required'
			)
		);
		if ($validator->fails())
		{	
			return Redirect::to('admin/settings/add-setting')
				->withErrors($validator)->withInput();
		}else{
			$obj	= new Setting;
			$obj->title    		= Input::get('title');
			$obj->key   		= Input::get('key');
			$obj->value   		= Input::get('value');
			$obj->input_type   	= Input::get('input_type');
			$obj->editable  	= !empty(Input::get('editable')) ? 1 : 0;
			$obj->save();
		}	
		$this->settingFileWrite();	
		Session::flash('flash_notice', 'Setting added successfully.'); 
		return Redirect::intended('admin/settings');
	}//end saveSetting()

	/**
	* function edit settings view page
	*
	*@param $Id as Id
	*
	* @return void
	*/
	public function editSetting($Id){
		$result			 = 	Setting::find($Id);
		if(empty($result)) {
			return Redirect::to('admin/dashboard');
		}
		return  View::make('admin.settings.edit',compact('result'));
	}//end editSetting()

	/**
	* function for update setting
	*
	* @param $Id as Id
	*
	* @return void
	*/

	public function updateSetting($Id){
		$thisData		=	Input::all(); 
		Input::replace($this->arrayStripTags($thisData));
		$validator  	= 	Validator::make(
			Input::all(),
			array(
				'title' 		=> 'required',
				'key' 			=> 'required',
				'value' 		=> 'required',
				'input_type' 	=> 'required'
			)
		);
		if ($validator->fails())
		{	
			return Redirect::to('admin/settings/edit-setting/'.$Id)
				->withErrors($validator)->withInput();
		}else{
			$obj	 				=  Setting::find($Id);
			$obj->title    			= Input::get('title');
			$obj->key   			= Input::get('key');
			$obj->value   			= Input::get('value');
			$obj->input_type   		= Input::get('input_type');
			$obj->editable  		= Input::get('editable');
			$obj->save();
		}	
		$this->settingFileWrite();	
		Session::flash('flash_notice', 'Setting updated successfully.'); 
		return Redirect::intended('admin/settings');
	}//end updateSetting()

	/**
	* function for delete setting
	*
	* @param $Id as Id
	*
	* @return void
	*/
	public function deleteSetting($Id = 0){
		if($Id){
			$this->_delete_table_entry('settings',$Id,'id');
			Session::flash('flash_notice', 'Setting deleted successfully.'); 
		}
		$this->settingFileWrite();
		return Redirect::intended('admin/settings');
	}//end deleteSetting()

	/**
	* function for write file on update and create
	*
	*@param $Id as Id
	*
	* @return void
	*/

	public function settingFileWrite() {
		$DB		=	Setting::query();
		$list	=	$DB->orderBy('key','ASC')->get(array('key','value'))->toArray();
		
        $file = SETTING_FILE_PATH;
		$settingfile = '<?php ' . "\n";
		foreach($list as $value){
			$val	=	str_replace('"',"'",$value['value']);
			if($value['key']=='Reading.records_per_page' || $value['key']=='Site.debug'){
				$settingfile .=  '$app->make('.'"config"'.')->set("'.$value['key'].'", '.$val.');' . "\n"; 
			}else{
				$settingfile .=  '$app->make('.'"config"'.')->set("'.$value['key'].'", "'.$val.'");' . "\n"; 
			}
		}
		$bytes_written = File::put($file, $settingfile);
		if ($bytes_written === false)
		{
			die("Error writing to file");
		}
	}//end settingFileWrite()
}//end SettingsController class