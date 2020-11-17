<?php
namespace App\Traits;
use Validator;
use App\Traits\MainTrait;

trait Login
{

	public $validateCheck;


	/**
     * Username used in ThrottlesLogins trait
     * 
     * @return string
     */
	public function apiRequestError(){

		return	response()->json(  ['code'=>404,'message'=>'error'] );
	}

	public function username(){
		return 'email';
	}

	public function validateData(){

		$this->validateCheck= Validator::make(request()->all(), [
			'email' =>  "email",
			'password'=> 'required' 
		] );

		return $this->validateCheck;
	}
}