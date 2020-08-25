<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class WinnerRequest extends FormRequest
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
            'code' => [
                'bail',
                'required',
                'string',
                'min:4',
                'max:20',
            ],
            'phone' => [
                'bail',
                'required',
                'string',
                'max:15',
                'regex:/^(0|\+98)?9(1[0-9]|9[0-2]|2[0-2]|0[1-5]|41|3[0,3,5-9])\d{7}$/' // iran mobile format
            ]
        ];
    }
}
