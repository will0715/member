<?php

namespace App\Http\Middleware;

use App\Repositories\CustomerRepository;
use App\Utils\CustomResponseUtil;
use App\Helpers\CustomerHelper;
use Illuminate\Support\Facades\Config;
use Closure;
use Poyi\PGSchema\Facades\PGSchema;
use Auth;

class SwitchCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // set customer name
        // $customer = Auth::guard('api')->user();
        $account = $request->header('X-Customer-Account');
        $customer = app(CustomerRepository::class)->getByAccount($account);

        if (empty($customer)) {
            abort(403, 'Customer name is required');
        }

        $dbSchemaName = $customer->getSchema();

        if (!PGSchema::schemaExists($dbSchemaName, 'pgsql')) {
            abort(403, 'Customer is not exist');
        }

        PGSchema::schema($dbSchemaName, 'pgsql');
        $request->merge(['_customerName' => $customer->name]);
        $request->merge(['_customer' => $customer]);
        CustomerHelper::setCustomer($customer);

        return $next($request);
    }
}
