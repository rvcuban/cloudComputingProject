<?php

function fetch_jobs($search_query, $location) {
    $apiKey = '3977c6abb9mshc9d89b41ce8c215p1f69a1jsn66e1ffec78d0';
    $apiHost = 'jobs-api14.p.rapidapi.com';

    $queryParams = http_build_query([
        'query' => $search_query,
        'location' => $location,
        'results_per_page' => 10
    ]);

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
    fputcsv($file, ['Job Title', 'Company', 'Location', 'URL']);

    foreach ($jobs as $job) {
        $title = $job['title'];
        $company = $job['company'];
        $location = $job['location'];
        $url = $job['jobProviders'][0]['url'] ?? '';
        fputcsv($file, [$title, $company, $location, $url]);
    }

    fclose($file);
    echo "Archivo CSV actualizado con éxito.";

    update_github_csv('job_list.csv');
}

function update_github_csv($filePath) {
    $githubToken =  getenv('CSV_TOKEN'); // Reemplaza con el nuevo token de GitHub
    $repoOwner = 'rvcuban';
    $repoName = 'cloudComputingProject';
    $filePathInRepo = 'job_list.csv'; // Ruta al archivo en el repositorio

    $fileContent = file_get_contents($filePath);
    $fileContentEncoded = base64_encode($fileContent);

    $url = "https://api.github.com/repos/$repoOwner/$repoName/contents/$filePathInRepo";

    $ch = curl_init($url);

    // Obtener el SHA del archivo existente
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'GitHub-API-Request');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: token $githubToken"
    ]);
    $existingFileResponse = json_decode(curl_exec($ch), true);

    if (isset($existingFileResponse['sha'])) {
        $sha = $existingFileResponse['sha'];
    } else {
        $sha = null;
    }

    // Actualizar el archivo en el repositorio
    $data = json_encode([
        "message" => "Updating job_list.csv",
        "content" => $fileContentEncoded,
        "sha" => $sha
    ]);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: token ' . $githubToken,
        'User-Agent: PHP-Script'
    ]);

    $response = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    if ($err) {
        echo "Error al actualizar el archivo en GitHub: " . $err;
    } else {
        echo "Archivo actualizado en GitHub: " . $response;
    }
}

// Fetch jobs and update the CSV
$jobs = fetch_jobs('php', 'New York');
save_jobs_to_csv($jobs);

?>

