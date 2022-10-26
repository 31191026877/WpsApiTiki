<div class="box">
	<div class="box-content">
        <section class="ui-layout__section">
            <header class="ui-layout__title"><h2>Địa chỉ</h2></header>
        </section>

        <section class="ui-layout__section" style="overflow: hidden;">

            <div class="form-group col-md-12" id="box_address">
                <label for="address" class="control-label">Địa chỉ</label>
                <div class="group">
                    <input type="text" name="address" value="<?php echo Request::post('address');?>" placeholder="Địa chỉ của bạn." id="address" class="form-control ">
                </div>
            </div>
            <div class="form-group col-md-12" id="box_city">
                <label for="city" class="control-label">Tỉnh / Thành Phố</label>
                <div class="group">
                    <select name="city" class="form-control " id="city">
                        <option value="0">Chọn tỉnh thành</option>
                        <option value="HA-NOI">Hà Nội</option>
                        <option value="HO-CHI-MINH">Hồ Chí Minh</option>
                        <option value="HA-GIANG">Hà Giang</option>
                        <option value="CAO-BANG">Cao Bằng</option>
                        <option value="BAC-KAN">Bắc Kạn</option>
                        <option value="TUYEN-QUANG">Tuyên Quang</option>
                        <option value="LAO-CAI">Lào Cai</option>
                        <option value="DIEN-BIEN">Điện Biên</option>
                        <option value="LAI-CHAU">Lai Châu</option>
                        <option value="SON-LA">Sơn La</option>
                        <option value="YEN-BAI">Yên Bái</option>
                        <option value="HOA-BINH">Hoà Bình</option>
                        <option value="THAI-NGUYEN">Thái Nguyên</option>
                        <option value="LANG-SON">Lạng Sơn</option>
                        <option value="QUANG-NINH">Quảng Ninh</option>
                        <option value="BAC-GIANG">Bắc Giang</option>
                        <option value="PHU-THO">Phú Thọ</option>
                        <option value="VINH-PHUC">Vĩnh Phúc</option>
                        <option value="BAC-NINH">Bắc Ninh</option>
                        <option value="HAI-DUONG">Hải Dương</option>
                        <option value="HAI-PHONG">Hải Phòng</option>
                        <option value="HUNG-YEN">Hưng Yên</option>
                        <option value="THAI-BINH">Thái Bình</option>
                        <option value="HA-NAM">Hà Nam</option>
                        <option value="NAM-DINH">Nam Định</option>
                        <option value="NINH-BINH">Ninh Bình</option>
                        <option value="THANH-HOA">Thanh Hóa</option>
                        <option value="NGHE-AN">Nghệ An</option>
                        <option value="HA-TINH">Hà Tĩnh</option>
                        <option value="QUANG-BINH">Quảng Bình</option>
                        <option value="QUANG-TRI">Quảng Trị</option>
                        <option value="THUA-THIEN-HUE">Thừa Thiên Huế</option>
                        <option value="DA-NANG">Đà Nẵng</option>
                        <option value="QUANG-NAM">Quảng Nam</option>
                        <option value="QUANG-NGAI">Quảng Ngãi</option>
                        <option value="BINH-DINH">Bình Định</option>
                        <option value="PHU-YEN">Phú Yên</option>
                        <option value="KHANH-HOA">Khánh Hòa</option>
                        <option value="NINH-THUAN">Ninh Thuận</option>
                        <option value="BINH-THUAN">Bình Thuận</option>
                        <option value="KON-TUM">Kon Tum</option>
                        <option value="GIA-LAI">Gia Lai</option>
                        <option value="DAK-LAK">Đắk Lắk</option>
                        <option value="DAK-NONG">Đắk Nông</option>
                        <option value="LAM-DONG">Lâm Đồng</option>
                        <option value="BINH-PHUOC">Bình Phước</option>
                        <option value="TAY-NINH">Tây Ninh</option>
                        <option value="BINH-DUONG">Bình Dương</option>
                        <option value="DONG-NAI">Đồng Nai</option>
                        <option value="BA-RIA-VUNG-TAU">Bà Rịa - Vũng Tàu</option>
                        <option value="LONG-AN">Long An</option>
                        <option value="TIEN-GIANG">Tiền Giang</option>
                        <option value="BEN-TRE">Bến Tre</option>
                        <option value="TRA-VINH">Trà Vinh</option>
                        <option value="VINH-LONG">Vĩnh Long</option>
                        <option value="DONG-THAP">Đồng Tháp</option>
                        <option value="AN-GIANG">An Giang</option>
                        <option value="KIEN-GIANG">Kiên Giang</option>
                        <option value="CAN-THO">Cần Thơ</option>
                        <option value="HAU-GIANG">Hậu Giang</option>
                        <option value="SOC-TRANG">Sóc Trăng</option>
                        <option value="BAC-LIEU">Bạc Liêu</option>
                        <option value="CA-MAU">Cà Mau</option>
                    </select>
                </div>
            </div>

            <div class="form-group col-md-12" id="box_districts">
                <label for="districts" class="control-label">Quận Huyện</label>
                <div class="group">
                    <select name="districts" class="form-control " id="districts"></select>
                </div>
            </div>
        </section>

	</div>
</div>

<script>
    $(function() {
        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                settings.data += '&csrf_test_name=' + encodeURIComponent(getCookie('csrf_cookie_name'));
            }
        });
        $('#city').select2();
        $('#districts').select2();
        $('#city').change(function() {
            let data = {
                province_id : $(this).val(),
                action: 'Cart_Ajax::loadDistricts'
            };
            $jqxhr   = $.post(ajax, data, function() {}, 'json');
            $jqxhr.done(function(response) {
                if(response.status === 'success') {
                    $('#districts').html(response.data);
                }
            });
        });
    });
</script>