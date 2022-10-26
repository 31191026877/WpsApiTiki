<?php
/**
 * @version ::3.1.0
 * - Update giao diện trang chi tiết đơn hàng của người dùng
 * - Fix lỗi khi đặt hàng có biến thể nhưng không chọn đầy đủ thuộc tính bị lỗi
 * - Fix lỗi không thể chuyển đơn hàng sang trạng thái giao hàng thất bại
 * - Fix lỗi update từ version 2.6.1 lên phiên bản mới nhất
 * - Fix lỗi không thay đổi được icon payment BACS và COD
 * - Fix lỗi xóa Nhóm thuộc tính trong chi tiết sản phẩm làm xóa hết thuộc tính trong các biến thể
 * - Tích hợp plugin "product options image" thành chức năng mặc định
 * @version ::3.0.0
 * - Update thay đổi giao diện in đơn hàng
 * - Add chức năng in đơn hàng hàng loạt và in từng đơn ở trang danh sách đơn hàng
 * - Add hook action "admin_order_table_column_action" thêm ở cột action table list đơn hàng
 * - Add trạng thái đơn hàng giao hàng thất bại
 * - Add chức năng báo cáo doanh thu
 * - Fix lỗi không hủy đơn hàng được
 * @version ::2.8.3
 * - Fix Không xóa được attribute item vừa add vào attribute lúc chưa lưu lại
 * - Fix Xóa Attribute bị lỗi không xóa được attribute item kèm theo
 * - Fix Khi thêm mới attribute item cuối bị thay thế bằng attribute item liền kề.
 * - Update object type của attribute ở language và router từ wcmc_attribute thành attributes
 * @version ::2.8.0
 * - Tối ưu code admin order
 * - Tối ưu code update core
 * - Tối ưu code khi active và uninstall
 * - Remove key woocommerce trong các quyền role
 * @---template ::2.0.0
 * - Thay đổi các class woocommerce thành page-cart
 * @version ::2.7.7
 * - Fix lỗi load phường xã khi thêm đơn hàng trong admin
 * - Fix lỗi không hiển thị thông tin ngân hàng khi chọn hình thức thanh toán BACS
 * - Fix lỗi không xóa được thông tin ngân hàng.
 * - Add khi thêm đơn hàng trong admin có thể tìm sản phẩm theo mã sản phẩm
 * @version ::2.7.6
 * - Fix lỗi không hiển thị cài đặt đơn hàng
 * @---version ::2.7.0
 * @param CORE 	    - Thay đổi cấu trúc thư mục file các chức năng admin
 * @param CORE 	    - Update function insert attribute fix compact php 7.3
 * @param CORE 	    - Update function insert attribute item fix compact php 7.3
 * @param BACKEND   - Update thay đổi trang thêm sưa attribute
 * @param CORE      - Fix một số lỗi đặt hàng biến thể
 * woocommerce_before_cart => cart_before
 * woocommerce_before_cart_table => cart_before_table
 * woocommerce_before_cart_contents => cart_before_contents
 * woocommerce_after_cart_contents => cart_after_contents
 * woocommerce_after_cart_table => cart_after_table
 * woocommerce_after_cart => cart_after
 * woocommerce_cart_review_order_after => cart_review_order_after
 * woocommerce_cart_review_order_before => cart_review_order_before
 * woocommerce_cart_review_order => cart_review_order

 * woocommerce_before_checkout => checkout_before
 * wcmc_checkout_content => checkout_content
 *
 * woocommerce_before_checkout_billing_form => checkout_before_billing_form
 * checkout_billing_form => * new filters
 * woocommerce_after_checkout_billing_form => checkout_after_billing_form
 *
 * woocommerce_before_checkout_shipping_form => checkout_before_shipping_form
 * checkout_shipping_form => * new filters
 * woocommerce_after_checkout_shipping_form => checkout_after_shipping_form
 *
 * @---version ::2.6.3
 * @param CORE 	    - Fix lỗi xóa tùy chọn
 * @---version ::2.6.2
 * @param CORE 	    - Fix một số lỗi nhỏ.
 * @param CORE 	    - Gơm thông tin khách hàng và user lại với nhau.
 * @param CORE 	    - Thay đổi một số trạng thái đơn hàng
 *         Thêm trạng thái thanh toán:
 *                  - Chờ thanh toán
 *                  - Đã thanh toán
 *                  - Đã hoàn tiền
 *          Chuyển đổi trạng thái
 *                  - Đang xử lý -> Đang đóng gói
 *                  - Chờ thanh toán -> Đang vận chuyển
 * @param CORE 	    - Add chức năng hủy đơn hàng
 * @---version ::2.6.1
 * @param CORE 	    - Sử dụng hàm được hỗ trợ để insert page và delete page
 * @param CORE 	    - Fix function cập nhật customer không gây lỗi khi tắt plugin wcmc-cart đi
 * @param DATABASE 	- Cập nhật database order_count,order_total,customer cho phép NULL
@---version ::2.6.0
 * @param DATABASE 	- Thêm trường dữ liệu weight (cân nặng) cho table products
 * @param DATABASE 	- Thêm Table order history
 * @param BACKEND 	- Thêm trường dữ liệu weight (cân nặng) cho các biến thể
 * @param BACKEND 	- Lưu dữ liệu weight (cân nặng) của sản phẩm vào đơn hàng khi đặt hàng
 * @param CORE 	    - FIX hàm wcmc_shipping_states_provinces trả về mảng thay vì trả vì chuổi rỗng
 * @param CORE 	    - Bổ sung lịch sử đơn hàng.
 * @param CORE 	    - Bổ sung function thao tác với lịch sử đơn hàng.
 * @param CORE 	    - Fix lỗi insert_order metadata kiểu array bị lỗi.
 * @param BACKEND 	- Fix lỗi lấy sản phẩm trong trang thêm đơn hàng (admin)
 * @param BACKEND 	- Fix lỗi xóa sản phẩm lỗi không tồn tại table sản phẩm biến thể
 * @param BACKEND 	- Fix lỗi tìm kiếm khách hàng để thêm đơn hàng lỗi không tìm được.
 * @param BACKEND 	- Fix lỗi sữa attributes item
 * @param BACKEND 	- Fix xóa attributes item
 * @param CORE 	    - Add filter "woocommerce_checkout_metadata_order_before_save" thay đổi metadata lưu vào đơn hàng
 * @param LANGUAGE 	- Fix ngôn ngữ sản phẩm khi thêm vào giỏ hàng
 * @param LANGUAGE 	- Fix ngôn ngữ attributes
 * @param LANGUAGE 	- Fix ngôn ngữ attributes item
@---template ::1.4.1
 * @param admin/order/detail/history.php  Thêm history cho chi tiết đơn hàng
 * @param admin/order/save/shipping.php  Thêm chọn loại phí ship cho trang thêm đơn hàng (admin)
 * @param cart/cart-item.php  Fix đa ngôn ngữ
 * @param heading-bar.php  Fix đa ngôn ngữ các bước
 * @param cart/cart-heading.php  Fix đa ngôn ngữ các bước
@---version ::2.5.1
* @param FRONTEND - CHANGE phương thức thông báo lỗi trang checkout
* @param FRONTEND - ADD thông báo lỗi cho thông tin giao hàng khi chọn giao hàng tới địa chỉ khác
* @param FRONTEND - ADD filter "checkout_fields_rules" quản lý các rule filters.
@---version ::2.5.0
* @param CORE 	    - CHANGE dữ liệu table variation được gợp chung vào table product
* @param CORE 	    - CHANGE dữ liệu table metadata variation được gợp chung vào metadata product
* @param CORE 	    - xóa table variation và variation meta
* @param CORE 	    - FIX lỗi đặt hàng email
* @param CORE 	    - FIX sữa attribute không load được ngôn ngữ 2
@---template ::1.4.0
 * @param detail/cart-variations.php  sữa lỗi $option['name'] thành $option['title']
 * @param detail/ajax_price_variation.php  sữa các biến _price, _price_sale thành price, price_sale
@---version ::2.4.1
 * @param CORE 	    - Fix insert_attribute đa ngôn ngữ bị lỗi
@---version ::2.4.0
 * @param CORE 	    - Fix wcmc_get_order khi truyền operator
 * @param CORE 	    - Thay đổi phương thức xử lý đơn hàng đồng bộ và dễ sử dụng hơn
 *                  wcmc_get_order                      => get_order
 *                  wcmc_gets_order                     => gets_order
 *                  wcmc_count_order                    => count_order
 *                  wcmc_update_order                   => update_order
 *                  wcmc_delete_order_by_id             => delete_order_by_id
 *                  wcmc_get_item_order                 => get_order_item
 *                  wcmc_gets_item_order                => gets_order_item
 *                  wcmc_delete_order_item_by           => delete_order_item_by
 *                  woocommerce_order_status            => order_status
 *                  woocommerce_order_status_label      => order_status_label
 *                  woocommerce_order_status_color      => order_status_color
 * @param CORE 	    - Tách cài đặt đơn hàng ra khỏi cài đặt sản phẩm
 * @param CORE 	    - Thêm quyền wcmc_order_setting quyền quản lý cấu hình đơn hàng.
 * @param CORE 	    - Fix lỗi khi up từ phiên bản 1.1 lên phiên bản mới bị mất thông tin khách hàng.
 * @param CORE 	    - Chuyển option thành attributes
 * @param CORE 	    - Tích hợp các phương thức lấy tỉnh thành quận huyện
 * @param CORE 	    - Tích hợp vận chuyển vào woocomerce cart
 * @param CORE 	    - Tích hợp tỉnh thành, quận huyện vào thông tin khách hàng.
 * @param BACKEND 	- Thêm cấu hình gửi email cho đơn hàng.
 * @param FRONTEND 	- Thêm tỉnh thành quận huyện vào các trường đặt hàng.
 @--template ::1.3.3
 * @param heading-bar.php   sữa lỗi css logo, đường dẫn logo.
 * @param admin/order/detail/sidebar-customer   Thêm thông tin tỉnh thành quận huyện.
@---version ::2.3.3
 * @param CORE 	    - Thêm phương thức operator vào wcmc_gets_order và wcmc_get_order
 * @param CORE 	    - Thêm hàm count_customer điếm số khách hàng
 * @param BACKEND 	- Thêm dashboard thống kê đơn hàng.
 * @param BACKEND 	- Điều chỉnh tìm kiếm đơn hàng từ điều kiện POST thành GET
@--template ::1.3.1
* @param admin/order/html-order-index   Đổi form tìm kiếm submit post thành submit get
@---version ::2.3.2
 * @param CORE 	    - Thêm function wcmc_get_template_version lấy version template
 * @param CORE 	    - Chuyển 1 số hàm wcmc_order_status từ admin order vào order helper.
 * 
@--template ::1.3.0
 * @param cart/...              Thay đổi giao diện trang giỏ hàng 
 * @param cart/cart-heading.php Thêm heading cho trang giỏ hàng
 * @param checkout/...          Thay đổi giao diện trang thanh toán
 * @param empty.php             Thay đổi giao diện trang empty
 * @param admin/order           Đổi các trường search thông tin đơn hàng có thể tùy biến.
 * @param Bỏ thao tác cập nhật thủ công thành ajax
@---version ::2.3.1
 * @param FRONTEND 	- Fix lỗi không có sản phẩm vẫn đặt hàng được.
 * @param BACKEND 	- Cập nhật sắp xếp thứ tự option item.
 * @param BACKEND 	- Thêm chức năng tạo đơn hàng trong admin.
 * @param BACKEND 	- Thêm chức năng quản lý khách hàng.
 *                      + Tự động thêm khách hàng khi đặt hàng
 *                      + Tự động cộng tiền cho khách hàng khi đơn hàng hoàn thành.
 *                      + Xem thông tin khách hàng.
 *                      + Cập nhật thông tin khách hàng
 *                      + Thêm khách hàng.
 *                      + Kích hoạt tài khoản
 *                      + Đổi mật khẩu tài khoản
 *                      + Block tài khoản //TODO
 * @param BACKEND 	- Thêm chức năng phân trang đơn hàng.
 * @param CORE 	    - Thêm filter wcmc_page_success_token giúp cập nhật token ở trang hoàn thành đơn hàng khi cần thiết.
 * @param CORE 	    - Fix hàm insert_order bị lỗi thay đổi status đơn hàng về chờ xét duyệt khi update mà không có tham số status.
 * @param CORE 	    - Fix hàm insert_order bị lỗi không update được người tạo đơn hàng.
 * @param CORE 	    - Thêm hàm wcmc_print_notice_label lấy message thông báo không template.
 * @param CORE 	    - Thêm hàm wcmc_count_order đếm số đơn hàng.
 * @param CORE 	    - Thêm file wcmc-role quản lý phân quyền thông qua plugin roles editor.
 * @param CORE 	    - Thêm một số quyền mới
 *                      + customer: customer_list           - xem danh sách khách hàng
 *                      + customer: customer_active         - Kích hoạt tài khoản khách hàng (tạo user và pass đăng nhập)
 *                      + customer: customer_add            - Thêm khách hàng mới
 *                      + customer: customer_edit           - Cập nhật thông tin khách hàng
 *                      + customer: customer_reset_password - Đặt lại password cho tài khoản khách hàng.
 * 
 *                      + order: wcmc_order_add - Thêm mới đơn hàng
 *                      + order: wcmc_order_copy - Nhân bản đơn hàng
 * 
 *                      + option: wcmc_attrattributes_add - Thêm mới tùy chọn.
@--template ::1.2.2
 * @param admin/order/html-order-index.php Thêm filter admin_order_action_bar_heading dưới đơn hàng. 
 * @param admin/order/save Thêm template của chức năng thêm, nhân bản order
 *        admin/order/html-order-save.php
 *        admin/order/save/product-items.php        //Danh sách sản phẩm
 *        admin/order/save/customer.php             //Khung chứ thông tin billing và shipping
 *        admin/order/save/customer-infomation.php  //thông tin billing và shipping
 *        admin/order/save/payments.php             //Danh sách hình thức thanh toán
 *        admin/order/save/amount-review.php        //Xem trước các phí ship, khuyến mãi, thanh toán
 * @param notices/eror.php Chỉnh sữa đồng bộ với thông báo error hệ thống.
 * @param admin/order/html-order-index.php Bổ sung phân trang.
@--database ::1.6
* @param cle_users Thêm colum order_total :: tính tổng tiền đã chi tiêu
* @param cle_users Thêm colum order_count :: tính tổng số đơn hàng cho user
* @param cle_users Thêm colum customer    :: thành viên là khách hàng
==========================================================================================================================
@---version ::2.3.0
 * @param CORE 	    - Thêm hàm order_detail_billing_info, order_detail_shipping_info để get dữ liệu khách hàng của đơn hàng.
 * @param BACKEND 	- Thêm chức năng in đơn hàng ở trang chi tiết đơn hàng.
@--template ::1.2.1
 * @param admin/order/detail/note.php               Fix lỗi không hiển thị hình thức thanh toán                       
 * @param admin/order/html-order-print.php          Add file template giao diện khi in đơn hàng                      
 * @param admin/order/detail/sidebar-customer.php   Đổi cách lấy thông tin khách hàng để bên thứ 3 có thể can thiệp   
 * 
 * 
 * 
 * 
@---version ::2.2.9
 * @param BACKEND 	- Fix không search được đơn hàng.
 * @param BACKEND 	- Thêm filter woocommerce_order_index_args giúp cập nhật điều kiện lấy đơn hàng khi cần thiết.
 * @param BACKEND 	- Thêm điều kiện tìm kiếm đơn hàng theo trạng thái.
 * @param CORE 	    - Fix hàm wcmc_gets_order khi lấy đơn hàng theo điều kiện meta_query với compare là LIKE sinh ra lỗi.
 * @param CORE 	    - Fix đa ngôn ngữ không nhận ngôn ngữ ở chi tiết đơn hàng.
@--template ::1.2.0
 * @param admin/order/html-order-index.php  Add trường search trạng thái đơn hàng
 * @param version.php                       Thêm file version hiển thị thông tin template	
 * 
 * */