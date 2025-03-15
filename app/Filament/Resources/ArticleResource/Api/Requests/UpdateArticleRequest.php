<?php

namespace App\Filament\Resources\ArticleResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'title' => 'required|string',
			'slug' => 'required|string',
			'content' => 'required|string',
			'author_id' => 'required|integer',
			'category_id' => 'required|integer',
			'image_url' => 'required|string',
			'thumbnail_url' => 'required|string',
			'status' => 'required',
			'published_at' => 'required',
			'total_views' => 'required|integer'
		];
    }
}
