<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as DB;

class MY_Model {
    public mixed $table     = null;
    public string $select   = '*';
    public DB $database;
    function __construct($table = null) {
        $this->database = new DB;
        $this->table = $table;
    }
    public function setTable($table = NULL): static {
        $this->table = $table;
        return $this;
    }
    public function getTable() {
        return $this->table;
    }
    public function query($sql = '') {
        if(empty($sql)) return false;
        return DB::select($sql);
    }
    public function get_data($args = [], $module = '') {
        return apply_filters('get_data_'.$module, $this->get(Qr::convert($args)), $args);
    }
    public function gets_data($args = [], $module = '') {
        return apply_filters('gets_data_'.$module, $this->gets(Qr::convert($args)), $args);
    }
    public function count_data($args =  [], $module = '') {
        return apply_filters('count_data_'.$module, $this->count(Qr::convert($args)), $args);
    }
    static function setData(Qr $args, $query = null, $table = null): bool|\Illuminate\Database\Query\Builder {

        $DB = ($table != null) ? DB::table($table) : $query;

        if(!empty($table) && !empty($args->metaQuery)) {
            $metaQuery = $args->metaQuery;
            $relation 	= 'and';
            if(!empty($metaQuery['relation'])) {
                $relation = strtolower($metaQuery['relation']); unset($metaQuery['relation']);
            }
            if($relation != 'and' || $relation != 'or') $relation = 'and';
            $tableMetaBox  = CLE_PREFIX. $table.'_metadata';
            $sql = 'SELECT `'.$tableMetaBox.'`.`object_id` FROM `'.$tableMetaBox.'`';
            foreach ($metaQuery as $key => $data) {
                $sql .= ' INNER JOIN `'.$tableMetaBox.'` AS mt'.$key.' ON ( `'.$tableMetaBox.'`.id = mt'.$key.'.id ) ';
            }
            $sql .= 'WHERE 1=1 AND ';
            $sql .= ' (( ';
            foreach ($metaQuery as $key => $data) {
                $mt = 'mt'.$key.'.';
                $sql .= '(';
                $sql .= (!empty($data['key'])) ? $mt.'`meta_key` = \''.$data['key'].'\'' : '';
                $compare = (!empty($data['compare'])) ? strtolower($data['compare']) : '=';
                if(!empty($data['value'])) {
                    if(!empty($data['key'])) $sql .= ' and ';
                    if($compare == '=' || $compare == '!=' || $compare == '>' || $compare == '<' || $compare == '>=' || $compare == '<=') {
                        $compare = ($compare == '==') ? '' : ' '.$compare ;
                        $sql .= $mt.'`meta_value`'.$compare.' \''.$data['value'].'\'';
                    }
                    else if( $compare == 'like' || $compare == 'not like') {
                        $sql .= $mt.'`meta_value` '.$compare.' \'%'.$data['value'].'%\'';
                    }
                }
                $sql .= ') '.$relation.' ';
            }
            $sql = trim($sql, ' '.$relation.' ');
            $sql .= '))';
            $args->whereRaw('id in ('.$sql.')');
        }

        if(!empty($args->from)) $DB->from = $args->from;

        if(!empty($args->columns)) $DB->columns = $args->columns;

        if(!empty($args->limit)) $DB->limit = $args->limit;

        if(!empty($args->offset)) $DB->offset = $args->offset;

        if(!empty($args->orders)) {
            foreach ($args->orders as $order) {
                if(isset($order['type']) && $order['type'] == 'Raw') {
                    if(is_string($order['sql'])) {
                        $DB->orderByRaw(DB::raw($order['sql']));
                    }
                    else {
                        $DB->orderByRaw(DB::raw($order['sql']->getValue()));
                    }
                }
                else {
                    $DB->orderBy($order['column'], $order['direction']);
                }
            }
        }

        if(!empty($args->groups)) $DB->groups = $args->groups;

        if(!empty($args->havings)) $DB->havings = $args->havings;

        if(!empty($args->distinct)) {
            if(is_string($args->distinct) || is_bool($args->distinct)) {
                $DB->distinct($args->distinct);
            }
            else {
                foreach ($args->distinct as $item) {
                    $DB->distinct($item);
                }
            }
        }

        if(!empty($args->joins)) {

            foreach ($args->joins as $key => $join) {

                if (isset($args->joins[$key]->operators)) unset($args->joins[$key]->operators);
                if (isset($args->joins[$key]->bitwiseOperators)) unset($args->joins[$key]->bitwiseOperators);
                if (isset($args->joins[$key]->bindings)) unset($args->joins[$key]->bindings);
                if (isset($args->joins[$key]->grammar)) unset($args->joins[$key]->grammar);

                if (!isset($join->joins)) continue;

                foreach ($join->joins as $k => $joins) {
                    if (isset($args->joins[$key]->joins[$k]->operators)) unset($args->joins[$key]->joins[$k]->operators);
                    if (isset($args->joins[$key]->joins[$k]->bitwiseOperators)) unset($args->joins[$key]->joins[$k]->bitwiseOperators);
                    if (isset($args->joins[$key]->joins[$k]->bindings)) unset($args->joins[$key]->joins[$k]->bindings);
                    if (isset($args->joins[$key]->joins[$k]->grammar)) unset($args->joins[$key]->joins[$k]->grammar);
                }
            }

            foreach ($args->joins as $items) {

                $type = (count($items->wheres) == 1) ? 'Basic' : 'Nested';

                $joinFunction = 'join';
                if ($items->type == 'left') $joinFunction = 'leftJoin';
                if ($items->type == 'right') $joinFunction = 'rightJoin';

                if ($type == 'Basic') {
                    $whereJoin = $items->wheres[0];
                    $DB->$joinFunction($items->table, $whereJoin['first'], $whereJoin['operator'], $whereJoin['second']);
                }
                if ($type == 'Nested') {
                    $DB->$joinFunction($items->table, function ($join) use ($items) {

                        if (isset($items->joins)) {
                            foreach ($items->joins as $joinData) {
                                $join = self::setDataJoins($join, $joinData);
                            }
                        }

                        $itemWhereJoin = [];
                        $itemWhereBase = ['wheres' => []];
                        foreach ($items->wheres as $itemWhere) {
                            if ($itemWhere['type'] == 'Column') $itemWhereJoin[] = $itemWhere;
                            if ($itemWhere['type'] == 'Basic' || $itemWhere['type'] == 'Nested') $itemWhereBase['wheres'][] = $itemWhere;
                        }
                        if (have_posts($itemWhereJoin)) {
                            foreach ($itemWhereJoin as $where) {
                                if ($where['type'] == 'Column') {
                                    if ($where['boolean'] == 'and') $join->on($where['first'], $where['operator'], $where['second']);
                                    if ($where['boolean'] == 'or') $join->orOn($where['first'], $where['operator'], $where['second']);
                                }
                            }
                        }
                        if (have_posts($itemWhereBase['wheres'])) $join = self::setDataWhere($join, (object)$itemWhereBase);
                    });
                }
            }
        }

        if(!empty($args->wheres)) {
            $DB = self::setDataWhere($DB, $args);
        }

        return $DB;
    }
    static function setDataWhere($DB, $args) {
        foreach ($args->wheres as $item) {
            $item = (array)$item;
            if($item['type'] == 'Basic' || $item['type'] == 'Null') {
                if(!isset($item['value'])) $item['value'] = null;
                if(!isset($item['operator'])) $item['operator'] = '=';
                if($item['boolean'] == 'and') {
                    if($item['value'] instanceof Qr) {
                        $DB->where($item['column'], $item['operator'], function($query) use ($item) {
                            $query = self::setData($item['value'], $query);
                        });
                    }
                    else {
                        $DB->where($item['column'], $item['operator'], $item['value']);
                    }
                }
                if($item['boolean'] == 'or') {
                    if($item['value'] instanceof Qr) {
                        $DB->orWhere($item['column'], $item['operator'], function($query) use ($item) {
                            $query = self::setData($item['value'], $query);
                        });
                    }
                    else {
                        $DB->orWhere($item['column'], $item['operator'], $item['value']);
                    }
                }
            }
            if($item['type'] == 'In') {
                if($item['boolean'] == 'and') {
                    if($item['values'] instanceof Qr) {
                        $DB->whereIn($item['column'], function($query) use ($item) {
                            $query = self::setData($item['values'], $query);
                        });
                    }
                    else {
                        $DB->whereIn($item['column'], $item['values']);
                    }
                }
                if($item['boolean'] == 'or') {
                    if($item['values'] instanceof Qr) {
                        $DB->whereIn($item['column'], function($query) use ($item) {
                            $query = self::setData($item['values'], $query);
                        });
                    }
                    else {
                        $DB->orWhereIn($item['column'], $item['values']);
                    }
                }
            }
            if($item['type'] == 'NotIn') {
                if($item['boolean'] == 'and') {
                    if($item['values'] instanceof Qr) {
                        $DB->whereNotIn($item['column'], function($query) use ($item) {
                            $query = self::setData($item['values'], $query);
                        });
                    }
                    else {
                        $DB->whereNotIn($item['column'], $item['values']);
                    }
                }
                if($item['boolean'] == 'or') {
                    if($item['values'] instanceof Qr) {
                        $DB->whereNotIn($item['column'], function($query) use ($item) {
                            $query = self::setData($item['values'], $query);
                        });
                    }
                    else {
                        $DB->orWhereNotIn($item['column'], $item['values']);
                    }
                }
            }
            if($item['type'] == 'Nested') {
                if($item['boolean'] == 'and') {
                    $DB->where(function ($query) use ($item) {
                        $query = self::setDataWhere($query, $item['query']);
                    });
                }
                if($item['boolean'] == 'or') {
                    $DB->orWhere(function ($query) use ($item) {
                        $query = self::setDataWhere($query, $item['query']);
                    });
                }
            }
            if($item['type'] == 'raw' || $item['type'] == 'Raw') {
                if($item['boolean'] == 'and') {
                    $DB->whereRaw($item['sql']);
                }
                if($item['boolean'] == 'or') {
                    $DB->orWhereRaw($item['sql']);
                }
            }
        }
        return $DB;
    }
    static function setDataJoins($DB, $args) {
        $DB->join($args->table, function ($join) use ($args) {
            if(isset($args->joins)) {
                foreach ($args->joins as $joinData) {
                    $join = self::setDataJoins($join, $joinData);
                }
            }
            if(have_posts($args->wheres)) {
                $itemWhereBase = ['wheres' => []];
                foreach ($args->wheres as $itemWhere) {
                    if($itemWhere['type'] == 'Column') {
                        if($itemWhere['boolean'] == 'and') $join->on($itemWhere['first'], $itemWhere['operator'], $itemWhere['second']);
                        if($itemWhere['boolean'] == 'or') $join->orOn($itemWhere['first'], $itemWhere['operator'], $itemWhere['second']);
                    }
                    if($itemWhere['type'] == 'Basic' || $itemWhere['type'] == 'Nested') $itemWhereBase['wheres'][] = $itemWhere;
                }
                if(have_posts($itemWhereBase['wheres'])) $join = self::setDataWhere($join, (object)$itemWhereBase);
            }
        });
        return $DB;
    }
    public function get($args) {
        if(is_array($args)) return $this->get_data($args);
        return self::setData($args, $this->database::table($this->table), $this->table)->first();
    }
    public function gets($args) {
        if(is_array($args)) return $this->gets_data($args);
        return self::setData($args, $this->database::table($this->table), $this->table)->get();
    }
    public function count($args): int {
        if(is_array($args)) return $this->count_data($args);
        return self::setData($args, $this->database::table($this->table), $this->table)->count();
    }
    public function max($column, $args) {
        return self::setData($args, $this->database::table($this->table), $this->table)->max($column);
    }
    public function min($column, $args) {
        return self::setData($args, $this->database::table($this->table), $this->table)->min($column);
    }
    public function avg($column, $args) {
        return self::setData($args, $this->database::table($this->table), $this->table)->avg($column);
    }
    public function sum($column, $args) {
        return self::setData($args, $this->database::table($this->table), $this->table)->sum($column);
    }
    public function exists($args): bool {
        return self::setData($args, $this->database::table($this->table), $this->table)->exists();
    }
    public function doesntExist($args): bool {
        return self::setData($args, $this->database::table($this->table), $this->table)->doesntExist();
    }
    public function add($data): int {
        return $this->database::table($this->table)->insertGetId($data);
    }
    public function update($update, $args): int {
        return self::setData($args, $this->database::table($this->table), $this->table)->update($update);
    }
    public function delete($args): int {
        return self::setData($args, $this->database::table($this->table), $this->table)->delete();
    }
    public function toSql($args): string {
        return self::setData($args, $this->database::table($this->table), $this->table)->toSql();
    }
}