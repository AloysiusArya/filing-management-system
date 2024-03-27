<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "link.php"; ?>
</head>

<?php
if (isset($_POST['submit'])) {
    $nomor_surat = mysqli_real_escape_string($conn, $_POST['nomor_surat']);
    $tujuan_permohonan = mysqli_real_escape_string($conn, $_POST['tujuan_permohonan']);
    $alamat_tujuan = mysqli_real_escape_string($conn, $_POST['alamat_tujuan']);
    $perihal = mysqli_real_escape_string($conn, $_POST['perihal']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $divisi = mysqli_real_escape_string($conn, $_POST['divisi']);
    $jenis_surat = mysqli_real_escape_string($conn, $_POST['jenis_surat']);
    $tipe_surat = mysqli_real_escape_string($conn, $_POST['tipe_surat']);

    if (isset($_FILES['file_pdf']['name']) && $_FILES['file_pdf']['name'] != '') {
        $fileTmpPath = $_FILES['file_pdf']['tmp_name'];
        $fileName = uniqid() . '-' . str_replace(' ', '_', $_FILES['file_pdf']['name']);
        $destPath = '../file/' . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $file_pdf = $fileName;
        } else {
            $file_pdf = NULL;
        }
    } else {
        $file_pdf = NULL;
    }

    $query = "INSERT INTO surat (nomor_surat, tujuan_permohonan, alamat_tujuan, perihal, tanggal, divisi, file_pdf, jenis_surat, tipe_surat) VALUES ('$nomor_surat', '$tujuan_permohonan', '$alamat_tujuan', '$perihal', '$tanggal', '$divisi', '$file_pdf', '$jenis_surat', '$tipe_surat')";

    if (mysqli_query($conn, $query)) {
        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Data Berhasil Ditambahkan!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Data Gagal Ditambahkan!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    }
}

if (isset($_POST['edit'])) {
    $id_surat = mysqli_real_escape_string($conn, $_POST['id_surat']);
    $nomor_surat = mysqli_real_escape_string($conn, $_POST['nomor_surat']);
    $tujuan_permohonan = mysqli_real_escape_string($conn, $_POST['tujuan_permohonan']);
    $alamat_tujuan = mysqli_real_escape_string($conn, $_POST['alamat_tujuan']);
    $perihal = mysqli_real_escape_string($conn, $_POST['perihal']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $divisi = mysqli_real_escape_string($conn, $_POST['divisi']);
    $jenis_surat = mysqli_real_escape_string($conn, $_POST['jenis_surat']);
    $tipe_surat = mysqli_real_escape_string($conn, $_POST['tipe_surat']);

    if (isset($_FILES['file_pdf']['name']) && $_FILES['file_pdf']['name'] != '') {
        $fileTmpPath = $_FILES['file_pdf']['tmp_name'];
        $fileName = uniqid() . '-' . str_replace(' ', '_', $_FILES['file_pdf']['name']);
        $destPath = '../file/' . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $file_pdf = $fileName;
        } else {
            $file_pdf = NULL;
        }

        $query = "UPDATE surat SET nomor_surat = '$nomor_surat', tujuan_permohonan = '$tujuan_permohonan', alamat_tujuan = '$alamat_tujuan', perihal = '$perihal', tanggal = '$tanggal', divisi = '$divisi', file_pdf = '$file_pdf', jenis_surat = '$jenis_surat', tipe_surat = '$tipe_surat' WHERE id = '$id_surat'";
    } else {
        $query = "UPDATE surat SET nomor_surat = '$nomor_surat', tujuan_permohonan = '$tujuan_permohonan', alamat_tujuan = '$alamat_tujuan', perihal = '$perihal', tanggal = '$tanggal', divisi = '$divisi', jenis_surat = '$jenis_surat', tipe_surat = '$tipe_surat' WHERE id = '$id_surat'";
    }

    if (mysqli_query($conn, $query)) {
        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Data Berhasil di Edit!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Data Gagal Di-Edit!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    }
}

if (isset($_POST['hapus'])) {
    $id_surat = mysqli_real_escape_string($conn, $_POST['id_surat']);

    $query = "DELETE FROM surat WHERE id = '$id_surat'";
    if (mysqli_query($conn, $query)) {
        $script = "
            Swal.fire({
                icon: 'success',
                title: 'Data Berhasil Dihapus!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    } else {
        $script = "
            Swal.fire({
                icon: 'error',
                title: 'Data Gagal Di-Hapus!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        ";
    }
}
?>




<body id="page-top">

    <div id="wrapper">

        <?php include "sidebar.php"; ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include "topbar.php"; ?>

                <div class="container-fluid">
                    <div class="mb-3">
                        <p>
                            <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                <i class="fas fa-plus-square"></i> Tambah Data Surat
                            </a>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="nomor_surat">Nomor Surat:</label>
                                        <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tujuan_permohonan">Tujuan Permohonan:</label>
                                        <input type="text" class="form-control" id="tujuan_permohonan" name="tujuan_permohonan">
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat_tujuan">Alamat Tujuan:</label>
                                        <textarea class="form-control" id="alamat_tujuan" name="alamat_tujuan"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="perihal">Perihal:</label>
                                        <input type="text" class="form-control" id="perihal" name="perihal">
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal">Tanggal:</label>
                                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="divisi">Divisi:</label>
                                        <select class="form-control" id="divisi" name="divisi" required>
                                            <option value="IT">IT</option>
                                            <option value="Accounting">Accounting</option>
                                            <option value="Umum">Umum</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="file_pdf">File PDF:</label>
                                        <input type="file" class="form-control-file" id="file_pdf" name="file_pdf">
                                    </div>
                                    <div class="form-group">
                                        <label for="jenis_surat">Jenis Surat:</label>
                                        <select class="form-control" id="jenis_surat" name="jenis_surat" required>
                                            <option value="Surat Penting">Surat Penting</option>
                                            <option value="Surat Biasa">Surat Biasa</option>
                                            <option value="Surat Tidak Perlu Dibalas">Surat Tidak Perlu Dibalas</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="tipe_surat">Tipe Surat:</label>
                                        <select class="form-control" id="tipe_surat" name="tipe_surat" required>
                                            <option value="Surat Masuk">Surat Masuk</option>
                                            <option value="Surat Keluar">Surat Keluar</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-primary w-100">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Surat Penting</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nomor Surat</th>
                                            <th>Tujuan Permohonan</th>
                                            <th>Alamat Tujuan</th>
                                            <th>Perihal</th>
                                            <th>Tanggal</th>
                                            <th>Divisi</th>
                                            <th>Jenis Surat</th>
                                            <th>Tipe Surat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $conn->prepare("SELECT * FROM surat WHERE jenis_surat = 'Surat Penting'");
                                        $stmt->execute();
                                        $surat = $stmt->get_result();
                                        ?>
                                        <?php $i = 1; ?>
                                        <?php foreach ($surat as $data) : ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= htmlspecialchars($data['nomor_surat']); ?></td>
                                                <td><?= htmlspecialchars($data['tujuan_permohonan']); ?></td>
                                                <td><?= htmlspecialchars($data['alamat_tujuan']); ?></td>
                                                <td><?= htmlspecialchars($data['perihal']); ?></td>
                                                <td><?= htmlspecialchars($data['tanggal']); ?></td>
                                                <td><?= htmlspecialchars($data['divisi']); ?></td>
                                                <td><?= htmlspecialchars($data['jenis_surat']); ?></td>
                                                <td><?= htmlspecialchars($data['tipe_surat']); ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal<?= $data['id'] ?>">Edit</a>
                                                    <br><br>
                                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal<?= $data['id'] ?>">Hapus</a>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="editModal<?= $data['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel">Edit Data Surat</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="POST">
                                                                <input type="hidden" name="id_surat" value="<?= $data['id']; ?>">
                                                                <div class="form-group">
                                                                    <label for="nomor_surat">Nomor Surat:</label>
                                                                    <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" value="<?= htmlspecialchars($data['nomor_surat']); ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="tujuan_permohonan">Tujuan Permohonan:</label>
                                                                    <input type="text" class="form-control" id="tujuan_permohonan" name="tujuan_permohonan" value="<?= htmlspecialchars($data['tujuan_permohonan']); ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="alamat_tujuan">Alamat Tujuan:</label>
                                                                    <textarea class="form-control" id="alamat_tujuan" name="alamat_tujuan" rows="3"><?= htmlspecialchars($data['alamat_tujuan']); ?></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="perihal">Perihal:</label>
                                                                    <input type="text" class="form-control" id="perihal" name="perihal" value="<?= htmlspecialchars($data['perihal']); ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="tanggal">Tanggal:</label>
                                                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $data['tanggal']; ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="divisi">Divisi:</label>
                                                                    <select class="form-control" id="divisi" name="divisi" required>
                                                                        <option value="IT" <?= ($data['divisi'] == 'IT') ? 'selected' : ''; ?>>IT</option>
                                                                        <option value="Accounting" <?= ($data['divisi'] == 'Accounting') ? 'selected' : ''; ?>>Accounting</option>
                                                                        <option value="Umum" <?= ($data['divisi'] == 'Umum') ? 'selected' : ''; ?>>Umum</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="jenis_surat">Jenis Surat:</label>
                                                                    <select class="form-control" id="jenis_surat" name="jenis_surat" required>
                                                                        <option value="Surat Penting" <?= ($data['jenis_surat'] == 'Surat Penting') ? 'selected' : ''; ?>>Surat Penting</option>
                                                                        <option value="Surat Biasa" <?= ($data['jenis_surat'] == 'Surat Biasa') ? 'selected' : ''; ?>>Surat Biasa</option>
                                                                        <option value="Surat Tidak Perlu Dibalas" <?= ($data['jenis_surat'] == 'Surat Tidak Perlu Dibalas') ? 'selected' : ''; ?>>Surat Tidak Perlu Dibalas</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="tipe_surat">Tipe Surat:</label>
                                                                    <select class="form-control" id="tipe_surat" name="tipe_surat" required>
                                                                        <option value="Surat Masuk" <?= ($data['tipe_surat'] == 'Surat Masuk') ? 'selected' : ''; ?>>Surat Masuk</option>
                                                                        <option value="Surat Keluar" <?= ($data['tipe_surat'] == 'Surat Keluar') ? 'selected' : ''; ?>>Surat Keluar</option>
                                                                    </select>
                                                                </div>
                                                                <button type="submit" name="edit" class="btn btn-primary w-100">Simpan</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="hapusModal<?= $data['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Hapus Data Surat</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus data surat dengan Nomor Surat: <b><?= htmlspecialchars($data['nomor_surat']) ?></b>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="id_surat" value="<?= $data['id'] ?>">
                                                                <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>


            </div>

            <?php include "footer.php"; ?>

        </div>

    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include "plugin.php"; ?>

    <script>
        $(document).ready(function() {
            $('#dataX').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json",
                    "oPaginate": {
                        "sFirst": "Pertama",
                        "sLast": "Terakhir",
                        "sNext": "Selanjutnya",
                        "sPrevious": "Sebelumnya"
                    },
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "sSearch": "Cari:",
                    "sEmptyTable": "Tidak ada data yang tersedia dalam tabel",
                    "sLengthMenu": "Tampilkan _MENU_ data",
                    "sZeroRecords": "Tidak ada data yang cocok dengan pencarian Anda"
                }
            });
        });
    </script>

    <script>
        <?php if (isset($script)) {
            echo $script;
        } ?>
    </script>
</body>

</html>