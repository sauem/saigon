window.CRAWLER = {
    thumbs: [],
    selected: [],
    files: []
};
if (localStorage.getItem("css_dom")) {
    $("body").find("input[name='css_element']").val(localStorage.getItem("css_dom"));
}

function complieTemplate(idTemplate, data) {
    let html = $("#" + idTemplate).html();
    let template = Handlebars.compile(html);
    return template(data);
}

$("#btnCrawler").click(function () {
    let _url = $(this).parent().find("input[name='url']").val();
    let _element = $(this).parent().find("input[name='css_element']").val();
    let _saveClass = $(this).parent().find("input[name='save_css']").val();

    if (_saveClass === "on") {
        localStorage.setItem("css_dom", _element);
    }

    swal.fire({
        title: "Đang xử lý...",
        onBeforeOpen: function () {
            swal.showLoading();
            getContent(_url, _element).then(res => {
                const {thumbs, content, name} = res;
                CRAWLER.thumbs = thumbs;
                if (thumbs.length === 0) {
                    swal.fire("Opsss!", "Kết quả trống!", "info");
                    return;
                }
                $("#result-crawl").html(complieTemplate("item-template", thumbs));
                setTimeout(() => swal.close(), 1000);
            }).catch(error => {
                swal.fire("Oppssss!", error.message, "error");
            });
        }
    })
});
$("#selectAll").click(function () {
    let _selects = $("#result-crawl").find("input[type='checkbox']");
    if (_selects.length <= 0) {
        swal.fire("Oopsss!", "Chưa có cái quần què gì hết!", "info");
        return false;
    }
    $.each(_selects, function (index) {
        let checked = $(this).is(":checked");
        $(this).attr("checked", !checked);
    });
});
$("#applyAll").click(function () {
    let _selects = $("#result-crawl").find("input[type='checkbox']");
    let _keys = [];
    $.each(_selects, function (index) {
        if ($(this).is(":checked")) {
            let _key = $(this).data('key');
            _keys.push(_key);
        }
    });
    CRAWLER.thumbs = CRAWLER.thumbs.filter((item, index) => _keys.includes(index));
    if (_keys <= 0) {
        swal.fire("Oopsss!", "Chưa có cái quần què gì hết!", "info");
        return false;
    }
    applyThumbs().catch(error => swal.fire("Oopsss!", error.message, "error"));

});

function __printThumbs() {
    let {files} = window.CRAWLER;

    if (files.length > 0) {
        let ids = files.map(item => item.id)
        $("body").find(".product_images").append(complieTemplate("thumb-template", files));
        $("body").find("input[name='product_image_gallery']").val(ids.toString());
    }
}

async function applyThumbs() {
    return await swal.fire({
        title: 'Đang upload ảnh...',
        closeOnClickOutside : false,
        onBeforeOpen: () => {
            swal.showLoading();
            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                cache: false,
                data: {action: 'apply_thumbs', thumbs: CRAWLER.thumbs},
                success: res => {
                    swal.hideLoading();
                    if (res.success) {
                        window.CRAWLER.files = res.data.files;
                        swal.fire("success", res.data.msg, "success");
                      //  __printThumbs();
                    }
                }
            });
        }
    })
}

async function getContent(_url, _element) {
    let result = {
        thumbs: [],
        content: "",
        name: ""
    }
    await $.ajax({
        url: "/wp-admin/admin-ajax.php",
        type: "POST",
        cache: false,
        //  dataType: "json",
        //   timeout: 3600,
        data: {action: "crawler", url: _url, dom: _element},
        success: function (res) {
            const {data} = res;
            if (data.success) {
                const {thumbs, content, name} = data.result;
                result.thumbs = thumbs;
                result.name = name;
                result.content = content;
            }
        },
        error: function (xhr, status, error) {
            alert(error);
        },
    });
    return result;
}