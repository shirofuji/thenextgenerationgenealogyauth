<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Mail;

class Auth extends Controller
{
    function __construct() {
        @session_start();
    }

    function signup (Request $request) {
    	$validator = Validator::make($request->all(), 
    		array(
                'username'=>array('required','unique:tng_users,username','min:6','max:32','alpha_num'),
    			'email'=>array('required','email','unique:tng_users,email'),
    			'password'=>array('required','min:8','confirmed'),
                'realname'=>array('required')
    		),
    		array(
    			'required'=>':attribute is required.',
    			'email'=>':attribute must contain a valid email address.',
    			'min'=>':attribute must be at least :min characters long.',
                'max'=>':attribute cannot be longer than :max characters.',
                'confirmed'=>'Please verify your :attribute.',
                'unique'=>':attribute already in use.',
                'alpha_num'=>':attribute can only contain letters and numbers.'
    		)
		);

    	if ($validator->fails()) {
    		return response()->json(array(
	    		'success'=>false,
	    		'errors'=>$validator->errors()
	    	), 200);
    	}

    	// $lastEntry = User::last();

    	$user = new User;
        // $user->id
		$user->username = $request->input('username');
		$user->email = $request->input('email');
        $user->realname = $request->input('realname');
		// $user->salt = $salt;
		$user->password = hash('sha256',$request->input('password'));
        $user->password_type = 'sha256';
        $user->description = '';
        $user->phone='';
        $user->website='';
        $user->address='';
        $user->city='';
        $user->state='';
        $user->zip='';
        $user->country='';
        $user->languageID=0;
        $user->notes='';
        $user->gedcom='';
        $user->mygedcom='';
        $user->personID='';
        $user->allow_edit=0;
        $user->tentative_edit=1;
        $user->allow_add=1;
        $user->allow_delete=0;
        $user->allow_lds=0;
        $user->allow_ged=0;
        $user->allow_pdf=1;
        $user->allow_private=1;
        $user->allow_profile=1;
        $user->role = 'guest';
        $user->allow_living=-1;
        $user->dt_registered=date('Y-m-d H:i:s');
        $user->dt_consented =date('Y-m-d H:i:s');
        $user->lastlogin = date('Y-m-d H:i:s');
        $user->dt_activated = date('Y-m-d H:i:s');
        $user->disabled=0;
        $user->no_email=0;
		// $user->social_token = '';
		// $user->token_expire = 0;
		$user->save();

        Mail::send('emails.activate', array(
            'token'=>base64_encode($user->userID)
        ), function ($m) use ($user) {
            $m->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'));
            $m->to($user->email)->subject(env('APP_NAME') . ' : Activate your account.');
        });

		return array(
			'success'=>true,
			'id'=>$user->user_id,
			'errors'=>array()
		);
    }

    function login (Request $request) {
    	$validator = Validator::make($request->all(), 
    		array(
    			'login-email'=>array('required'),
    			'login-password'=>array('required'),
    		),
    		array(
    			'required'=>':attribute is required.',
    			'email'=>':attribute must contain a valid email address.',
    		)
		);

    	if ($validator->fails()) {
    		return response()->json(array(
	    		'success'=>false,
	    		'errors'=>$validator->errors()
	    	), 200);
    	}

    	$user = User::where('email','=',$request->input('login-email'))
            ->orWhere('username','=',$request->input('login-email'))
            ->first();

    	if (!$user) {
    		return response()->json(array(
	    		'success'=>false,
	    		'errors'=>array('login-password'=>array('Email or password is incorrect.'))
	    	), 200);
    	}

    	$password_hash = hash('sha256',$request->input('login-password'));

    	if ($user->password != $password_hash) {
    		return response()->json(array(
	    		'success'=>false,
	    		'errors'=>array('login-password'=>array('Email or password is incorrect.')),
                'debug'=>array(
                    'test1'=>$password_hash,
                    'test2'=>$user->password
                )
	    	), 200);
    	}

    	$request->session()->put('user_logged_in', $user->user_id);
        $user->lastlogin = date('Y-m-d H:i:s');
        $user->save();

    	return array(
			'success'=>true,
			'id'=>$user->user_id,
			'errors'=>array()
		);
    }

    function index (Request $request) {
        $user_id = $request->session()->get('user_logged_in', false);
        if ($user_id) {
        	return view('auth');
        }

        $user = User::where('userID','=',$user_id)->first();
        if (!$user) {
            return view('auth');
        }

        $allow_admin = $user->allow_add || $user->allow_edit || $user->allow_delete? 1 : 0;
        $rootpath = '/homepages/24/d884156410/htdocs/maram/';
        $newroot = preg_replace( "/\//", "", $rootpath );
        $newroot = preg_replace( "/ /", "", $newroot );
        $newroot = preg_replace( "/\./", "", $newroot );

        setcookie("tngloggedin_$newroot", "1", 0, "/");

        $_SESSION['logged_in'] = 1;
        $_SESSION['allow_edit'] = $user->allow_edit;
        $_SESSION['allow_add'] = $user->allow_add;
        $_SESSION['allow_delete'] = $user->allow_delete;
        $_SESSION['tentative_edit'] = $user->tentative_edit;

        $_SESSION['allow_media_edit'] = $user->allow_edit;
        $_SESSION['allow_media_add'] = $user->allow_add;
        $_SESSION['allow_delete'] = $user->allow_delete;

        $_SESSION['mygedcom'] = $user->mygedcom;
        $_SESSION['mypersonID'] = $user->personID;
        $_SESSION['allow_admin'] = $allow_admin;
        $_SESSION['tngrole'] = $user->role;

        $_SESSION['allow_living'] = $user->allow_living;
        $_SESSION['allow_private'] = $user->allow_private;
        $_SESSION['allow_profile'] = $user->allow_profile;
        $_SESSION['allow_lds'] = $user->allow_lds;

        $_SESSION['availabletrees'] = $user->gedcom;

        $trees = explode(',',$user->gedcom);
        $numtrees = count($trees);
        if($numtrees > 1) {
            if(isset($_COOKIE['activetree:' . $user->username]))
                $assignedtree = $_SESSION['assignedtree'] = $_COOKIE['activetree:' . $user->username];
            else {
                $assignedtree = $_SESSION['assignedtree'] = $trees[0];
                setcookie('activetree:' . $user->username, $assignedtree, 0, "/");
            }
        } else {
            $_SESSION['assignedtree'] = $user->gedcom;
        }

        $_SESSION['assignedbranch'] = $user->branch;
        $_SESSION['currentuser'] = $user->username;
        $_SESSION['currentuserdesc'] = $user->description;
        $_SESSION['session_rp'] = $rootpath;

        return redirect('http://www.maram.santhiran.com/admin.php');
    }

    function password () {
    	return view('password');
    }

    function authgoogle(Request $request) {
    	$validator = Validator::make(
    		$request->all(),
    		array(
    			'username'=>'required',
    			'token'=>'required'
    		),
    		array(
    			':required'=>'Invalid request.'
    		)
    	);

    	if ($validator->fails()) {
    		return array(
    			'success'=>false,
    			'errors'=>$validator->errors()
    		);
    	}

    	$user = User::where('username','=',$request->input('username'))->first();

    	if (!$user) {
    		$user = new User;
            // $user->id
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->realname = $request->input('realname');
            // $user->salt = $salt;
            $user->password = hash('sha256', $request->input('token'));
            $user->password_type = 'sha256';
            $user->description = '';
            $user->phone='';
            $user->website='';
            $user->address='';
            $user->city='';
            $user->state='';
            $user->zip='';
            $user->country='';
            $user->languageID=0;
            $user->notes='';
            $user->gedcom='';
            $user->mygedcom='';
            $user->personID='';
            $user->allow_edit=0;
            $user->tentative_edit=1;
            $user->allow_add=1;
            $user->allow_delete=0;
            $user->allow_lds=0;
            $user->allow_ged=0;
            $user->allow_pdf=1;
            $user->allow_private=1;
            $user->allow_profile=1;
            $user->role = 'guest';
            $user->allow_living=0;
            $user->dt_registered=date('Y-m-d H:i:s');
            $user->dt_consented =date('Y-m-d H:i:s');
            $user->lastlogin = date('Y-m-d H:i:s');
            $user->dt_activated = date('Y-m-d H:i:s');
            $user->disabled=0;
            $user->no_email=0;
            // $user->social_token = '';
            // $user->token_expire = 0;
            $user->save();

			return array(
				'success'=>true,
				'errors'=>array()
			);
    	}

    	// if ($user->social_token == '') {
    	// 	return array(
    	// 		'success'=>false,
    	// 		'errors'=>array('email'=>array('This email has already been registered, please login with your password.'))
    	// 	);
    	// }

    	$user->password = hash('sha256',$request->input('token'));
    	$user->save();

    	$request->session()->put('user_logged_in', $user->user_id);
        $request->session()->put('user_logged_in', $user->user_id);
        $user->lastlogin = date('Y-m-d H:i:s');
        $user->save();

        $allow_admin = $user->allow_add || $user->allow_edit || $user->allow_delete? 1 : 0;
        $rootpath = '/homepages/24/d884156410/htdocs/maram/';
        $newroot = preg_replace( "/\//", "", $rootpath );
        $newroot = preg_replace( "/ /", "", $newroot );
        $newroot = preg_replace( "/\./", "", $newroot );

        setcookie("tngloggedin_$newroot", "1", 0, "/");

        $_SESSION['logged_in'] = 1;
        $_SESSION['allow_edit'] = $user->allow_edit;
        $_SESSION['allow_add'] = $user->allow_add;
        $_SESSION['allow_delete'] = $user->allow_delete;
        $_SESSION['tentative_edit'] = $user->tentative_edit;

        $_SESSION['allow_media_edit'] = $user->allow_edit;
        $_SESSION['allow_media_add'] = $user->allow_add;
        $_SESSION['allow_delete'] = $user->allow_delete;

        $_SESSION['mygedcom'] = $user->mygedcom;
        $_SESSION['mypersonID'] = $user->personID;
        $_SESSION['allow_admin'] = $allow_admin;
        $_SESSION['tngrole'] = $user->role;

        $_SESSION['allow_living'] = $user->allow_living;
        $_SESSION['allow_private'] = $user->allow_private;
        $_SESSION['allow_profile'] = $user->allow_profile;
        $_SESSION['allow_lds'] = $user->allow_lds;

        $_SESSION['availabletrees'] = $user->gedcom;

        $trees = explode(',',$user->gedcom);
        $numtrees = count($trees);
        if($numtrees > 1) {
            if(isset($_COOKIE['activetree:' . $user->username]))
                $assignedtree = $_SESSION['assignedtree'] = $_COOKIE['activetree:' . $user->username];
            else {
                $assignedtree = $_SESSION['assignedtree'] = $trees[0];
                setcookie('activetree:' . $user->username, $assignedtree, 0, "/");
            }
        } else {
            $_SESSION['assignedtree'] = $user->gedcom;
        }

        $_SESSION['assignedbranch'] = $user->branch;
        $_SESSION['currentuser'] = $user->username;
        $_SESSION['currentuserdesc'] = $user->description;
        $_SESSION['session_rp'] = $rootpath;

    	return array(
				'success'=>true,
				'errors'=>array()
			);
    }

    function submit_reset(Request $request) {
    	$validator = Validator::make($request->all(),
    		array(
    			'email'=>array('required','email')
    		),
    		array(
    			'required'=>':attribute is required.',
    			'email'=>':attribute must contain a valid email address.'
    		));

    	if ($validator->fails()) {
    		return array(
    			'success'=>false,
    			'errors'=>$validator->errors()
    		);
    	}

    	$user = User::where('email','=',$request->input('email'))->first();
    	if (!$user) {
    		return array(
    			'success'=>false,
    			'errors'=>array(
    				'email'=>array('Email address not found.')
    			)
    		);
    	}

    	// $user->reset_token = hash('sha256',$request->input('email'));
        $tmp_pass = md5(strtotime('now'));
        $user->password = hash('sha256',$tmp_pass);
    	$user->save();


    	Mail::send('emails.passwordreset', array(
    		'token'=>$tmp_pass
    	), function ($m) use ($user) {
    		$m->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'));
    		$m->to($user->email)->subject(env('APP_NAME') . ' : Password Reset Requested');
    	});

    	return array(
    		'success'=>true,
    		'errors'=>array()
    	);
    }

    function create_password(Request $request) {
    	$validator = Validator::make($request->all(), array(
    		'token'=>array('required'),
    		'password'=>array('required','min:8','confirmed')
    	),array(
    		'required'=>':attribute is missing, invalid request.',
    		'min'=>':attribute must contain at least :min characters.',
    		'confirmed'=>':attribute must be verified.'
    	));

    	if ($validator->fails()) {
    		return array(
    			'success'=>false,
    			'errors'=>$validator->errors()
    		);
    	}

    	$user = User::where('reset_token','=',$request->input('token'))->first();
    	if (!$user) {
    		return array(
    			'success'=>false,
    			'errors'=>array('token'=>array('Token invalid or expired.'))
    		);
    	}

    	$user->password = hash('sha256',$request->input('password'));
    	$user->save();

    	return array(
    		'success'=>true,
    		'errors'=>array()
    	);
    }

    function logout (Request $request) {
    	$request->session()->flush();
        session_destroy();
    	return redirect('/');
    }

    function verify_login (Request $request) {
    	$user_id = $request->session->get('user_logged_in', false);
    	if ($user_id) {
	    	$user = User::where('userID','=',$user_id)->first();

	    	if ($user) {
	    		return array(
	    			'logged_in'=>true,
	    			'ID'=>$user->userID,
	    			'email'=>$user->email
	    		);
	    	}
    	}

    	return array(
    		'logged_in'=>false,
    		'ID'=>0,
    		'email'=>''
    	);
    }

    function activate_account(Request $request) {
        $token = $request->input('tk');

        $user_id = base64_decode($token);

        $user = User::where('userID','=', $user_id)->first();
        if (!$user) {
            return 'Invalid request.';
        }

        $user->allow_living = 0;
        $user->save();

        return redirect('/?msg=Account activated.');
    }
}
