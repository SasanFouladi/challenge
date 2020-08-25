<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CodeRequest extends FormRequest
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
            'name' => 'bail|string|min:4|required',
            'description' => 'bail|string|min:4',
            'code' => 'bail|required|max:24|string|'.Rule::unique('codes','code')->ignore($this->request->get('id')),
            'capacity' => 'bail|required|integer|between:1,10000',
            'enable' => "bail|required|boolean",
        ];
    }
}
