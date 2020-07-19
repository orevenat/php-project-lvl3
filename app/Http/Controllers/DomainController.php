<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $domains = Db::table('domains')
            ->leftJoin('domain_checks', 'domains.id', '=', 'domain_checks.domain_id')
            ->orderBy('domains.id')
            ->orderByDesc('domain_checks.created_at')
            ->distinct('domains.id')
            ->get(['domains.id', 'domains.name', 'domain_checks.created_at as last_check']);

        return view('domain.index', compact('domains'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formData = $request->input('domain');
        $validatedData = Validator::make($formData, [
            'name' => 'required|url'
        ])->validate();

        $parsedUrl = parse_url($validatedData['name']);
        $host = "{$parsedUrl['scheme']}://{$parsedUrl['host']}";

        $domain = DB::table('domains')->where('name', $host)->first();

        if ($domain) {
            return redirect()->route('home')
                             ->with('error', 'Url already exists');
        }

        $id = DB::table('domains')->insertGetId(
            array_merge(
                $validatedData,
                [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            )
        );

        return redirect()->route('domains.show', $id)
                         ->with('success', 'Successfully created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $domain = DB::table('domains')->find($id);
        $domain_checks = DB::table('domain_checks')
                            ->where('domain_id', $domain->id)
                            ->get();

        return view('domain.show', compact('domain', 'domain_checks'));
    }
}