<?php

class Job_model extends MY_Model
{
    public $table = 'job';

    public function search_count($where)
    {
        $this->db->from($this->table);
        if (!empty($where['q'])) {
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
        $this->db->select($this->table . '.*,company.name as c_name,company.description as c_description,company.address as c_address,company.industry as c_industry,company.number as c_number');
        $this->db->from($this->table);
        $this->db->join('company', "company.id = {$this->table}.company_id", 'left');
        if (!empty($where['q'])) {
            $this->db->like('job.name', $where['q']);
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
