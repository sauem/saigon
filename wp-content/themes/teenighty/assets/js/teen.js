$(".owl-carousel.slider-index").owlCarousel({
    items: 1
})

$(".owl-carousel.article").owlCarousel({
    items: 3,
    0: {
        items: 1,
    },
    480: {
        items: 1,
    },
    768: {
        items: 2,
    }
})

$(".owl-carousel.list-thumbs").owlCarousel({
    items: 4,
    0: {
        items: 1,
    },
    480: {
        items: 1,
    },
    768: {
        items: 4,
    }
})