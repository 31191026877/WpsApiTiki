<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SKD_list_table {

    public mixed $items;

    public mixed $table;

    public mixed $module;

    public mixed $model;

    public mixed $_column_headers;

    public mixed $global = [];

    public $_class_table;

    public function __construct($args = []) {
        $args = array_merge([
            'items'     => [],
            'table'     => null,
            'module'    => null,
            'model'     => '',
            '_column_headers'   => '',
            '_class_table'      => [],
            'global'            => [],
        ], $args);
        $this->items            = $args['items'];
        $this->table            = $args['table'];
        $this->module           = $args['module'];
        $this->model            = $args['model'];
        $this->_column_headers  = $args['_column_headers'];
        $this->global           = $args['global'];
    }

    /**
	 * Get a list of CSS classes for the table tag.
	 * @return array List of CSS classes for the table tag.
	 */
    protected function get_table_classes() {
		return array( 'display', 'table', 'table-striped', 'media-table', $this->_class_table );
	}

    public function get_columns() {
        return $this->_column_headers;
    }

    protected function get_column_count() {
		return count($this->_column_headers);
	}

    /**
	 *
	 * @param object $item
	 * @param string $column_name
	 */
	protected function column_default($column_name, $item, $global) {
        if(isset($item->$column_name)) echo $item->$column_name;
    }

    /**
	 * Generate the table rows
	 * @access public
	 */
	public function display_rows() {
		foreach ($this->items as $item ) {
		    $this->single_row($item);
        }
	}

    /**
	 * Generates content for a single row of the table
	 * @access public
	 * @param object $item The current item
	 */
	public function single_row($item) {
		echo '<tr class="js_column tr_'.$item->id.'">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}

    /**
	 * Generates the columns for a single row of the table
	 * @access protected
	 * @param object $item The current item
	 */
	protected function single_row_columns( $item ) {
        $columns = $this->get_columns();
        foreach ( $columns as $column_name => $column_display_name ) {
            $classes = "$column_name column-$column_name";
            $attributes = "class='$classes'";

            if ( 'cb' === $column_name ) {
				echo '<td scope="row" class="check-column">';
				echo '<input class="icheck select" value="'.$item->id.'" type="checkbox" name="select[]">';
				echo '</td>';
			} elseif ( method_exists( $this, '_column_' . $column_name ) ) {
                echo call_user_func(array($this, '_column_' . $column_name), $item, $column_name, $this->module, $this->table, $classes);
            } elseif ( method_exists( $this, 'column_' . $column_name ) ) {
                echo "<td $attributes>";
				echo call_user_func( array( $this, 'column_' . $column_name ), $item, $column_name, $this->module, $this->table );
				echo "</td>";
            } else {
				echo "<td $attributes>";
				$this->column_default($column_name, $item, $this->global );
				echo "</td>";
			}
        }
	}

    /**
	 * Print column headers, accounting for hidden and sortable columns.
	 * @param bool $with_id Whether to set the id attribute or not
	 */
    public function print_column_headers( $with_id = true ) {
        
        $columns = $this->get_columns();

        foreach ( $columns as $column_key => $column_display_name ) {
            $class  = array( 'manage-column', "column-$column_key" );
            $id     = $with_id ? "id='$column_key'" : '';


            if($column_key == 'cb') {
                if(is_string($class)) {
                    $class .= 'check-column';
                }
                else {
                    $class[] = 'check-column';
                }
                $column_display_name = '<input type="checkbox" name="select[]" id="select_all" class="icheck">';
            }
            if(!empty($class)) $class = "class='" . join( ' ', $class ) . "'";
            echo "<th $id $class>$column_display_name</th>";
        }
    }

    public function display_rows_or_message()
    {
        if (have_posts($this->items) ) {
			$this->display_rows();
		} else {
			echo '<tr class="no-items"><td class="colspanchange" colspan="' . $this->get_column_count() . '">';
			?>
            <div class="text-center">
                <svg class="svg-next-icon mb-5 color-heather svg-next-icon-size-100" width="100" height="100"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 208 230"><defs><linearGradient id="linear-gradient" x1="133.69" y1="120.61" x2="73.06" y2="91.22" gradientTransform="matrix(1, 0, 0, -1, 0, 231.28)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#95abc2"></stop><stop offset="1" stop-color="#fff"></stop></linearGradient><mask id="mask" x="134.5" y="14.55" width="17.97" height="17.21" maskUnits="userSpaceOnUse"><g id="mask0"><path d="M137,24a8.1,8.1,0,0,1,8.1-8.1h6.3v6.3a8.1,8.1,0,0,1-8.1,8.1H137Z" fill="#fff"></path></g></mask></defs><title>Artwork</title><path d="M94.2,85.7l85.9,23.6v82.6l-85.9-37Z" fill="#8e99a3"></path><path d="M94.3,85.7,24.1,105.3v86.8l70.1-37.6Z" fill="#a0aab5"></path><path d="M139.4,185.9c-19.4-5.7-53.4-5.2-66.2-38.3-11.7-30.4,31.5-59.6,43.6-46.8,8.6,9.1-2.4,22.2-17.7,20.7C82,119.8,71.9,104.5,75.3,88c6.7-32.7,58.3-54.4,60.5-55.9" fill="none" stroke-width="3" stroke-dasharray="6 7" stroke="url(#linear-gradient)"></path><path d="M141,21.5s-.5-12.3-3.7-15.6a8.4,8.4,0,0,0-11.8,11.8C128.7,21,141,21.5,141,21.5Z" fill="#ced8e5" opacity="0.4"></path><path d="M146.7,26.4s.5,12.3,3.7,15.6a8.4,8.4,0,0,0,11.8-11.8C159.1,26.9,146.7,26.4,146.7,26.4Z" fill="#ced8e5" opacity="0.4"></path><path d="M137,24a8.1,8.1,0,0,1,8.1-8.1h6.3v6.3a8.1,8.1,0,0,1-8.1,8.1H137Z" fill="#a8b7c5"></path><g mask="url(#mask)"><rect x="140.1" y="16.7" width="2.3" height="16.69" transform="translate(23.6 107.2) rotate(-45)" fill="#dde5f2"></rect><path d="M139.1,16.2l1.6-1.6,11.8,11.8L150.9,28Z" fill="#dde5f2"></path></g><path d="M159.2,12.3l5.5-1.3" fill="none" stroke="#8696c5" stroke-width="0.9"></path><path d="M156.9,10.1l2.1-5.5" fill="none" stroke="#8696c5" stroke-width="0.9"></path><path d="M153.7,20.5a6.1,6.1,0,1,0-6.1-6.1A6.1,6.1,0,0,0,153.7,20.5Z" fill="#8b9aa8"></path><path d="M24.1,105.5,110,132.8V229L24.1,192Z" fill="#dae2ec"></path><path d="M180.5,109.2l-71.7,23.6V229l71.7-37Z" fill="#c5d0d9"></path><path d="M180.3,109.1,208,146.2l-73.6,29L109,132.6Z" fill="#dae2ec"></path><path d="M24,105.3,0,142.8l84.6,32.4,24.7-42.7Z" fill="#c5d0d9"></path></svg></svg>
                <h4>Không tìm thấy bất kỳ dữ liệu nào</h4>
            </div>
            <?php
			echo '</td></tr>';
		}
    }

    public function display(){
        ?>
        <div class="table-responsive">
            <table class="<?php echo implode( ' ', $this->get_table_classes() ); ?>">
                <thead>
                <tr><?php $this->print_column_headers(); ?></tr>
                </thead>
                <tbody>
                <?php $this->display_rows_or_message();?>
                </tbody>
            </table>
        </div>
        <?php
    }
}