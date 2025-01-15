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
    <div style="display: flex; flex-direction: column; gap: 20px; margin-top: 20px;">
        <!-- First Div with Buttons -->
        <div>
            <button type="button" class="btn btn-primary" hidden>قبول الاعمار بين 20 الى 30</button>

            <form method="POST" action="{{ route('supervisor.betweenAge') }}">
                @csrf
                <button type="submit" class="btn btn-primary">قبول الاعمار بين 20 الى 30</button>

             </form>



            <form method="POST" action="{{ route('supervisor.adminMoney') }}">
                @csrf
                 
                <button type="submit" class="btn btn-primary">قبول مبلغ من الادمن</button>

             </form>

 
            <form method="POST" action="{{ route('supervisor.twoDays') }}">
                @csrf
                 
                <button   type="submit" class="btn btn-primary">الرفض لعدم الدخول اكثر من يومين</button>
            </form>

        </div>
    
        <!-- Second Div with Input Field -->
       
   

    <!-- Display Items -->
    <div class="table-container ">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>OnOff</th>
                    <th>اسم المستخدم</th>
                    <th>العمر</th>
                    <th>المبلغ المطلوب</th>
                    <th>تاريخ الطلب</th>
                    <th>  نسبة القبول </th>
                    <th>حالة الطلب</th>
                    <th> السبب</th>
                     
                    
                    
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
               
                @if($orders)
                @foreach ($orders as $order)
                    
                
                <tr>
                    <td>
                        @if (isset($order['OnOff']) && $order['OnOff'] === 'نشط')
        <span class="text-success">{{ $order['OnOff'] }}</span>
    @else
        <span class="text-danger">{{ $order['OnOff'] ?? 'غير متوفر' }}</span>
    @endif
                        
                    </td>
                    <td>{{ $order['UserName'] }}</td>
                    <td>{{ $order['Age'] }}</td>
                    <td>{{ $order['RequiredAmount'] }}</td>
                   {{-- <td>{{ $order['OrderDate'] }}</td> --}}
                   <td> 


           
                    @php
                    $orderDate = \Carbon\Carbon::parse($order['OrderDate']);
    $now = now();

    $yearsDiff = (int) $orderDate->diffInYears($now);
    $daysDiff = (int) $orderDate->diffInDays($now) % 365; // Exclude full years
    $hoursDiff = (int) $orderDate->diffInHours($now) % 24; // Exclude full days
    $minutesDiff = (int) $orderDate->diffInMinutes($now) % 60; // Exclude full hours
                @endphp
                
                @if ($yearsDiff > 0)
                    <span>{{ $yearsDiff }} سنة </span><span> {{ $daysDiff }} يوم  </span><span> {{ $hoursDiff }} ساعة   </span><span> {{ $minutesDiff }} دقيقة</span>
                @elseif ($daysDiff > 0)
                    <span>{{ $daysDiff }} يوم  </span> 
                    <br>
                    <span>  {{ $hoursDiff }} ساعة  </span> 
                    <br>
                    <span>  {{ $minutesDiff }} دقيقة</span>
                      
                @elseif ($hoursDiff > 0)
                    <span>{{ $hoursDiff }} ساعة  </span> <br> <span>  {{ $minutesDiff }} دقيقة</span>
                @elseif ($minutesDiff > 0)
                    <span>{{ $minutesDiff }} دقيقة</span>
                @else
                    <span>الآن</span>
                @endif
                
</td>



                    <td>{{ $order['PercentageAcceptance'] }}</td>
                    <td>{{ $order['OrderStatus'] }}</td>
                    <td>{{ $order['Reason'] }}</td>
                    <td>
                        <form method="POST" action="{{ route('order.destroy', $order['OrderID']) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @endif
              
            </tbody>
        </table>
    </div>

</div> 
  
   
</body>
</html>



<script>
/*
fetch(`/supervisor/inActiveThreeDays`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                       // alert(data.message);
                       // $('#withdrawModal').modal('hide');
                      //  location.reload(); // Reloads to show updated balance and withdrawal count
                    } else {
                        alert('Failed');
                    }
                })
                .catch(error => console.error('Error  :', error));




fetch(`/supervisor/moreSixty`, {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Content-Type': 'application/json',
    }
})
.then(response => {
    if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
    }
    return response.json();
})
.then(data => {
    if (data.success) {
      //  alert(data.message);
    } else {
        alert('Operation failed');
    }
})
.catch(error => console.error('Error:', error));
*/



</script>
