<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_html_split_regex() {

    static $regex;

    if ( ! isset( $regex ) ) {
            $comments =
                      '!'           // Start of comment, after the <.
                    . '(?:'         // Unroll the loop: Consume everything until --> is found.
                    .     '-(?!->)' // Dash not followed by end of comment.
                    .     '[^\-]*+' // Consume non-dashes.
                    . ')*+'         // Loop possessively.
                    . '(?:-->)?';   // End of comment. If not found, match all input.

            $cdata =
                      '!\[CDATA\['  // Start of comment, after the <.
                    . '[^\]]*+'     // Consume non-].
                    . '(?:'         // Unroll the loop: Consume everything until ]]> is found.
                    .     '](?!]>)' // One ] not followed by end of comment.
                    .     '[^\]]*+' // Consume non-].
                    . ')*+'         // Loop possessively.
                    . '(?:]]>)?';   // End of comment. If not found, match all input.

            $escaped =
                      '(?='           // Is the element escaped?
                    .    '!--'
                    . '|'
                    .    '!\[CDATA\['
                    . ')'
                    . '(?(?=!-)'      // If yes, which type?
                    .     $comments
                    . '|'
                    .     $cdata
                    . ')';

            $regex =
                      '/('              // Capture the entire match.
                    .     '<'           // Find start of element.
                .     '(?'          // Conditional expression follows.
                    .         $escaped  // Find end of escaped element.
                    .     '|'           // ... else ...
                    .         '[^>]*>?' // Find end of normal element.
                    .     ')'
                    . ')/';
    }

    return $regex;
}

function wp_kses_attr_parse( $element ) {
    $valid = preg_match('%^(<\s*)(/\s*)?([a-zA-Z0-9]+\s*)([^>]*)(>?)$%', $element, $matches);
    if ( 1 !== $valid ) {
        return false;
    }

    $begin =  $matches[1];
    $slash =  $matches[2];
    $elname = $matches[3];
    $attr =   $matches[4];
    $end =    $matches[5];

    if ( '' !== $slash ) {
        // Closing elements do not get parsed.
        return false;
    }

    // Is there a closing XHTML slash at the end of the attributes?
    if ( 1 === preg_match( '%\s*/\s*$%', $attr, $matches ) ) {
        $xhtml_slash = $matches[0];
        $attr = substr( $attr, 0, -strlen( $xhtml_slash ) );
    } else {
        $xhtml_slash = '';
    }

    // Split it
    $attrarr = wp_kses_hair_parse( $attr );
    if ( false === $attrarr ) {
        return false;
    }

    // Make sure all input is returned by adding front and back matter.
    array_unshift( $attrarr, $begin . $slash . $elname );
    array_push( $attrarr, $xhtml_slash . $end );

    return $attrarr;
}

if(!function_exists('add_shortcode')){

	function add_shortcode($tag, $func)
	{
		$CI = &get_instance();

		if(trim($tag) == '') return;

		if(preg_match( '@[<>&/\[\]\x00-\x20=]@', $tag ) !== 0) return;

		$CI->shortCode[$tag] = $func;

	}
}

if(!function_exists('remove_shortcode')){

	function remove_shortcode($tag)
	{
		$CI = &get_instance();
		unset($CI->shortCode[$tag]);
	}
}

if(!function_exists('remove_all_shortcode')){

	function remove_all_shortcode($tag)
	{
		$CI = &get_instance();
		$CI->shortCode = [];
	}
}

if(!function_exists('shortcode_exists')){

	function shortcode_exists($tag)
	{
		$CI = &get_instance();
		return array_key_exists( $tag, $CI->shortCode );
	}
}

if(!function_exists('get_shortcode_regex')){

	function get_shortcode_regex($tagnames = null) {

        $ci = &get_instance();

		if ( empty( $tagnames ) ) { $tagnames = array_keys( $ci->shortCode ); }

        $tagregexp = join( '|', array_map('preg_quote', $tagnames) );


        return
            '\\['                                // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagregexp)"                     // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\]'                          // ... and closing bracket
            . '|'
            .     '\\]'                          // Closing bracket
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            .             '[^\\[]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\[]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
            .         '\\[\\/\\2\\]'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
	}
}

if(!function_exists('has_shortcode')){

	function has_shortcode($content, $tag)
	{
		$CI = &get_instance();
		if ( strpos( $content, '[' ) === false)  return false;
		if ( shortcode_exists( $tag ) ) {
			preg_match_all( '/' . get_shortcode_regex() . '/', $content, $matches, PREG_SET_ORDER );
			if ( empty( $matches ) ) return false;

            foreach ( $matches as $shortCode ) {
                if ($tag === $shortCode[2]) {
                    return true;
                } elseif ( ! empty( $shortCode[5] ) && has_shortcode( $shortCode[5], $tag ) ) {
                    return true;
                }
            }
		}
		return false;
	}
}

if(!function_exists('do_shortcode')){

	function do_shortcode($content, $ignore_html = false)
	{
		$CI = &get_instance();
		if ( false === strpos( $content, '[' ) ) return $content;
		if (empty($CI->shortCode) || !is_array($CI->shortCode)) return $content;
		preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
		$tagnames = array_intersect( array_keys( $CI->shortCode ), $matches[1] );
		if ( empty( $tagnames ) ) return $content;

		$content = do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames );


		$pattern = get_shortcode_regex( $tagnames );

		$content = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $content );

		$content = unescape_invalid_shortcodes( $content );
		return $content;
	}
}

if(!function_exists('do_shortcode_tag')){

	function do_shortcode_tag($m)
	{
		$CI = &get_instance();
		if ( $m[1] == '[' && $m[6] == ']' )  return substr($m[0], 1, -1);
		$tag = $m[2];
		$attr = shortcode_parse_atts( $m[3] );
		if ( ! is_callable( $CI->shortCode[ $tag ] ) ) return $m[0];
		if ( isset( $m[5] ) ) return $m[1] . call_user_func( $CI->shortCode[$tag], $attr, $m[5], $tag ) . $m[6];
		else return $m[1] . call_user_func( $CI->shortCode[$tag], $attr, null,  $tag ) . $m[6];
	}
}

if(!function_exists('do_shortcodes_in_html_tags')){

	function do_shortcodes_in_html_tags($content, $ignore_html, $tagnames) {

		$trans      = array( '&#91;' => '&#091;', '&#93;' => '&#093;' );
		$content    = strtr( $content, $trans );
		$trans      = array( '[' => '&#91;', ']' => '&#93;' );
		$pattern    = get_shortcode_regex( $tagnames );
		$textarr    = preg_split( get_html_split_regex(), $content , -1, PREG_SPLIT_DELIM_CAPTURE );

		foreach ( $textarr as &$element ) {
			if ( '' == $element || '<' !== $element[0] ) continue;
			$noopen = false === strpos( $element, '[' );
			$noclose = false === strpos( $element, ']' );
			if ( $noopen || $noclose ) {
				if ( $noopen xor $noclose )  $element = strtr( $element, $trans );
				continue;
			}
			if ( $ignore_html || '<!--' === substr( $element, 0, 4 ) || '<![CDATA[' === substr( $element, 0, 9 ) ) {
				$element = strtr( $element, $trans );
				continue;
			}

			$attributes = wp_kses_attr_parse( $element );

			if ( false === $attributes ) {
				if ( 1 === preg_match( '%^<\s*\[\[?[^\[\]]+\]%', $element ) ) $element = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $element );
				$element = strtr( $element, $trans );
				continue;
			}
			$front = array_shift( $attributes );
			$back = array_pop( $attributes );
			$matches = [];
			preg_match('%[a-zA-Z0-9]+%', $front, $matches);
			$elname = $matches[0];
			foreach ( $attributes as &$attr ) {
				$open = strpos( $attr, '[' );
				$close = strpos( $attr, ']' );
				if ( false === $open || false === $close ) continue;
				$double = strpos( $attr, '"' );
				$single = strpos( $attr, "'" );
				if ( ( false === $single || $open < $single ) && ( false === $double || $open < $double ) ) {
					$attr = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $attr );
				} else {
					$count = 0;
					$new_attr = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $attr, -1, $count );
					if ( $count > 0 ) {
						$new_attr = wp_kses_one_attr( $new_attr, $elname );
						if ( '' !== trim( $new_attr ) ) $attr = $new_attr;
					}
				}

			}
			$element = $front . implode( '', $attributes ) . $back;
			$element = strtr( $element, $trans );
		}
		$content = implode( '', $textarr );
		return $content;
	}
}

if(!function_exists('unescape_invalid_shortcodes')){

	function unescape_invalid_shortcodes($content )
	{
		$CI = &get_instance();
		$trans = array( '&#91;' => '[', '&#93;' => ']' );
	    $content = strtr( $content, $trans );
	    return $content;
	}
}

if(!function_exists('get_shortcode_atts_regex')){

	function get_shortcode_atts_regex( )
	{
		return '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
	}
}

if(!function_exists('shortcode_parse_atts')){

	function shortcode_parse_atts($text)
	{
		$atts = [];
		$pattern = get_shortcode_atts_regex();
		$text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
		if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
			foreach ($match as $m) {
                if (!empty($m[1]))
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                elseif (!empty($m[3]))
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                elseif (!empty($m[5]))
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                elseif (isset($m[7]) && strlen($m[7]))
                    $atts[] = stripcslashes($m[7]);
                elseif (isset($m[8]))
                    $atts[] = stripcslashes($m[8]);
            }
            foreach( $atts as &$value ) {
                if ( false !== strpos( $value, '<' ) ) {
                    if ( 1 !== preg_match( '/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value ) ) {
                        $value = '';
                    }
                }
            }
		} else {
	        $atts = ltrim($text);
	    }
	    return $atts;
	}
}

if(!function_exists('shortcode_atts')){
	function shortcode_atts( $pairs, $atts, $shortcode = '' ) {
	    $atts = (array)$atts;
	    $out = [];
	    foreach ($pairs as $name => $default) {
	        if ( array_key_exists($name, $atts) )
	            $out[$name] = $atts[$name];
	        else
	            $out[$name] = $default;
	    }

	    if ( $shortcode ) {
	        $out = apply_filters( "shortcode_atts_{$shortcode}", $out, $pairs, $atts, $shortcode );
	    }

	    return $out;
	}
}

if(!function_exists('strip_shortcode_tag')){
	function strip_shortcode_tag( $m ) {
	    if ( $m[1] == '[' && $m[6] == ']' ) {
	            return substr($m[0], 1, -1);
	    }
	    return $m[1] . $m[6];
	}
}


