<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Fideloper\Proxy\TrustProxies as BaseTrustProxies;

class TrustProxies extends BaseTrustProxies
{
    protected $proxies;

    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
