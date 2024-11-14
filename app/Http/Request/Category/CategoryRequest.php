<?php

namespace App\Http\Request\Category;

use Illuminate\Validation\Rule;
use App\Http\Request\BaseRequest;
use App\Models\Category;

class CategoryRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'id' => 'nullable|uuid|exists:categories,id',
            'name' => [
                'required',
                'string',
                Rule::unique((new Category())->getTable())->ignore($this->id ?? null)
            ],
            'description' => 'string|nullable',
        ];
    }
}
