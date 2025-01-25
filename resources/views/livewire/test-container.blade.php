<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    @livewire('btn-accept-admin')

     
    @livewire('btn-more-two')
    
    @livewire('btn-accept-twenty')
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
          <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
            @livewire('Bt1')</button>
          <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">
            @livewire('Bt2')</button>

            <button class="nav-link" id="nav-contact-tab" 
            data-bs-toggle="tab" data-bs-target="#nav-contact" 
            type="button" role="tab" aria-controls="nav-contact" aria-selected="false"
             > @livewire('Bt3')
           </button>
 
        </div>
      </nav>
      <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            
            <div style="display: flex; flex-direction: column; gap: 20px; margin-top: 20px;">
                <!-- First Div with Buttons -->
            
            
                <!--    Second Div with Input Field -->
               
               
        @livewire('tab1')
        
        </div> 
        </div>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
          @livewire('tab2') 
        </div>
        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
          
          @livewire('tab3')
        </div>
      </div>

</div>

<script>
  function refreshRejectedTab() {
      // Trigger the Livewire component refresh
     // Livewire.find('RejectedTab');
    //  const component = Livewire.find('rejected-tab'); // Use the id specified in the Blade file
    //    component.$refresh();
   // Livewire.emit('RejectedTab');

   // alert(component);
  }
</script>
