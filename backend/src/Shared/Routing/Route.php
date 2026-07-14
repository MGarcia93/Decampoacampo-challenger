<?php
namespace App\Shared\Routing;

use App\Shared\Enums\MethodEnum;
use stdClass;
readonly class Route
{


    public function __construct(
        public string $path,
        public MethodEnum $method,
        public string $controller,
        public string $action,
        public ?stdClass $parameters = null
    ) {
    }

}