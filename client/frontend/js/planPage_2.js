/* ======================
   DOM ELEMENTS
====================== */
const price = Number(document.getElementById("price")?.dataset.price ?? 0);

const quantityEl = document.getElementById("quantity");
const btnIncrease = document.getElementById("btnIncrease");
const btnDecrease = document.getElementById("btnDecrease");

const telephoneEl = document.getElementById("telephone");
const dateInput = document.getElementById("day");

const subtotalEl = document.getElementById("subtotal");
const taxEl = document.getElementById("tax");
const totalEl = document.getElementById("total_price");

const inputQuantity = document.getElementById("input_quantity");
const inputSubtotal = document.getElementById("input_subtotal");
const inputTax = document.getElementById("input_tax");
const inputTotal = document.getElementById("input_total");

/* ======================
   STATE
====================== */
let quantity = Number(quantityEl.innerText);

/* ======================
   INIT
====================== */
initDate();
updateState();

/* ======================
   EVENT LISTENERS
====================== */
btnIncrease.addEventListener("click", () => {
  quantity++;
  updateState();
});

btnDecrease.addEventListener("click", () => {
  if (quantity > 1) quantity--;
  updateState();
});

telephoneEl.addEventListener("input", handleTelephoneInput);

/* ======================
   FUNCTIONS
====================== */
function initDate() {
  const today = new Date();
  const formattedToday = [today.getFullYear(), String(today.getMonth() + 1).padStart(2, "0"), String(today.getDate()).padStart(2, "0")].join("-");

  dateInput.value = formattedToday;
  dateInput.min = formattedToday;
}

function updateState() {
  toggleDecreaseButton();

  const subtotal = quantity * price;
  const tax = subtotal * 0.1;
  const total = subtotal + tax;

  quantityEl.innerText = quantity;

  subtotalEl.innerHTML = formatPrice(subtotal);
  taxEl.innerHTML = formatPrice(tax);
  totalEl.innerHTML = formatPrice(total);

  syncHiddenInputs({ quantity, subtotal, tax, total });
}

function toggleDecreaseButton() {
  const disabled = quantity <= 1;
  btnDecrease.classList.toggle("pointer-events-none", disabled);
  btnDecrease.classList.toggle("bg-gray-200", disabled);
}

function handleTelephoneInput(e) {
  const numbersOnly = e.target.value.replace(/\D/g, "");

  let formatted = "";
  if (numbersOnly.length <= 3) {
    formatted = numbersOnly;
  } else if (numbersOnly.length <= 7) {
    formatted = `${numbersOnly.slice(0, 3)}-${numbersOnly.slice(3)}`;
  } else {
    formatted = `${numbersOnly.slice(0, 3)}-${numbersOnly.slice(3, 7)}-${numbersOnly.slice(7, 11)}`;
  }

  e.target.value = formatted;
}

function syncHiddenInputs({ quantity, subtotal, tax, total }) {
  inputQuantity.value = quantity;
  inputSubtotal.value = subtotal;
  inputTax.value = tax;
  inputTotal.value = total;
}

function formatPrice(value) {
  return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
