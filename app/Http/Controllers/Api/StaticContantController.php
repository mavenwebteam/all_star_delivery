<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Content;
use Validator;
use Illuminate\Http\JsonResponse;

class StaticContantController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string|max:50',
            ]);
            if ($validator->fails())
            {
                return $this->sendError(trans($validator->errors()->first()),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                die;
            }
            $content = Content::where('slug',$request->type)->first();
            if($content){
                $data = array(
                    'title' => $content->title,
                    'description' => $content->description
                );
                return $this->sendResponse($data, trans('message.GET_DATA'));
            }else{
                return $this->sendError(trans('Type is not match'),JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                die;
            }
            
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage(),JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
    
}
