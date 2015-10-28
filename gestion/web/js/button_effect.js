document.querySelector("#actions").addEventListener("click", function (e) {
    if (e.target !== e.currentTarget) {
        test(e.target);
        setTimeout(function () {
            test(e.target);
        }, 1000);
    }

});

function test(that) {
    that.classList.toggle('anim');
}