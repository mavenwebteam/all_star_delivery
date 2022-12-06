<?php 
namespace App\Http\Controllers\SubAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Validator;
use App\Models\Country;
use App\Models\Content;
use App\Uniqcode;
use App\Models\Admin;
use Hash;
use Auth;
use DB;
use App\Helpers;
use Config;
use Session;
use Mail;

class ContentController extends Controller {
  private $admin;
  public function __construct()
    {
		if (session('admin')['id'])
		{
			$admindata = Admin::find(session('admin')['id']);
			$this->user = $admindata;
		}

    }

	public function index(Request $request)

	{
		$content_data = Content::orderBy("created_at","DESC");
		if(!empty($request->input('title')))
		{ 
			$src = $request->input('title');
			$content_data =$content_data->where('title','like',"%$src%");
		
        }
		$content_data=$content_data->paginate(10);
		
		if ($request->ajax()) 
		{
			return view('sub_admin.content.search', compact('content_data'));  
        }
		$admindata = Admin::find(session('admin')['id']);

		return view('sub_admin.content.show', compact('content_data','admindata'));
		

         
	}
	
	public function addcontent(Request $request)
	{
		return view('sub_admin.content.add');
	} 
	public function addcontentpost(Request $request)
	{
			$validator = Validator::make($request->all(), [
			'title' => 'required',
			'description' => 'required',
			
			]);
			if ($validator->fails()) 
			{//echo '<pre>';print_r($validator->errors());exit;
				return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$content = new Content();				
				$title =  $request->input('title');
				$description =  $request->input('description');	

				$slug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($title)));
				$oldslug = Content::where('slug', '=', $slug)->first();
				if ($oldslug === null) 
				{$newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($title)));}
				else { $newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($title.'-'.time())));}

				
				$content->slug = $newslug;
				$content->title = $title;
				$content->description = $description;
                $content->save();
                echo json_encode(array('class'=>'success','message'=>'Content Added successfully.'));die;
				
			}
		
	} 
	
	public function editcontent($id)

	{
		$id = base64_decode($id);
		$content_data = Content::find($id);
		return view('sub_admin.content.edit',["content_data" => $content_data]);

	}
	

	public function editcontentpost(Request $request)

	{
		$id = $request->input('id');
		 $id =  base64_decode($id);
		$validator = Validator::make($request->all(), [
			
			'title' => 'required',
			'description' => 'required',
			 ]);

			  if ($validator->fails()) 
			  {
					return response()->json(['success'=>false ,'errors'=>$validator->errors()]);
			}else
			{
				$content = Content::find($id);
			
				$title =  $request->input('title');
				$description =  $request->input('description');

				$slug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($title)));
				$oldslug = Content::where('slug', '=', $slug)->where('id', '!=', $id)->first();
				if ($oldslug === null) 
				{$newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($title)));}
				else { $newslug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower($title.'-'.time())));}

				
				$content->slug = $newslug;

				$content->title = $title;
				$content->description = $description;
                $content->save();
				echo json_encode(array('class'=>'success','message'=>'Content Edit successfully.'));die;
				
			}	

	}



	public function contentstatus(Request $request)
	{
		 $id = base64_decode($request->input('id'));
		$contentdata = Content::find($id);
		
		if($contentdata->status=="1")
		{
			$contentdata->status = "0";
			$contentdata->save();
			echo json_encode(array('class'=>'success','message'=>'Content Deactive successfully.'));die;
		

		}else
		{
			$contentdata->status = "1";
			$contentdata->save();
			echo json_encode(array('class'=>'success','message'=>'Content Active successfully.'));die;
			
			  
		}
		
	}

	// public function contentremove(Request $request)
	// {
    //      $id = base64_decode($request->input('id'));
    //     $Content = Content::find($id);
    //     $Content->delete();

    //     return response()->json(['success'=>true ,'message'=>'<div class="alert alert-success alert-dismissible">
    //     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    //     <h4><i class="icon fa fa-check"></i> Alert!</h4>
    //     Content Remove Successfully
    //   </div>']);

	//    }
	
}

?>