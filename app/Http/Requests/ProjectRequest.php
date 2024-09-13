<?php

namespace App\Http\Requests;

use App\Http\Utils;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class ProjectRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'string|max:255',
            'value' => 'required|integer',
            'type' => 'required|in:project,activity',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre del proyecto es obligatorio',
            'name.string' => 'El nombre del proyecto debe ser de tipo string',
            'name.max' => 'El nombre del proyecto debe tener un tamaño máximo de 255 caracteres',
            'description.required' => 'La descripción del proyecto es obligatoria',
            'description.string' => 'La descripción del proyecto debe ser de tipo string',
            'description.max' => 'La descripción del proyecto debe tener un tamaño máximo de 255 caracteres',
            'value.required' => 'El valor del proyecto es obligatorio',
            'value.decimal' => 'El valor del proyecto debe ser de tipo decimal',
            'type.required' => 'El tipo del proyecto es obligatorio',
            'type.enum' => 'El tipo del proyecto debe ser de tipo project o activity',
        ];
    }

    protected function failedValidation(Validator $validator){
        $errors = $validator->errors()->all();
        $errorMessage = 'Error, no se pudo guardar en el sistema. Intente nuevamente. '. implode(', ', $errors);
        $response = Utils::responseJson(
            Response::HTTP_BAD_REQUEST,
            $errorMessage,
            [],
            Response::HTTP_OK
        );
        throw new HttpResponseException($response);

    }
}
