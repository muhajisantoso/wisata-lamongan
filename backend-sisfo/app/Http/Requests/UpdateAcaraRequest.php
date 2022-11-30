<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcaraRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'kategori_id' => 'required',
            'deskripsi' => 'nullable',
            'tanggal' => 'required',
            'gambar' => 'nullable'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nama',
            'kategori_id' => 'kategori'
        ];
    }
}
