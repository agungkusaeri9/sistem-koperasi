@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('assets/sweetalert2/sweetalert2.all.min.js') }}"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: true,
                timer: 4500
            })
        </script>
    @endif

    @if (session('status'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('status') }}',
                showConfirmButton: true,
                timer: 4500
            })
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                timer: 4500
            })
        </script>
    @endif

    <script>
        $('body').on('click', '.btnDelete', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data yang sudah dihapus tidak bisa dikembalikan lagi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yakin',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let action = $(this).data('action');
                    $('#formDelete').attr('action', action);
                    $('#formDelete').submit();
                }
            })
        })
    </script>
@endpush
