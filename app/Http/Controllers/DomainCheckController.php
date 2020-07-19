<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DomainCheckController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $domain = DB::table('domains')->find($request->route('domain'));

        $response = Http::get($domain->name);

        DB::table('domain_checks')->insert(
            [
                'domain_id' => $domain->id,
                'status_code' => $response->status(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        return redirect()->route('domains.show', $domain->id)
                         ->with('info', 'Website has been checked!');
    }
}
