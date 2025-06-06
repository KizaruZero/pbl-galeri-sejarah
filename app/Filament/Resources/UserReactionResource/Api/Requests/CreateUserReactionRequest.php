<?php

namespace App\Filament\Resources\UserReactionResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserReactionRequest extends FormRequest
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
            'user_id' => 'required|integer',
            'comment_id' => 'nullable|integer',
            'reaction_type_id' => 'nullable|integer'
        ];
    }
}
