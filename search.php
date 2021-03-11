<!DOCTYPE HTML>
    
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <title> Wyszukiwarka </title>
    <meta name="description" content="Wyszukiwarka!" />
    <meta name="keywords" content="elastic,search,baza,danych, wyszukiwarka" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    
    <link rel="stylesheet" href="style.css" type="text/css" />
    <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
</head>
    
<body>
        
<div id="container">

<?php

    use Elasticsearch\ClientBuilder;

    require 'vendor/autoload.php';

    $hosts = [
        'host' => 'localhost',
        'port' => '9200',
        'scheme' => 'https',
        'user' => 'username',
        'pass' => 'password1'
    ];

    $client = ClientBuilder::create()->setHosts($hosts)->build();

    $search_value = $_POST['query'];
    $words = explode(" ", $search_value);

    if(isset($_POST['query']))
    {
           $q = $client->search([  
            'index'=>'movies_v2',
            'type'=>'_doc',
            'body' => [
                'size' => 10,
                "query" => [
                    "bool" => [
                      "must" => [
                        "multi_match" => [
                          "query" => $search_value,
                          "type" => "bool_prefix",
                          "operator" => "and",
                          "fields" => [
                            "title"
                            ]
                        ]
                      ],
                      "should" => [
                        [
                          "span_first" => [
                            "match" => [
                              "span_term" => [
                                "title._index_prefix" => strtolower($words[0])
                              ]
                              ],
                            "end" => 1
                          ]
                        ]
                      ]
                    ]
                  ]
            ]
        ]);
                            
        $output = '';
        
        $res = $q['hits']['hits'];
        $ile = count($res);

        if($ile >= 1)
        {       
            for ($i=0; $i<$ile; $i++)
            {
                $output .= '<div class="field">';
                if (!empty($res[$i]['_source']['poster']))
                {
                    $output .= '<div class="poster"> <img src="'.$res[$i]['_source']['poster'].'" width="200" height="285"></img></div>';
                }
                else
                {
                    $output .= '<div class="poster"><img src="default_poster.jpg" width="200" height="285"></img></div>';
                }
                
                $output .= '<div class="title">'.$res[$i]['_source']['title'].'</div>';
                $output .= '<br/>';

                if (!empty($res[$i]['_source']['directors']))
                {
                    $output .= '<div class="director"> Reżyser: '.$res[$i]['_source']['directors'][0]['name'].'</div>';
                }
                else
                {
                    $output .= '<div class="director"> Reżyser: brak danych</div>';
                }
                $output .= '<div class="year"> Rok: '.$res[$i]['_source']['year'].'</div>';
                $output .= '<div class="rate">'.$res[$i]['_source']['rate'].'&#9733; </div>';
                
                $output .='</div>';
            }
        }
          
        echo $output;   
}

?>

</div>
</body>

</html>