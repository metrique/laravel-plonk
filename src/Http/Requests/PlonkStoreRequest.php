<?php

namespace Metrique\Plonk\Http\Requests;

use Metrique\Plonk\Http\Requests\Request;

class PlonkStoreRequest extends Request
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
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|image|max:5120',
            'title' => 'required|min:3|max:255',
            'alt' => 'required|min:3|max:255',
        ];
    }
}