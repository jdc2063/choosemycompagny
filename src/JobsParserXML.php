<?php

class JobsParserXML
{
    private PDO $db;

    private string $file;

    public function __construct(PDO $db, string $file)
    {
        $this->file = $file;
        $this->db = $db;
    }

    public function cleanDatabaseJob(int $id_partner): bool
    {
        /* remove existing items */
        $this->db->exec('DELETE FROM job WHERE id_partenaire = ' . $id_partner);
        return true;
    }

    public function importJobs(): int
    {
        /* parse XML file */
        $xml = simplexml_load_file($this->file);

        /* import each item */
        $count = 0;
        $sql = 'INSERT INTO job (reference, title, description, url, company_name, publication) VALUES (
                :reference, :title,:description,:url,:company_name, :publication)';
        $prefix = $xml->offerUrlPrefix ? : '';
        foreach ($xml->item as $item) {
            $date = New DateTime($item->pubDate);
            $statement = $this->db->prepare($sql);
            $statement->execute(['reference' => $item->ref,'title'=>$item->title,'description'=>$item->description,'url'=>$prefix.$item->url, 'company_name'=> $item->company, 'publication' => $date->format("Y/m/d")]);
            $count++;
        }
        return $count;
    }

}
