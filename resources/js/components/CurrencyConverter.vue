<template>
    <div class="currency-converter">
        <h1 class="title">Конвертер валют</h1>
        <form @submit.prevent="convertCurrency" class="form">
            <input v-model="amount" type="number" placeholder="Сумма" required class="input"/>
            <div class="currency-select">
                <select v-model="fromCurrency" required class="select">
                    <option v-for="currency in currencies" :key="currency" :value="currency">{{ currency }}</option>
                </select>
                <span class="arrow">→</span>
                <select v-model="toCurrency" required class="select">
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
            currencies: ['USD', 'EUR', 'GBP'],
            convertedAmount: null
        };
    },
    methods: {
        async convertCurrency() {
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
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f7f7f7;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.title {
    font-size: 2em;
    color: #333;
    margin-bottom: 20px;
}

.form {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.input {
    padding: 10px;
    font-size: 1.1em;
    border-radius: 4px;
    border: 1px solid #ccc;
    outline: none;
}

.input:focus {
    border-color: #007bff;
}

.currency-select {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.select {
    padding: 10px;
    font-size: 1.1em;
    border-radius: 4px;
    border: 1px solid #ccc;
    outline: none;
    width: 45%;
}

.arrow {
    font-size: 1.5em;
    color: #007bff;
}

.button {
    padding: 12px;
    font-size: 1.2em;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #0056b3;
}

.result {
    margin-top: 20px;
    font-size: 1.2em;
    color: #333;
    font-weight: bold;
}
</style>
