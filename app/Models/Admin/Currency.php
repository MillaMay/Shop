<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    // public $timestamps = false; Это нужно было до миграции update данной таблицы

    protected $fillable = [
        'title',
        'code',
        'symbol_left',
        'symbol_right',
        'value',
        'base',
        'created_at', // добавила после миграции update
        'updated_at', // добавила после миграции update
    ];

    public function scopeByCode($query, $code) // теперь где нужно можно использовать к Currency byCode
    {
        return $query->where('code', $code);
    }

    public function isBase()
    {
        return $this->base == 1;
    }
}
