<?php

class resume_model extends MY_Model
{
    public $table = 'resume';

    public function resume_list($where = array(), $limit, $offset)
    {
    	$this->db->select('create_time');
        $this->db->from($this->table);
        if (!empty($where)) {
            $this->db->where($where);
        }

        $this->db->order_by('create_time', 'desc');
	    $this->db->limit($limit);
	    $this->db->offset($offset);

        return $this->db->get()->result_array();
    }
}
