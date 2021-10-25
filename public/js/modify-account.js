let input = document.getElementById('button-modify');
let form = document.getElementById('modify');
input.onclick = showForm;

function showForm(){
    input.style.height = "0";
    input.style.opacity = "0";
    form.style.height = "350px";
}