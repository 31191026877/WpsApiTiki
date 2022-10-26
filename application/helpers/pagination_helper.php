<?php 
/**
* libraries phân trang website
*/
class Pagination {

    protected int $currentPage = 1;

    protected int $totalRecords = 1;

    protected int $totalPages = 1;

    protected int $limit = 20;

    protected int $offset = 0;

    protected string $url;

    protected string $urlFirst;

    protected int $range = 5;

    protected int $minPage;

    protected int $maxPage;

    public function __construct($config = []) {

        if(!empty($config['currentPage'])) $this->currentPage = $config['currentPage'];

        if(!empty($config['totalRecords'])) $this->totalRecords = $config['totalRecords'];

        if(!empty($config['limit'])) $this->limit = $config['limit'];

        if(!empty($config['url'])) $this->url = $config['url'];

        if(!empty($config['urlFirst'])) $this->urlFirst = $config['urlFirst'];

        if(!empty($config['range'])) $this->range = $config['range'];

        if ($this->limit < 0) $this->limit = 0;

        if ($this->currentPage < 0) $this->currentPage = 1;

        if(!is_numeric($this->totalRecords)) $this->totalRecords = 0;

        $this->totalPages = (!empty($this->limit)) ? ceil($this->totalRecords / $this->limit) : 0;

        $this->offset = ($this->currentPage - 1)*$this->limit;

        $middle = ceil($this->range / 2);

        if ($this->totalPages < $this->range) {

            $this->minPage = 1;

            $this->maxPage = $this->totalPages;
        }
        else
        {
            $this->minPage = $this->currentPage - $middle + 1;

            $this->maxPage = $this->currentPage + $middle - 1;

            if ($this->minPage < 1) {

                $this->minPage = 1;

                $this->maxPage = $this->range;
            }
            else if ($this->maxPage > $this->range)
            {
                $this->maxPage = $this->totalPages;

                $this->minPage = $this->totalPages - $this->range + 1;
            }
        }
    }

    public function offset() {
        return $this->limit*$this->currentPage - $this->limit;
    }

    public function currentPage() {
        return $this->currentPage;
    }

    public function totalPages(): float|int {
        return $this->totalPages;
    }

    public function createLink($page) {
        if ($page <= 1 && !empty($this->urlFirst)) return $this->urlFirst;
        if(str_contains($this->url, '{paging}')) {
            return str_replace('{paging}', $page, $this->url);
        }
        else {
            return str_replace('{page}', $page, $this->url);
        }
    }

    public function backend(): string {

        if($this->totalRecords <= $this->limit) return '';

        $html =  apply_filters('admin_pagination_start', '<nav><ul class="pagination">');

        // Nút prev và first
        if ($this->currentPage > 1) {
            $html .= apply_filters('admin_pagination_first', '<li class="page-item"><a class="page-link" href="'.$this->createLink('1').'" aria-label="Previous"><i class="fal fa-chevron-double-left"></i></a></li>', $this->createLink('1') );
            $html .= apply_filters('admin_pagination_prev', '<li class="page-item"><a class="page-link" href="'.$this->createLink($this->currentPage - 1).'">Prev</a></li>', $this->createLink($this->currentPage - 1));
        }

        //nút prev và first disabled
        if ($this->currentPage == 1) {
            $html .= apply_filters('admin_pagination_first_disabled', '<li class="page-item disabled"><span class="page-link"><i class="fal fa-chevron-double-left"></i></span></li>');
            $html .= apply_filters('admin_pagination_prev_disabled',  '<li class="page-item disabled"><span class="page-link">Prev</span></li>');
        }

        // lặp trong khoảng cách giữa min và max để hiển thị các nút
        for ($i = $this->minPage; $i <= $this->maxPage; $i++)  {
            // Trang hiện tại
            if ($this->currentPage == $i) {
                $html .= apply_filters('admin_pagination_item_active', '<li class="page-item active" aria-current="page"><span class="page-link">'.$i.'</span></li>', $i);
            }
            else{
                $html .= apply_filters('admin_pagination_item', '<li class="page-item"><a class="page-link" href="'.$this->createLink($i).'">'.$i.'</a></li>', $this->createLink($i), $i);
            }
        }

        // Nút last và next
        if ($this->currentPage < $this->totalPages) {
            $html .= apply_filters('admin_pagination_next', '<li class="page-item"><a class="page-link" href="'.$this->createLink($this->currentPage + 1).'">Next</a></li>', $this->createLink($this->currentPage + 1) );
            $html .= apply_filters('admin_pagination_last', '<li class="page-item"><a class="page-link" href="'.$this->createLink($this->totalPages).'" aria-label="Next"><span aria-hidden="true"><i class="fal fa-chevron-double-right"></i></span></a></li>', $this->createLink($this->totalPages) );
        }

        $html .=    apply_filters('admin_pagination_end', '</ul></nav>');

        return $html;
    }

    public function frontend(): string {

        if($this->totalRecords <= $this->limit) return '';

        $html =  apply_filters('pagination_start', '<nav><ul class="pagination">');

        // Nút prev và first
        if ($this->currentPage > 1) {
            $html .= apply_filters('pagination_first', '<li><a class="pagination-item" href="'.$this->createLink('1').'" data-page-number="1"><i class="fal fa-chevron-double-left"></i></a></li>', $this, 1);
            $html .= apply_filters('pagination_prev', '<li><a class="pagination-item" href="'.$this->createLink($this->currentPage-1).'" data-page-number="'.($this->currentPage - 1).'">Prev</a></li>', $this, $this->currentPage - 1);
        }

        //nút prev và first disabled
        if ($this->currentPage == 1) {
            $html .= apply_filters('pagination_first_disabled', '<li class="disabled"><span aria-hidden="true"><i class="fal fa-chevron-double-left"></i></span></li>');
            $html .= apply_filters('pagination_prev_disabled',  '<li class="disabled"><span aria-hidden="true">Prev</span></li>');
        }

        // lặp trong khoảng cách giữa min và max để hiển thị các nút
        for ($i = $this->minPage; $i <= $this->maxPage; $i++) {
            // Trang hiện tại
            if ($this->currentPage == $i){
                $html .= apply_filters('pagination_item_active', '<li class="active"><span>'.$i.'</span></li>', $this, $i);
            }
            else{
                $html .= apply_filters('pagination_item', '<li><a class="pagination-item" href="'.$this->createLink($i).'" data-page-number="'.$i.'">'.$i.'</a></li>', $this, $i);
            }
        }

        // Nút last và next
        if ($this->currentPage < $this->totalPages) {
            $html .= apply_filters('pagination_next', '<li><a class="pagination-item" href="'.$this->createLink($this->currentPage + 1).'" data-page-number="'.($this->currentPage+1).'">Next</a></li>', $this );
            $html .= apply_filters('pagination_last', '<li><a class="pagination-item" href="'.$this->createLink($this->totalPages).'" data-page-number="'.($this->totalPages).'"><i class="fal fa-chevron-double-right"></i></a></li>', $this );
        }

        $html .=    apply_filters('pagination_end', '</ul></nav>');

        return $html;
    }

    public function html(): string {
        if(Admin::is())
            return $this->backend();
        else
            return $this->frontend();
    }
}