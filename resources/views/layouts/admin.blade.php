<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Font Awesome (for legacy admin components) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Layout CSS (Reusing Siswa Layout for consistency) -->
    <link rel="stylesheet" href="{{ asset('css/siswa/layout.css') }}">

    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    
    
    
    <!-- Page Specific CSS -->
    @yield('styles')
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Admin Sidebar Component -->
    @include('components.admin-sidebar')

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header Component -->
        @include('components.header', [
            'title' => $pageTitle ?? 'Admin Dashboard',
            'subtitle' => $pageSubtitle ?? 'Panel Administrasi'
        ])

        <!-- Content -->
        <div class="content-container">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (Needed for some admin components like modal events in table.blade.php) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Layout JS -->
    <script src="{{ asset('js/siswa/layout.js') }}"></script>
    {{-- Script --}}
    <script src="{{ asset('js/alpine.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js"></script>
    
    <!-- Page Specific JS -->
    @yield('scripts')
    <script>
        window.addEventListener('swal:alert', event => {
            console.log('test');
            const icon=event.detail.icon || 'info';
            let textColor='text-white';
            let bgColor;
            switch(icon)
            {
                case 'error' :
                    bgColor='bg-danger';
                    break;
                case 'success' :
                    bgColor='bg-success';
                    break;
                case 'warning' :
                    bgColor='bg-warning';
                    textColor='text-dark';
                    break;
                default : 
                    bgColor='bg-info';
                    break;
            }
            Swal.fire({
                icon: event.detail.icon || 'info',
                title: event.detail.title || 'Info',
                text: event.detail.text || '',
                iconColor : textColor==='text-dark' ? 'black' : 'white',
                toast: true,
                showConfirmButton : false,
                timerProgressBar: true,
                timer: 3000,
                position: "top",
                customClass : {
                    popup : `${bgColor} ${textColor}`,
                    title : `${textColor} ml-2 mt-2`,
                    htmlContainer : `${textColor} ml-2`,
                    
                },
                didOpen: ()=> {
                    
                }
            });
        });

        window.addEventListener('swal:confirm', event => {
            Swal.fire({
                title: event.detail.title || 'Konfirmasi',
                text: event.detail.text || '',
                icon: event.detail.icon || 'warning',
                
                
                showCancelButton: true,
                confirmButtonText: event.detail.confirmButtonText || 'Ya',
                cancelButtonText: event.detail.cancelButtonText || 'Batal',
                
            }).then((result) => {
                if(result.isConfirmed) event.detail.method()
            });
        });
    </script>
    
    @if(session()->has('success'))
        
        <script>
            console.log('Success')
            setTimeout(()=>{window.dispatchEvent(new CustomEvent('swal:alert', {detail : @json(session('success'))}))}, 400)
        </script>
    @endif
    @if(session()->has('alert'))
        <script>
            console.log('Alerting')
            alert("{{ session('alert')['text'] }}")
        </script>
    @endif
    @if(session()->has('error'))
    
        <script>
            console.log('Error')
            setTimeout(()=>{window.dispatchEvent(new CustomEvent('swal:alert', {detail : @json(session('error'))}))}, 400)
        </script>
    @endif
</body>
</html>
