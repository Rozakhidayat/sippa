<script>
  document.addEventListener('success', function(event) {
                Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: event.detail.message, 
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
                });
            });

        document.addEventListener('error', function(event) {
            Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            
            title: event.detail.message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
            });
            });

  document.addEventListener('show-delete-confirmation', () => {
              Swal.fire({
              title: "Yakin hapus data?",
              text: "Data tidak bisa dikembalikan!",
              icon: "warning",
              showCancelButton: true,
              confirmButtonText: "Ya, hapus!",
              cancelButtonText: "Batal"
              }).then((result) => {
              if (result.isConfirmed) {
              Livewire.dispatch('deleteConfirmed');
              }
              });
              });
</script>

<script>
  document.addEventListener('openModalCreate', () => {
      let modalEl = document.getElementById('create');
      let modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
      });
      
      document.addEventListener('openEditModal', () => {
      let modalEl = document.getElementById('edit');
      let modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
      });

        document.addEventListener('closeModal', () => {
        document.querySelectorAll('.modal.show').forEach((modalEl) => {
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
        });
        });

        document.addEventListener('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        document.body.style = '';
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        });
</script>

@if (session()->has('success'))
<script>
  document.addEventListener('livewire:navigated', () => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: "{{ session('success') }}", 
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true
                    });
                }, { once: true }); 
</script>
@endif

@if (session()->has('error'))
<script>
  document.addEventListener('livewire:navigated', () => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: "{{ session('error') }}", 
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true
                    });
                }, { once: true }); 
</script>
@endif