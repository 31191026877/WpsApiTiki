<?php
function renderContact() {
    ?>
    <script>
        $(function () {
            const tabPane = document.querySelectorAll(".tab-pane");
            tabPane.forEach((item) => {
                item.classList.add('thu-gon');
                const newDiv = document.createElement('div');
                const newBtnXemThem = document.createElement('a');
                newDiv.className = "xem-them";
                newBtnXemThem.className = "btn";
                newBtnXemThem.innerText = "Xem thêm";
                newDiv.appendChild(newBtnXemThem);
                item.appendChild(newDiv);
            });

            const listXemThem = document.querySelectorAll('.xem-them a');
            listXemThem.forEach((item) => {
                item.addEventListener('click', function () {
                    if(item.innerText === 'Xem thêm') {
                        item.innerText = 'Thu gọn';
                        const thugon = document.querySelectorAll('.tab-pane.thu-gon');
                        thugon.forEach((item) => {
                            item.classList.remove('thu-gon');
                            item.classList.add('mo-rong');

                        });
                    } else {
                        item.innerText = 'Xem thêm';
                        const morong = document.querySelectorAll('.tab-pane.mo-rong');
                        morong.forEach((item) => {
                            item.classList.remove('mo-rong');
                            item.classList.add('thu-gon');

                        })
                    }
                })
            })
        })
    </script>
    <style>
        .contact-detail {
            padding-bottom: 15px;
        }
        .products-detail .tab-content {
            padding: 10px 10px 50px;
            position: relative;
        }

        .nav-tabs {
            border: none !important;
        }

        .xem-them {
            position: absolute;
            content: "";
            bottom: 10px;
            display: flex;
            left: 50%;
            transform: translateX(-50%);
            justify-content: center;
            z-index: 10;
        }

        .xem-them .btn {
            display: block;
            color: var(--theme-color);
            padding: 5px 10px;
            border-radius: 4px;
            border: 1px solid var(--theme-color);
        }

        .tab-pane.thu-gon {
            max-height: 200px;
            overflow: hidden;
        }

    </style>
    <?php
}
add_action('product_detail_info', 'renderContact', 60);


function tabDetail($a) {

    $a['content']['title'] = 'Thông Tin Chi tiết';

    return $a;

};


add_filter('product_tabs', 'tabDetail', 10);