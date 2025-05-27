<?php
// include('components/head.php');
// include('config/class/database.php'); // Include the Database class


// // Create Database object
// $database = new Database();

// // Fetch total patients and appointments
// $query_patients = "SELECT COUNT(*) as total_patients FROM patients";
// $result_patients = $database->db_query($query_patients);
// $row_patients = $result_patients->fetch(PDO::FETCH_ASSOC);
// $total_patients = $row_patients['total_patients'];

// $query_appointments = "SELECT COUNT(*) as total_appointments FROM appointments";
// $result_appointments = $database->db_query($query_appointments);
// $row_appointments = $result_appointments->fetch(PDO::FETCH_ASSOC);
// $total_appointments = $row_appointments['total_appointments'];

// // Fetch patient details (for the profile click)
// if (isset($_GET['patient_id'])) {
//     $patient_id = $_GET['patient_id'];
//     $query_patient_details = "SELECT * FROM patients WHERE id = :patient_id";
//     $result_patient_details = $database->db_query($query_patient_details, [':patient_id' => $patient_id]);
//     $patient_details = $result_patient_details->fetch(PDO::FETCH_ASSOC);
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Include AdminLTE CSS -->
  <link rel="stylesheet" href="path/to/admin-lte.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="#" class="brand-link">
        <img src="path/to/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Admin Dashboard</span>
      </a>
      <!-- Sidebar Menu -->
      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="dashboard.php" class="nav-link active">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="patients.php" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>Patients</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="appointments.php" class="nav-link">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>Appointments</p>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard</span></h4>

        <!-- Display Patient and Appointment Count -->
        <div class="row">
          <!-- Patient Count -->
          <div class="col-lg-6 col-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h5 class="card-title">Total Patients</h5>
              </div>
              <div class="card-body">
                <h3><?php echo $total_patients; ?></h3>
              </div>
            </div>
          </div>
          <!-- Appointment Count -->
          <div class="col-lg-6 col-12">
            <div class="card card-outline card-success">
              <div class="card-header">
                <h5 class="card-title">Total Appointments</h5>
              </div>
              <div class="card-body">
                <h3><?php echo $total_appointments; ?></h3>
              </div>
            </div>
          </div>
        </div>

        <!-- Patient List -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Patients</h5>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Firstname</th>
                      <th>Lastname</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Fetch patients from the database
                    $query = "SELECT * FROM patients";
                    $result = $database->db_query($query);
                    while ($patient = $result->fetch(PDO::FETCH_ASSOC)) {
                      echo "<tr>
                              <td>{$patient['id']}</td>
                              <td>{$patient['firstname']}</td>
                              <td>{$patient['lastname']}</td>
                              <td><a href='dashboard.php?patient_id={$patient['id']}'>View Profile</a></td>
                            </tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Patient Profile Display (if patient clicked) -->
        <?php if (isset($patient_details)) { ?>
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Patient Profile</h5>
                </div>
                <div class="card-body">
                  <p><strong>Firstname:</strong> <?php echo $patient_details['firstname']; ?></p>
                  <p><strong>Middlename:</strong> <?php echo $patient_details['middlename']; ?></p>
                  <p><strong>Lastname:</strong> <?php echo $patient_details['lastname']; ?></p>
                  <p><strong>Birthdate:</strong> <?php echo $patient_details['birthdate']; ?></p>
                  <p><strong>Address:</strong> <?php echo $patient_details['address']; ?></p>
                  <p><strong>Contact No:</strong> <?php echo $patient_details['contact_no']; ?></p>
                  <p><strong>Age:</strong> <?php echo $patient_details['age']; ?></p>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</body>
</html>
