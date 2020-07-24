<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $domains = app('db')->table('domains')
            ->leftJoin('domain_checks', 'domains.id', '=', 'domain_checks.domain_id')
            ->orderBy('domains.id')
            ->orderByDesc('domain_checks.created_at')
            ->distinct('domains.id')
            ->get(['domains.id', 'domains.name', 'domain_checks.created_at as last_check', 'domain_checks.status_code']);

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
        $validator = app('validator')->make($formData, [
            'name' => 'required|url|max:255'
        ]);

        if ($validator->fails()) {
            flash('Not a valid url')->error();

            return redirect()->route('home')
                             ->withErrors($validator)
                             ->withInput();
        }

        $parsedUrl = parse_url($formData['name']);
        $host = "{$parsedUrl['scheme']}://{$parsedUrl['host']}";

        $domain = app('db')->table('domains')->where('name', $host)->first();

        if ($domain) {
            $id = $domain->id;
            flash('Url already exists');
        } else {
            $id = app('db')->table('domains')->insertGetId(
                array_merge(
                    [
                        'name' => $host,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]
                )
            );

            flash('Successfully created!');
        }

        return redirect()->route('domains.show', $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $domain = app('db')->table('domains')->find($id);
        $domain_checks = app('db')->table('domain_checks')
                            ->where('domain_id', $domain->id)
                            ->get();

        return view('domain.show', compact('domain', 'domain_checks'));
    }
}
