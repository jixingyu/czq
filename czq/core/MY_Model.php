<?php
class MY_Model extends CI_Model
{
    public $table;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get One Row By id
     */
    public function get_one($where)
    {
        $this->db->from($this->table);
        $this->db->where($where);
        return $this->db->get()->row_array();
    }

    /*
     * Get Lists
     */
    public function get_list($where = array(), $limit = false, $offset = false, $by = false, $sort = 'desc', $group_by = array(), $assoc = false)
    {
        $this->db->from($this->table);
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($by)) {
            $sort = ('asc' === strtolower(trim($sort))) ? 'asc' : 'desc';
            $this->db->order_by($by, $sort);
        }
        if ($limit) {
            $this->db->limit($limit);
        }
        if ($offset) {
            $this->db->offset($offset);
        }
        if (!empty($group_by)) {
            $this->db->group_by($group_by);
        }
        $result = $this->db->get()->result_array();
        if ($assoc) {
            $tmp = array();
            foreach ($result as $value) {
                $k = $value[$assoc];
                $tmp[$k] = $value;
            }
            $result = $tmp;
        }
        return $result;
    }

    /*
     * Get Lists
     */
    public function get_list_in($in_array, $key = 'id', $where = array(), $assoc = false)
    {
        $this->db->from($this->table);
        $this->db->where_in($key, $in_array);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $result = $this->db->get()->result_array();
        if ($assoc) {
            $tmp = array();
            foreach ($result as $value) {
                $k = $value[$assoc];
                $tmp[$k] = $value;
            }
            $result = $tmp;
        }

        return $result;
    }

    /*
     * Get Count
     */
    public function get_count($where = array())
    {
        $this->db->from($this->table);
        if (!empty($where)) {
            $this->db->where($where);
        }
        //$test = $this->db->count_all_results();
        //var_dump($this->db->last_query());
        //echo $test;
        return $this->db->count_all_results();
    }

    /*
     * Get Count
     */
    public function get_count_in($in_array, $key = 'id', $where = array())
    {
        $this->db->from($this->table);
        $this->db->where_in($key, $in_array);
        if (!empty($where)) {
            $this->db->where($where);
        }

        return $this->db->count_all_results();
    }

    public function find($value, $key = 'id')
    {
        $this->db->where($key, $value);
        $query = $this->db->get($this->table);

        return $query->row_array();
    }

    public function insert($set)
    {
        $this->db->insert($this->table, $set);

        return $this->db->insert_id();
    }

    public function insert_batch($set)
    {
        if (is_array($set) && !empty($set)) {
            $this->db->insert_batch($this->table, $set);
        }
    }

    public function update($set, $where)
    {
        if (is_numeric($where)) {
            $where = array('id' => $where);
        }
        $this->db->where($where);

        return $this->db->update($this->table, $set);
    }

    public function update_in($set, $in_array, $key = 'id', $where = array())
    {
        if (is_numeric($where)) {
            $where = array('id' => $where);
        }
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->where_in($key, $in_array);

        $this->db->update($this->table, $set);
    }

    public function update_batch($set, $key = 'id')
    {
        return $this->db->update_batch($this->table, $set, $key);
    }

    public function my_update_batch($set, $index, $where = NULL)
    {
        if (!empty($where)) {
            $this->db->where($where);
            $where = $this->db->ar_where;
            $this->db->ar_where = array();
        }

        $index = '`' . $index . '`';
        foreach ($set as &$values) {
            $tmp = array();
            foreach ($values as $key => $value) {
                $tmp["`{$key}`"] = "'{$value}'";
            }
            $values = $tmp;
        }

        for ($i = 0, $total = count($set); $i < $total; $i = $i + 100) {
            $set_piece = array_slice($set, $i, 100);

            $ids = array();
            $where = ($where != '' AND count($where) >=1) ? implode(" ", $where).' AND ' : '';

            foreach ($set_piece as $key => $val) {
                $ids[] = $val[$index];

                foreach (array_keys($val) as $field) {
                    if ($field != $index) {
                        if (strpos($field, ':') !== false) {
                            $final[str_replace(':', '', $field)][] =  'WHEN '.$index.' = '.$val[$index].' THEN '.$val[$field];
                        } else {
                            $final[$field][] =  'WHEN '.$index.' = '.$val[$index].' THEN '.$val[$field];
                        }
                    }
                }
            }

            $sql = "UPDATE ".$this->table." SET ";
            $cases = '';

            foreach ($final as $k => $v) {
                $cases .= $k.' = CASE '."\n";
                foreach ($v as $row) {
                    $cases .= $row."\n";
                }

                $cases .= 'ELSE '.$k.' END, ';
            }

            $sql .= substr($cases, 0, -2);

            $sql .= ' WHERE '.$where.$index.' IN ('.implode(',', $ids).')';

            $this->db->query($sql);
        }
    }

    public function set($key, $value = '', $escape = TRUE)
    {
        $this->db->set($key, $value, $escape);
    }

    public function delete($where)
    {
        if (is_numeric($where)) {
            $where = array('id' => $where);
        }
        $this->db->where($where);
        $this->db->delete($this->table);
    }

    public function delete_in($in_array, $key = 'id', $where = array())
    {
        if (is_numeric($where)) {
            $where = array('id' => $where);
        }
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->where_in($key, $in_array);

        $this->db->delete($this->table);
    }

    public function replace($set)
    {
        return $this->db->replace($this->table, $set);
    }
}
