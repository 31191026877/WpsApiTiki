<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Str {

    public static function clear($value = '') {
        if(!is_string($value)) return $value;
        return preg_replace('([!"#$&’\(\)\*\+,\-\./0123456789:;<=>\?ABCDEFGHIJKLMNOPQRSTUVWXYZ\[\\\]\^_‘abcdefghijklmnopqrstuvwxyz\{\|\}~¡¢£⁄¥ƒ§¤“«‹›ﬁﬂ–†‡·¶•‚„”»…‰¿`´ˆ˜¯˘˙¨˚¸˝˛ˇ—ÆªŁØŒºæıłøœß÷¾¼¹×®Þ¦Ð½−çð±Çþ©¬²³™°µ ÁÂÄÀÅÃÉÊËÈÍÎÏÌÑÓÔÖÒÕŠÚÛÜÙÝŸŽáâäàåãéêëèíîïìñóôöòõšúûüùýÿž€\'])', '', strip_tags($value));
    }
    /**
     * Phương thức trả về mọi thứ sau giá trị đã cho trong một chuỗi.
     * Toàn bộ chuỗi sẽ được trả lại nếu giá trị không tồn tại trong chuỗi:Str::after
     * $slice = Str::after('This is my name', 'This is');
     */
    public static function after(string $subject, string $search) {
        return $search === '' ? $subject : array_reverse(explode($search, $subject, 2))[0];
    }
    /**
     * Phương thức trả về mọi thứ sau lần xuất hiện cuối cùng của giá trị nhất định trong một chuỗi.
     * Toàn bộ chuỗi sẽ được trả lại nếu giá trị không tồn tại trong chuỗi:Str::afterLast
     * $slice = Str::afterLast('App\Http\Controllers\Controller', '\\');
     */
    public static function afterLast(string $subject, string $search) {

        if ($search === '') return $subject;

        $position = strrpos($subject, (string) $search);

        if ($position === false) return $subject;

        return substr($subject, $position + strlen($search));
    }
    /**
     * Phương thức trả về mọi thứ trước giá trị đã cho trong chuỗi:Str::before
     * $slice = Str::before('This is my name', 'my name');
     */
    public static function before(string $subject, string $search) {
        return $search === '' ? $subject : explode($search, $subject)[0];
    }
    /**
     * Phương pháp trả về tất cả mọi thứ trước khi xảy ra cuối cùng của giá trị nhất định trong một chuỗi:Str::beforeLast
     * $slice = Str::beforeLast('This is my name', 'is');
     */
    public static function beforeLast(string $subject, string $search) {

        if ($search === '') return $subject;

        $pos = mb_strrpos($subject, $search);

        if ($pos === false) return $subject;

        return static::substr($subject, 0, $pos);
    }
    /**
     * Phương thức trả về phần chuỗi giữa hai giá trị:Str::between
     * $slice = Str::between('This is my name', 'This', 'name');
     */
    public static function between($subject, $from, $to) {

        if ($from === '' || $to === '') return $subject;

        return static::beforeLast(static::after($subject, $from), $to);
    }
    /**
     * Phương pháp xác định nếu chuỗi cho trước có chứa giá trị nhất định (trường hợp nhạy cảm):Str::contains
     *
     * @param  string  $haystack
     * @param  string|string[]  $needles
     * @return bool
     * $contains = Str::contains('This is my name', 'my');
     * $contains = Str::contains('This is my name', ['my', 'foo']);
     */
    public static function contains($haystack, $needles) {

        foreach ((array) $needles as $needle) {
            if ($needle !== '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Phương pháp xác định nếu chuỗi cho trước chứa tất cả các giá trị mảng:Str::containsAll
     *
     * @param  string  $haystack
     * @param  string[]  $needles
     * @return bool
     * $containsAll = Str::containsAll('This is my name', ['my', 'name']);
     */
    public static function containsAll($haystack, array $needles) {
        foreach ($needles as $needle) {
            if (! static::contains($haystack, $needle)) {
                return false;
            }
        }
        return true;
    }
    /**
     * Phương pháp xác định nếu chuỗi cho trước kết thúc với giá trị nhất định:Str::endsWith
     *
     * @param  string  $haystack
     * @param  string|string[]  $needles
     * @return bool
     * $result = Str::endsWith('This is my name', 'name'); // true
     * $result = Str::endsWith('This is my name', ['name', 'foo']); //true
     * $result = Str::endsWith('This is my name', ['this', 'foo']); // false
     */
    public static function endsWith($haystack, $needles) {
        foreach ((array) $needles as $needle) {
            if ($needle !== '' && substr($haystack, -strlen($needle)) === (string) $needle) {
                return true;
            }
        }
        return false;
    }

    /**
     * Phương pháp thêm một phiên bản giá trị cho một chuỗi nếu nó không đã bắt đầu với giá trị
     *
     * @param  string  $value
     * @param  string  $prefix
     * @return string
     * $adjusted = Str::start('this/string', '/'); // /this/string
     * $adjusted = Str::start('/this/string', '/'); // /this/string
     */
    public static function start($value, $prefix) {
        $quoted = preg_quote($prefix, '/');

        return $prefix.preg_replace('/^(?:'.$quoted.')+/u', '', $value);
    }


    /**
     * Phương pháp thêm một phiên bản giá trị nhất định cho một chuỗi nếu nó không đã kết thúc với giá trị:Str::finish
     *
     * @param  string  $value
     * @param  string  $cap
     * @param  string  $cap
     * @return string
     * $adjusted = Str::finish('this/string', '/'); // this/string/
     * $adjusted = Str::finish('this/string/', '/'); // this/string/
     */
    public static function finish($value, $cap) {

        $quoted = preg_quote($cap, '/');

        return preg_replace('/(?:'.$quoted.')+$/u', '', $value).$cap;
    }

    /**
     * Phương pháp xác định nếu một chuỗi nhất định phù hợp với một mô hình nhất định. Dấu hoa thị có thể được sử dụng để chỉ ra wildcards:Str::is
     *
     * @param  string|array  $pattern
     * @param  string  $value
     * @return bool
     * $matches = Str::is('foo*', 'foobar'); // true
     * $matches = Str::is('baz*', 'foobar'); // false
     */
    public static function is($pattern, $value) {
        $patterns = Arr::wrap($pattern);

        if (empty($patterns) || empty($value)) {
            return false;
        }

        foreach ($patterns as $pattern) {
            // If the given value is an exact match we can of course return true right
            // from the beginning. Otherwise, we will translate asterisks and do an
            // actual pattern match against the two strings to see if they match.
            if ($pattern == $value) {
                return true;
            }

            $pattern = preg_quote($pattern, '#');

            // Asterisks are translated into zero-or-more regular expression wildcards
            // to make it convenient to check if the strings starts with the given
            // pattern such as "library/*", making any string check convenient.
            $pattern = str_replace('\*', '.*', $pattern);

            if (preg_match('#^'.$pattern.'\z#u', $value) === 1) {
                return true;
            }
        }

        return false;
    }

    public static function isUrl($value) {
        if(empty($value)) return false;
        if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $value)) // `i` flag for case-insensitive
            return true;
        return false;
    }

    public static function isSerialized($value, $strict = true) {
        // if it isn't a string, it isn't serialized.
        if(!is_string($value)) {
            return false;
        }
        $value = trim($value);
        if ( 'N;' == $value ) {
            return true;
        }
        if ( strlen( $value ) < 4 ) {
            return false;
        }
        if ( ':' !== $value[1] ) {
            return false;
        }
        if ( $strict ) {
            $lastc = substr( $value, -1 );
            if ( ';' !== $lastc && '}' !== $lastc ) {
                return false;
            }
        } else {
            $semicolon = strpos( $value, ';' );
            $brace     = strpos( $value, '}' );
            // Either ; or } must exist.
            if ( false === $semicolon && false === $brace )
                return false;
            // But neither must be in the first X characters.
            if ( false !== $semicolon && $semicolon < 3 )
                return false;
            if ( false !== $brace && $brace < 4 )
                return false;
        }
        $token = $value[0];
        switch ( $token ) {
            case 's' :
                if ( $strict ) {
                    if ( '"' !== substr( $value, -2, 1 ) ) {
                        return false;
                    }
                } elseif ( false === strpos( $value, '"' ) ) {
                    return false;
                }
            // or else fall through
            case 'a' :
            case 'O' :
                return (bool) preg_match( "/^{$token}:[0-9]+:/s", $value );
            case 'b' :
            case 'i' :
            case 'd' :
                $end = $strict ? '$' : '';
                return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $value );
        }
        return false;
    }
    /**
     * Phương pháp xác định nếu chuỗi cho trước bắt đầu với giá trị nhất định
     *
     * @param  string  $haystack
     * @param  string|string[]  $needles
     * @return bool
     * $result = Str::startsWith('This is my name', 'This'); // true
     */
    public static function startsWith($haystack, $needles) {
        foreach ((array) $needles as $needle) {
            if ((string) $needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0) {
                return true;
            }
        }

        return false;
    }
    /**
     * Phương thức trả về độ dài của chuỗi đã cho:Str::length
     *
     * @param  string  $value
     * @param  string|null  $encoding
     * @return int
     */
    public static function length($value, $encoding = null) {

        if ($encoding) {
            return mb_strlen($value, $encoding);
        }

        return mb_strlen($value);
    }
    /**
     * Phương pháp truncates chuỗi nhất định theo chiều dài được chỉ định:Str::limit
     *
     * @param  string  $value
     * @param  int  $limit
     * @param  string  $end
     * @return string
     */
    public static function limit($value, $limit = 100, $end = '...') {
        if(empty($value)) return $value;
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }
        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')).$end;
    }
    /**
     * Phương pháp chuyển chuỗi nhất định thành chữ thường
     *
     * @param  string  $value
     * @return string
     */
    public static function lower($value) {
        return (!empty($value)) ? mb_strtolower($value, 'UTF-8') : $value;
    }
    /**
     * Phương pháp chuyển chuỗi đã cho thành chữ hoa
     *
     * @param  string  $value
     * @return string
     */
    public static function upper($value) {
        return (!empty($value)) ? mb_strtoupper($value, 'UTF-8') : $value;
    }
    /**
     * Phương pháp này giới hạn số từ trong một chuỗi
     *
     * @param  string  $value
     * @param  int  $words
     * @param  string  $end
     * @return string
     */
    public static function words($value, $words = 100, $end = '...') {
        preg_match('/^\s*+(?:\S++\s*+){1,'.$words.'}/u', $value, $matches);

        if (! isset($matches[0]) || static::length($value) === static::length($matches[0])) {
            return $value;
        }

        return rtrim($matches[0]).$end;
    }

    /**
     * Phương pháp này tạo ra một chuỗi ngẫu nhiên chiều dài được chỉ định.
     * Chức năng này sử dụng chức năng của PHP:Str::randomrandom_bytes
     * @param int $length
     * @return string
     * $random = Str::random(40);
     * @throws Exception
     */
    public static function random($length = 16) {
        $string = '';
        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
            $bytes = random_bytes($size);
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }
        return $string;
    }

    /**
     * Phương pháp này thay thế một giá trị nhất định trong chuỗi tuần tự bằng cách sử dụng một mảng
     *
     * @param  string  $search
     * @param  array<int|string, string>  $replace
     * @param  string  $subject
     * @return string
     * $string = 'The event will take place between ? and ?';
     * $replaced = Str::replaceArray('?', ['8:30', '9:00'], $string);
     * // The event will take place between 8:30 and 9:00
     */
    public static function replaceArray($search, array $replace, $subject) {

        $segments = explode($search, $subject);

        $result = array_shift($segments);

        foreach ($segments as $segment) {
            $result .= (array_shift($replace) ?? $search).$segment;
        }

        return $result;
    }

    /**
     * Phương pháp này thay thế sự xuất hiện đầu tiên của một giá trị nhất định trong một chuỗi
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $subject
     * @return string
     * $replaced = Str::replaceFirst('the', 'a', 'the quick brown fox jumps over the lazy dog');
     * // a quick brown fox jumps over the lazy dog
     */
    public static function replaceFirst($search, $replace, $subject) {
        if ($search == '') {
            return $subject;
        }

        $position = strpos($subject, $search);

        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }

    /**
     * Phương pháp thay thế sự xuất hiện cuối cùng của một giá trị nhất định trong một chuỗi
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $subject
     * @return string
     * $replaced = Str::replaceLast('the', 'a', 'the quick brown fox jumps over the lazy dog');
     * // the quick brown fox jumps over a lazy dog
     */
    public static function replaceLast($search, $replace, $subject) {
        $position = strrpos($subject, $search);

        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }

    public static function ascii($str) {

        $str = static::lower($str);

        $chars = array(
            'a'	=>	array('ɑ̃','ấ','ầ','ẩ','ẩ','ẫ','ấ','ẫ','ậ','ậ','Ɑ̃','Ấ','Ầ','Ấ','Ẩ','Ẩ','Ẫ','Ẫ','Ậ','Ậ','ắ','ă','ằ','ắ','ẳ','ẳ','ẵ','ẵ','ặ','ặ','Ắ','Ă','Ằ','Ắ','Ẳ','Ẳ','Ẵ','Ẵ','Ặ','Ặ','á','á','à','à','ả','ả','ã','ã','ạ','ạ','â','ă','Á','Á','À','À','Ả','Ả','Ã','Ã','Ạ','Ạ','Â','Â','Ă','Ă'),
            'e' =>	array('ë','ế','ề','ề','ế','ể','ể','ễ','ễ','ệ','ệ','Ë','Ế','Ế','Ề','Ề','Ể','Ể','Ễ','Ễ','Ệ','Ệ','é','é','è','è','ẻ','ẻ','ẽ','ẽ','ẹ','ẹ','ê','ê','É','É','È','È','Ẻ','Ẻ','Ẽ','Ẽ','Ẹ','Ẹ','Ê','Ế'),
            'i'	=>	array('ï','í','í','ì','ì','ỉ','ỉ','ĩ','ĩ','ị','ị','Ï','î','I','Í','Í','Ì','Ì','Ỉ','Ỉ','Ĩ','Ĩ','Ị','Ị','Î'),
            'o'	=>	array('ố','ô','ồ','ồ','ổ','ổ','ỗ','ỗ','ộ','ộ','Ố','Ố','Ồ','Ồ','Ổ','Ổ','Ổ','Ỗ','Ỗ','Ộ','Ộ','ớ','ớ','ờ','ờ','ở','ở','ỡ','ỡ','ợ','ợ','Ớ','Ớ','Ờ','Ờ','Ở','Ở','Ỡ','Ỡ','Ợ','Ợ','ó','ó','ò','ò','ỏ','ỏ','õ','õ','ọ','ọ','ô','ô','ơ','ơ','Ó','Ó','Ò','Ò','Ỏ','Ỏ','Õ','Õ','Ọ','Ọ','Ô','Ô','Ơ','Ơ'),
            'u'	=>	array('ü','û','ứ','ứ','ừ','ừ','ử','ử','ữ','ữ','ự','ự','Ü','Û','Ứ','Ứ','Ừ','Ừ','Ử','Ử','Ữ','Ữ','Ự','Ự','ú','ú','ù','ù','ủ','ủ','ũ','ũ','ụ','ụ','ư','ư','Ú','Ú','Ù','Ù','Ủ','Ủ','Ũ','Ũ','Ụ','Ụ','Ư','Ư'),
            'y'	=>	array('ý','ý','ỳ','ỳ','ỷ','ỷ','ỹ','ỹ','ỵ','ỵ','Ý','Ý','Ỳ','Ỳ','Ỷ','Ỷ','Ỹ','Ỹ','Ỵ','Ỵ'),
            'd'	=>	array('đ','đ','Đ','Đ'),
            ''	=>	array(',','.','¼','½','¾','⅓','⅔','⅛','⅜','⅝','⅞',''),
            'divided by' => array('÷'),
            'infinity' => array('∞'),
            'square root' => array('√'),
            'plus-minus' => array('±'),
            'times' => array('×'),
            'almost equal to' => array('≈'),
            'greater than or equal to' => array('≥'),
            'less than or equal to' => array('≤'),
            'not equal to' => array('≠'),
            'identical to' => array('≡'),
            'left' => array('←'),
            'right' => array('→'),
            'up' => array('↑'),
            'down' => array('↓'),
            'left and right' => array('↔'),
            'up and down' => array('↕'),
            'care of' => array('℅'),
            'estimated' => array('℮'),
            'ohm' => array('Ω'),
            'female' => array('♀'),
            'male' => array('♂'),
            'copyright' => array('©'),
            'registered' => array('®'),
            'trademark' => array('™'),
            'l' => array('l’', 'L’'),
            'c' => array('ɔ̃','ç','Ɔ̃','Ç'),
        );

        foreach ($chars as $key => $arr) {
            foreach ($arr as $val) {
                $str = str_replace($val, $key, $str);
            }
        }

        return $str;
    }
    /**
     * Phương pháp này tạo ra một URL thân thiện "slug" từ chuỗi cho trước
     *
     * @param  string  $title
     * @param  string  $separator
     * @param  string|null  $language
     * @return string
     * $slug = Str::slug('Skilldo 4 CMS', '-');
     * // skilldo-4-cms
     */
    public static function slug($title, $separator = '-') {

        $title = static::ascii($title);

        // Convert all dashes/underscores into separator
        $flip = $separator === '-' ? '_' : '-';

        $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);

        // Replace @ with the word 'at'
        $title = str_replace('@', $separator.'at'.$separator, $title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', static::lower($title));

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

        return trim($title, $separator);
    }

    /**
     * Phương pháp này tạo ra một price
     *
     * @param  string  $title
     * @param  string  $separator
     * @param  string|null  $language
     * @return string
     * $slug = Str::price('2,000,000');
     * // 2000000
     */
    public static function price($price) {

        $price = static::clear($price);

        $price = str_replace(',', '', $price);

        $price = str_replace('.', '', $price);

        return (int)$price;
    }

    /**
     * Phương thức trả về phần chuỗi được chỉ định bởi các tham số bắt đầu và độ dài
     *
     * @param  string  $string
     * @param  int  $start
     * @param  int|null  $length
     * @return string
     * $converted = Str::substr('The Laravel Framework', 4, 7); // Laravel
     */
    public static function substr($string, $start, $length = null)
    {
        return mb_substr($string, $start, $length, 'UTF-8');
    }
    /**
     * Returns the number of substring occurrences.
     *
     * @param  string  $haystack
     * @param  string  $needle
     * @param  int  $offset
     * @param  int|null  $length
     * @return int
     */
    public static function substrCount($haystack, $needle, $offset = 0, $length = null)
    {
        if (! is_null($length)) {
            return substr_count($haystack, $needle, $offset, $length);
        } else {
            return substr_count($haystack, $needle, $offset);
        }
    }
    /**
     * Phương thức trả về chuỗi nhất định với ký tự đầu tiên được viết hoa
     *
     * @param  string  $string
     * @return string
     */
    public static function ucfirst($string)
    {
        return static::upper(static::substr($string, 0, 1)).static::substr($string, 1);
    }
    /**
     * Convert the given string to title case.
     *
     * @param  string  $value
     * @return string
     * $converted = Str::title('a nice title uses the correct case');
     * // A Nice Title Uses The Correct Case
     */
    public static function title($value)
    {
        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Fluent strings provide a more fluent,
     * object-oriented interface for working with string values, allowing you to chain multiple string operations together using a more readable syntax compared to traditional string operations.
     * @param $string
     * @return CmsStringable
     */
    public static function of($string) {
        return new CmsStringable($string);
    }
}

class CmsStringable {
    /**
     * The underlying string value.
     *
     * @var string
     */
    protected $value;

    /**
     * Create a new instance of the class.
     *
     * @param string $value
     * @return void
     */
    public function __construct($value = '')
    {
        $this->value = (string)$value;
    }

    public function clear()
    {
        return new static(Str::clear($this->value));
    }

    /**
     * Return the remainder of a string after the first occurrence of a given value.
     *
     * @param string $search
     * @return static
     */
    public function after($search)
    {
        return new static(Str::after($this->value, $search));
    }

    /**
     * Return the remainder of a string after the last occurrence of a given value.
     *
     * @param string $search
     * @return static
     */
    public function afterLast($search)
    {
        return new static(Str::afterLast($this->value, $search));
    }

    /**
     * Append the given values to the string.
     *
     * @param array $values
     * @return static
     */
    public function append(...$values)
    {
        return new static($this->value . implode('', $values));
    }

    /**
     * Get the portion of a string before the first occurrence of a given value.
     *
     * @param string $search
     * @return static
     */
    public function before($search)
    {
        return new static(Str::before($this->value, $search));
    }

    /**
     * Get the portion of a string before the last occurrence of a given value.
     *
     * @param string $search
     * @return static
     */
    public function beforeLast($search)
    {
        return new static(Str::beforeLast($this->value, $search));
    }

    /**
     * Get the portion of a string between two given values.
     *
     * @param string $from
     * @param string $to
     * @return static
     */
    public function between($from, $to)
    {
        return new static(Str::between($this->value, $from, $to));
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param string|array $needles
     * @return bool
     */
    public function contains($needles)
    {
        return Str::contains($this->value, $needles);
    }

    /**
     * Determine if a given string contains all array values.
     *
     * @param array $needles
     * @return bool
     */
    public function containsAll(array $needles)
    {
        return Str::containsAll($this->value, $needles);
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param string|array $needles
     * @return bool
     */
    public function endsWith($needles)
    {
        return Str::endsWith($this->value, $needles);
    }

    /**
     * Cap a string with a single instance of a given value.
     *
     * @param string $cap
     * @return static
     */
    public function finish($cap)
    {
        return new static(Str::finish($this->value, $cap));
    }

    /**
     * Determine if a given string matches a given pattern.
     *
     * @param string|array $pattern
     * @return bool
     */
    public function is($pattern)
    {
        return Str::is($pattern, $this->value);
    }

    /**
     * Determine if the given string is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->value === '';
    }

    /**
     * Determine if the given string is not empty.
     *
     * @return bool
     */
    public function isNotEmpty()
    {
        return !$this->isEmpty();
    }

    /**
     * Return the length of the given string.
     *
     * @param string $encoding
     * @return int
     */
    public function length($encoding = null)
    {
        return Str::length($this->value, $encoding);
    }

    /**
     * Limit the number of characters in a string.
     *
     * @param int $limit
     * @param string $end
     * @return static
     */
    public function limit($limit = 100, $end = '...')
    {
        return new static(Str::limit($this->value, $limit, $end));
    }

    /**
     * Convert the given string to lower-case.
     *
     * @return static
     */
    public function lower()
    {
        return new static(Str::lower($this->value));
    }

    /**
     * Get the string matching the given pattern.
     *
     * @param string $pattern
     * @return static|null
     */
    public function match($pattern)
    {
        preg_match($pattern, $this->value, $matches);

        if (!$matches) {
            return new static;
        }

        return new static($matches[1] ?? $matches[0]);
    }

    /**
     * Prepend the given values to the string.
     *
     * @param array $values
     * @return static
     */
    public function prepend($values)
    {
        return new static(implode('', $values) . $this->value);
    }

    /**
     * Replace the given value in the given string.
     *
     * @param string|string[] $search
     * @param string|string[] $replace
     * @return static
     */
    public function replace($search, $replace)
    {
        return new static(str_replace($search, $replace, $this->value));
    }

    /**
     * Replace a given value in the string sequentially with an array.
     *
     * @param string $search
     * @param array $replace
     * @return static
     */
    public function replaceArray($search, array $replace)
    {
        return new static(Str::replaceArray($search, $replace, $this->value));
    }

    /**
     * Replace the first occurrence of a given value in the string.
     *
     * @param string $search
     * @param string $replace
     * @return static
     */
    public function replaceFirst($search, $replace)
    {
        return new static(Str::replaceFirst($search, $replace, $this->value));
    }

    /**
     * Replace the last occurrence of a given value in the string.
     *
     * @param string $search
     * @param string $replace
     * @return static
     */
    public function replaceLast($search, $replace)
    {
        return new static(Str::replaceLast($search, $replace, $this->value));
    }

    /**
     * Replace the patterns matching the given regular expression.
     *
     * @param string $pattern
     * @param \Closure|string $replace
     * @param int $limit
     * @return static
     */
    public function replaceMatches($pattern, $replace, $limit = -1)
    {
        if ($replace instanceof Closure) {
            return new static(preg_replace_callback($pattern, $replace, $this->value, $limit));
        }

        return new static(preg_replace($pattern, $replace, $this->value, $limit));
    }

    /**
     * Begin a string with a single instance of a given value.
     *
     * @param string $prefix
     * @return static
     */
    public function start($prefix)
    {
        return new static(Str::start($this->value, $prefix));
    }

    /**
     * Convert the given string to upper-case.
     *
     * @return static
     */
    public function upper()
    {
        return new static(Str::upper($this->value));
    }

    /**
     * Convert the given string to title case.
     *
     * @return static
     */
    public function title()
    {
        return new static(Str::title($this->value));
    }

    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param string $separator
     * @param string|null $language
     * @return static
     */
    public function slug($separator = '-')
    {
        return new static(Str::slug($this->value, $separator));
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param string|array $needles
     * @return bool
     */
    public function startsWith($needles)
    {
        return Str::startsWith($this->value, $needles);
    }

    /**
     * Returns the portion of string specified by the start and length parameters.
     *
     * @param int $start
     * @param int|null $length
     * @return static
     */
    public function substr($start, $length = null)
    {
        return new static(Str::substr($this->value, $start, $length));
    }

    /**
     * Returns the number of substring occurrences.
     *
     * @param string $needle
     * @param int|null $offset
     * @param int|null $length
     * @return int
     */
    public function substrCount($needle, $offset = null, $length = null)
    {
        return Str::substrCount($this->value, $needle, $offset, $length);
    }

    /**
     * Trim the string of the given characters.
     *
     * @param string $characters
     * @return static
     */
    public function trim($characters = null)
    {
        return new static(trim(...array_merge([$this->value], func_get_args())));
    }

    /**
     * Left trim the string of the given characters.
     *
     * @param string $characters
     * @return static
     */
    public function ltrim($characters = null)
    {
        return new static(ltrim(...array_merge([$this->value], func_get_args())));
    }

    /**
     * Right trim the string of the given characters.
     *
     * @param string $characters
     * @return static
     */
    public function rtrim($characters = null)
    {
        return new static(rtrim(...array_merge([$this->value], func_get_args())));
    }

    /**
     * Make a string's first character uppercase.
     *
     * @return static
     */
    public function ucfirst()
    {
        return new static(Str::ucfirst($this->value));
    }

    /**
     * Apply the callback's string changes if the given "value" is true.
     *
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return mixed|$this
     */
    public function when($value, $callback, $default = null)
    {
        if ($value) {
            return $callback($this, $value) ?: $this;
        } elseif ($default) {
            return $default($this, $value) ?: $this;
        }

        return $this;
    }

    /**
     * Execute the given callback if the string is empty.
     *
     * @param callable $callback
     * @return static
     */
    public function whenEmpty($callback)
    {
        if ($this->isEmpty()) {
            $result = $callback($this);

            return is_null($result) ? $this : $result;
        }

        return $this;
    }

    /**
     * Limit the number of words in a string.
     *
     * @param int $words
     * @param string $end
     * @return static
     */
    public function words($words = 100, $end = '...')
    {
        return new static(Str::words($this->value, $words, $end));
    }

    /**
     * Proxy dynamic properties onto methods.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->{$key}();
    }

    /**
     * Get the raw string value.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }
}

class FileHandler {

    public static function extension($file) {
        if(Str::is( '*?v=*', $file)) {
            $file = Str::before($file, '?v=');
        }
        if(Str::is( '*fonts.googleapis.com*', $file)) {
            return 'css';
        }
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $ext = Str::lower($ext);
        return (empty($ext)) ? 'unknown' : $ext;
    }

    public static function type($file) {

        $ext = static::extension($file);

        if($ext == 'unknown') {

            $parsed = parse_url($file);

            $domain = (isset($parsed['scheme']))?$parsed['host']:$parsed['path'];

            if(strpos($domain, 'youtube.') > 0) return 'youtube';

            if(strpos($domain, 'vimeo.') > 0) return 'vimeo';

            return 'unknown';
        }

        $extention['image']    = array('bmp', 'rle', 'dib', 'gif', 'jpg', 'jpeg', 'jpe', 'png', 'pns', 'tiff', 'svg', 'webp' );

        $extention['video']    = array( 'mov', 'mpeg', 'm4v', 'mp4', 'avi', 'mpg', 'wma', "flv", "webm" );

        $extention['audio']    = array( 'mp3', 'mpga', 'm4a', 'ac3', 'aiff', 'mid', 'ogg', 'wav' );

        $extention['archives'] = array( 'zip', 'rar', 'gz', 'tar', 'iso', 'dmg' );

        $extention['psd'] = array('psd');

        $extention['pdf'] = array('pdf');

        $extention['doc'] = array('doc', 'docx', 'txt');

        $extention['excel'] = array("xml","xsl",'xlsx');

        foreach ( $extention as $type => $data ) {

            if(in_array($ext, $data) !== false ) return $type;
        }

        return 'file';
    }

    public static function handlingUrl($field) {
        $field = (String)Str::of($field)->replace(Url::base().SOURCE, '')->replace(SOURCE, '');
        return trim($field ,'/');
    }
}