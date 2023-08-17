<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {   //Find Comment
        $comment = $this->route('comment');
        return $this->user()->can('delete', $comment);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
