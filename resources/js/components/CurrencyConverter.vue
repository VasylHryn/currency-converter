<template>
  <div class="currency-converter">
    <h1 class="title">Конвертер валют</h1>
    <form @submit.prevent="convertCurrency" class="form">
      <input
        v-model="amount"
        type="number"
        placeholder="Сумма"
        required
        class="input"
        @input="convertCurrency">
      <div class="currency-select">
        <select v-model="fromCurrency" required class="select" @change="convertCurrency">
          <option v-for="currency in currencies" :key="currency" :value="currency">{{ currency }}</option>
        </select>
        <span class="arrow">→</span>
        <select v-model="toCurrency" required class="select" @change="convertCurrency">
          <option v-for="currency in currencies" :key="currency" :value="currency">{{ currency }}</option>
        </select>
      </div>
      <button type="submit" class="button">Конвертировать</button>
    </form>
    <div v-if="convertedAmount !== null" class="result">
      <p>{{ amount }} {{ fromCurrency }} = {{ convertedAmount }} {{ toCurrency }}</p>
    </div>
  </div>
</template>



<script>
export default {
  data() {
    return {
  amount: '',
  fromCurrency: 'USD',
  toCurrency: 'EUR',
  currencies: [
    'USD', 'EUR', 'GBP', 'UAH', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'INR', 'MXN',
    'BRL', 'RUB', 'ZAR', 'SEK', 'NOK', 'DKK', 'SGD', 'HKD', 'NZD', 'TRY', 'PLN',
    'KRW', 'ILS', 'THB', 'IDR', 'PHP', 'MYR', 'CZK', 'HUF', 'SAR', 'AED'
  ],
  convertedAmount: null
};

  },
  methods: {
  async convertCurrency() {
    // Проверяем, что amount не пустой
    if (!this.amount || isNaN(this.amount)) {
      this.convertedAmount = null;
      return;
    }

    // Выполняем запрос на конвертацию
    const response = await fetch('/convert-currency', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        from: this.fromCurrency,
        to: this.toCurrency,
        amount: this.amount
      })
    });

    const data = await response.json();
    if (response.ok) {
      this.convertedAmount = data.converted_amount;
    } else {
      alert(data.error || 'Ошибка при конвертации');
    }
  }
}


};
</script>

<style scoped>
.currency-converter {
  display: flex;
  flex-direction: column;
  align-items: center;
  max-width: 450px;
  margin: 50px auto;
  padding: 30px;
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease-in-out;
}


.title {
  font-size: 2.2em;
  color: #333;
  margin-bottom: 25px;
  font-weight: 600;
}

.form {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 25px;
}

.input {
  padding: 12px;
  font-size: 1.2em;
  border-radius: 6px;
  border: 1px solid #ccc;
  outline: none;
  transition: border-color 0.3s;
}

.input:focus {
  border-color: #007bff;
}

.currency-select {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
}

.select {
  padding: 12px;
  font-size: 1.1em;
  border-radius: 6px;
  border: 1px solid #ccc;
  outline: none;
  width: 48%;
  transition: border-color 0.3s;
}

.select:focus {
  border-color: #007bff;
}

.arrow {
  font-size: 1.8em;
  color: #007bff;
  font-weight: 700;
}

.button {
  padding: 14px;
  font-size: 1.3em;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.button:hover {
  background-color: #0056b3;
  transform: scale(1.05);
}

.result {
  margin-top: 25px;
  font-size: 1.3em;
  color: #333;
  font-weight: 700;
}

@media (max-width: 600px) {
  .currency-converter {
    padding: 20px;
  }

  .title {
    font-size: 1.8em;
  }

  .input, .select, .button {
    font-size: 1em;
  }
}
</style>
