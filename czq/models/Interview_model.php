<?php

class Interview_model extends MY_Model
{
    public $table = 'interview';

    public function interview_count($where) {
        $this->db->from($this->table);
        if (isset($where['user_id'])) {
            $this->db->join('apply', "apply.id = {$this->table}.apply_id");
        }
        if (!empty($where)) {
            $this->db->where($where);
        }

        return $this->db->count_all_results();
    }

    public function interview_list($where, $limit, $offset = 0) {
        $this->db->select("{$this->table}.address,apply.job_id,interview_time,company.name as company,job.name as job");
        $this->db->from($this->table);
        $this->db->join('apply', "apply.id = {$this->table}.apply_id");
        $this->db->join('job', "job.id = apply.job_id");
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

    public function interview_list_admin($where, $limit, $offset = 0) {
        $this->db->select("{$this->table}.apply_id,{$this->table}.address,{$this->table}.interview_time,member.email");
        $this->db->from($this->table);
        $this->db->join('apply', "apply.id = {$this->table}.apply_id");
        $this->db->join('member', "member.user_id = apply.user_id");

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
