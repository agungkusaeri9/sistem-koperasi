@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    @if ($serverside == false)
        <script>
            $('#dtTable').DataTable({
                responsive: true
            });
        </script>
    @else
        {{ $slot }}
    @endif
@endpush
