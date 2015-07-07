<?php

class Apply_model extends MY_Model
{
    public $table = 'apply';

    public function apply_list($where, $limit, $offset = 0) {
        $this->db->select("{$this->table}.job_id,{$this->table}.create_time,company.name as company,job.name as job");
        $this->db->from($this->table);
        $this->db->join('job', "job.id = {$this->table}.job_id");
        $this->db->join('company', "company.id = job.company_id");

        if (!empty($where)) {
            $this->db->where($where);
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('create_time', 'desc');
        return $this->db->get()->result_array();
    }

    public function apply_list_admin($where, $limit, $offset = 0) {
        $this->db->select("{$this->table}.id,{$this->table}.job_id,{$this->table}.create_time,{$this->table}.status,company.name as company,resume.real_name,resume.mobile,job.name as job,interview.address,interview.interview_time");
        $this->db->from($this->table);
        $this->db->join('interview', "interview.apply_id = {$this->table}.id");
        $this->db->join('resume', "resume.id = {$this->table}.resume_id");
        $this->db->join('job', "job.id = {$this->table}.job_id");
        $this->db->join('company', "company.id = job.company_id");

        if (!empty($where)) {
            $this->db->where($where);
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('create_time', 'desc');
        return $this->db->get()->result_array();
    }
}
