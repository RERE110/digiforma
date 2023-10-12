<?php

use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;
use Spatie\WebhookServer\WebhookCall;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;

header('Content-Type: application/json; charset=utf-8');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    $result = OpenAI::completions()->create([
        'model' => 'gpt-3.5-turbo',
        'prompt' => 'PHP is',
    ]);
    return $result['choices'][0]['text'];


    $body = '<pre>{"data":{"program":{"category":{"name":"Bureautique"},"costsInter":[{"cost":800.0}],"description":"Objectif de formation : À l’issue de cette formation, vous maîtriserez tous les outils pour exploiter avec efficacité et pertinence vos données sous Access.","durationInHours":14.0,"goals":[{"text":"Maîtriser lorganisation des données sous Access pour en faciliter lanalyse"},{"text":"Mettre au point des requêtes simples et complexes"},{"text":"Construire des états pour présenter les résultats"},{"text":"Importer et exporter des données"}],"id":"630217","image":{"url":"https://cdn.filestackcontent.com/joCL5sTNWjtfnnQXOkKQ"},"name":"Access - Niveau 2","steps":[{"substeps":[{"text":"Rappel sur les notions de base : champs, tables, relations"},{"text":"Méthodologie pour créer et optimiser un schéma relationnel"},{"text":"Créer une table de jonction pour gérer les relations \"plusieurs à plusieurs\""},{"text":"Identifier lintérêt de créer une relation \"un à un\""},{"text":"Définir les clés primaires"},{"text":"Contrôler la cohérence des données"}],"text":"Maîtriser lorganisation des données sous Access"},{"substeps":[{"text":"Rappel : requêtes sélection, regroupement, analyse croisée"},{"text":"Créer des requêtes basées sur des requêtes"},{"text":"Définir des jointures"},{"text":"Ajouter des formules de calcul"},{"text":"Mettre au point des requêtes paramétrées"},{"text":"Détecter les doublons, ou les différences entre tables"},{"text":"Manipuler des données par lot, créer dynamiquement une table : les requêtes Action"},{"text":"Réunir des données de plusieurs tables : les requêtes Union"},{"text":"Manipuler le langage SQL"}],"text":"Mettre au point des requêtes simples et complexes"},{"substeps":[{"text":"Créer et mettre en page un état"},{"text":"Trier et regrouper des données"},{"text":"Maîtriser le concept de section"},{"text":"Paramétrer les ruptures"},{"text":"Ajouter des formules de calculs"},{"text":"Insérer des graphiques, images"},{"text":"Éditer des étiquettes de publipostage"},{"text":"Construire des états élaborés : la notion de sous-état"}],"text":"Construire des états"},{"substeps":[{"text":"Importer/exporter des données dExcel, de fichiers txt, csv"},{"text":"Attacher des tables Access, des classeurs Excel"}],"text":"Importer et exporter des données"}]}}}</pre>';
    $new = str_replace("<pre>", "", $body);
    $new_new = str_replace("</pre>", "", $new);
    $json = json_decode($new_new, true);
    foreach ($json['data'] as $programm) {
        return $programm['name'];
    }

    $graphQLquery = '{"query": "query{ program(id:814337) {id name description costsInter{cost} goals {text} category{name} durationInHours steps{text substeps{text}} image{url}}} "}';
    $response = Http::withBody(
        $graphQLquery
    )->withHeaders([
        'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MzAwNjgsInR5cGUiOiJ1c2VyIiwibW9kZSI6ImFwaSIsImV4cCI6MjAxMTA3NjczMSwiaXNzIjoiRGlnaWZvcm1hIn0.jOrnMIf8ZpodiPF9QYChLbEuldW4AiJTMwaCi2gfnMc',
        'X-Second' => 'bar'
    ])->post('https://app.digiforma.com/api/v1/graphql/');

    $json = json_decode($response->body(), true);
    foreach ($json['data'] as $programm) {
        $my_post = array(
            'post_type' => 'lp_course',
            'post_title' => json_decode('"' . $programm['name'] . '"'),
            'post_content' => $programm['description'],
            'post_status' => 'publish',
            'meta_input' => array(
                '_lp_duration' => $programm['durationInHours'],
                '_lp_price' => $programm['costsInter'][0]['cost'],
                'digiforma_id' => $programm['id']
            )
        );
        return $programm['name'];
    }
    $client = Http::withBody(
        $response->getBody()->getContents()
    )->post('https://tender-payne.51-75-243-206.plesk.page/wp-json/uap/v2/uap-6087-6088');
});


