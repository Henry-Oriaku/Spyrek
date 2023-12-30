<?php

namespace Spyrek\Core;

trait Model
{
    use Database;
    protected $limit        = 10;
    protected $offset       = 0;
    protected $order_type   = "desc";
    protected $order_column = "sn";
    /** Data Format error holder
     * eg. Invalid email...
     */
    public $errors       = [];


    function findAll()
    {
        $query = "SELECT * FROM $this->table ORDER BY $this->order_column $this->order_type  LIMIT $this->limit offset $this->offset";

        return $this->query($query);
    }

    /**
     *  This select a row from the table where the values match the condition
     * @param array $data An array containing the items to match eg. ['name' => 'henry]
     * @param array $data_not An array containing the items not to match eg. ['name' => 'henry]
     *  */
    function where($data, $data_not = [])
    {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);

        $query = "SELECT * FROM $this->table where ";
        //get ths 
        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . " && ";
        }
        foreach ($keys_not as $key) {
            $query .= $key . " != :" . $key . " && ";
        }

        $query = trim($query, " && ");

        $query .= " ORDER BY $this->order_column $this->order_type  LIMIT $this->limit offset $this->offset";

        //merge the two data
        $data = array_merge($data, $data_not);

        return $this->query($query, $data);
    }

    /** 
     * Returns the first occurence of th value
     * @param array $data An array containing the items to match eg. ['name' => 'henry]
     * @param array $data_not An array containing the items not to match eg. ['name' => 'henry]
     *  */
    function first($data, $data_not = [])
    {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);

        $query = "SELECT * FROM $this->table where ";
        //get ths 
        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . " && ";
        }
        foreach ($keys_not as $key) {
            $query .= $key . " != :" . $key . " && ";
        }

        $query = trim($query, " && ");

        $query .= " limit $this->limit offset $this->offset";

        //merge the two data
        $data = array_merge($data, $data_not);

        $result = $this->query($query, $data);

        if ($result)
            return $result[0];

        return false;
    }

    /**
     * This inserts a data to the table
     * @param array $data An array containing the items to insert eg. ['name' => 'henry]
     *  */
    public function insert($data)
    {
        /** Remove unwanted data */
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }
        $keys = array_keys($data);

        $query = "INSERT INTO $this->table (" . implode(' ,', $keys) . ") VALUES (:" . implode(' , :', $keys) . ")";
        $this->query($query, $data);

        return true;
    }

    /**
     * This updates the table
     * @param string $id The id to match
     * @param string $id_column The column to check on
     * @param array $data The datas eg ['name' => 'henry']
     */
    public function update($id, $data, $id_column = 'id')
    {
        /** Remove unwanted data */
        if (!empty($this->allowedColumns)) {

            foreach ($data as $key) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }
        $query = "UPDATE $this->table SET ";

        $keys = array_keys($data);

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . " , ";
        }

        $query = trim($query, ' , '); //remove extra comma
        $query .= " WHERE $id_column = :$id_column";

        $data[$id_column] = $id;

        $this->query($query, $data);

        return false;
    }

    /**
     *  This deletes a row from the table
     * @param string $id The Id to find and delete
     * @param string $id_column The name of the column to find and delete
     *  */
    public function delete($id, $id_column = 'id')
    {
        $data[$id_column] = $id;
        $query = "DELETE FROM $this->table where $id_column = :$id_column";

        $this->query($query, $data);
    }
}
