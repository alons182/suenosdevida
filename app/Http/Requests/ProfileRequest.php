<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProfileRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'first_name' => 'required',
            'last_name' => 'required',
            'ide' => 'required',
            'address' => 'required',
            'telephone' => 'required',
            'province' => 'required',
            'canton' => 'required',
            'city' => 'required',
            'bank' => 'required',
            'number_account' => 'required',
		];
	}

}
