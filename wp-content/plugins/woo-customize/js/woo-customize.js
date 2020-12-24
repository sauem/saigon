window.THUMBS = {
    thumbs: [],
    color: [],
    size: []
}
var initSelect2 = function (el = 'select2') {
    $("."+el).select2()
}

async function getAttributes() {
    return await $.ajax({
        url: '/wp-admin/admin-ajax.php',
        type: 'GET',
        data: {action: 'attributes'},
    })
}

$(".addImageColor").click(function () {
    alert('asdad')

});
$("body").on("click", ".btn-trash", function () {
    $(this).closest(".item").remove();
})