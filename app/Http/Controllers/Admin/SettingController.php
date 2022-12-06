<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use App\Models\Setting;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
class SettingController extends Controller {
	/**
	* function for list all settings
	*
	* @param  null
	* 
	* @return view page
	*/
	
	
	/**
	* prefix function
	*
	* @param $prefix as prefix
	* 
	* @return void
	*/

	public function prefix() { 
	    $prefix = "Site";
		$result = Setting::where('key', 'like', $prefix.'%')->orderBy('id', 'ASC')->get()->toArray();
		return  View::make('admin.site-settings.edit', compact('result','prefix'));
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
		Session::put('msg', '<strong class="alert alert-success">Data successfully updated.</strong>');
		return  Redirect::intended('admin/site-setting');
	}


	

	/**
	* function for save added new settings
	*
	*@param null
	*
	* @return void
	*/

	
     public  function arrayStripTags($array){
		$result			=	array();
		foreach ($array as $key => $value) {
			// Don't allow tags on key either, maybe useful for dynamic forms.
			$key = strip_tags($key,ALLOWED_TAGS_XSS);
	 
			// If the value is an array, we will just recurse back into the
			// function to keep stripping the tags out of the array,
			// otherwise we will set the stripped value.
			if (is_array($value)) {
				$result[$key] = $this->arrayStripTags($value);
			} else {
				// I am using strip_tags(), you may use htmlentities(),
				// also I am doing trim() here, you may remove it, if you wish.
				$result[$key] = trim(strip_tags($value,ALLOWED_TAGS_XSS));
			}
		}
		
		return $result;
		
	}
	

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