<?php

namespace App\Filament\Resources\UserCommentResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserCommentRequest extends FormRequest
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
            'content' => 'required|string',
            'user_id' => 'required|integer',
            'content_photo_id' => 'nullable|integer',
            'content_video_id' => 'nullable|integer'
        ];
    }
}
