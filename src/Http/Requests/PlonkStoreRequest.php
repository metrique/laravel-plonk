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
            'file' => 'required_without:data|image|max:20480',
            // 'data' => 'required_without:file|string',
            'title' => 'required|string|min:3|max:191',
            'alt' => 'required|string|min:3|max:191',
        ];
    }
}
