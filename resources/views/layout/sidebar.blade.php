<nav class="sidebar sidebar-offcanvas dynamic-active-class-disabled pt-3" id="sidebar">
    <ul class="nav">
        <li class="nav-item {{ active_class(['dashboard']) }}">
            <a class="nav-link" href="{{ route('dashboard.index') }}">
                <i class="menu-icon mdi mdi-television"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        @can('visitor')
            <li class="nav-item {{ active_class(['appointment']) }}">
                <a class="nav-link" href="{{ route('portal') }}">
                    <i class="menu-icon mdi mdi-comment-plus-outline"></i>
                    <span class="menu-title">Create Appointment</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['appointment/history']) }}">
                <a class="nav-link" href="{{ route('appointment.history') }}">
                    <i class="menu-icon mdi mdi-ticket-confirmation"></i>
                    <span class="menu-title">My Appointment</span>
                </a>
            </li>
        @endcan

        @can('approver')
            <li class="nav-item {{ active_class(['approval']) }}">
                <a class="nav-link" href="{{ route('ticket.index') }}">
                    <i class="menu-icon mdi mdi-comment-check-outline"></i>
                    <span class="menu-title">Ticket Approval</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['approval/history']) }}">
                <a class="nav-link" href="{{ route('ticket.history') }}">
                    <i class="menu-icon mdi mdi-history"></i>
                    <span class="menu-title">History</span>
                </a>
            </li>
            {{-- <li class="nav-item {{ active_class(['book-room']) }}">
                <a class="nav-link" href="{{ route('room.index') }}">
                    <i class="menu-icon mdi mdi-door"></i>
                    <span class="menu-title">Book Room</span>
                </a>
            </li> --}}
        @endcan
        @can('superadmin')
            <li class="nav-item {{ active_class(['department']) }}">
                <a class="nav-link" href="{{ route('department.index') }}">
                    <i class="menu-icon mdi mdi-account-group"></i>
                    <span class="menu-title">Departement</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['user']) }}">
                <a class="nav-link" href="{{ route('user.index') }}">
                    <i class="menu-icon mdi mdi-account"></i>
                    <span class="menu-title">User Management</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['approval']) }}">
                <a class="nav-link" href="{{ route('ticket.index') }}">
                    <i class="menu-icon mdi mdi-comment-check-outline"></i>
                    <span class="menu-title">Ticket Approval</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['approval/history']) }}">
                <a class="nav-link" href="{{ route('ticket.history') }}">
                    <i class="menu-icon mdi mdi-history"></i>
                    <span class="menu-title">History</span>
                </a>
            </li>
            {{-- <li class="nav-item {{ active_class(['book-room']) }}">
            <a class="nav-link" href="{{ route('room.index') }}">
                <i class="menu-icon mdi mdi-door"></i>
                <span class="menu-title">Book Room</span>
            </a>
        </li> --}}
        @endcan
        @can('admin')
            <li class="nav-item {{ active_class(['card']) }}">
                <a class="nav-link" href="{{ route('card.index') }}">
                    <i class="menu-icon mdi mdi-id-card"></i>
                    <span class="menu-title">Card Dashboard</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['qrScanView', 'qrScan', 'cardScan']) }}">
                <a class="nav-link" href="{{ route('qrScanView.index') }}">
                    <i class="menu-icon mdi mdi-qrcode-scan"></i>
                    <span class="menu-title">Scan QR Code</span>
                </a>
            </li>
        @endcan

        @can('GA')
            <li class="nav-item {{ active_class(['facility/history']) }}">
                <a class="nav-link" href="{{ route('facility.history') }}">
                    <i class="menu-icon mdi mdi-history"></i>
                    <span class="menu-title">History</span>
                </a>
            </li>
        @endcan
    </ul>
</nav>
