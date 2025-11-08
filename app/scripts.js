// JavaScript untuk mengontrol scroll carousel
const carousel = document.querySelector('.carousel');

function movePrev() {
    carousel.scrollLeft -= 200; // Sesuaikan dengan lebar buku atau nilai yang sesuai
}

function moveNext() {
    carousel.scrollLeft += 200; // Sesuaikan dengan lebar buku atau nilai yang sesuai
}
