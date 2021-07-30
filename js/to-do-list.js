// Create a "close" button and append it to each list item
var myNodelist = document.getElementsByClassName("li-program");
var i;
for (i = 0; i < myNodelist.length; i++) {
    var span = document.createElement("SPAN");
    var txt = document.createTextNode("\u00D7");
    span.className = "close";
    span.appendChild(txt);
    myNodelist[i].appendChild(span);
}

// Click on a close button to hide the current list item
var close = document.getElementsByClassName("close");
// console.log('close: ' + close);
var i;
for (i = 0; i < close.length; i++) {
    close[i].onclick = function () {
        var div = this.parentElement;
        // console.log('div: ' + div);

        // div.style.display = "none";
        div.remove();
    }
}

// Add a "checked" symbol when clicking on a list item
var list = document.querySelector('#program-list');
list.addEventListener('click', function (ev) {
    if (ev.target.tagName === 'LI') {
        ev.target.classList.toggle('checked');
    }
}, false);


// Create a new list item when clicking on the "Add" button
function newElement() {
    var li = document.createElement("li");
    li.className = 'li-program';
    var inputValue = document.getElementById("typehead_program").value;
    var t = document.createTextNode(inputValue);
    li.appendChild(t);
    if (inputValue === '') {

        Swal.fire({
            type: 'error',
            title: 'Seleccione un programa de la lista o agregue uno nuevo',
            showConfirmButton: false,
            timer: 1500
        })

    } else {
        document.getElementById("program-list").appendChild(li);
    }
    document.getElementById("typehead_program").value = "";

    var span = document.createElement("SPAN");
    var txt = document.createTextNode("\u00D7");
    span.className = "close";
    span.appendChild(txt);
    li.appendChild(span);

    for (i = 0; i < close.length; i++) {
        close[i].onclick = function () {
            var div = this.parentElement;
            div.style.display = "none";
        }
    }
}