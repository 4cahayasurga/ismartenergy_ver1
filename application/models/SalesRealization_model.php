<?php
class SalesRealization_model extends CI_Model {

    private $table = 'SalesRealization';

    public function insert_batch($data)
    {
        return $this->db->insert_batch($this->table, $data);
    }

}
?>