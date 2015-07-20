<?php

class favorite_model extends MY_Model
{
    public $table = 'favorite';

    public function favorite_list($where, $limit, $offset = 0) {
        $this->db->select("{$this->table}.job_id,{$this->table}.create_time as date,company.name as company,job.name as job,job.degree,job.salary");
        $this->db->from($this->table);
        $this->db->join('job', "job.id = {$this->table}.job_id");
        $this->db->join('company', "company.id = job.company_id");

        if (!empty($where)) {
            $this->db->where($where);
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('date', 'desc');
        return $this->db->get()->result_array();
    }
}
