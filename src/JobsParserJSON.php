<?php

class JobsParserJSON
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

    /**
     * @throws Exception
     */
    public function importJobs(): int
    {

        /* parse XML file */
        $json = file_get_contents($this->file);
        $json = json_decode($json);

        /* import each item */
        $count = 0;
        $sql = 'INSERT INTO job (reference, title, description, url, company_name, publication) VALUES (
                :reference, :title,:description,:url,:company_name, :publication)';
        $prefix = $json->offerUrlPrefix ? : '';
        foreach ($json->offers as $item) {
            $date = New DateTime($item->publishedDate);
            $statement = $this->db->prepare($sql);
            $statement->execute(['reference' => $item->reference,'title'=>$item->title,'description'=>$item->description,'url'=>$prefix.$item->urlPath, 'company_name'=> $item->companyname, 'publication' => $date->format("Y/m/d")]);
            $count++;
        }
        return $count;
    }
}
