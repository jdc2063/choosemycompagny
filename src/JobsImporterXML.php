<?php

class JobsImporterXML
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
        foreach ($xml->item as $item) {
            printMessage($item->pubDate);
            $this->db->exec('INSERT INTO job (reference, title, description, url, company_name, publication) VALUES ('
                . '\'' . addslashes($item->ref) . '\', '
                . '\'' . addslashes($item->title) . '\', '
                . '\'' . addslashes($item->description) . '\', '
                . '\'' . addslashes($item->url) . '\', '
                . '\'' . addslashes($item->company) . '\', '
                . '\'' . addslashes($item->pubDate) . '\')'
            );
            $count++;
        }
        return $count;
    }

}
