<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centered Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa; /* Light gray background */
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <form method="POST" action="{{ route('order.store') }}">
        @csrf
        <div class="form-row align-items-center">
            <div class="col-auto">
                <label class="sr-only" for="inlineFormInput">المبلغ</label>
                <input 
                    type="number" 
                    name="userRequiredAmount" 
                    class="form-control mb-2" 
                    id="inlineFormInput" 
                    placeholder="إدخل المبلغ المطلوب"
                >
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-2">تقديم الطلب</button>
            </div>
        </div>
    </form>
</body>
</html>
