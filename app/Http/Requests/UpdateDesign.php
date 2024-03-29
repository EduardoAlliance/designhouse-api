<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDesign extends FormRequest
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
            'title'=>'required|unique:designs,title,'.$this->design->id,
            'description'=>'required|string|max:140|min:20',
            'tags'=>'required',
            'team'=> 'required_if:assign_to_team,true'
        ];
    }



}
