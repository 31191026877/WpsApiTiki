<?php
/**
 * @---version ::3.5.0
 * @param CORE - Update PHP 8.0
 * @param CORE - Update CMS 5.0
 * @param CORE - Chuyển trình hiển thị sản phẩm thương hiệu thành trang sản phẩm
 * @param CORE - Chuyển trình hiển thị sản phẩm nhà sản xuất thành trang sản phẩm
 * @param CORE - Thêm filter controllers_product_index_data thay đổi function lấy controllers sản phẩm trang index
 * @param TEMPLATE - Thêm filter product_detail_heading_related điều chỉnh tiêu đề "sản phẩm liên quan"
 * @param TEMPLATE - Thêm filter product_detail_heading_watched điều chỉnh tiêu đề "sản phẩm đã xem"
 * @---version ::3.4.0
 * @param BACKEND - Thêm xem đường link vào table thương hiệu trường name
 * @param BACKEND - Thêm trường thương hiệu vào danh sách khi thêm menu trong admin
 * @param CORE - Cấu trúc lại các chức năng trong admin thành từng module
 * @param CORE - Đổi Class Brand thành Brands
 * @param CORE - Đổi Class Supplier thành Suppliers
 * @param CORE - Thay đổi tên, class và xóa tên hook của phiên bản củ
 * @param CORE - Thêm tùy chọn "product_fulltext_search"
 * @param CORE - Hỗ trợ input popover advance dạng product từ cms version 4.6.0
 * @param TEMPLATE 	- Thiết kế lại giao diện chia sẽ mạng xã hội
 * @---version ::3.3.0
 * @param CORE 	 - Thêm hot key F2 thêm sản phẩm
 * @param CORE 	 - Thêm hot key F3 thêm danh mục sản phẩm
 * @param CORE 	 - Thêm hot key CTRL + F3 thêm danh mục sản phẩm nhanh
 * @param CORE 	 - Thêm chức năng thêm danh mục sản phẩm nhanh
 * @---version ::3.2.2
 *  @param CORE 	 - Bổ sung setting sản phẩm bán chạy vào cấu hình
 * @---version ::3.2.1
 * @param CORE 	 - Khi sửa sản phẩm quay lại vẫn ở vị trí củ
 * @param CORE 	 - Cài đặt hiển thị danh mục sản phẩm, sản phẩm bán chạy, nổi bật, khuyến mãi ở sidebar
 * @param CORE 	 - Cài đặt hiển thị danh mục sản phẩm, nội dung content trên, nội dung content dưới
 * @param CORE 	 - Thêm layout 3
 * @---version ::3.2.0
 * @param CORE 	 - Gở bỏ một số cài đặt không còn dùng
 * @param CORE 	 - Thêm cài đặt cho vị trí sản phẩm liên quan
 * @param CORE 	 - Thêm cài đặt cho sản phẩm đã xem
 * @param CORE 	 - Thêm input_popover cho brand (Thương hiệu)
 * @param CORE 	 - Fix lỗi insert supplier phiên bản PHP 7.3
 * @---version ::3.1.1
 * @param CORE 	 - Lấy sản phẩm liên quan chính xác hơn
 * @---version ::3.1.0
 * @param CORE 	 - Fix lỗi xóa danh mục không xóa các liên kết
 * @param CORE 	 - Bổ sung filter admin_product_field_related chèn field vào metabox related
 * @param CORE 	 - Tối ưu tốc độ lưu products
 * @param CORE 	 - Fix cập nhật status (yêu thích, nổi bật, bán chạy) không được
 * @param CORE 	 - Fix xóa danh mục sản phẩm tự động xóa danh mục trong menu
 * @param CORE 	 - Bổ sung trường brand_id vào function insert product
 * @param BACKEND - Bổ sung trường title vào item product detail
 * @param BACKEND - Bổ sung đa ngôn ngữ vào item product detail
 * @---version ::3.0.6
 * @param CORE 	 - Cập nhật module products
 * @---version ::3.0.5
 * @param CORE 	 - bổ sung cache breadcrumb
 * @---version ::3.0.0
 * @param CORE 	 - Update function product sang class product
 * @param CORE 	 - Update function product category sang class product category
 * @param CORE 	 - Update function brands sang class brand
 * @param CORE 	 - Update function suppliers sang class supplier
 * @param CORE 	 - Update function suppliers sang dạng form builder
 * @param TEMPLATE	 - Chuyển trang danh sách sản phẩm thành load ajax
 * @---Template ::2.0.0
 * @param Detail - Thêm share sản phẩm bằng OR code
 * @---version ::2.2.1
 * @param CORE 	 - Fix lỗi kích hoạt phiên bản php 7.3
 * @param CORE 	 - UPDATE lỗi insert product PHP 7.3
 * @param CORE 	 - UPDATE lỗi insert brands PHP 7.3
 * @param CORE 	 - Fix lỗi template
 * @param CORE 	 - Fix lỗi count product đếm cả sản phẩm trong thùng rác dẫn đến phân trang sai.
 * @param BACKEND 	 - UPDATE kiểu nhóm sản phẩm "yêu thích, bán chạy, nổi bật" lại với nhau
 * @---version ::2.2.0
 * @param CORE 	 - Chuyển nhiều hàm từ wcmc sang scmc
 * @param CORE 	 - Fix lỗi khi xóa brands xóa sai các thông tin liên quan.
 * @param CORE 	 - Thêm mô tả cho thương hiệu
 *
@---version ::2.1.0
 * @param CORE 	    - Fix một số lỗi lấy dữ liệu.
 * @param CORE 	    - Fix lỗi xóa doanh mục category_id kiểu số databse kiểu chữ.
 * @param CORE 	    - Fix lỗi params không tác dụng khi sử dụng điều kiện tree (wcmc_gets_category)
 * @param CORE 	    - Đổi Tên một số Fuction
 *                      wcmc_get_category           ------> get_product_category
 *                      wcmc_gets_category          ------> gets_product_category
 *                      wcmc_delete_category        ------> delete_product_category
 *                      wcmc_delete_list_category   ------> delete_list_product_category
 *                      woocommerce_controllers_products_index ------> controllers_product_index
 *                      woocommerce_controllers_products_detail ------> controllers_product_detail
 * @param CORE 	    - Add thêm quản lý thương hiệu
 * @param TEMPLATE 	- Add thêm quản lý layout
@---database ::1.6
 * @param ADD thêm table brands
 * @param ADD thêm trường brand_id vào table products
@---version ::2.0.5
 * @param BACKEND 	- Chỉ hiển thị trường "Nhà sản xuất" khi có dữ liệu thương hiệu
 * @param BACKEND 	- Thêm tùy chọn bật tắt "Nhà sản xuất"
 * @param CORE 	    - Thêm biến "product_id" vào hàm wcmc_gets_category : lấy tất cả danh mục của sản phẩm có id là "product_id"
 * @param FONTEND 	- Fix lỗi sản phẩm đã xem ở đa ngôn ngữ
@---version ::2.0.4
 * @param DATABASE 	- Thêm trường dữ liệu weight (cân nặng) cho table products
 * @param BACKEND 	- Thêm trường dữ liệu weight (cân nặng) vào form thêm và chỉnh sửa sản phẩm
 * @param CORE 	    - Thêm trường dữ liệu weight (cân nặng) vào hàm insert_product
 * @param LANGUAGE 	- Thêm thư mục language - ngôn ngữ tiếng anh
@---Template ::1.1.3
 *  @param template/detail/tabs.php Thêm key language cho tiêu đề các tab
 *  @param template/detail/related.php Thêm key language cho tiêu đề và hàm load file giao diện để có thể sửa trong template
@---version ::2.0.3
 * @param CORE 	- Cập nhật các hàm get, gets, count, insert, delete product phù hợp với wcmc cart 2.5.0
 * @param CORE 	- Cập nhật các hàm gets, count lấy dữ liệu theo taxonomy và attribiue
@---version ::2.0.2
 * @param FONTEND 	- Bổ sung biến status vào trang danh sách sản phẩm, tìm sản phẩm theo trạng thái
@---version ::2.0.1
 * @param FONTEND 	- Fix lỗi search sản phẩm
 * @param FONTEND 	- Thêm page danh sách sản phẩm theo nhà sản xuất
 * @param BACKEND 	- Fix không lưu được ảnh nhà sản xuất
 * @param BACKEND 	- Cập nhật hàm gets_product và count_product theo chuẩn cms 3.0.0
@---Template ::1.1.2
@---version ::2.0.0
 * @param BACKEND 	- Tách cài đặt sản phẩm ra khỏi woocommerce cài đặt
 * @param BACKEND 	- UPDATE lưu setting bằng ajax
 * @param CORE 	    - ADD quyền wcmc_product_setting quyền quản lý cấu hình sản phẩm.
 * @param CORE 	    - ADD function wcmc_delete_category, wcmc_delete_list_category
 * @param CORE 	    - ADD function delete_product, delete_list_product
 * @param CORE 	    - ADD function get_product_category_meta, update_product_category_meta, delete_product_category_meta
 * @param CORE 	    - MOVE các file setting (admin/views) vào thư mục admin/views/setting
 * @param CORE 	    - FIX lỗi lấy sai danh mục, sản phẩm trong một số trường hợp đặc biệt.
 * @param CORE 	    - ADD thêm mã code vào product
 * @param CORE 	    - ADD thêm quản lý nhà sản xuất
@---database ::1.3
 * @param ADD thêm table suppliers
 * @param ADD thêm trường supplier_id vào table products
 * @param ADD thêm trường code vào table products
=======================================================================================
@---version ::1.9.0
 * @param BACKEND 	- Thay đổi kiểu chọn danh mục sản phẩm thành kiểu popover (chỉ tác dụng từ cms.v.2.5.4).
 * @param CORE 	    - Thêm file wcmc-role quản lý phân quyền thông qua plugin roles editor.
@---Template ::1.1.1
* @param template/detail/related.php        Thêm class products-related	
* @param template/detail/related_slider.php Thêm button next prev
* @param template/widget/viewed_product.php Thêm class products-viewed
=======================================================================================
@---version ::1.8.9
 * @param CORE 	    - Fix lỗi hàm wcmc_get_category lấy danh mục sản phẩm thuộc đa ngôn ngữ sai.</p>

@---Template ::1.1.0
 * @param template/version.php          Thêm file version hiển thị thông tin template: 	
 * @param template/index/products.php   Thêm div row : template/index/products.php (19)
 * 
=======================================================================================
@---version ::1.8.8
 * @param CORE 	    - Fix lỗi hàm count_product sai làm phân trang bị sai.</p>
 * @param CORE 	    - Fix lỗi hàm gets_product khi truyền taxonomy vào lấy dữ liệu sai.</p>
 * @param CORE 	    - Thêm chế độ gets_product lấy sản phẩm thuộc category và taxonomy cùng lúc.</p>*/