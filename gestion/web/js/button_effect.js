    document.querySelector("#actions").addEventListener("click", event);


function test(that) {
    that.classList.toggle('anim');
}

function event(e) {
    if (e.target !== e.currentTarget) {
        test(e.target);
        setTimeout(function () {
            test(e.target);
        }, 1000);
    }
}