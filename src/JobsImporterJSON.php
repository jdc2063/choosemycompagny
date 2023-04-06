<?php

class JobsImporterJSON
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
        foreach ($json->offers as $item) {
            $date = New DateTime($item->publishedDate);
            $this->db->exec('INSERT INTO job (reference, title, description, url, company_name, publication) VALUES ('
                . '\'' . addslashes($item->reference) . '\', '
                . '\'' . addslashes($item->title) . '\', '
                . '\'' . addslashes($item->description) . '\', '
                . '\'' . addslashes($item->urlPath) . '\', '
                . '\'' . addslashes($item->companyname) . '\', '
                . '\'' . addslashes($date->format("Y/m/d")) . '\')'
            );
            $count++;
        }
        return $count;
    }
}
