<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Generator</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

</head>
<body style="background-image: url({{ asset('assets/img/background.png') }})">
<div class="container">
    <div class="invoice-form">
        <h2 class="text-center mb-4">Генератор счетов</h2>
        <form action="{{ route('invoice.generate') }}" id="generateInvoiceForm" method="POST">
            @csrf
            <div class="form-group">
                <label for="template">Шаблон:</label>
                <select class="form-control" id="template" name="template" required>
                    <option value=""></option>
                    @foreach($templates as $key => $name)
                        <option value="{{ $key }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="number">Номер счета:</label>
                <input type="text" class="form-control" id="number" name="number" required>
            </div>
            <div class="form-group">
                <label for="amount">Сумма:</label>
                <input type="number" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="form-group">
                <label for="date">Дата:</label>
                <input type="text" class="form-control" id="date" name="date" placeholder="dd.mm.yyyy" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Создать</button>
        </form>
    </div>
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
</div>
</body>
</html>
