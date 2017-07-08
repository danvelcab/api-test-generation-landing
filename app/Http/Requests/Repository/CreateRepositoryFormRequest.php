<?php

namespace App\Http\Requests\Repository;

use App\Providers\CodesServiceProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRepositoryFormRequest extends FormRequest
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
        $rules = [
            //COOKIES
            "url"                  =>      "required|string|max:500",
            "folder"                  =>      "required|string|max:100",
        ];

        return $rules;
    }
}
