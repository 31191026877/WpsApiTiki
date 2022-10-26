<?php
Class Scart {

    public $ci;

    function __construct() {
        $this->ci = &get_instance();
    }

    public function getCI() {
        return $this->ci;
    }

    static function getItems() {
        return (new Scart)->getCI()->cart->contents();
    }
    static function getItem($rowID) {
        return (new Scart)->getCI()->cart->get_item($rowID);
    }

    static function total() {
        return (new Scart)->getCI()->cart->total();
    }

    static function totalQty() {
        return (new Scart)->getCI()->cart->total_items();
    }

    static function update($data = []) {
        return (new Scart)->getCI()->cart->update($data);
    }

    static function insert($data = []) {
        return (new Scart)->getCI()->cart->insert($data);
    }

    static function delete($rowID) {
        return (new Scart)->getCI()->cart->update(['rowid' => $rowID, 'qty' => 0]);
    }

    static function empty() {
        return (new Scart)->getCI()->cart->destroy();
    }
}