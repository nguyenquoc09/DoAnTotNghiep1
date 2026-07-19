<?php

namespace App\Support;

trait GeneratesCode
{
    protected static function bootGeneratesCode()
    {
        static::created(function ($model) {
            $column = $model->getCodeColumn();
            if (!$model->{$column}) {
                $model->{$column} = $model->getCodePrefix() . str_pad((string) $model->getKey(), 6, '0', STR_PAD_LEFT);
                $model->saveQuietly();
            }
        });
    }

    abstract protected function getCodeColumn();
    abstract protected function getCodePrefix();
}
