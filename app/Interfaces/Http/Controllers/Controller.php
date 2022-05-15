<?php

namespace App\Interfaces\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use  Illuminate\Routing\Controller as BaseController;
use JetBrains\PhpStorm\ArrayShape;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ResponseTrait;

    /**
     * Get map of resource methods to ability names.
     *
     * @return array
     */
    #[ArrayShape(['index' => "string", 'show' => "string", 'create' => "string", 'store' => "string", 'edit' => "string", 'update' => "string", 'destroy' => "string"])]
    protected function resourceAbilityMap(): array
    {
        return [
            'index' => 'viewAny',
            'show' => 'view',
            'create' => 'create',
            'store' => 'store',
            'edit' => 'edit',
            'update' => 'update',
            'destroy' => 'destroy'
        ];
    }
}
