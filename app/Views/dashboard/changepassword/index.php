<?= $this->extend('dashboard/templates/dashboard'); ?>
<?= $this->section('title'); ?>
<div class="d-flex justify-content-start align-items-center">
    <a class="fs-5 me-3 link-body-emphasis" href="<?= base_url('/settings'); ?>"><i class="fa-solid fa-arrow-left"></i></a>
    <span class="fw-medium fs-5 flex-fill text-truncate"><?= $headertitle; ?></span>
    <div id="loadingSpinner" class="spinner-border spinner-border-sm" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<div style="min-width: 1px; max-width: 1px;"></div>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-3 px-md-4 pt-3">
    <div class="d-xxl-flex justify-content-center">
        <div class="no-fluid-content">
            <div class="alert alert-info rounded-3" role="alert">
                <div class="d-flex align-items-start">
                    <div style="width: 12px; text-align: center;">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div class="w-100 ms-3">
                        Kata sandi harus minimal 3 karakter. Sesi yang menggunakan akun Anda selain dari perangkat ini akan dihapus setelah mengganti kata sandi. Disarankan untuk menggunakan kata sandi kuat demi keamanan.
                    </div>
                </div>
            </div>
            <div class="alert alert-warning rounded-3" id="capsLockStatus" role="alert" style="display: none;">
                <div class="d-flex align-items-start">
                    <div style="width: 12px; text-align: center;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="w-100 ms-3">
                        <strong><em>CAPS LOCK</em> AKTIF!</strong> Harap periksa status <em>Caps Lock</em> pada papan tombol (<em>keyboard</em>) Anda.
                    </div>
                </div>
            </div>
            <?= form_open_multipart('/settings/changepassword/update', 'id="changePasswordForm"'); ?>
            <fieldset class="border rounded-3 px-2 py-0">
                <legend class="float-none w-auto mb-0 px-1 fs-6 fw-bold">Kata Sandi Pengguna</legend>
                <div class="form-floating mb-2">
                    <input type="password" class="form-control rounded-3 <?= (validation_show_error('current_password')) ? 'is-invalid' : ''; ?>" id="current_password" name="current_password" placeholder="current_password">
                    <label for="current_password">Kata Sandi Lama</label>
                    <div class="invalid-feedback">
                        <?= validation_show_error('current_password'); ?>
                    </div>
                </div>
                <div class="form-floating mb-2">
                    <input type="password" class="form-control rounded-3 <?= (validation_show_error('new_password1')) ? 'is-invalid' : ''; ?>" id="new_password1" name="new_password1" placeholder="new_password1">
                    <label for="new_password1">Kata Sandi Baru</label>
                    <div class="invalid-feedback">
                        <?= validation_show_error('new_password1'); ?>
                    </div>
                </div>
                <div class="form-floating mb-2">
                    <input type="password" class="form-control rounded-3 <?= (validation_show_error('new_password2')) ? 'is-invalid' : ''; ?>" id="new_password2" name="new_password2" placeholder="new_password2">
                    <label for="new_password2">Konfirmsi Kata Sandi Baru</label>
                    <div class="invalid-feedback">
                        <?= validation_show_error('new_password2'); ?>
                    </div>
                </div>
            </fieldset>
            <hr>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                <button class="btn btn-primary rounded-3 bg-gradient" type="submit" id="submitBtn"><i class="fa-solid fa-pen-to-square"></i> Ubah</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>
<?= $this->section('javascript'); ?>
<script>
    function checkCapsLockStatus(event) {
        if (event.originalEvent.getModifierState("CapsLock")) {
            $('#capsLockStatus').show();
        } else {
            $('#capsLockStatus').hide();
        }
    }
    $(document).ready(function() {
        $('#loadingSpinner').hide(); // Menyembunyikan spinner loading saat halaman siap

        // Deteksi perubahan status Caps Lock saat tombol ditekan
        $(document).on('keydown', function(event) {
            checkCapsLockStatus(event);
        });

        // Deteksi perubahan status Caps Lock saat tombol ditekan
        $('input[type="password"]').on('keydown', function(event) {
            checkCapsLockStatus(event);
        });

        // Menangani event klik pada tombol dengan ID 'submitBtn'
        $(document).on('click', '#submitBtn', function(e) {
            e.preventDefault(); // Mencegah perilaku default dari tombol
            $('#changePasswordForm').submit(); // Mengirimkan form untuk mengubah kata sandi
            $('input').prop('disabled', true); // Menonaktifkan semua field input
            $('#submitBtn').prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                <span role="status">Memproses, silakan tunggu...</span>
            `); // Mengubah tampilan tombol submit menjadi loading
        });
    });
</script>
<?= $this->endSection(); ?>