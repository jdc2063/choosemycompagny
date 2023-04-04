<?php

class JobsImporter
{
    private PDO $db;

    private string $file;

    public function __construct(string $host, string $username, string $password, string $databaseName, string $file)
    {
        $this->file = $file;
        
        /* connect to DB */
        try {
            $this->db = new PDO('mysql:host=' . $host . ';dbname=' . $databaseName, $username, $password);
        } catch (Exception $e) {
            die('DB error: ' . $e->getMessage() . "\n");
        }
    }

    public function cleanDatabase(): bool
    {
        /* remove existing items */
        $this->db->exec('DELETE FROM job');
        return true;
    }

    public function importJobsXml(): int
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

    public function importJobsJson(): int
    {

        /* parse XML file */
        $json = file_get_contents($this->file);
        $json = json_decode($json);

        /* import each item */
        $count = 0;
        foreach ($json->offers as $item) {
            $date = DateTimeImmutable::createFromFormat('D M H:i:s e Y');
            $this->db->exec('INSERT INTO job (reference, title, description, url, company_name, publication) VALUES ('
                . '\'' . addslashes($item->reference) . '\', '
                . '\'' . addslashes($item->title) . '\', '
                . '\'' . addslashes($item->description) . '\', '
                . '\'' . addslashes($item->urlPath) . '\', '
                . '\'' . addslashes($item->companyname) . '\', '
                . '\'' . addslashes($date->format("Y\m\d")) . '\')'
            );
            $count++;
        }
        return $count;
    }
}
