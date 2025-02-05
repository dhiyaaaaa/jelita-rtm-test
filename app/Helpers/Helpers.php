<?php

// app/Helpers/Helpers.php

if (!function_exists('get_type')) {
    /**
     * Get the corresponding column name based on the type.
     *
     * @param string $type
     * @return string|null
     */
    function get_type(string $type)
    {
        return match ($type) {
            'prodi' => 'prodi_id',
            'fakultas' => 'fakultas_id',
            'universitas' => 'unit_id',
            'unit' => 'unit_id',
            default => abort(404),
        };
    }
}

if (!function_exists('get_type_model')) {
    /**
     * Get the column name and value based on the model's attributes.
     *
     * @param object $model
     * @return array
     */
    function get_type_model($model)
    {
        if ($model->prodi_id) {
            return ['kolom' => 'prodi_id', 'value' => $model->prodi_id, 'type' => 'prodi'];
        } elseif ($model->fakultas_id) {
            return ['kolom' => 'fakultas_id', 'value' => $model->fakultas_id, 'type' => 'fakultas'];
        } elseif ($model->unit_id) {
            return ['kolom' => 'unit_id', 'value' => $model->unit_id, 'type' => 'universitas'];
        } else {
            abort(404);
        }
    }
}
