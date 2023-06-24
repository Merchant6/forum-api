<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostDataUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['integer'],
            // 'user_id' => ['int'],
            'title' => ['string', 'max:100', 'min:10'],
            'content' => ['string', 'min:10'],
            'up_votes' => ['nullable', 'integer'],
            'down_votes' => ['nullable', 'integer']
        ];
    }
}
