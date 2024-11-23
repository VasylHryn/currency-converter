<template>
  <div class="currency-converter">
    <h1 class="title">Currency Converter</h1>
    <form @submit.prevent="convertCurrency" class="form">
      <input
        v-model="amount"
        type="number"
        placeholder="Amount"
        required
        class="input">
      <div class="currency-select">
        <select v-model="fromCurrency" required class="select">
          <option v-for="currency in currencies" :key="currency" :value="currency">{{ currency }}</option>
        </select>
        <span class="arrow">→</span>
        <select v-model="toCurrency" required class="select">
          <option v-for="currency in currencies" :key="currency" :value="currency">{{ currency }}</option>
        </select>
      </div>
      <button type="submit" class="button">Convert</button>
    </form>
    <div v-if="convertedAmount !== null" class="result">
      <p>{{ amount }} {{ fromCurrency }} = {{ convertedAmount }} {{ toCurrency }}</p>
    </div>
    <p class="credit">Developed by Vasyl Hryn</p>
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
      if (!this.amount || isNaN(this.amount)) {
        this.convertedAmount = null;
        return;
      }

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
        alert(data.error || 'Conversion error');
      }
    }
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap');

.currency-converter {
  display: flex;
  flex-direction: column;
  align-items: center;
  max-width: 600px;
  margin: 50px auto;
  padding: 30px;
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease-in-out;
  font-family: 'Jost';

}

.title {
  font-size: 2.5em;
  color: #1e2a3a;
  margin-bottom: 25px;
  font-weight: 600;
  text-align: center;
}

.form {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 50px;
}

.input {
  padding: 15px;
  font-size: 1.4em;
  border-radius: 8px;
  border: 1px solid #ddd;
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
  padding: 15px;
  font-size: 1.2em;
  border-radius: 8px;
  border: 2px solid #ddd;
  outline: none;
  width: 48%;
  transition: border-color 0.3s;
}



.arrow {
  font-size: 2em;
  color: #02794f;
  font-weight: 700;
}

.button {
  padding: 18px;
  font-size: 1.5em;
  background-color: #02794f;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.button:hover {
  background-color: #004f34;
  transform: scale(1.05);
}

.result {
  margin-top: 30px;
  font-size: 1.4em;
  color: #333;
  font-weight: 700;
  text-align: center;
}

.credit {
  font-size: 1em;
  color: #333;
  text-align: center;
  margin-top: 20px;
  font-weight: 500;
}

@media (max-width: 900px) {
  .currency-converter {
    padding: 20px;
  }

  .title {
    font-size: 2em;
  }

  .input, .select, .button {
    font-size: 1.2em;
  }
}
</style>
