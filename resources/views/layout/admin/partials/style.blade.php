<!-- core:css -->
<link rel="stylesheet" href="../assets/vendors/core/core.css">
<!-- endinject -->

<!-- Plugin css for this page -->
<link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
<!-- End plugin css for this page -->

<!-- inject:css -->
<link rel="stylesheet" href="../assets/fonts/feather-font/css/iconfont.css">
<link rel="stylesheet" href="../assets/vendors/flag-icon-css/css/flag-icon.min.css">
<!-- endinject -->

<!-- Layout styles -->
<link rel="stylesheet" href="../assets/css/demo1/style.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.css"/>
<!--end::Global Stylesheets Bundle-->
<style>
    .loader {
        width: 30px;
        aspect-ratio: 2;
        --_g: no-repeat radial-gradient(circle closest-side,#e2e2e2 90%,#a0a0a000);
        background:
            var(--_g) 0%   50%,
            var(--_g) 50%  50%,
            var(--_g) 100% 50%;
        background-size: calc(100%/3) 50%;
        animation: l3 1s infinite linear;
    }
    @keyframes l3 {
        20%{background-position:0%   0%, 50%  50%,100%  50%}
        40%{background-position:0% 100%, 50%   0%,100%  50%}
        60%{background-position:0%  50%, 50% 100%,100%   0%}
        80%{background-position:0%  50%, 50%  50%,100% 100%}
    }
</style>
<style>
    /* overlay full-page */
    /* #page-loader {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(255,255,255,0.85);
        z-index: 99999;
        align-items: center;
        justify-content: center;
        pointer-events: auto;
    } */

    #page-loader {
        position: fixed;
        inset: 0;
        background: rgba(255,255,255,0.85);
        z-index: 99999;
        align-items: center;         /* vertikal tengah */
        justify-content: center;     /* horizontal tengah */
        pointer-events: auto;
        /* opacity: 0; */
        /* visibility: hidden; */
        transition: opacity 0.2s ease, visibility 0.2s ease;
        padding: 20%;
    }

    /* inner container (optional) */
    #page-loader .loader-inner {
        display: flex;
        gap: 12px;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        /* kalau ingin benar-benar kosong/hanya blank overlay, biarkan kosong */
    }

    /* simple spinner (opsional) */
    #page-loader .spinner {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 4px solid rgba(0,0,0,0.12);
        border-top-color: rgba(0,0,0,0.45);
        animation: spin 0.9s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* saat loader aktif, nonaktifkan scroll halaman */
    body.loader-active {
        overflow: hidden !important;
    }
</style>
@stack('style')
