<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, Session;
class SettingController extends Controller
{
    /**
     * Edit vendor setting
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request){
        $user = Auth::user();
        $is_notification = $user->is_notification;
        return view('vendor.setting.edit', compact('is_notification'));
    }

    /**
     * Edit vendor setting
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){
        $user = Auth::user();
        // dd($request->is_notification);
        $user->is_notification = $request->is_notification ? "1" : "0";
        $user->save();
        $is_notification = $user->is_notification;
        Session::flash('success', 'Setting is updated');
        return view('vendor.setting.edit', compact('is_notification'));
    }
}

