<?php

class JobsImport
{
    private PDO $db;

    private string $file;

    private int $id_partner;

    public function __construct(PDO $db, string $file, int $id_partner)
    {
        $this->file = $file;
        $this->db = $db;
        $this->id_partner = $id_partner;
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
        foreach ($json->offers as $item) {
            $this->importJobs($item->reference, $item->title, $item->description, $prefix, $item->urlPath, $item->companyname, $item->pubDate);
            $count++;
        }
        return $count;
    }

    public function importJobs($reference, $title, $description, $prefix, $url, $company_name, $publication): bool
    {
        $date = New DateTime($publication);
        $sql = 'INSERT INTO job (reference, title, description, url, company_name, publication, id_partenaire) VALUES (
                :reference, :title,:description,:url,:company_name, :publication, :id_partenaire)';
        $statement = $this->db->prepare($sql);
        $statement->execute(['reference' => $reference,'title'=>$title,'description'=>$description,'url'=>$prefix . $url, 'company_name'=> $company_name, 'publication' => $date->format("Y/m/d"), 'id_partenaire'=> $this->id_partner]);
        return true;
    }

}
