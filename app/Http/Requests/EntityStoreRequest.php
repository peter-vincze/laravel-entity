<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Entity;
use Illuminate\Validation\Validator;

class EntityStoreRequest extends FormRequest
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
        $request = request();
        $request->routeIs('entity.store');
        $uniqueAddable = $request->routeIs('entity.store') ? "" : "," . $request->route('entity');
        return [
            'salary'      => 'required|numeric|gte:600000|lte:900000',
            'name'        => 'required|string|min:5',
            'email'       => "required|string|email|unique:entities,email{$uniqueAddable}",
            'docker'      => 'required|boolean',
            'agile'       => 'required|boolean',
            'start'       => 'required|date|date_format:Y-m-d|before:2021-06-16|after:tomorrow',
            'senior'      => 'nullable|boolean',
            'fullstack'   => 'nullable|boolean',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Get required Rules from Rules
     *
     * @return array
     */
    public function requiredRules()
    {
        return array_keys(array_filter($this->rules(),
            function ($value)
            {
                return mb_strpos($value, 'required') !== false;
            }
        ));
    }
}
