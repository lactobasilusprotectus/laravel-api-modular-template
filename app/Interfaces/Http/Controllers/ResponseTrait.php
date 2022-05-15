<?php

namespace App\Interfaces\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    protected string $resourceItem;

    protected string $resourceCollection;

    protected function respondWithCustomData($data, $status = 200): JsonResponse
    {
        return new JsonResponse([
           'data' => $data,
            'meta' => ['timestamp' => $this->getTimestampInMilliseconds()]
        ], $status);
    }

    protected function getTimestampInMilliseconds(): int
    {
        return intdiv((int)now()->format('Uu'), 1000);
    }

    protected function respondWithNoContent(): JsonResponse
    {
        return new JsonResponse([
            'data' => null,
            'meta' => ['timestamp' => $this->getTimestampInMilliseconds()]
        ], Response::HTTP_NO_CONTENT);
    }

    protected function respondWithCollection(LengthAwarePaginator $paginator)
    {
        return (new $this->resourceCollection($paginator))->additional(
            ['meta' => ['timestamp' => $this->getTimestampInMilliseconds()]]
        );
    }

    protected function respondWithItem(Model $item)
    {
        return (new $this->resourceItem($item))->additional(
            ['meta' => ['timestamp' => $this->getTimestampInMilliseconds()]]
        );
    }
}
