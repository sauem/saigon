<?php
add_action('admin_enqueue_scripts', function () {
    if (is_admin())
        wp_enqueue_media();
});

?>
<button type="button" class="set_custom_images button">Chọn ảnh sản phẩm</button>

<div class="">
    <hr>
    <div id="result-thumb">

    </div>
</div>
<script id="thumbnail-template" type="text/x-handlebars-template">
    {{#each this.thumbs}}
        <div class="item">
        <div class="type">
            <p>Mẫu ảnh <a href="javascript:;" class="btn-trash">Xóa</a></p>
            <img src="{{this}}" width="200">
            <button class="set_custom_images button">Chọn thumb</button>
        </div>
        <div class="attr">
            <p>Màu sắc</p>
            <hr>
            <div class="color">
                {{#each ../color.variants}}
                <div class="item-color">
                    <button type="button" class="bagde colorThumb button">{{this.name}} </button>
                    <a href="javascript:;" class="removeColor">x</a>
                </div>
                {{/each}}
            </div>
            <div class="clearfix"></div>
            <hr>
           <div class="size">
               <p>Kích thước</p>
               <select multiple class="select2" style="width: 100%">
                   {{#each ../size.variants}}
                   <option selected>{{this.name}}</option>
                   {{/each}}
               </select>
           </div>
            <hr>
            <div style="display:flex;">
                <div>
                    <p>Giá sản phẩm</p>
                    <input type="text" placeholder="Giá gốc">
                </div>
                <div>
                    <p>Giá sản phẩm</p>
                    <input type="text" placeholder="Giá khuyến mãi">
                </div>
            </div>
        </div>
    </div>
    {{/each}}
</script>
<script>
    jQuery(document).ready(function () {
        var $ = jQuery;
        initSelect2();
        if ($('.set_custom_images').length > 0) {
            if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
                $('.set_custom_images').on('click', function (e) {
                    e.preventDefault();
                    var button = $(this);
                    var id = button.prev();

                    getAttributes().then(res => {
                        wp.media.editor.open(button);
                        if (res.success) {
                            wp.media.editor.send.attachment = function (props, attachment) {
                                //print thumbnails
                                window.THUMBS.thumbs.push(attachment.url);
                                window.THUMBS.color = res.data.color;
                                window.THUMBS.size = res.data.size;
                            };

                            wp.media.frame.on('insert', function(e) {
                                $("#result-thumb").html(complieTemplate("thumbnail-template",THUMBS));
                                initSelect2();
                            });
                        }
                    })

                    return false;
                });
            }
        }
        $("body").on("click",".removeColor",function () {
            alert("Adas");
        });
        $("body").on("click",".colorThumb", function () {
            alert("color thumb");
        });
    });
</script>