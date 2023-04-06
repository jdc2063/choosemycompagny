<?php

/************************************
Entry point of the project.
To be run from the command line.
************************************/

include_once(__DIR__ . '/utils.php');
include_once(__DIR__ . '/config.php');


printMessage("Starting...");

$PDO = new ConnectionDB(SQL_HOST, SQL_USER, SQL_PWD, SQL_DB);
$jobsLister = new JobsLister($PDO->getPDO());
$partners = $jobsLister->listPartner();

foreach($partners as $partner){
    $extension = pathinfo($partner['file'], PATHINFO_EXTENSION);
    $jobsImporter = new JobsImport($PDO->getPDO(), RESSOURCES_DIR . $partner['file'], $partner['id']);
    $jobsLister->cleanDatabaseJob($partner['id']);
    try {
         switch ($extension) {
            case 'xml' :
                $count = $jobsImporter->parseXML();
                break;
             case 'json':
                 $count = $jobsImporter->parseJSON();
                 break;
             default:
                 printMessage("L'extension ". $extension ." ne peux pas être traiter");
                 continue 2;
        }
    } catch (Exception $e) {
        $count = 0;
        printMessage("Une erreur est survenue");
    }
    printMessage("> {count} jobs imported.", ['{count}' => $count]);
}

/* list jobs */

$jobs = $jobsLister->listJobs();

printMessage("> all jobs ({count}):", ['{count}' => count($jobs)]);
foreach ($jobs as $job) {
    printMessage(" {id}: {reference} - {title} - {publication}", [
    	'{id}' => $job['id'],
    	'{reference}' => $job['reference'],
    	'{title}' => $job['title'],
    	'{publication}' => $job['publication']
    ]);
}


printMessage("Terminating...");
