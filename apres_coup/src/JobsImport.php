<?php

class JobsImport
{
    private PDO $db;

    private string $file;

    public function __construct(PDO $db, string $file)
    {
        $this->file = $file;
        $this->db = $db;
    }

    public function parseXML(): int
    {
        $count = 0;
        $xml = simplexml_load_file($this->file);
        $prefix = $xml->offerUrlPrefix ? : '';

        foreach ($xml->item as $item) {
            $this->importJobs($item->ref, $item->title, $item->description, $prefix, $item->url, $item->company, $item->pubDate);
            $count++;
        }

        return $count;
    }

    public function parseJSON(): int
    {
        $count = 0;
        $json = file_get_contents($this->file);
        $json = json_decode($json);

        $prefix = $json->offerUrlPrefix ? : '';
        foreach ($json->item as $item) {
            $this->importJobs($item->reference, $item->title, $item->description, $prefix, $item->urlPath, $item->companyname, $item->pubDate);
            $count++;
        }
        return $count;
    }

    public function importJobs($reference, $title, $description, $prefix, $url, $company_name, $publication): bool
    {
        $date = New DateTime($publication);
        $sql = 'INSERT INTO job (reference, title, description, url, company_name, publication) VALUES (
                :reference, :title,:description,:url,:company_name, :publication)';
        $statement = $this->db->prepare($sql);
        $statement->execute(['reference' => $reference,'title'=>$title,'description'=>$description,'url'=>$prefix . $url, 'company_name'=> $company_name, 'publication' => $date->format("Y/m/d")]);
        return true;
    }

}
