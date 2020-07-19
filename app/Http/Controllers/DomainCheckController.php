<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use DiDom\Document;

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
        $currentTime = Carbon::now();

        $response = Http::get($domain->name);
        if ($response->status() == 200) {
            $content = $response->getBody()->getContents();
            $document = new Document();
            $document->loadHtml($content);

            if ($document->has('h1')) {
                $h1 = $document->first('h1')->text();
            }

            if ($document->has('meta[name=description]')) {
                $description = $document->first('meta[name=description]')
                                        ->getAttribute('content');
            }

            if ($document->has('meta[name=keywords]')) {
                $keywords = $document->first('meta[name=keywords]')
                                     ->getAttribute('content');
            }
        }


        DB::table('domain_checks')->insert(
            [
                'domain_id' => $domain->id,
                'status_code' => $response->status(),
                'h1' => $h1 ?? null,
                'description' => $description ?? null,
                'keywords' => $keywords ?? null,
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ]
        );

        return redirect()->route('domains.show', $domain->id)
                         ->with('info', 'Website has been checked!');
    }
}
