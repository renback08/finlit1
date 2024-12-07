document.addEventListener("DOMContentLoaded", initExpenseTracker);

function initExpenseTracker() {
  const balance = document.getElementById("balance");
  const money_plus = document.getElementById("money-plus");
  const money_minus = document.getElementById("money-minus");
  const form = document.getElementById("form");
  const text = document.getElementById("text");
  const amount = document.getElementById("amount");
  const incomeBtn = document.getElementById('income-btn');
  const expenseBtn = document.getElementById('expense-btn');
  const incomeList = document.getElementById('income-list');
  const expenseList = document.getElementById('expense-list');

  const localStorageTransactions = JSON.parse(localStorage.getItem('transactions'));
  let transactions = localStorage.getItem('transactions') !== null ? localStorageTransactions : [];
  let transactionType = 'income';

  incomeBtn.addEventListener('click', () => {
    transactionType = 'income';
    incomeBtn.classList.add('active');
    expenseBtn.classList.remove('active');
  });

  expenseBtn.addEventListener('click', () => {
    transactionType = 'expense';
    expenseBtn.classList.add('active');
    incomeBtn.classList.remove('active');
  });

  text.addEventListener('input', function(e) {
    this.value = this.value.replace(/[^A-Za-z\s]/g, '');
  });

  amount.addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9.]/g, '');
  });

  function addTransaction(e) {
    e.preventDefault();
    if (text.value.trim() !== '' && amount.value.trim() !== '') {
      const transaction = {
        id: generateID(),
        text: text.value,
        amount: transactionType === 'income' ? +amount.value : -amount.value
      };
  
      transactions.push(transaction);
  
      addTransactionDOM(transaction);
      updateValues();
      updateLocalStorage();
  
      text.value = '';
      amount.value = '';
    }
  }
  
  function generateID() {
    return Math.floor(Math.random() * 1000000);
  }

  function formatNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  function addTransactionDOM(transaction) {
    const item = document.createElement('li');
    item.classList.add(transaction.amount < 0 ? 'minus' : 'plus');

    item.innerHTML = `
      ${transaction.text} <span> ₱${formatNumber(Math.abs(transaction.amount).toFixed(2))}</span>
      <button class="delete-btn">x</button>
    `;

    const deleteBtn = item.querySelector('.delete-btn');
    deleteBtn.addEventListener('click', () => removeTransaction(transaction.id));

    if (transaction.amount < 0) {
      expenseList.appendChild(item);
    } else {
      incomeList.appendChild(item);
    }
  }

  function updateValues() {
    const amounts = transactions.map(transaction => transaction.amount);
    const total = amounts.reduce((acc, item) => (acc += item), 0).toFixed(2);
    const income = amounts
      .filter(item => item > 0)
      .reduce((acc, item) => (acc += item), 0)
      .toFixed(2);
    const expense = (amounts
      .filter(item => item < 0)
      .reduce((acc, item) => (acc += item), 0) * -1)
      .toFixed(2);

    balance.innerText = `₱${formatNumber(total)}`;
    money_plus.innerText = `₱${formatNumber(income)}`;
    money_minus.innerText = `₱${formatNumber(expense)}`;
  }

  function removeTransaction(id) {
    transactions = transactions.filter(transaction => transaction.id !== id);
    updateLocalStorage();
    init();
  }

  function updateLocalStorage() {
    localStorage.setItem('transactions', JSON.stringify(transactions));
  }

  function init() {
    incomeList.innerHTML = '';
    expenseList.innerHTML = '';
    transactions.forEach(addTransactionDOM);
    updateValues();
  }

  init();

  form.addEventListener('submit', addTransaction);
}