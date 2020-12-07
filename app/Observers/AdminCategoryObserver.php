<?php

namespace App\Observers;

use App\Models\Admin\Category;

class AdminCategoryObserver // Паттерн
{
    public function creating(Category $category) // Мой метод, чтобы created работал до метода create
    {
        $this->setAlias($category);
    }

    public function setAlias(Category $category) // Автоматическое создание алиаса в новую категорию
    {
        if (empty($category->alias)) {
            $category->alias = \Str::slug($category->title, '-') . rand(11,99);
        }
    }

    public function created(Category $category) // Будет работать после метода create в CategoryController
    {
        //
    }

    public function updating(Category $category) // Мой метод
    {
        $this->setAlias($category);
    }

    public function updated(Category $category)
    {
        //
    }

    /**
     * Handle the category "deleted" event.
     *
     * @param  \App\Models\Admin\Category  $category
     * @return void
     */
    public function deleted(Category $category)
    {
        //
    }

    /**
     * Handle the category "restored" event.
     *
     * @param  \App\Models\Admin\Category  $category
     * @return void
     */
    public function restored(Category $category)
    {
        //
    }

    /**
     * Handle the category "force deleted" event.
     *
     * @param  \App\Models\Admin\Category  $category
     * @return void
     */
    public function forceDeleted(Category $category)
    {
        //
    }
}
