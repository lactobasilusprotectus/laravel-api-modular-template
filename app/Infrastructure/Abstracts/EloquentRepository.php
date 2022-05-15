<?php

namespace App\Infrastructure\Abstracts;

use App\Infrastructure\Contracts\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;

class EloquentRepository implements BaseRepository
{
    /**
     * @var bool
     */
    protected bool $withoutGlobalScope = false;

    /**
     * @var array
     */
    protected array $with = [];

    /**
     * @param Model $model
     */
    public function __construct(public Model $model){}

    /**
     * @inheritDoc
     */
    public function with(array $with = []): BaseRepository
    {
        $this->with = $with;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withoutGlobalScopes(): BaseRepository
    {
        $this->withoutGlobalScope = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function findOneById(string $id): Model
    {
        if (!Uuid::isValid($id)){
            throw (new ModelNotFoundException())->setModel(get_class($this->model));
        }

        if (!empty($this->with) || auth()->check()){
            return $this->findOneBy(compact('id'));
        }

        return Cache::remember($id, now()->addHour(), function () use ($id){
           return $this->findOneBy(compact('id'));
        });
    }

    /**
     * @inheritDoc
     */
    public function findOneBy(array $criteria): Model
    {
        if (!$this->withoutGlobalScope){
            return $this->model->with($this->with)
                ->where($criteria)
                ->orderByDesc('created_at')
                ->firstOrFail();
        }

        return $this->model->with($this->with)
            ->where($criteria)
            ->orderByDesc('created_at')
            ->firstOrFail();
    }

    /**
     * @inheritDoc
     */
    public function findByFilters(): LengthAwarePaginator
    {
       return $this->model->with($this->with)->paginate();
    }

    /**
     * @inheritDoc
     */
    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * @inheritDoc
     */
    public function update(Model $model, array $data): Model
    {
        return tap($model)->update($data);
    }
}
