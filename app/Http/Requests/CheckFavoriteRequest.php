<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckFavoriteRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'imdb_id' => 'nullable|string|regex:/^tt\d{7,}$/',
        ];
    }

    public function messages()
    {
        return [
            'imdb_id.string' => 'IMDb ID must be a string',
            'imdb_id.regex' => 'IMDb ID must be in the format ttXXXXXXXX',
        ];
    }
}
