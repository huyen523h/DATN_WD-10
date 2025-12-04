<?php

namespace App\Http\Requests\Admin\Operation;

use Illuminate\Validation\Rule;

class UpdateTourOperationRequest extends StoreTourOperationRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $operationId = $this->route('tour_operation');

        $rules['operation_code'] = [
            'required',
            'string',
            'max:100',
            Rule::unique('tour_operations', 'operation_code')->ignore($operationId),
        ];

        return $rules;
    }
}

