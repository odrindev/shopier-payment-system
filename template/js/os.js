$(document).ready(
  function () {
      var loader = document.querySelector('#loader');
      var page = document.querySelector('#page');
      loader.style.display = 'none';
      page.style.display = 'block';
  }
);

function amount() {
  const price = document.getElementById('price').value;
  const pay = document.getElementById('payto');
  const total = price + ".00";
  pay.innerHTML = total;
}