<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Entity;

class EntityStoreRequest extends FormRequest
{
    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $request = request();
            if ($request->route('entity')) {
                Entity::findOrFail($request->route('entity'));
            }
            if ($request->routeIs('entity.store') &&
                Entity::where('email', $request->email)->count() > 0 ||
                $request->routeIs('entity.update') &&
                Entity::where('email', $request->email)->where('id', '!=', $request->route('entity'))->count() > 0
            ) {
                    $validator->errors()->add('email', __('entity.email_is_already_in_use', ['email' => $request->email]));
            }
        });
    }
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
            'salary'      => 'required|numeric|gte:600000|lte:900000',
            'name'        => 'required|string|min:5',
            'email'       => 'required|string|email',
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
