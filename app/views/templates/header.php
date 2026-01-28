<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul'] ?></title>
    <link rel="shortcut icon" href="<?= BASEURL; ?>img/logo.svg" />

    <link rel="stylesheet" href="<?= BASEURL; ?>/css/style.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/beranda.css?v=<?= time(); ?>">
    <!-- <link rel="stylesheet" href="<?= BASEURL; ?>/css/jenisBarang.css?v=<?= time(); ?>"> -->
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/kelolaAkun.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="<?= BASEURL; ?>css/sidebar.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/MerekBarang.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/peminjaman.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/FormPeminjaman.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/profile.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/login.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/ValidasiPeminjaman.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/register.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/DetailPeminjaman.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/ValidasiPeminjamanIndex.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/Riwayat.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/DetailRiwayat.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/LengkapiPeminjaman.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/DetailBarang.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/DetailDetailBarang.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/FormDetailBarang.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/error.css?v=<?= time(); ?>">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        .loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            /* Putih bersih transparan */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(3px);
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }

        .loader-wrapper.hidden {
            opacity: 0;
            visibility: hidden;
        }

        /* Animasi Sesuai UIverse */
        .pl1 {
            display: block;
            width: 8em;
            height: 8em;
        }

        .pl1__g,
        .pl1__rect {
            animation: pl1-a 1.5s cubic-bezier(0.65, 0, 0.35, 1) infinite;
        }

        .pl1__g {
            transform-origin: 64px 64px;
        }

        .pl1__rect:first-child {
            animation-name: pl1-b;
        }

        .pl1__rect:nth-child(2) {
            animation-name: pl1-c;
        }

        @keyframes pl1-a {
            from {
                transform: rotate(0);
            }

            80%,
            to {
                transform: rotate(90deg);
            }
        }

        @keyframes pl1-b {
            from {
                animation-timing-function: cubic-bezier(0.33, 0, 0.67, 0);
                width: 40px;
                height: 40px;
            }

            20% {
                width: 40px;
                height: 0;
            }

            60% {
                width: 0;
                height: 40px;
            }

            80%,
            to {
                width: 40px;
                height: 40px;
            }
        }

        @keyframes pl1-c {
            from {
                width: 40px;
                height: 40px;
                transform: translate(0, 48px);
            }

            20% {
                width: 40px;
                height: 88px;
                transform: translate(0, 0);
            }

            40% {
                width: 40px;
                height: 40px;
                transform: translate(0, 0);
            }

            60% {
                width: 88px;
                height: 40px;
                transform: translate(0, 0);
            }

            80%,
            to {
                width: 40px;
                height: 40px;
                transform: translate(48px, 0);
            }
        }
    </style>
</head>

<body>
    <div id="loading-screen" class="loader-wrapper">
        <svg height="128px" width="128px" viewBox="0 0 128 128" class="pl1">
            <defs>
                <linearGradient y2="1" x2="1" y1="0" x1="0" id="pl-grad">
                    <stop stop-color="#0c1740" offset="0%"></stop>
                    <stop stop-color="#60a5fa" offset="100%"></stop>
                </linearGradient>
                <mask id="pl-mask">
                    <rect fill="url(#pl-grad)" height="128" width="128" y="0" x="0"></rect>
                </mask>
            </defs>
            <g fill="#bfdbfe">
                <g class="pl1__g">
                    <g transform="translate(20,20) rotate(0,44,44)">
                        <g class="pl1__rect-g">
                            <rect height="40" width="40" ry="8" rx="8" class="pl1__rect"></rect>
                            <rect transform="translate(0,48)" height="40" width="40" ry="8" rx="8" class="pl1__rect">
                            </rect>
                        </g>
                        <g transform="rotate(180,44,44)" class="pl1__rect-g">
                            <rect height="40" width="40" ry="8" rx="8" class="pl1__rect"></rect>
                            <rect transform="translate(0,48)" height="40" width="40" ry="8" rx="8" class="pl1__rect">
                            </rect>
                        </g>
                    </g>
                </g>
            </g>
            <g mask="url(#pl-mask)" fill="#0c1740">
                <g class="pl1__g">
                    <g transform="translate(20,20) rotate(0,44,44)">
                        <g class="pl1__rect-g">
                            <rect height="40" width="40" ry="8" rx="8" class="pl1__rect"></rect>
                            <rect transform="translate(0,48)" height="40" width="40" ry="8" rx="8" class="pl1__rect">
                            </rect>
                        </g>
                        <g transform="rotate(180,44,44)" class="pl1__rect-g">
                            <rect height="40" width="40" ry="8" rx="8" class="pl1__rect"></rect>
                            <rect transform="translate(0,48)" height="40" width="40" ry="8" rx="8" class="pl1__rect">
                            </rect>
                        </g>
                    </g>
                </g>
            </g>
        </svg>
    </div>