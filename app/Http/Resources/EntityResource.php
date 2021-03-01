<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Entity;

class EntityResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'salary'      => $this->salary,
            'name'        => $this->name,
            'email'       => $this->email,
            'docker'      => $this->docker,
            'agile'       => $this->agile,
            'start'       => $this->start,
            'senior'      => $this->senior,
            'fullstack'   => $this->fullstack,
            'description' => $this->description,
        ];
    }

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
            if ($request->routeIs('entity.update') && !Entity::find($request->route('id'))) {
                    $validator->errors()->add('email', __('entity.not_found', ['id' => $request->route('id')]));
            }
            elseif ($request->routeIs('entity.store') &&
                Entity::where('email', $request->email) ||
                $request->routeIs('entity.update') &&
                Entity::where('email', $request->email)->where('id', '!=', $request->route('id'))
            ) {
                    $validator->errors()->add('email', __('entity.email_is_already_in_use', ['email' => $request->email]));
            }
        });
    }
}
