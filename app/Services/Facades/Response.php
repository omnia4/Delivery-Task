<?php 

namespace App\Services\Facades;

use App\Services\Response as ResponseService;
use Illuminate\Support\Facades\Facade;

class Response extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ResponseService::class;
    }
}