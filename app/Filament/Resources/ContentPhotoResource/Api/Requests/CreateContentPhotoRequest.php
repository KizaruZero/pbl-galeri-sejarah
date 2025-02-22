<?php

namespace App\Filament\Resources\ContentPhotoResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContentPhotoRequest extends FormRequest
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
			'user_id' => 'required|integer',
			'image_url' => 'required|string',
			'description' => 'required|string',
			'source' => 'required|string',
			'alt_text' => 'required|string',
			'note' => 'required|string',
			'status' => 'required|string',
			'approved_at' => 'required'
		];
    }
}
