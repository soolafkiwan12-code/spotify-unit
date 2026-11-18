<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManage() ?? false;
    }

    public function rules(): array
    {
        return [
            'album_id' => ['required', 'exists:albums,id'],
            'title' => ['required', 'string', 'max:150'],
            'duration_seconds' => ['required', 'integer', 'min:1', 'max:3600'],
            'genre' => ['nullable', 'string', 'max:60'],
        ];
    }
}
