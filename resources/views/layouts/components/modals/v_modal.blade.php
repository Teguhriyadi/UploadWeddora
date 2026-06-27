<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content logout-modal">
            <div class="modal-body p-0">
                <button class="logout-modal-close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="logout-modal-body">
                    <div class="logout-modal-icon">
                        <i class="fa fa-sign-out-alt"></i>
                    </div>

                    <div class="logout-modal-title" id="logoutModalLabel">
                        Yakin ingin logout?
                    </div>

                    <div class="logout-modal-text">
                        Anda akan keluar dari dashboard dan perlu login kembali untuk melanjutkan aktivitas.
                    </div>

                    <div class="logout-modal-actions">
                        <button class="btn btn-light logout-btn-secondary" type="button" data-dismiss="modal">
                            Tetap di sini
                        </button>
                        <a class="btn btn-primary logout-btn-primary" href="{{ url('/logout') }}">
                            <i class="fa fa-sign-out-alt mr-1"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
