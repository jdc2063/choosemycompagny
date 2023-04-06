<?php

class JobsLister
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function listJobs(): array
    {
        $jobs = $this->db->query('SELECT id, reference, title, description, url, company_name, publication FROM job')->fetchAll(PDO::FETCH_ASSOC);

        return $jobs;
    }

    public function listPartner(int $id_partner = 0): array
    {
        $condition = $id_partner != 0 ? " WHERE id = " . $id_partner : '';
        print_r('SELECT id, name, file FROM partner '.$condition);
        $jobs = $this->db->query('SELECT id, name, file FROM partner '.$condition)->fetchAll(PDO::FETCH_ASSOC);
        return $jobs;
    }

    public function listNomenclature(int $id_partner): array
    {
        $jobs = $this->db->query('SELECT reference, title, description, prefix_url, url, company_name, publication FROM nomenclature WHERE id = '.$id_partner )->fetchAll(PDO::FETCH_ASSOC);

        return $jobs;
    }
}
