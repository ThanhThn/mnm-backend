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
            'name' => [
                'required',
                'string',
                Rule::unique((new Category())->getTable())->ignore($this->route()->parameter('category')->id ?? null)
            ],
            'slug' => [
                'required',
                'string',
                Rule::unique((new Category())->getTable())->ignore($this->route()->parameter('category')->id ?? null)
            ],
            'description' => 'string',
        ];
    }
}
