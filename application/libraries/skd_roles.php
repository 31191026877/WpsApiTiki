<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SKD_Roles {

	public $roles;

	public $role_objects = [];

	public $role_names = [];

	public $role_key;

	public $use_db = true;

	protected $site_id = 0;

	public function __construct( $site_id = null ) {
		$this->for_site( $site_id );
	}

	public function __call( $name, $arguments ) {
		if ( '_init' === $name ) return call_user_func_array( array( $this, $name ), $arguments );
		return false;
	}

	protected function _init() {
		$this->for_site();
	}

	public function reinit() {
		$this->for_site();
	}

	public function add_role( $role, $display_name, $capabilities = [] ) {

		if (empty($role) || isset($this->roles[$role])) return;

		$this->roles[$role] = array('name' => $display_name, 'capabilities' => $capabilities);

		if ($this->use_db) Option::update( $this->role_key, serialize($this->roles));

		$this->role_objects[$role] = new SKD_Role( $role, $capabilities );

		$this->role_names[$role] = $display_name;

		return $this->role_objects[$role];
	}

    public function update_role( $role, $display_name, $capabilities = [] ) {

        if (empty($role) || empty($this->roles[$role])) return;

        $capabilities = (empty($capabilities)) ? $this->roles[$role]['capabilities'] : $capabilities;

        $this->roles[$role] = ['name' => $display_name, 'capabilities' => $capabilities];

        if ($this->use_db) Option::update($this->role_key, $this->roles);

        $this->role_objects[$role] = new SKD_Role( $role, $capabilities );

        $this->role_names[$role] = $display_name;

        return $this->role_objects[$role];
    }

	public function remove_role( $role ) {

		if ( ! isset( $this->role_objects[$role] ) ) return;

		unset( $this->role_objects[$role] );
		unset( $this->role_names[$role] );
		unset( $this->roles[$role] );

		option::update( $this->role_key, serialize($this->roles) );
	}

	public function add_cap( $role, $cap, $grant = true ) {

		if ( ! isset( $this->roles[$role] ) ) return;

		$this->roles[$role]['capabilities'][$cap] = $grant;

		option::update( $this->role_key, serialize($this->roles) );
	}

	public function remove_cap( $role, $cap ) {

		if ( ! isset( $this->roles[$role] ) ) return;

		unset( $this->roles[$role]['capabilities'][$cap] );

        option::update( $this->role_key, serialize($this->roles) );
	}

	public function get_role( $role ) {

		if ( isset( $this->role_objects[$role] ) )
			return $this->role_objects[$role];
		else
			return null;
	}

	public function get_names() {
		return $this->role_names;
	}

	public function is_role( $role ) {

		return isset( $this->role_names[$role] );

	}

	public function init_roles() {

		if ( empty( $this->roles ) ) return;

		$this->role_objects = [];

		$this->role_names =  [];

		foreach ( array_keys( $this->roles ) as $role ) {

			$this->role_objects[ $role ] = new SKD_Role( $role, $this->roles[ $role ]['capabilities'] );

			$this->role_names[ $role ] = $this->roles[ $role ]['name'];

			do_action( 'skd_roles_init', $this );
		}
	}

	public function for_site( $site_id = null ) {

		$this->role_key = 'user_roles';

		$this->roles = $this->get_roles_data();

		$this->init_roles();
	}

	protected function get_roles_data() {

		return option::get( $this->role_key, [] );

	}
}

class SKD_Role {

	public $name;

	public $capabilities;

	public function __construct( $role, $capabilities ) {

		$this->name = $role;

		$this->capabilities = $capabilities;
	}

	public function add_cap( $cap, $grant = true ) {

		$this->capabilities[$cap] = $grant;

		skd_roles()->add_cap( $this->name, $cap, $grant );
	}

	public function remove_cap( $cap ) {

		unset( $this->capabilities[$cap] );

		skd_roles()->remove_cap( $this->name, $cap );
	}

	public function has_cap( $cap ) {

		$capabilities = apply_filters( 'role_has_cap', $this->capabilities, $cap, $this->name );

		if ( !empty( $capabilities[$cap] ) )
			return $capabilities[$cap];
		else
			return false;
	}
}