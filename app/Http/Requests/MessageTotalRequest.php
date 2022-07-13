<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MessageTotalRequest extends FormRequest
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
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'period_group_unit' => ['required',  Rule::in(['year', 'month','day'])]
        ];
    }
}
