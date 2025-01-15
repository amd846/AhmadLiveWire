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
   
    <!-- Display Items -->
    <div class="table-container">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>رقم الطلب</th>
                    <th>المبلغ المطلوب</th>
                    <th>تاريخ الطلب</th>
                    <th>حالة الطلب</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @if($orders)
                <tr>
                    <td>{{ $orders->id }}</td>
                    <td>{{ $orders->userRequiredAmount }}</td>
                    <td>{{ $orders->userOrderDate }}</td>
                    <td>{{ $orders->userOrderStatus }}</td>
                    <td>
                        <form method="POST" action="{{ route('order.destroy', $orders->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                        </form>
                    </td>
                </tr>
                @endif
              
            </tbody>
        </table>
    </div>

    
  
   
</body>
</html>
