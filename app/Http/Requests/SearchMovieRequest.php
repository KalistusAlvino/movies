<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchMovieRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            's' => 'required|string|min:2|max:100',
            'page' => 'sometimes|integer|min:1',
            'type' => 'sometimes|in:movie,series,episode',
        ];
    }

    public function messages()
    {
        return [
            's.required' => trans('validation.search_required'),
            's.min' => trans('validation.search_min'),
            's.max' => trans('validation.search_max'),
            'page.integer' => trans('validation.page_integer'),
            'page.min' => trans('validation.page_min'),
            'type.in' => trans('validation.invalid_type'),
        ];
    }
}
