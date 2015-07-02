<?php

class Job_model extends MY_Model
{
    public $table = 'job';

    public function search_count($where)
    {
        $this->db->from($this->table);
        if ($where['q']) {
            $this->db->like('job.name', $where['q']);
            $this->db->join('company', "company.id = {$this->table}.company_id", 'left');
            $this->db->like('company.name', $where['q']);
            unset($where['q']);
        }

        if (!empty($where)) {
            $this->db->where($where);
        }

        return $this->db->count_all_results();
    }

    public function search_jobs($where, $limit, $offset = 0)
    {
        $this->db->from($this->table);
        if ($where['q']) {
            $this->db->like('job.name', $where['q']);
            $this->db->join('company', "company.id = {$this->table}.company_id", 'left');
            $this->db->like('company.name', $where['q']);
            unset($where['q']);
        }

        if (!empty($where)) {
            $this->db->where($where);
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result_array();
    }
}
