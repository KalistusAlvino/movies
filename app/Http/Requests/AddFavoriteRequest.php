<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddFavoriteRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'imdb_id' => 'required|string|regex:/^tt\d{7,10}$/',
        ];
    }

    public function messages()
    {
        return [
            'imdb_id.required' => trans('validation.imdb_id_required'),
            'imdb_id.regex' => trans('validation.invalid_imdb_id'),
        ];
    }
}
