<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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


        DB::table('domain_checks')->insert(
            [
                'domain_id' => $domain->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        return redirect()->route('domains.show', $domain->id)
                         ->with('info', 'Website has been checked!');
    }
}
