@php $header = __('Lịch Giao dịch'); @endphp
<x-app-layout>
   <div class="card card-primary card-outline">
      <div class="card-body p-0">
         <div id="calendar"></div>
      </div>
   </div>

   <!-- Transaction Detail Modal -->
   <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="eventModalLabel">{{ __('Chi tiết Giao dịch') }}</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label>{{ __('Số tiền') }}</label>
                  <p id="modalAmount" class="font-weight-bold h4"></p>
               </div>
               <div class="form-group">
                  <label>{{ __('Mô tả') }}</label>
                  <p id="modalDescription"></p>
               </div>
               <div class="form-group">
                  <label>{{ __('Danh mục') }}</label>
                  <span id="modalCategory" class="badge badge-info p-2"></span>
               </div>
            </div>
            <div class="modal-footer">
               <a href="#" id="modalLink" class="btn btn-primary">{{ __('Xem đầy đủ') }}</a>
               <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Đóng') }}</button>
            </div>
         </div>
      </div>
   </div>

   @push('styles')
      <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
      <style>
         #calendar {
            padding: 20px;
            background: #fff;
            min-height: 800px;
         }

         .dark-mode #calendar {
            background: #343a40;
            color: #fff;
         }

         .fc-toolbar-title {
            font-size: 1.5rem !important;
            text-transform: capitalize;
         }

         .fc-event {
            cursor: pointer;
         }

         /* Dark mode overrides for calendar */
         .dark-mode .fc-theme-standard td,
         .dark-mode .fc-theme-standard th {
            border-color: #4b545c;
         }

         .dark-mode .fc-daygrid-day-number {
            color: #fff;
         }

         .dark-mode .fc-col-header-cell-cushion {
            color: #fff;
         }

         .dark-mode .fc-button-primary {
            background-color: #3f6791;
            border-color: #3f6791;
         }

         .dark-mode .fc-button-primary:hover {
            background-color: #345579;
            border-color: #345579;
         }

         .dark-mode .fc-button-active {
            background-color: #2c4765 !important;
            border-color: #2c4765 !important;
         }
      </style>
   @endpush

   @push('scripts')
      <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
      <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/vi.js'></script>
      <script>
         document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
               initialView: 'dayGridMonth',
               locale: 'vi',
               headerToolbar: {
                  left: 'prev,next today',
                  center: 'title',
                  right: 'dayGridMonth,timeGridWeek,listMonth'
               },
               events: '{{ route("calendar.events") }}',
               eventClick: function (info) {
                  var props = info.event.extendedProps;
                  var amount = new Intl.NumberFormat('vi-VN').format(props.amount) + ' ₫';

                  document.getElementById('modalAmount').innerText = amount;
                  document.getElementById('modalAmount').className = 'font-weight-bold h4 ' + (props.type === 'income' ? 'text-success' : 'text-danger');
                  document.getElementById('modalDescription').innerText = props.description || '{{ __("Không có mô tả") }}';
                  document.getElementById('modalCategory').innerText = props.category;
                  document.getElementById('modalLink').href = '/transactions/' + info.event.id + '/edit';

                  $('#eventModal').modal('show');
               },
               dayMaxEvents: true // allow "more" link when too many events
            });
            calendar.render();
         });
      </script>
   @endpush
</x-app-layout>