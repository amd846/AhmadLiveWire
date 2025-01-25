<div>
    {{-- The Master doesn't talk, he acts. --}}
<div>
        {{-- If your happiness depends on money, you will never be happy with yourself. --}}
        <button class="btn btn-primary"  wire:click.prevent="moreTwo" >الرفض لعدم الدخول اكثر من يومين</button>
        <button class="btn btn-primary"  wire:click.prevent="acceptAdmin">قبول مبلغ من الادمن</button>
        <button class="btn btn-primary"  wire:click.prevent="acceptTwenty">قبول الاعمار بين 20 الى 30</button>



</div>

 


 
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
        Orders</button>

 
        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">
            Accepted</button>

        <button class="nav-link" id="nav-contact-tab" 
        data-bs-toggle="tab" data-bs-target="#nav-contact" 
        type="button" role="tab" aria-controls="nav-contact" aria-selected="false"
         >Rejected
        </button>

    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        
        <div style="display: flex; flex-direction: column; gap: 20px; margin-top: 20px;">
            <!-- Order Div with Buttons -->
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
            
        
            <!--   End Order Div with Input Field -->
           
           
     
        </div> 
    </div>
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

           <!-- Accepted Div with Buttons -->
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
                         @if($AcceptedOrders)
                        @foreach ($AcceptedOrders as $order)
                            
                        
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
        
        
            <!--   End Accpeted Div with Input Field -->

    </div>
    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            <!-- Rejected Div with Buttons -->
        
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
                             @if($RejectedOrders)
                            @foreach ($RejectedOrders as $order)
                                
                            
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
            
            <!--   End Rejected Div with Input Field -->
     </div>
</div>

















</div>
