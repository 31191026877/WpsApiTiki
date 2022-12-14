<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('value')) {

    function value($value) {
        return $value instanceof Closure ? $value() : $value;
    }
}

class Arr {

    public static function accessible($value) {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    public static function get($array, $key, $default = null) {

        if (! static::accessible($array)) {
            return value($default);
        }

        if (is_null($key)) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        if (strpos($key, '.') === false) {
            return $array[$key] ?? value($default);
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return value($default);
            }
        }

        return $array;
    }

    public static function has($array, $keys) {
        $keys = (array) $keys;

        if (! $array || $keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            $subKeyArray = $array;

            if (static::exists($array, $key)) {
                continue;
            }

            foreach (explode('.', $key) as $segment) {
                if (static::accessible($subKeyArray) && static::exists($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    public static function hasAny($array, $keys) {
        if (is_null($keys)) {
            return false;
        }

        $keys = (array) $keys;

        if (! $array) {
            return false;
        }

        if ($keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            if (static::has($array, $key)) {
                return true;
            }
        }

        return false;
    }

    public static function add($array, $key, $value) {

        if (is_null(static::get($array, $key))) {
            static::set($array, $key, $value);
        }
        return $array;
    }

    public static function collapse($array) {

        $results = [];

        foreach ($array as $values) {
            if ($values instanceof Collection) {
                $values = $values->all();
            } elseif (! is_array($values)) {
                continue;
            }

            $results[] = $values;
        }

        return array_merge([], ...$results);
    }

    public static function crossJoin(...$arrays) {

        $results = [[]];

        foreach ($arrays as $index => $array) {
            $append = [];

            foreach ($results as $product) {
                foreach ($array as $item) {
                    $product[$index] = $item;

                    $append[] = $product;
                }
            }

            $results = $append;
        }

        return $results;
    }

    public static function divide($array) {
        return [array_keys($array), array_values($array)];
    }

    public static function dot($array, $prepend = '') {
        $results = [];
        foreach ($array as $key => $value) {
            if (is_array($value) && ! empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }
        return $results;
    }

    public static function except($array, $keys) {
        static::forget($array, $keys);
        return $array;
    }

    public static function exists($array, $key){
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }
        return array_key_exists($key, $array);
    }

    public static function first($array, callable $callback = null, $default = null) {

        if (is_null($callback)) {
            if (empty($array)) {
                return value($default);
            }
            foreach ($array as $item) {
                return $item;
            }
        }

        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return value($default);
    }

    public static function last($array, callable $callback = null, $default = null) {
        if (is_null($callback)) {
            return empty($array) ? value($default) : end($array);
        }
        return static::first(array_reverse($array, true), $callback, $default);
    }

    public static function only($array, $keys) {

        return array_intersect_key($array, array_flip((array) $keys));
    }

    public static function isAssoc(array $array) {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }

    public static function prepend($array, $value, $key = null) {
        if (is_null($key)) {
            array_unshift($array, $value);
        } else {
            $array = [$key => $value] + $array;
        }

        return $array;
    }

    public static function pull(&$array, $key, $default = null) {

        $value = static::get($array, $key, $default);

        static::forget($array, $key);

        return $value;
    }

    public static function query($array) {
        return http_build_query($array, '', '&', PHP_QUERY_RFC3986);
    }

    public static function random($array, $number = null) {
        $requested = is_null($number) ? 1 : $number;

        $count = count($array);

        if ($requested > $count) {
            throw new InvalidArgumentException(
                "You requested {$requested} items, but there are only {$count} items available."
            );
        }

        if (is_null($number)) {
            return $array[array_rand($array)];
        }

        if ((int) $number === 0) {
            return [];
        }

        $keys = array_rand($array, $number);

        $results = [];

        foreach ((array) $keys as $key) {
            $results[] = $array[$key];
        }

        return $results;
    }

    public static function set(&$array, $key, $value) {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        foreach ($keys as $i => $key) {
            if (count($keys) === 1) {
                break;
            }

            unset($keys[$i]);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }

    public static function shuffle($array, $seed = null) {
        if (is_null($seed)) {
            shuffle($array);
        } else {
            mt_srand($seed);
            shuffle($array);
            mt_srand();
        }

        return $array;
    }

    public static function where($array, callable $callback) {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    public static function forget(&$array, $keys) {
        $original = &$array;

        $keys = (array) $keys;

        if (count($keys) === 0) {
            return;
        }

        foreach ($keys as $key) {
            // if the exact key exists in the top-level, remove it
            if (static::exists($array, $key)) {
                unset($array[$key]);

                continue;
            }

            $parts = explode('.', $key);

            // clean up before each pass
            $array = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }

            unset($array[array_shift($parts)]);
        }
    }

    public static function wrap($value) {

        if (is_null($value)) {
            return [];
        }

        return is_array($value) ? $value : [$value];
    }

    public static function flatten($array): array {
        $return = array();
        foreach ($array as $key => $value) {
            if (is_array($value)){ $return = array_merge($return, Arr::flatten($value));}
            else {$return[$key] = $value;}
        }
        return $return;
    }
}