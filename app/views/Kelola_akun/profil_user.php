<div class="content">
    <div class="content-beranda">
        <div class="profile-page-wrapper">
            <!-- Header with Back Button -->
            <div class="profile-top-bar"
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h2 style="color: #0c1740; margin: 0; font-weight: 700;">Detail Profil Akun</h2>
                <a href="<?= BASEURL; ?>KelolaAkun" class="btn-back-modern"
                    style="text-decoration: none; background: white; color: #475569; padding: 10px 20px; border-radius: 12px; display: flex; align-items: center; gap: 8px; font-weight: 600; border: 1px solid #e2e8f0; transition: all 0.2s;">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>

            <?php $profile_data = $data['profile']; ?>

            <div class="profile-grid" style="display: grid; grid-template-columns: 350px 1fr; gap: 25px;">
                <!-- Left Card: Identity -->
                <div class="card-modern profile-id-card"
                    style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 -10px 15px -3px rgba(0, 0, 0, 0.05), 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border: 1px solid #e2e8f0;">
                    <div class="card-header-accent"
                        style="height: 100px; background: linear-gradient(135deg, #0c1740 0%, #3b82f6 100%);"></div>
                    <div class="card-body" style="padding: 0 30px 40px; text-align: center; margin-top: -50px;">
                        <div class="photo-container" style="position: relative; display: inline-block;">
                            <?php
                            $foto_profil = $profile_data['foto'];
                            if (empty($foto_profil) || $foto_profil == "../public/img/foto-profile/" || !file_exists(str_replace('../public/', '', $foto_profil))) {
                                echo '<div style="width: 120px; height: 120px; border-radius: 50%; background: white; border: 4px solid white; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); display: flex; justify-content: center; align-items: center;">
                                <i class="fa-solid fa-circle-user" style="font-size: 110px; color: #1e293b;"></i>
                              </div>';
                            } else {
                                echo '<img src="' . BASEURL . $foto_profil . '" style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid white; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); object-fit: cover;" alt="profile">';
                            }
                            ?>
                        </div>
                        <h3 style="margin: 15px 0 5px; color: #1e293b; font-weight: 700;">
                            <?= htmlspecialchars($profile_data['nama_user']); ?>
                        </h3>
                        <span
                            style="background: #eff6ff; color: #3b82f6; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;"><?= htmlspecialchars($profile_data['role']); ?></span>

                        <div class="profile-stats"
                            style="display: flex; justify-content: center; gap: 30px; margin-top: 25px; border-top: 1px solid #f1f5f9; padding-top: 25px;">
                            <div class="stat-item">
                                <div style="font-size: 20px; font-weight: 800; color: #0c1740;">
                                    <?= count($data['history']); ?>
                                </div>
                                <div style="font-size: 12px; color: #64748b; font-weight: 500;">Total Pinjam</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Card: Personal Info -->
                <div class="card-modern"
                    style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 -10px 15px -3px rgba(0, 0, 0, 0.05), 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border: 1px solid #e2e8f0;">
                    <div
                        style="display: flex; align-items: center; gap: 10px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #f1f5f9;">
                        <i class="fa-solid fa-address-card" style="color: #3b82f6; font-size: 20px;"></i>
                        <h3 style="margin: 0; color: #0c1740; font-weight: 700;">Informasi Detail</h3>
                    </div>

                    <div class="info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="info-box"
                            style="background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #f1f5f9;">
                            <label
                                style="display: block; font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">Email</label>
                            <div style="color: #1e293b; font-weight: 500;">
                                <?= htmlspecialchars($profile_data['email']); ?>
                            </div>
                        </div>
                        <div class="info-box"
                            style="background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #f1f5f9;">
                            <label
                                style="display: block; font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">NIM/NIP</label>
                            <div style="color: #1e293b; font-weight: 500;">
                                <?= htmlspecialchars($profile_data['nim_nip'] ?? '-'); ?>
                            </div>
                        </div>
                        <div class="info-box"
                            style="background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #f1f5f9;">
                            <label
                                style="display: block; font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">No.
                                HP</label>
                            <div style="color: #1e293b; font-weight: 500; font-family: monospace;">
                                <?= htmlspecialchars($profile_data['no_hp_user']); ?>
                            </div>
                        </div>
                        <div class="info-box"
                            style="background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #f1f5f9;">
                            <label
                                style="display: block; font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">Jenis
                                Kelamin</label>
                            <div style="color: #1e293b; font-weight: 500;">
                                <?= htmlspecialchars($profile_data['jenis_kelamin']); ?>
                            </div>
                        </div>
                        <div class="info-box"
                            style="grid-column: span 2; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #f1f5f9;">
                            <label
                                style="display: block; font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">Alamat</label>
                            <div style="color: #1e293b; font-weight: 500; line-height: 1.6;">
                                <?= nl2br(htmlspecialchars($profile_data['alamat'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan History Card -->
            <div class="card-modern"
                style="background: white; border-radius: 20px; padding: 30px; margin-top: 25px; box-shadow: 0 -10px 15px -3px rgba(0, 0, 0, 0.05), 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border: 1px solid #e2e8f0;">
                <div
                    style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #f1f5f9;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-clock-rotate-left" style="color: #3b82f6; font-size: 20px;"></i>
                        <h3 style="margin: 0; color: #0c1740; font-weight: 700;">Riwayat Peminjaman</h3>
                    </div>
                    <span
                        style="background: #f1f5f9; color: #475569; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;"><?= count($data['history']); ?>
                        Records</span>
                </div>

                <div style="overflow-x: auto;">
                    <table class="history-table" style="width: 100%; border-collapse: separate; border-spacing: 0 8px;">
                        <thead>
                            <tr
                                style="color: #64748b; text-transform: uppercase; font-size: 11px; font-weight: 700; letter-spacing: 0.05em;">
                                <th style="padding: 10px 15px; text-align: left;">No</th>
                                <th style="padding: 10px 15px; text-align: left;">Periode Pinjam</th>
                                <th style="padding: 10px 15px; text-align: left;">Judul Kegiatan</th>
                                <th style="padding: 10px 15px; text-align: left;">Pembimbing</th>
                                <th style="padding: 10px 15px; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['history'])): ?>
                                <?php $i = 1;
                                foreach ($data['history'] as $h): ?>
                                    <tr class="table-row-hover"
                                        style="background: white; border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s;">
                                        <td
                                            style="padding: 15px; color: #64748b; font-weight: 600; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; border-left: 1px solid #f1f5f9; border-radius: 12px 0 0 12px;">
                                            <?= $i++; ?>
                                        </td>
                                        <td
                                            style="padding: 15px; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9;">
                                            <div style="font-weight: 600; color: #1e293b;">
                                                <?= date('d M Y', strtotime($h['tanggal_peminjaman'])); ?>
                                            </div>
                                            <div style="font-size: 11px; color: #94a3b8;">s/d
                                                <?= date('d M Y', strtotime($h['tanggal_pengembalian'])); ?>
                                            </div>
                                        </td>
                                        <td
                                            style="padding: 15px; font-weight: 500; color: #1e293b; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9;">
                                            <?= htmlspecialchars($h['judul_kegiatan']); ?>
                                        </td>
                                        <td
                                            style="padding: 15px; color: #475569; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9;">
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <i class="fa-solid fa-user-tie" style="font-size: 12px; color: #cbd5e1;"></i>
                                                <?= htmlspecialchars($h['dosen_pembimbing'] ?? '-'); ?>
                                            </div>
                                        </td>
                                        <td
                                            style="padding: 15px; text-align: center; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; border-right: 1px solid #f1f5f9; border-radius: 0 12px 12px 0;">
                                            <?php
                                            $statusClass = '';
                                            if ($h['status'] == 'Disetujui' || $h['status'] == 'Selesai')
                                                $statusClass = 'background: #dcfce7; color: #166534;';
                                            elseif ($h['status'] == 'Ditolak')
                                                $statusClass = 'background: #fee2e2; color: #991b1b;';
                                            else
                                                $statusClass = 'background: #fef9c3; color: #854d0e;';
                                            ?>
                                            <span
                                                style="padding: 6px 14px; border-radius: 8px; font-size: 11px; font-weight: 700; <?= $statusClass ?> text-transform: uppercase;">
                                                <?= $h['status']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="padding: 40px; text-align: center;">
                                        <img src="<?= BASEURL; ?>img/empty-history.svg" alt="no history"
                                            style="width: 150px; opacity: 0.5; margin-bottom: 15px;">
                                        <div style="color: #94a3b8; font-weight: 500;">Belum ada riwayat peminjaman untuk
                                            akun ini.</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        .btn-back-modern:hover {
            background: #f1f5f9 !important;
            transform: translateX(-4px);
            color: #0c1740 !important;
            border-color: #cbd5e1 !important;
        }

        .table-row-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 10;
            position: relative;
        }

        .table-row-hover:hover td {
            border-color: #3b82f6 !important;
            background-color: #f8fafc !important;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @media (max-width: 992px) {
            .profile-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>