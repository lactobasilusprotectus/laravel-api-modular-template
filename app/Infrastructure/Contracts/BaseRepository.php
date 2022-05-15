<?php

namespace App\Infrastructure\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepository
{
    /**
     * mengatur relasi antar tabel
     *
     * @param array $with
     * @return BaseRepository
     */
    public function with(array $with = []): BaseRepository;

    /**
     * Setel atribut withoutGlobalScopes ke true dan terapkan ke kueri.
     *
     * @return BaseRepository
     */
    public function withoutGlobalScopes(): BaseRepository;

    /**
     * Mencari data berdasarkan id
     *
     * @param string $id
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOneById(string $id): Model;

    /**
     * Mencari data berdasarkan criteria
     *
     * @param array $criteria
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOneBy(array $criteria): Model;

    /**
     * Mencari seluruh data
     *
     * @return LengthAwarePaginator
     */
    public function findByFilters(): LengthAwarePaginator;

    /**
     * mensimpan data ke database
     *
     * @param array $data
     * @return Model
     */
    public function store(array $data): Model;

    /**
     * Update data di database
     *
     * @param Model $model
     * @param array $data
     * @return Model
     */
    public function update(Model $model, array $data): Model;
}
