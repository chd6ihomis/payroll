<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'General Payroll') }}</title>
  <link rel="icon" href="{!! asset('/image/doh.ico') !!}" />

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('/plugins/fontawesome-free/css/all.min.css') }}">

  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.dataTables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">

  <!-- Sweet Alert -->
  <link rel="stylesheet" href="{{ asset('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('/plugins/toastr/toastr.min.css') }}">

  <!-- Date Range Picker -->
  <link rel="stylesheet" href="{{ asset('/plugins/daterangepicker/daterangepicker.css') }}">

  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('/plugins/select2/css/select2.css') }}">
  <link rel="stylesheet" href="{{ asset('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

  <!-- SummerNote -->
  <link rel="stylesheet" href="{{ asset('/plugins/summernote/summernote-bs4.min.css') }}">
  
  <!-- Spinner -->
  <link rel="stylesheet" href="{{ asset('/css/plugin/spinner.css') }}">
  <style>
  aside {
		overflow: scroll;

	}
	aside::-webkit-scrollbar {
	  width: 10px;               /* width of the entire scrollbar */
	}
	aside::-webkit-scrollbar-track {
	  background: #fafafa;        /* color of the tracking area */
	}
	body::-webkit-scrollbar-thumb {
	  background-color: blue;    /* color of the scroll thumb */
	  border-radius: 20px;       /* roundness of the scroll thumb */
	  border: 3px solid orange;  /* creates padding around scroll thumb */
	}

  </style>
</head>

<body onload="loadModal()" class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">
    @include('admin.navbar')

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-success elevation-4">
      <!-- Brand Logo -->
      <a href="{{ route('home') }}" class="brand-link navbar-success">
        <img src="{{ asset('image/doh.png') }}" alt="DOH Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light text-light">Western Visayas CHD</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="{{ asset('image/gplogo.png') }}" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="{{ route('home') }}" class="d-block">{{ config('app.name') }}</a>
          </div>
        </div>

        @include('admin.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
          <!-- Content Header (Page header) -->

          <!-- Main content -->
          @yield('content')

        </div>
        <!-- /.content-wrapper -->
		
		

        <!-- Main Footer -->
        <footer class="main-footer text-sm">
			
          <strong>DOH Western Visayas CHD - </strong>
          {{ config('app.name') }}
          <div class="float-right d-none d-sm-inline-block">
            <b>Powered by</b> <a href="https://adminlte.io">AdminLTE.io</a>.
          </div>
        </footer>
      </div>
	  
	  @include('admin.chat')
	  
	    <div class="modal fade bd-example-modal-sm" id="spinner" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static">
          <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content invisible">
                <div class="row justify-content-center visible">
                    <div class="col-12-lg">
                        <div class="loadingio-spinner-spin-rosjtmqueao"><div class="ldio-vcs6kz5c8mk">
                        <div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div>
                        </div></div>
                    </div>                
                </div> 
            </div>
          </div>
        </div>
	</div>
      <!-- ./wrapper -->

      <!-- REQUIRED SCRIPTS -->
      <!-- jQuery -->
      <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
      <!-- Bootstrap -->
      <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
      <!-- DataTables -->
      <script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
      <script src="{{ asset('/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
      <script src="{{ asset('/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
      <script src="{{ asset('/plugins/datatables-buttons/js/jszip.min.js') }}"></script>
      <script src="{{ asset('/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
      <script src="{{ asset('/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
      <script src="{{ asset('/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

      <!-- overlayScrollbars -->
      <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
      <!-- AdminLTE App -->
      <script src="{{ asset('dist/js/adminlte.js') }}"></script>

      <!-- PAGE PLUGINS -->
      <!-- jQuery Mapael -->
      <script src="{{ asset('plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
      <script src="{{ asset('plugins/raphael/raphael.min.js') }}"></script>
      <script src="{{ asset('plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
      <script src="{{ asset('plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>

      <!-- ChartJS -->
      <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>

      <!-- Sweet Alert -->
      <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

      <!-- Toastr -->
      <script src="{{ asset('/plugins/toastr/toastr.min.js') }}"></script>

      <!-- Select2 -->
      <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>

      <!-- InputMask -->
      <script src="{{ asset('/plugins/moment/moment.min.js') }}"></script>
      <script src="{{ asset('/plugins/inputmask/jquery.inputmask.min.js') }}"></script>

      <!-- Date Range Picker -->
      <script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}"></script>

      <!-- Boostrap Switch -->
      <script src="{{ asset('/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

      <!-- SummerNote -->
      <script src="{{ asset('/plugins/summernote/summernote-bs4.min.js') }}"></script>

      <!-- PAGE SCRIPTS -->
      <script src="{{ asset('dist/js/pages/dashboard2.js') }}"></script>

	  <script>
	     function loadModal(){
			 
			 $('#selectPeriod').modal('show');
		 }
		 
	  </script>
	  <script>
        function loadSpinner() {
          $('#spinner').modal('show');
          $('.form-modal').css('display', 'none');
        };
      </script>
	  <script>
        $(function() {


          $('.datemask').inputmask('mm/dd/yyyy', {
            'placeholder': 'mm/dd/yyyy'
          });

          //Date range picker
          $('.date-picker').daterangepicker();
		  
          //Initialize Select2 Elements
          $('.select2').select2();

          //Boostrap Switch
          $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
          })

          // Summernote
          $('#summernote').summernote()

          var salaryID;

          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

          // SALARY EDIT
          $('body').on('click', '#editSalary', function(event) {

            event.preventDefault();
            var id = $(this).data('id');
            $.get(id + '/edit', function(data) {
              $('#employee').val(data.employee.employee_name);
              $('#eworking_days').val(data.data.working_days);
              $('#eday').val(data.data.day);
              $('#ehr').val(data.data.hr);
              $('#emin').val(data.data.min);
              $('#etax').val(data.data.tax);
              $('#epagibig').val(data.data.pagibig);
              $('#esss').val(data.data.sss);
              $('#ephilhealth').val(data.data.philhealth);
              $('#ephilhealth_otc').val(data.data.philhealth_otc);
              $('#ecoop').val(data.data.coop);
              $('#ecoop_loan').val(data.data.coop_loan);
              $('#ecomm_allowance').val(data.data.comm_allowance);
              $('#salary_id').val(data.data.id);
              $('#epayroll_id').val(data.data.payroll_id);
              $('#calculation').val(data.data.calculation);
            })
          });

          // EMPLOYEE EDIT
          $('body').on('click', '#editEmployee', function(event) {

            event.preventDefault();
            var id = $(this).data('id');
            console.log(id);
            $.get('/employees/' + id + '/edit', function(data) {
              $('#employee_id_e').val(data.data.employee_id);
              $('#employee_name_e').val(data.data.employee_name);
              $('#birthdate_e').val(data.date.birthdate);
              $('#contact_num_e').val(data.data.contact_num);
              $('#position_e').val(data.data.position);
              $('#office_e').val(data.date.office);
              $('#monthly_rate_e').val(data.data.monthly_rate);
              $('#fund_source_e').html(data.model.source);
              $('#lbp_num_e').val(data.data.lbp_num);
              $('#tin_num_e').val(data.data.tin_num);
              $('#pagibig_num_e').val(data.data.pagibig_num);
              $('#sss_num_e').val(data.data.sss_num);
              $('#philhealth_num_e').val(data.data.philhealth_num);
              $('#start_date_e').val(data.date.start_date);
              $('#end_date_e').val(data.date.end_date);
              $('#id_e').val(data.data.id);

              $('#status_e').bootstrapSwitch('state', false);
              if (data.data.status == 'true') {
                $('#status_e').bootstrapSwitch('state', true);
              }
            })
          });

          // SALARY INCORRECT
          $('body').on('click', '#salaryIncorrect', function(event) {

            event.preventDefault();
            var id = $(this).data('id');
            console.log(id);
            $.get('/salaryincorrect/' + id + '/edit', function(data) {
              $('#salary_id_inc').val(data.data.id);
              $('#payroll_id_inc').val(data.data.payroll_id);
            })
          });

          // SALARY CORRECT
          $('body').on('click', '#salaryCorrect', function(event) {

            event.preventDefault();
            var id = $(this).data('id');
            console.log(id);
            $.get('/salaryincorrect/' + id + '/edit', function(data) {
              $('#salary_id_cor').val(data.data.id);
              $('#payroll_id_cor').val(data.data.payroll_id);
            })
          });

		  // FUND SOURCE EDIT
		  $('body').on('click', '#editFundsource', function(event) {

			event.preventDefault();
			var id = $(this).data('id');
			$.get('/refs-fundsources-edit/' + id, function(data) {
			  $('#id_e').val(data.data.id);
			  $('#desc_e').val(data.data.desc);
			  $('#mfo_pap_e').val(data.data.mfo_pap);
			  $('#isConap_e').html(data.output.source);
			})
		  });

		  // FUND SOURCE EDIT
		  $('body').on('click', '#editSignatory', function(event) {

			event.preventDefault();
			var id = $(this).data('id');
			$.get('/refs-signatories-edit/' + id, function(data) {
			  $('#id_e').val(data.data.id);
			  $('#name_e').val(data.data.name);
			  $('#position_e').val(data.data.position);
			  $('#division_e').val(data.data.division);
			})
		  });
	  
          // NOTIFICATIONS
          $('body').on('click', '#showNotifications', function(event) {

            event.preventDefault();
            var id = $(this).data('id');
            console.log(id);
            $.get('/notifications/' + id, function(data) {
              $('#showComments').html(data.data);
              $('#salary_id_notif').val(data.salary);
              $('#payroll_id_notif').val(data.payroll);
            })
          });
		  
		  // CHATS
          $('body').on('click', '#showChats', function(event) {

            event.preventDefault();
            var id = $(this).data('id');
            console.log(id);
            $.get('/chats/' + id, function(data) {
              $('#showchats').html(data.data);
              $('#to_notif').val(data.user);
              $('#convo_notif').val(data.convo);
            })
          });

          $('body').on('click', '#viewLikers', function(event) {

            event.preventDefault();
            var id = $(this).data('id');
            console.log(id);
            $.get('/view-likers/' + id, function(data) {
              $('#displayLikers').html(data);
            })
          });
		  
		  $('body').on('click', '#duplicatePayroll', function(event) {

            event.preventDefault();
            var id = $(this).data('id');
            console.log(id);
            $.get('/get-payroll/' + id + '/edit', function(data) {
              $('#getpayroll').val(data.data.id);
            })
          });

          $('body').on('click', '#viewRecent', function(event) {

            event.preventDefault();
            var id = $(this).data('id');
            console.log(id);
            $.get('/view-recent/' + id, function(data) {
              $('#subject_r').html(data.data.subject);
              $('#publisher_r').html(data.data.publisher);
              $('#content_r').html(data.data.content);
              $('#created_r').html(data.data.created_at);
            })
          });
		  
		    $('#employeetbl').DataTable({
			  "paging": true,
			  "lengthChange": true,
			  "searching": true,
			  "ordering": false,
			  "info": true,
			  "autoWidth": false,
			  "responsive": true,
			  "stateSave": true,
			});
			
			

        });
      </script>

      <script type="text/javascript">
        $(document).ready(function () {
            if ($("#salarytbl").length) {
              
                $('#salarytbl').DataTable({
                  "paging": true,
                  "lengthChange": true,
                  "searching": true,
                  "ordering": false,
                  "info": true,
                  "autoWidth": false,
                  "responsive": true,
                  "stateSave": true,
                });
            }
        });
      </script>

      <script>
        @if(Session::has('message'))
        var type = "{{Session::get('alert-type', 'success')}}";

        switch (type) {
          case 'success':
            toastr.success("{{ Session::get('message') }}",
              toastr.options = {
                "closeButton": true,
                "positionClass": "toast-top-right"
              }
            );
            break;
          case 'error':
            toastr.error("{{ Session::get('message') }}",
              toastr.options = {
                "closeButton": true,
                "positionClass": "toast-top-right"
              }
            );
            break;
          case 'info':
            toastr.info("{{ Session::get('message') }}",
              toastr.options = {
                "positionClass": "toast-top-right"
              }
            );
            break;
        }

        @endif
      </script>

</body>

</html>