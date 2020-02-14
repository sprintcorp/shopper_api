<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @param Category $category
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'user_id' => (int)$category->id,
            'title' => (string)$category->name,
            'description' => (string)$category->description,
            'created_at' => (string)$category->created_at,
            'updated_at' => (string)$category->updated_at,
            'deleted_at' => isset($category->deleted_at)?(string)$category->deleted_at:null,
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes = [
            'user_id' => 'id',
            'title' => 'title',
            'description' => 'description',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
