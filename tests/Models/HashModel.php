<?php

namespace Tests\Models;

use Veelasky\LaravelHashId\Contracts\HashesId;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class HashModel extends BasicModel implements HashesId
{
    use HashableId;
}
