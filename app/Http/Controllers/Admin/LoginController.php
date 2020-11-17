<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Login;
use App\Models\Admin;
class LoginController extends Controller
{
    //
	use Login;

	public function login(Request $request){
		if ($this->validateData()->fails() ) {
			return	$this->apiRequestError();
		}
		try{
			return Admin::where('email',$request->email )->first()->passportToken();
			
		}catch(\Throwable $e){
			return response()->json([
				'code' => 404 ,
				'message' => 'Login Failed'
			]);
		}
	}
}
