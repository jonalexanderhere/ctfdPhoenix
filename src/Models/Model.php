<?php

namespace CTFd\Models;

use CTFd\Database;

/**
 * Base Model Class
 */
abstract class Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $attributes = [];
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }
    
    /**
     * Get all records
     */
    public static function all()
    {
        $model = new static();
        $db = Database::getInstance();
        $sql = "SELECT * FROM {$model->table}";
        return $db->fetchAll($sql);
    }
    
    /**
     * Find by ID
     */
    public static function find($id)
    {
        $model = new static();
        $db = Database::getInstance();
        $sql = "SELECT * FROM {$model->table} WHERE {$model->primaryKey} = :id";
        $result = $db->fetchOne($sql, ['id' => $id]);
        
        if ($result) {
            $model->attributes = $result;
            return $model;
        }
        
        return null;
    }
    
    /**
     * Find by condition
     */
    public static function where($column, $operator, $value = null)
    {
        $model = new static();
        
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $db = Database::getInstance();
        $sql = "SELECT * FROM {$model->table} WHERE {$column} {$operator} :value";
        $results = $db->fetchAll($sql, ['value' => $value]);
        
        return array_map(function($row) use ($model) {
            $instance = new static();
            $instance->attributes = $row;
            return $instance;
        }, $results);
    }
    
    /**
     * Create new record
     */
    public function save()
    {
        $db = Database::getInstance();
        
        if (isset($this->attributes[$this->primaryKey])) {
            // Update
            $this->update();
        } else {
            // Insert
            $this->insert();
        }
    }
    
    /**
     * Insert new record
     */
    protected function insert()
    {
        $db = Database::getInstance();
        $columns = array_keys($this->attributes);
        $values = array_values($this->attributes);
        $placeholders = array_map(function($col) { return ":{$col}"; }, $columns);
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $params = array_combine($placeholders, $values);
        $db->query($sql, $params);
        
        $this->attributes[$this->primaryKey] = $db->getConnection()->lastInsertId();
    }
    
    /**
     * Update record
     */
    protected function update()
    {
        $db = Database::getInstance();
        $id = $this->attributes[$this->primaryKey];
        unset($this->attributes[$this->primaryKey]);
        
        $sets = [];
        foreach ($this->attributes as $key => $value) {
            $sets[] = "{$key} = :{$key}";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . 
               " WHERE {$this->primaryKey} = :id";
        
        $params = array_merge($this->attributes, ['id' => $id]);
        $db->query($sql, $params);
    }
    
    /**
     * Delete record
     */
    public function delete()
    {
        $db = Database::getInstance();
        $id = $this->attributes[$this->primaryKey] ?? null;
        
        if ($id) {
            $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
            $db->query($sql, ['id' => $id]);
        }
    }
    
    /**
     * Get attribute
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }
    
    /**
     * Set attribute
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }
    
    /**
     * Check if attribute exists
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }
    
    /**
     * Convert to array
     */
    public function toArray()
    {
        return $this->attributes;
    }
}

