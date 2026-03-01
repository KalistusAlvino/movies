<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetByImdbRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'imdbId' => 'required|string|regex:/^tt\d{7,}$/',
        ];
    }

    public function messages()
    {
        return [
            'imdbId.required' => 'IMDb ID is required',
            'imdbId.string' => 'IMDb ID must be a string',
            'imdbId.regex' => 'IMDb ID must be in the format ttXXXXXXXX',
        ];
    }
}
