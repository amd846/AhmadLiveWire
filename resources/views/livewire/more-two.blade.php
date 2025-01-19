<div>
     
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
     <button class="btn btn-primary"  wire:click.prevent="moreTwo" >الرفض لعدم الدخول اكثر من يومين</button>

     
</div>


<script>
    document.addEventListener('refreshRejectedTab', () => {
   // Livewire.emit('$refresh');
});
    </script>