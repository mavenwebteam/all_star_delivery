<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Validator;
use App\User;
use App\Models\Emailtemplates;
use Session;
use Hash;
use DB;
use Auth, Config;
use Mail;
use App\Models\Admin;
class ForgotpassController extends Controller {
    public function index() {
        if (session('admin') ['id']) {
        } else {
            return view('admin.fogot');
        }
    }
    public function postmail(Request $request) {
        $validator = Validator::make($request->all(), 
			[
				'email' => 'required|email|exists:users,email',
		 	]);
        if ($validator->fails()) {
            return redirect('/admin/forgot-password')->withErrors($validator)->withInput();
        } else {
            $email = $request->input('email');
            $emaildata = DB::table('users')->where('email', $email)->where('is_admin', '1')->first();
            if (!isset($emaildata->id)) {
                Session::put('msg', '<strong class="alert alert-danger">Please enter your registered email.</strong>');
                return redirect('/admin/forgot-password');
            } else {
                $length = 10;
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0;$i < $length;$i++) {
                    $randomString.= $characters[rand(0, $charactersLength - 1) ];
                }
                $url = $randomString;
                $cur_date = date("Y-m-d H:i:s");
                $user = User::find($emaildata->id);
                $user->forgot_url = $url;
                $user->forgot_time = $cur_date;
                $user->save();
                $create_url = \App::make('url')->to('/admin/create-password') . "/" . $url;
                //$data = array( 'email' => $email, 'first_name' => $emaildata->first_name, 'from' => 'noreply@demoasite1.com', 'from_name' => 'Bringo', 'data' =>$create_url );
                //Mail::send( 'admin.temp_forgot', $data, function( $message ) use ($data)
                //{
                //	$message->to( $data['email'] )->from( $data['from'], $data['from_name'] )->subject( 'forget password' );
                //});
                $emailData = Emailtemplates::where('slug', '=', 'user-forgot-password')->first();
                if ($emailData) {
                    $textMessage = $emailData->description;
                    $user->subject = $emailData->subject;
                    //$activate_url =\App::make('url')->to("account-activate/".base64_encode($user->id));
                    $settingsEmail = Config::get("Site.email");
                    $full_name = $user->first_name;
                    $subject = $emailData->subject;
                    if ($user->email != '') {
                        $textMessage = str_replace(array('{USER_NAME}', '{FORGOT_PASSWORD_LINK}', '{LINK}'), array($user->first_name, $create_url, $create_url), $textMessage);
                        /*Mail::raw($textMessage, function ($messages) use ($user) {
                        $to = $user->email;
                        $messages->to($to)->subject($user->subject);
                        });*/
                        //echo '<pre>'; print_r($textMessage); die;
                        $this->sendMail($user->email, $full_name, $subject, $textMessage, $settingsEmail);
                    }
                }
                Session::put('msg', '<strong class="alert alert-success">Forgot password instruction has been successfully sent.</strong>');
                return redirect('/admin/login');
            }
        }
    }
    public function createpass($uniqurl) {
        $emaildata = DB::table('users')->whereForgot_url($uniqurl)->first();
        //dd($emaildata);
        if (!isset($emaildata->id)) {
            return view('pages.error404', ['msg' => 'Invalid url!']);
        } else {
            $time = date('Y-m-d H:i:s');
            $time1 = strtotime($emaildata->forgot_time);
            $time2 = strtotime($time);
            $diff = $time2 - $time1;
            $hour_diff = $diff / 3600;
            if ($hour_diff > 24) {
                return view('pages.error404', ['msg' => 'Your Session has been expired!']);
            } else {
                return view('admin.createpassword', ['uniqurl' => $uniqurl]);
            }
        }
    }
    public function createpasspost(Request $request) {
        $validator = Validator::make($request->all(), ['uniqurl' => 'required', 'password' => 'required|min:8|max:16|confirmed', 'password_confirmation' => 'required|min:8|max:16', ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $password = $request->input('password');
            $uniqurl = $request->input('uniqurl');
            $uniqurldata = DB::table('users')->whereForgot_url($uniqurl)->first();
            if (!isset($uniqurldata->id)) {
                return view('pages.error404', ['msg' => 'Invalid url!']);
            } else {
                $user = User::find($uniqurldata->id);
                $user->forgot_url = "";
                $user->password = Hash::make($password);
                $user->save();
                Session::put('msg', '<strong class="alert alert-success">password changed successfully. You can login with new password.</strong>');
                return redirect('/admin/login');
            }
        }
    }
}
?>
