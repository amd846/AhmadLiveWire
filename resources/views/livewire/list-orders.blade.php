<div>
    {{-- Do your work, then step back. --}}
    
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
