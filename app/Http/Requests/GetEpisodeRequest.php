<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetEpisodeRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            't' => 'required|string|min:2|max:255',
            'Season' => 'required|integer|min:1',
            'Episode' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            't.required' => 'Series title is required',
            't.min' => 'Series title must be at least 2 characters',
            't.max' => 'Series title must not exceed 255 characters',
            'Season.required' => 'Season number is required',
            'Season.integer' => 'Season must be a valid number',
            'Season.min' => 'Season must be at least 1',
            'Episode.required' => 'Episode number is required',
            'Episode.integer' => 'Episode must be a valid number',
            'Episode.min' => 'Episode must be at least 1',
        ];
    }
}
