<?= $this->extend('dashboard/templates/dashboard'); ?>
<?= $this->section('title'); ?>
<div class="d-flex justify-content-start align-items-center">
    <a class="fs-5 me-3 text-success-emphasis" href="<?= base_url('/settings'); ?>"><i class="fa-solid fa-arrow-left"></i></a>
    <span class="fw-medium fs-5 flex-fill text-truncate"><?= $headertitle; ?></span>
    <div id="loadingSpinner" class="spinner-border spinner-border-sm" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<div style="min-width: 1px; max-width: 1px;"></div>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-3 px-md-4 pt-3">
    <?= form_open_multipart('/settings/update', 'id="userInfoForm"'); ?>
    <?= csrf_field(); ?>
    <fieldset class="border rounded-3 px-2 py-0">
        <legend class="float-none w-auto mb-0 px-1 fs-6 fw-bold">Informasi Pengguna</legend>
        <div class="form-floating mb-2">
            <input type="text" class="form-control rounded-3 <?= (validation_show_error('username')) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?= (old('username')) ? old('username') : htmlspecialchars(session()->get('username')); ?>" autocomplete="off" dir="auto" placeholder="username">
            <label for="username">Nama Pengguna*</label>
            <div class="invalid-feedback">
                <?= validation_show_error('username'); ?>
            </div>
        </div>
    </fieldset>
    <hr>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
        <button class="btn btn-primary rounded-3 bg-gradient" type="submit" id="submitBtn"><i class="fa-solid fa-user-pen"></i> Ubah</button>
    </div>
    <?= form_close(); ?>
</main>
<?= $this->endSection(); ?>
<?= $this->section('javascript'); ?>
<script>
    $(document).ready(function() {
        $('#loadingSpinner').hide();
        $('input.form-control').on('input', function() {
            // Remove the is-invalid class for the current input field
            $(this).removeClass('is-invalid');
            // Hide the invalid-feedback message for the current input field
            $(this).siblings('.invalid-feedback').hide();
        });
        $(document).on('click', '#submitBtn', function(e) {
            e.preventDefault();
            $('#userInfoForm').submit();
            $('input').prop('disabled', true);
            $('#submitBtn').prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                <span role="status">Mengubah, silakan tunggu...</span>
            `);
        });
    });
</script>
<?= $this->endSection(); ?>