<?php

function fetch_jobs($search_query, $location) {
    $apiKey = 'e5548e4023msh9334b7417de87dap13c1bdjsncc574899c8d3';
    $apiHost = 'jobs-api14.p.rapidapi.com';

    // Parámetros de la solicitud
    $queryParams = http_build_query([
        'query' => $search_query,
        'location' => $location,
        'results_per_page' => 10
    ]);

    // URL de la API
    $url = "https://$apiHost/list?$queryParams";

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Key: $apiKey",
            "X-RapidAPI-Host: $apiHost"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
        return [];
    } else {
        $jobs = json_decode($response, true);
        if (isset($jobs['jobs'])) {
            return $jobs['jobs'];
        } else {
            echo "Error al obtener ofertas de empleo. Respuesta de la API: " . json_encode($jobs);
            return [];
        }
    }
}

function save_jobs_to_csv($jobs) {
    $file = fopen('job_list.csv', 'w');
    fputcsv($file, ['Job Title', 'Company', 'Location', 'URL']); // Encabezados del CSV

    foreach ($jobs as $job) {
        $title = $job['title'];
        $company = $job['company'];
        $location = $job['location'];
        $url = $job['jobProviders'][0]['url'] ?? ''; // Assuming the first job provider has the URL
        fputcsv($file, [$title, $company, $location, $url]);
    }

    fclose($file);
    echo "Archivo CSV actualizado con éxito.";
}

$search_query = 'developer';
$location = 'switzerland';
$jobs = fetch_jobs($search_query, $location);
if (!empty($jobs)) {
    save_jobs_to_csv($jobs);
}
?>
