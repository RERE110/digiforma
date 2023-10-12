<?php

namespace App\Console\Commands;

use App\Models\Formation;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Spatie\WebhookServer\WebhookCall;
use Illuminate\Support\Facades\Http;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $formations = Formation::where('in_wordpress','=',0)->get();

        foreach ($formations as $formation)
        {
            sleep(5);
            $id = $formation->internal_id;
            $graphQLqueryProgram = '{"query": "query{ program(id:'.$id.') {id name description costsInter{cost} goals {text} category{name} durationInHours steps{text substeps{text}} image{url}}} "}';
            $response = Http::withBody(
                $graphQLqueryProgram
            )->withHeaders([
              'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MzAwNjgsInR5cGUiOiJ1c2VyIiwibW9kZSI6ImFwaSIsImV4cCI6MjAxMTA3NjczMSwiaXNzIjoiRGlnaWZvcm1hIn0.jOrnMIf8ZpodiPF9QYChLbEuldW4AiJTMwaCi2gfnMc',
                'X-Second' => 'bar'
            ])->post('https://app.digiforma.com/api/v1/graphql/');
            $send = Http::withBody(
                $response->getBody()->getContents()
            )->post('https://ascent-formation.fr/wp-json/uap/v2/uap-6087-6088');
        }

        //$graphQLquery = '{"query": "query{ programs { id }}"}';
        //$response = Http::withBody(
          //  $graphQLquery
        //)->withHeaders([
          //  'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MzAwNjgsInR5cGUiOiJ1c2VyIiwibW9kZSI6ImFwaSIsImV4cCI6MjAxMTA3NjczMSwiaXNzIjoiRGlnaWZvcm1hIn0.jOrnMIf8ZpodiPF9QYChLbEuldW4AiJTMwaCi2gfnMc',
            //'X-Second' => 'bar'
        //])->post('https://app.digiforma.com/api/v1/graphql/');
        //$programms = json_decode($response->body(), true);
        //foreach ($programms as $programm) {
          //  foreach ($programm as $item) {
            //    foreach ($item as $ite) {
              //      $formation = Formation::firstOrCreate([
                //        'internal_id' => $ite['id']
                  //  ]);
                    //sleep(5);
                    //$id = $ite['id'];
                    //$graphQLqueryProgram = '{"query": "query{ program(id:'.$id.') {id name description costsInter{cost} goals {text} category{name} durationInHours steps{text substeps{text}} image{url}}} "}';
                    //$response = Http::withBody(
                    //    $graphQLqueryProgram
                    //)->withHeaders([
                    //  'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MzAwNjgsInR5cGUiOiJ1c2VyIiwibW9kZSI6ImFwaSIsImV4cCI6MjAxMTA3NjczMSwiaXNzIjoiRGlnaWZvcm1hIn0.jOrnMIf8ZpodiPF9QYChLbEuldW4AiJTMwaCi2gfnMc',
                    //    'X-Second' => 'bar'
                    //])->post('https://app.digiforma.com/api/v1/graphql/');
                    //$send = Http::withBody(
                    //    $response->getBody()->getContents()
                    //)->post('https://tender-payne.51-75-243-206.plesk.page/wp-json/uap/v2/uap-6087-6088');
              //  }
            //}
        //}
    }
}
