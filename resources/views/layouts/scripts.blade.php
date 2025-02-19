<!-- ///////////// Js Files ////////////////////  -->
<!-- Jquery -->
<script src="{{ asset('assets/js/lib/jquery-3.4.1.min.js') }}"></script>

{{-- <script src="{{ asset('assets/lib/select2.min.js') }}"></script>
<script src="{{ asset('assets/lib/select2.js') }}"></script> --}}
<!-- Bootstrap -->
<script src="{{ asset('assets/js/lib/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/bootstrap.min.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<!-- Base Js File -->
<script src="{{ asset('assets/js/base.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
<script src="{{ asset('assets/js/webcamjs/webcam.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ URL::asset('__service-worker.js')}}"></script> --}}
{{-- <script src="{{ asset('assets/__service-worker.json') }}"></script> --}}

<!-- ///////////// Js Files End ////////////////////  -->

{{-- @if (Request::is('products*') || Request::is('sales*'  ) || Request::is('reports*') || Request::is('customers*')) --}}
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/plugins/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
{{-- @endif --}}


