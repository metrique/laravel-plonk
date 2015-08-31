<?php

namespace Metrique\Plonk\Http\Requests;

use Metrique\Plonk\Http\Requests\Request;

class PlonkUpdateRequest extends Request
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
            'title' => 'required|min:3|max:255',
            'alt' => 'required|min:3|max:255',
        ];
    }
}