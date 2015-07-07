<?php

class Interview_model extends MY_Model
{
    public $table = 'interview';

    public function interview_list($where, $limit, $offset = 0) {
        $this->db->select("{$this->table}.address,apply.job_id,interview_time,company.name as company,job.name as job");
        $this->db->from($this->table);
        $this->db->join('apply', "apply.id = {$this->table}.apply_id");
        $this->db->join('job', "job.id = {$this->table}.job_id");
        $this->db->join('company', "company.id = job.company_id");

        if (!empty($where)) {
            $this->db->where($where);
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('interview_time', 'desc');
        return $this->db->get()->result_array();
    }
}
