<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
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
            'clock_in' => 'required|before:clock_out',
            'clock_out' => 'required|after:clock_in',
            'break_start' => 'required|before:break_end|before:clock_out',
            'break_end' => 'required|after:break_start|before:clock_out',
            'note' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'clock_in.before' => '出勤時間もしくは退勤時間が不適切な値です',
            'break_start.before' => '出勤時間もしくは退勤時間が不適切な値です',
            'break_end.before' => '出勤時間もしくは退勤時間が不適切な値です',
            'note.required' => '備考を記入してください',
        ];
    }
}
