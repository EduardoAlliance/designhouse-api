<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsProfile extends FormRequest
{
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
            'tagline'=>'required',
            'name'=>'required',
            'about'=>'required|string|min:20',
            'formatted_address'=>'required',
            'available_to_hire'=>'required',
            'location.latitude'=>'required|min:-90|max:90',
            'location.longitude'=>'required|min:-180|max:180'
        ];
    }
}
