<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Разбить строку с ФИО на три части
     *
     * @return $this
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => explode(' ', $this->name),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'array', 'size:3'],
            'comment' => 'nullable|string',
            'itemName' => ['required'],
            'manufacturer' => ['required', 'string'],
        ];
    }
}
