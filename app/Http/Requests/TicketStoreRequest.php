<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => [
                'file',
                'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Тема заявки обязательна',
            'title.max' => 'Тема не должна превышать 255 символов',
            'content.required' => 'Текст заявки обязателен',
            'attachments.*.max' => 'Каждый файл не должен превышать 10MB',
            'attachments.*.mimes' => 'Поддерживаемые форматы: jpg, png, pdf, doc, docx, txt, xls, xlsx',
        ];
    }
}
