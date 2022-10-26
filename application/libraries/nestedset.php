<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Illuminate\Database\Capsule\Manager as DB;

class Nestedset {

	public $table;

	public $checked 	= NULL;

	public $params 		= NULL;

	public $where 		= NULL;

	public $data 		= NULL;

	public $count 		= 0;

	public $count_level = 0;

	public $lft 		= NULL;

	public $rgt 		= NULL;
	
	public $level 		= NULL;

	public $module 		= '';

	function __construct($params = NULL){

	    if(isset($params['module'])) $this->module = $params['module'];

	    if(isset($params['table'])) $this->table = $params['table'];

		$this->where       = (isset($params['where'])) ? $params['where'] : [];

		$this->params      = $params;

		$this->checked     = NULL;

		$this->count       = 0;

		$this->count_level = 0;

		$this->lft         = NULL;

		$this->rgt         = NULL;

		$this->level       = NULL;
	}

	//get dữ liệu
	public function get($param = NULL){

		$this->data = $this->getsCategory();

		return $this->data;
	}

	public function getsCategory($parent_id = 0, $trees = NULL) {

		if(!$trees) $trees = [];

		$this->where['parent_id'] = $parent_id;

        $db = DB::table($this->table);

        foreach ($this->where as $key => $item) {
            $db->where($key, $item);
        }

		$root = $db->orderBy('order')->get();

		foreach ($root as $key => $val) {
			$trees[] = $val;
			$trees = $this->getsCategory($val->id , $trees);
		}

		return $trees;
	}

	//set dữ liệu
	public function set() {

		$arr = NULL;

		if(isset($this->data) && is_array($this->data)) {
			foreach ($this->data as $val) {
				$arr[$val->id][$val->parent_id] = 1;
				$arr[$val->parent_id][$val->id] = 1;
			}
		}

		return $arr;
	}

	//hàm đệ quy
	public function recursive($start = 0, $arr = NULL){

		$this->lft[$start] = ++$this->count;

		$this->level[$start] = $this->count_level;

		if(isset($arr) && is_array($arr)){

			foreach($arr as $key => $val){

				if((isset($arr[$start][$key]) || isset($arr[$key][$start])) &&(!isset($this->checked[$key][$start]) && !isset($this->checked[$start][$key]))){
					
					$this->count_level++;

					$this->checked[$start][$key] = 1;

					$this->checked[$key][$start] = 1;

					$this->recursive($key, $arr);

					$this->count_level--;
				}
			}
		}

		$this->rgt[$start] = ++$this->count;

	}

	//hàm update level, lft, rgt dữ liệu
	public function action(){

		if(isset($this->level) && is_array($this->level) && isset($this->lft) && is_array($this->lft) && isset($this->rgt) && is_array($this->rgt)){

			$data = NULL;

			foreach($this->level as $key => $val){
				$data = [
					'level' => $val,
					'lft'   => $this->lft[$key],
					'rgt'   => $this->rgt[$key],
				];
                DB::table($this->table)->where('id', $key)->update($data);
			}
		}
    }
}